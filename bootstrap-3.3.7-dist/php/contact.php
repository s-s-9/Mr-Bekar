<?php
	//initially set the session to null
	session_start();
	$_SESSION['user'] = 'NULL';
	
	//connect to database
	$servernamedb = 'localhost';
	$usernamedb = 'root';
	$passworddb = '';
	$dbnamedb = 'mr_baker';
	$connection = new mysqli($servernamedb, $usernamedb, $passworddb, $dbnamedb);
	if(!$connection){
		die('Connecting to database failed!');
	}
	//insert user's feedback to the feedbacks table
	if(isset($_GET['CONTACTEMAIL']) && isset($_GET['CONTACTMESSAGE'])){
		$CONTACTEMAIL = $_GET['CONTACTEMAIL'];
		$CONTACTMESSAGE = $_GET['CONTACTMESSAGE'];
		$sql = "INSERT INTO feedbacks (email, message) VALUES ('$CONTACTEMAIL', '$CONTACTMESSAGE')";
		if($connection->query($sql)===true){
			echo 'success';
		}
		else{
			echo 'failed';
		}
	}
?>