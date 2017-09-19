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
	
	//return user's picture from email
	if(isset($_GET['GETPICTUREFROMEMAIL'])){
		$GETPICTUREFROMEMAIL = $_GET['GETPICTUREFROMEMAIL'];
		$sql = "SELECT picture FROM bekars WHERE email = '$GETPICTUREFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['picture'];
	}
	
	//return user's resume from email
	if(isset($_GET['GETRESUMEFROMEMAIL'])){
		$GETRESUMEFROMEMAIL = $_GET['GETRESUMEFROMEMAIL'];
		$sql = "SELECT resume FROM bekars WHERE email = '$GETRESUMEFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['resume'];
		
	}
	
	//change profile picture
	if(isset($_POST['USEREMAILFORPHOTOUPLOAD'])){
		//save the user that is changing the picture
		$USEREMAILFORPHOTOUPLOAD = $_POST['USEREMAILFORPHOTOUPLOAD'];
		//delete the previous picture if it is not the default picture
		$sql = "SELECT picture FROM bekars WHERE email = '$USEREMAILFORPHOTOUPLOAD'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		if($result['picture']!='uploads/defaultbekar.png'){
			$fileToBeDeleted = '../'.$result['picture'];
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
		$fileNewName = $uniqueId.'profilepic'.'.'.$fileExtFinal;
		//save the temporary location, because we have to move the file later
		$fileTmpLocation = $file['tmp_name'];
		$fileNewLocation = '../uploads/'.$fileNewName;
		$fileNewLocationToWebsite = 'uploads/'.$fileNewName;
		//now actually upload the file
		move_uploaded_file($fileTmpLocation, $fileNewLocation);
		//save this new profile pic in database
		$sql = "UPDATE bekars SET picture = '$fileNewLocationToWebsite' WHERE email = '$USEREMAILFORPHOTOUPLOAD'";
		if($connection->query($sql)===true){
			echo $fileNewLocationToWebsite;
		}
		else{
			echo 'failedtoupdatedatabase';
		}
	}
	
	//upload resume
	if(isset($_POST['USEREMAILFORRESUMEUPLOAD'])){
		//save the user that is uploading the resume
		$USEREMAILFORRESUMEUPLOAD = $_POST['USEREMAILFORRESUMEUPLOAD'];
		//delete the previous resume
		$sql = "SELECT resume FROM bekars WHERE email = '$USEREMAILFORRESUMEUPLOAD'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$fileToBeDeleted = '../'.$result['resume'];
		unlink($fileToBeDeleted);
		//save the uploaded file
		$file = $_FILES['resume'];
		//save the name and extension
		$fileName = $file['name'];
		$fileExt = explode('.', $fileName);
		$fileExtFinal = strtolower(end($fileExt));
		//give this resume a new name
		$uniqueId = uniqid('', true);
		$fileNewName = $uniqueId.'resume'.'.'.$fileExtFinal;
		//save the temporary location, because we have to move the file later
		$fileTmpLocation = $file['tmp_name'];
		$fileNewLocation = '../uploads/'.$fileNewName;
		$fileNewLocationToWebsite = 'uploads/'.$fileNewName;
		//now actually upload the file
		move_uploaded_file($fileTmpLocation, $fileNewLocation);
		//save this new resume in database
		$sql = "UPDATE bekars SET resume = '$fileNewLocationToWebsite' WHERE email = '$USEREMAILFORRESUMEUPLOAD'";
		if($connection->query($sql)===true){
			echo $fileNewLocationToWebsite;
		}
		else{
			echo 'failedtoupdatedatabase';
		}
	}
	
	//show projects
	if(isset($_GET['USEREMAILFORSHOWINGPROJECTS'])){
		$skillFullForms = array('dbm'=>'Database Management', 'gmd' => 'Game Development', 'mad' => 'Mobile Application Development',
								'net' => 'Networking', 'oop' => 'Object Oriented Programming', 'prs' => 'Problem Solving', 
								'wdb' => 'Web Development (Back End)', 'wdf' => 'Web Development (Front End)');
		$USEREMAILFORSHOWINGPROJECTS = $_GET['USEREMAILFORSHOWINGPROJECTS'];
		$sql = "SELECT * FROM bekarprojects WHERE email = '$USEREMAILFORSHOWINGPROJECTS'";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			echo '<div class = "well text-left">';
			echo 	'<button class = "close deleteProjectButton">';
			echo 		'<span class = "glyphicon glyphicon-trash"></span>';
			echo 	'</button>';
			echo 	'<h5 class = "text-primary">Name: '.$row['name'].'</h5>';
			echo	'<h5 class = "text-primary">Category: '.$skillFullForms[$row['skill']].'</h5>';
			echo	'<h5 class = "text-primary">Description:</h5>';
			echo	'<p>'.$row['description'].'</p>';
			echo	'<a href = "'.$row['projectfile'].'" download><span class = "glyphicon glyphicon-file"></span> Project link</a>';
			echo	'<h5></h5>';
			echo '</div>';
		}
	}
	
	//upload project
	if(isset($_POST['USEREMAILFORPROJECTUPLOAD'])){
		$projectName = $_POST['projectName'];
		$projectDesc = $_POST['projectDesc'];
		$projectSkills = $_POST['projectSkills'];
		//save the user that is uploading the project
		$USEREMAILFORPROJECTUPLOAD = $_POST['USEREMAILFORPROJECTUPLOAD'];
		//save the uploaded file
		$file = $_FILES['projectFile'];
		//save the name and extension
		$fileName = $file['name'];
		$fileExt = explode('.', $fileName);
		$fileExtFinal = strtolower(end($fileExt));
		//give this project a new name
		$uniqueId = uniqid('', true);
		$fileNewName = $uniqueId.'project'.'.'.$fileExtFinal;
		//save the temporary location, because we have to move the file later
		$fileTmpLocation = $file['tmp_name'];
		$fileNewLocation = '../uploads/'.$fileNewName;
		$fileNewLocationToWebsite = 'uploads/'.$fileNewName;
		//now actually upload the file
		move_uploaded_file($fileTmpLocation, $fileNewLocation);
		//save this new resume in database
		$sql = "INSERT INTO bekarprojects (email, skill, name, description, projectfile) 
				VALUES ('$USEREMAILFORPROJECTUPLOAD', '$projectSkills', '$projectName', '$projectDesc', '$fileNewLocationToWebsite')";
		if($connection->query($sql)===true){
			echo $fileNewLocationToWebsite;
		}
		else{
			echo 'failedtoupdatedatabase';
		}
	}
	
	//delete project
	if(isset($_GET['USEREMAILFORPROJECTDELETE'])){
		$USEREMAILFORPROJECTDELETE = $_GET['USEREMAILFORPROJECTDELETE'];
		//delete the project file
		$sql = "SELECT projectfile FROM bekarprojects WHERE email = '$USEREMAILFORPROJECTDELETE'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$fileToBeDeleted = '../'.$result['projectfile'];
		unlink($fileToBeDeleted);
		//delete the row from the database
		$projectFile = $result['projectfile'];
		$sql = "DELETE FROM bekarprojects WHERE projectfile = '$projectFile'";
		if($connection->query($sql)===true){
			echo 'deleted from database';
		}
		else{
			echo 'failed to delete from database';
		}
	}
	
	//returning user's skills to js
	if(isset($_GET['USEREMAILFORSKILLSHOW'])){
		$USEREMAILFORSKILLSHOW = $_GET['USEREMAILFORSKILLSHOW'];
		$sql = "SELECT * FROM bekarskills WHERE email = '$USEREMAILFORSKILLSHOW'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['dbm'].$result['gmd'].$result['mad'].$result['net'].$result['oop'].$result['prs'].$result['wdb'].$result['wdf'];
	}
	
	//if user updated skills
	if(isset($_GET['USEREMAILFORSKILLUPDATE']) && isset($_GET['UPDATESKILLS'])){
		$USEREMAILFORSKILLUPDATE = $_GET['USEREMAILFORSKILLUPDATE'];
		$UPDATESKILLS = $_GET['UPDATESKILLS'];
		echo $USEREMAILFORSKILLUPDATE;
		//first remove all skills of this user
		$sql = "UPDATE bekarskills SET dbm = 0, gmd = 0, mad = 0, net = 0, 
				oop = 0, prs = 0, wdb = 0, wdf = 0 WHERE email = '$USEREMAILFORSKILLUPDATE'";
		if($connection->query($sql)===true){
			//echo 'removed all skills';
		}
		for($i = 0; $i<sizeof($UPDATESKILLS); $i++){
			$currentSkill = $UPDATESKILLS[$i];
			$sql = "UPDATE bekarskills SET $currentSkill = 1 WHERE email = '$USEREMAILFORSKILLUPDATE'";
			if($connection->query($sql)===true){
				echo 'added '.$currentSkill;
			}
		}
	}
	
	//returning user's languages to js
	if(isset($_GET['USEREMAILFORLANGUAGESHOW'])){
		$USEREMAILFORLANGUAGESHOW = $_GET['USEREMAILFORLANGUAGESHOW'];
		$sql = "SELECT * FROM bekarlanguages WHERE email = '$USEREMAILFORLANGUAGESHOW'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['cor'].$result['cpp'].$result['jav'].$result['pyt'].$result['htm'].$result['css'].$result['php'].$result['msq'].
			 $result['csh'].$result['rub'].$result['per'].$result['njs'].$result['dot'].$result['mon'].$result['vba'].$result['fba'];
	}
	
	//if user updated languages
	if(isset($_GET['USEREMAILFORLANGUAGEUPDATE']) && isset($_GET['UPDATELANGUAGES'])){
		$USEREMAILFORLANGUAGEUPDATE = $_GET['USEREMAILFORLANGUAGEUPDATE'];
		$UPDATELANGUAGES = $_GET['UPDATELANGUAGES'];
		echo $USEREMAILFORLANGUAGEUPDATE;
		//first remove all languages of this user
		$sql = "UPDATE bekarlanguages SET cor = 0, cpp = 0, jav = 0, pyt = 0, htm = 0, css = 0, php = 0, msq = 0,
		csh = 0, rub = 0, per = 0, njs = 0, dot = 0, mon = 0, vba = 0, fba = 0 WHERE email = '$USEREMAILFORLANGUAGEUPDATE'";
		if($connection->query($sql)===true){
			//echo 'removed all languages';
		}
		for($i = 0; $i<sizeof($UPDATELANGUAGES); $i++){
			$currentLanguage = $UPDATELANGUAGES[$i];
			$sql = "UPDATE bekarlanguages SET $currentLanguage = 1 WHERE email = '$USEREMAILFORLANGUAGEUPDATE'";
			if($connection->query($sql)===true){
				echo 'added '.$currentLanguage;
			}
		}
	}
	
	//returning user's skills to js when uploading project
	if(isset($_GET['USEREMAILFORSHOWINGSKILLSINPROJECTUPLOAD'])){
		$USEREMAILFORSHOWINGSKILLSINPROJECTUPLOAD = $_GET['USEREMAILFORSHOWINGSKILLSINPROJECTUPLOAD'];
		$sql = "SELECT * FROM bekarskills WHERE email = '$USEREMAILFORSHOWINGSKILLSINPROJECTUPLOAD'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['dbm'].$result['gmd'].$result['mad'].$result['net'].$result['oop'].$result['prs'].$result['wdb'].$result['wdf'];
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