<?php
	session_start();
	
	//connect to database
	$servernamedb = 'localhost';
	$usernamedb = 'root';
	$passworddb = '';
	$dbnamedb = 'mr_baker';
	$connection = new mysqli($servernamedb, $usernamedb, $passworddb, $dbnamedb);
	if(!$connection){
		die('Connecting to database failed!');
	}
	
	if(isset($_GET['PAGENAME'])){
		$PAGENAME = $_GET['PAGENAME'];
		echo $PAGENAME;
	}
	
	//return current user's email
	if(isset($_GET['GETUSERNAME'])){
		if(isset($_SESSION['user'])){
			echo $_SESSION['user'];
		}
		else{
			echo 'nobody';
		}
	}
	
	//logout user
	if(isset($_GET['LOGOUT'])){
		unset($_SESSION['user']);
		echo 'success';
	}
	
	//return user's role
	if(isset($_GET['GETROLEFROMEMAIL'])){
		$GETROLEFROMEMAIL = $_GET['GETROLEFROMEMAIL'];
		$sql = "SELECT role FROM users WHERE email = '$GETROLEFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['role'];
	}
	
	//return the current date to javascript
	if(isset($_GET['GETCURRENTDATE'])){
		$GETCURRENTDATE = $_GET['GETCURRENTDATE'];
		$sql = "SELECT CURDATE() AS d";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['d'];
	}
	
	//if a job is posted
	if(isset($_POST['USEREMAILFORJOBUPLOAD'])){
		echo 'server speaking';
		//save details about the post
		$jobposition = $_POST['jobposition'];
		$jobtype = $_POST['jobtype'];
		$jobsalary = $_POST['jobsalary'];
		$jobdeadline = $_POST['jobdeadline'];
		//save the user that is posting the job
		$USEREMAILFORJOBUPLOAD = $_POST['USEREMAILFORJOBUPLOAD'];
		//save the uploaded file
		$file = $_FILES['jobcircular'];
		//save the name and extension
		$fileName = $file['name'];
		$fileExt = explode('.', $fileName);
		$fileExtFinal = strtolower(end($fileExt));
		//give this circular a new name
		$uniqueId = uniqid('', true);
		$fileNewName = $uniqueId.'circular'.'.'.$fileExtFinal;
		//save the temporary location, because we have to move the file later
		$fileTmpLocation = $file['tmp_name'];
		$fileNewLocation = '../uploads/'.$fileNewName;
		$fileNewLocationToWebsite = 'uploads/'.$fileNewName;
		//now actually upload the file
		move_uploaded_file($fileTmpLocation, $fileNewLocation);
		//add an entry to the jobs table
		$sql = "INSERT INTO jobs (postedby, position, type, salary, deadline, circular)
				VALUES ('$USEREMAILFORJOBUPLOAD', '$jobposition', '$jobtype', '$jobsalary', '$jobdeadline', '$fileNewLocationToWebsite')";
		if($connection->query($sql)===true){
			echo 'added to jobs';
			//get this job's id
			$jobno = $connection->insert_id;
			//add entries in the jobskills and joblanguages tables for this job
			$sql = "INSERT INTO jobskills (id) VALUES ('$jobno')";
			if($connection->query($sql)===true){
				echo 'added entry to jobskills';
			}
			$sql = "INSERT INTO joblanguages (id) VALUES ('$jobno')";
			if($connection->query($sql)===true){
				echo 'added entry to joblanguages';
			}
			//add the skills specified for this job
			$skillsspecified = 0;
			for($i = 0; $i<3; $i+=1){
				if($_POST['jobskill'.$i]!='none'){
					$skillsspecified += 1;
				}
			}
			if($skillsspecified>0){
				$sql = 'UPDATE jobskills SET ';
				for($i = 0; $i<$skillsspecified-1; $i+=1){
					$sql.=$_POST['jobskill'.$i].' = \'1\', ';
				}
				$sql.=$_POST['jobskill'.($skillsspecified-1)].' = \'1\' WHERE id = \''.$jobno.'\';';
				if($connection->query($sql)===true){
					echo 'updated jobskills';
				}
				else{
					echo 'failed to update jobskills';
				}
			}
			
			//add the languages specified for this job
			$languagesspecified = 0;
			for($i = 0; $i<5; $i+=1){
				if($_POST['joblanguage'.$i]!='none'){
					$languagesspecified += 1;
				}
			}
			if($languagesspecified>0){
				$sql = 'UPDATE joblanguages SET ';
				for($i = 0; $i<$languagesspecified-1; $i+=1){
					$sql.=$_POST['joblanguage'.$i].' = \'1\', ';
				}
				$sql.=$_POST['joblanguage'.($languagesspecified-1)].' = \'1\' WHERE id = \''.$jobno.'\';';
				if($connection->query($sql)===true){
					echo 'updated joblanguages';
				}
				else{
					echo 'failed to update joblanguages';
				}
			}
			//increment this company's number of total jobs posted
			$sql = "UPDATE companies SET jobsposted = jobsposted+1, totalsalary = totalsalary+'$jobsalary' 
					WHERE email = '$USEREMAILFORJOBUPLOAD'";
			if($connection->query($sql)===true){
				echo 'updated new columns';
			}
			else{
				echo 'failed to update new columns';
			}
		}
		else{
			echo 'failed adding to jobs';
		}
	}
	
	//if the user searched for something at the nav bar, return the relevant results
	if(isset($_GET['SEARCHATNAVBAR'])){
		$SEARCHATNAVBAR = $_GET['SEARCHATNAVBAR'];
		//make the sql query to retrieve the top 10 results
		$sql = "SELECT name, email, role FROM users WHERE name LIKE '%$SEARCHATNAVBAR%' LIMIT 10";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			$name = $row['name'];
			$email = $row['email'];
			$role = $row['role'];
			if($role==='baker'){
				//echo '<form action = "./viewbakerprofile.html" method = "GET">';
				echo '<a class = "btn" href = "./viewbakerprofile.html?'.'email='.$email.'" target = "_blank">'.$name.'</a><br>';
			}
			else if($role==='company'){
				//echo '<form action = "./viewcompanyprofile.html" method = "GET">';
				echo '<a class = "btn" href = "./viewcompanyprofile.html?'.'email='.$email.'" target = "_blank">'.$name.'</a><br>';
			}
			//echo		'<input type = "hidden" name = "email" value = "'.$email.'">';
			//echo		'<button class = "btn btn-link" type = "submit">'.$name.'</button>';
			//echo 	 '</form>';
			
		}
	}
?>