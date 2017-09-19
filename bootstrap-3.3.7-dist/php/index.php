<?php
	//initially set the session to null
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
	
	//check if email exists while registering a user
	if(isset($_GET['DOESTHISEMAILEXIST'])){
		$DOESTHISEMAILEXIST = $_GET['DOESTHISEMAILEXIST'];
		$sql = "SELECT email FROM users WHERE email = '$DOESTHISEMAILEXIST'";
		$result = $connection->query($sql);
		if($result->num_rows>0){
			echo 'decline';
		}
		else{
			echo 'accept';
		}
	}
	
	//if someone tried to login
	if(isset($_GET['EMAILLOGIN']) && isset($_GET['PASSWORDLOGIN'])){
		$EMAILLOGIN = $_GET['EMAILLOGIN'];
		$PASSWORDLOGIN = $_GET['PASSWORDLOGIN'];
		$sql = "SELECT name FROM users WHERE email = '$EMAILLOGIN' AND password = '$PASSWORDLOGIN'";
		$result = $connection->query($sql);
		if($result->num_rows>0){
			$_SESSION['user'] = $EMAILLOGIN;
			echo 'matched';
		}
		else{
			echo 'nope';
		}
	}
	
	//if a user is logged in, return his email, else return nobody
	if(isset($_GET['GETUSERNAME'])){
		if(isset($_SESSION['user'])){
			echo $_SESSION['user'];
		}
		else{
			echo 'nobody';
		}
	}
	
	//return the role of a user that entered email, password correctly
	if(isset($_GET['BEKARORCOMPANY'])){
		$BEKARORCOMPANY = $_GET['BEKARORCOMPANY'];
		$sql = "SELECT role FROM users WHERE email = '$BEKARORCOMPANY'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		echo $result['role'];
	}
	
	//dummy for ajax testing
	if(isset($_GET['PAGENAME'])){
		$PAGENAME = $_GET['PAGENAME'];
		echo $PAGENAME;
	}
	
	//remove old jobs
	if(isset($_GET['REMOVEOLDJOBS'])){
		//first, delete the circulars of the old jobs
		$sql = "SELECT circular FROM jobs WHERE deadline < (SELECT CURDATE())";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			$filetobedeleted = '../'.$row['circular'];
			unlink($filetobedeleted);
		}
		//now delete the old jobs from database
		$sql = "DELETE FROM jobs WHERE deadline < (SELECT CURDATE())";
		if($connection->query($sql)===true){
			echo 'deleted old jobs';
		}
		else{
			echo 'problem deleting old jobs';
		}
		//remove done interview sessions
		$sql = "DELETE FROM interviewcalls WHERE interviewdate < (SELECT CURDATE())";
		if($connection->query($sql)===true){
			echo 'deleted done interviews';
		}
		else{
			echo 'problem deleting done interviews';
		}
	}
	
	//if a user signs up
	if(isset($_GET['NAMESIGNUP']) && isset($_GET['BIRTHDAYSIGNUP']) && isset($_GET['GENDERSIGNUP']) 
	   && isset($_GET['EMAILSIGNUP']) && isset($_GET['PASSWORDSIGNUP']) && isset($_GET['ROLESIGNUP'])){
		    $NAMESIGNUP = $_GET['NAMESIGNUP'];
		    $BIRTHDAYSIGNUP = $_GET['BIRTHDAYSIGNUP'];
		    $GENDERSIGNUP = $_GET['GENDERSIGNUP'];
		    $EMAILSIGNUP = $_GET['EMAILSIGNUP'];
		    $PASSWORDSIGNUP = $_GET['PASSWORDSIGNUP'];
		    $ROLESIGNUP = $_GET['ROLESIGNUP'];
			if($ROLESIGNUP=='company'){
				$BIRTHDAYSIGNUP = '0000-00-00';
				$GENDERSIGNUP = '0';
			}
		    //insert values into database and send accept to javascript
			$sql = "INSERT INTO users (name, birthday, gender, email, password, role)
			VALUES ('$NAMESIGNUP', '$BIRTHDAYSIGNUP', '$GENDERSIGNUP', '$EMAILSIGNUP', '$PASSWORDSIGNUP', '$ROLESIGNUP')";
			if($connection->query($sql)===true){
				//echo 'registered1';
				if($ROLESIGNUP=='baker'){
					//enter an entry for this user in the bakers table
					$DEFAULTPICTURE = 'uploads/defaultbekar.png';
					$sql = "INSERT INTO bekars (email, picture) 
					VALUES ('$EMAILSIGNUP', '$DEFAULTPICTURE')";
					if($connection->query($sql)===true){
						echo 'inserted into bekars';
					}
					else{
						echo 'failed';
					}
					//enter an entry for this user in the bakerskills table
					$sql = "INSERT INTO bekarskills (email) VALUES ('$EMAILSIGNUP')";
					if($connection->query($sql)===true){
						echo 'inserted into bekarskills';
					}
					else{
						echo 'failed';
					}
					
					//enter an entry for this user in the bakerlanguages table
					$sql = "INSERT INTO bekarlanguages (email) VALUES ('$EMAILSIGNUP')";
					if($connection->query($sql)===true){
						echo 'inserted into bekarlanguages';
					}
					else{
						echo 'failed';
					}
				}
				else{
					//tables related to companies will be added soon
					//add and entry to the companies table for this company
					$DEFAULTPICTURE = 'uploads/defaultcompany.png';
					$sql = "INSERT INTO companies (email, logo) 
					VALUES ('$EMAILSIGNUP', '$DEFAULTPICTURE')";
					if($connection->query($sql)===true){
						echo 'registered';
					}
					else{
						echo 'failed';
					}
				}
			}
			else{
				echo 'failed';
			}
	   }
?>