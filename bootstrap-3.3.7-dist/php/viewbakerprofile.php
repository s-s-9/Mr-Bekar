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
	
	//return email of the user that's being viewed
	if(isset($_GET['GETUSERNAME'])){
		if(isset($_SESSION['user'])){
			echo $_SESSION['user'];
		}
		else{
			echo 'nobody';
		}
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
			echo 	'<h5 class = "text-primary">Name: '.$row['name'].'</h5>';
			echo	'<h5 class = "text-primary">Category: '.$skillFullForms[$row['skill']].'</h5>';
			echo	'<h5 class = "text-primary">Description:</h5>';
			echo	'<p>'.$row['description'].'</p>';
			echo	'<a href = "'.$row['projectfile'].'" download><span class = "glyphicon glyphicon-file"></span> Project link</a>';
			echo	'<h5></h5>';
			echo '</div>';
		}
	}
	
	//returning user's skills to js
	if(isset($_GET['USEREMAILFORSKILLSHOW'])){
		$USEREMAILFORSKILLSHOW = $_GET['USEREMAILFORSKILLSHOW'];
		$sql = "SELECT * FROM bekarskills WHERE email = '$USEREMAILFORSKILLSHOW'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['dbm'].$result['gmd'].$result['mad'].$result['net'].$result['oop'].$result['prs'].$result['wdb'].$result['wdf'];
	}
	
	//returning user's languages to js
	if(isset($_GET['USEREMAILFORLANGUAGESHOW'])){
		$USEREMAILFORLANGUAGESHOW = $_GET['USEREMAILFORLANGUAGESHOW'];
		$sql = "SELECT * FROM bekarlanguages WHERE email = '$USEREMAILFORLANGUAGESHOW'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['cor'].$result['cpp'].$result['jav'].$result['pyt'].$result['htm'].$result['css'].$result['php'].$result['msq'].
			 $result['csh'].$result['rub'].$result['per'].$result['njs'].$result['dot'].$result['mon'].$result['vba'].$result['fba'];
	}
	
?>