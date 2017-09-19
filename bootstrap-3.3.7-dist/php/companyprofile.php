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
	
	//dummy for ajax testing
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
	
	//return user's name from email
	if(isset($_GET['GETNAMEFROMEMAIL'])){
		$GETNAMEFROMEMAIL = $_GET['GETNAMEFROMEMAIL'];
		$sql = "SELECT name FROM users WHERE email = '$GETNAMEFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['name'];
	}
	
	//return user's website from email
	if(isset($_GET['GETWEBSITEFROMEMAIL'])){
		$GETWEBSITEFROMEMAIL = $_GET['GETWEBSITEFROMEMAIL'];
		$sql = "SELECT website FROM companies WHERE email = '$GETWEBSITEFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['website'];
	}
	
	//return user's picture from email
	if(isset($_GET['GETPICTUREFROMEMAIL'])){
		$GETPICTUREFROMEMAIL = $_GET['GETPICTUREFROMEMAIL'];
		$sql = "SELECT logo FROM companies WHERE email = '$GETPICTUREFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['logo'];
	}
	
	//change logo
	if(isset($_POST['USEREMAILFORPHOTOUPLOAD'])){
		//save the user that is changing the picture
		$USEREMAILFORPHOTOUPLOAD = $_POST['USEREMAILFORPHOTOUPLOAD'];
		//delete the previous picture if it is not the default picture
		$sql = "SELECT logo FROM companies WHERE email = '$USEREMAILFORPHOTOUPLOAD'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		if($result['logo']!='uploads/defaultcompany.png'){
			$fileToBeDeleted = '../'.$result['logo'];
			unlink($fileToBeDeleted);
		}
		//save the uploaded file
		$file = $_FILES['picture'];
		//save the name and extension
		$fileName = $file['name'];
		$fileExt = explode('.', $fileName);
		$fileExtFinal = strtolower(end($fileExt));
		//give this picture a new name
		$uniqueId = uniqid('', true);
		$fileNewName = $uniqueId.'logo'.'.'.$fileExtFinal;
		//save the temporary location, because we have to move the file later
		$fileTmpLocation = $file['tmp_name'];
		$fileNewLocation = '../uploads/'.$fileNewName;
		$fileNewLocationToWebsite = 'uploads/'.$fileNewName;
		//now actually upload the file
		move_uploaded_file($fileTmpLocation, $fileNewLocation);
		//save this new profile pic in database
		$sql = "UPDATE companies SET logo = '$fileNewLocationToWebsite' WHERE email = '$USEREMAILFORPHOTOUPLOAD'";
		if($connection->query($sql)===true){
			echo $fileNewLocationToWebsite;
		}
		else{
			echo 'failedtoupdatedatabase';
		}
	}
	
	//if the company updated their website
	if(isset($_GET['USEREMAILFORWEBSITEUPDATE']) && isset($_GET['WEBSITEURL'])){
		$USEREMAILFORWEBSITEUPDATE = $_GET['USEREMAILFORWEBSITEUPDATE'];
		$WEBSITEURL = $_GET['WEBSITEURL'];
		if($WEBSITEURL!=''){
			$sql = "UPDATE companies SET website = '$WEBSITEURL' WHERE email = '$USEREMAILFORWEBSITEUPDATE'";
			if($connection->query($sql)===true){
				echo 'updated';
			}
			else{
				echo 'failed';
			}
		}
	}
	
	//view the jobs posted by this company
	if(isset($_GET['VIEWJOBSPOSTEDFROMEMAIL'])){
		echo 'serverserver';
		$VIEWJOBSPOSTEDFROMEMAIL = $_GET['VIEWJOBSPOSTEDFROMEMAIL'];
		$sql = "SELECT * FROM jobs WHERE postedby = '$VIEWJOBSPOSTEDFROMEMAIL'";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			echo '<tr>';
			echo 	'<td>'.$row['id'].'</td>';
			echo 	'<td>'.$row['position'].'</td>';
			echo 	'<td>'.$row['type'].'</td>';
			echo 	'<td>'.$row['salary'].'</td>';
			echo 	'<td>'.$row['deadline'].'</td>';
			echo 	'<td>'.'<a href = "'.$row['circular'].'" download>
								<span class = "glyphicon glyphicon-download">Circular</span>
							</a>
					 </td>';
			echo '</tr>';
		}
	}
	
	//view stats of this company
	if(isset($_GET['VIEWSTATSFROMEMAIL'])){
		$VIEWSTATSFROMEMAIL = $_GET['VIEWSTATSFROMEMAIL'];
		$sql = "SELECT jobsposted, totalsalary, interviewcalls FROM companies WHERE email = '$VIEWSTATSFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo '<dt>Jobs Posted: '.$result['jobsposted'].'</dt>';
		if($result['jobsposted']>0){
			echo '<dt>Average Salary: '.round($result['totalsalary']/$result['jobsposted']).'</dt>';
			echo '<dt>Interviews/Post: '.round($result['interviewcalls']/$result['jobsposted']).'</dt>';
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