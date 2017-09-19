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
	
	//view this bekar's pending applications
	if(isset($_GET['VIEWPENDINGAPPLICATIONSFROMEMAIL'])){
		$VIEWPENDINGAPPLICATIONSFROMEMAIL = $_GET['VIEWPENDINGAPPLICATIONSFROMEMAIL'];
		echo '<br>';
		echo '<p class = "alert alert-info">This section shows your pending applications. Pray hard!</p>';
		echo '<div class = "well">';
		echo 	'<br>';
		echo 	'<table class = "table table-striped">';
		echo		'<thead>';
		echo 			'<tr>';
		echo				'<th class = "text-center">Job ID</th>';
		echo				'<th class = "text-center">Position</th>';
		echo				'<th class = "text-center">Salary</th>';
		echo				'<th class = "text-center">Posted By</th>';
		echo			'</tr>';
		echo		'</thead>';
		echo		'<tbody class = "text-left">';
		//get all the jobs where this bekar's application is pending
		$sql = "SELECT id, position, salary, postedby, email FROM pendingapplications NATURAL JOIN jobs
				WHERE email = '$VIEWPENDINGAPPLICATIONSFROMEMAIL'";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			$id = $row['id'];
			$position = $row['position'];
			$salary = $row['salary'];
			$postedby = $row['postedby'];
			//get the name of the company who posted this job
			$sql = "SELECT name FROM users WHERE email = '$postedby'";
			$resultcompanyname = mysqli_fetch_assoc($connection->query($sql));
			$companyname = $resultcompanyname['name'];
			echo		'<tr>';
			echo			'<td class = "text-center">'.$id.'</td>';
			echo			'<td class = "text-center">'.$position.'</td>';
			echo			'<td class = "text-center">'.$salary.'</td>';
			echo			'<td class = "text-center">
								<a href = "./viewcompanyprofile.html?email='.$postedby.'" target = "_blank">
									'.$companyname.
								'</a>';
							'</td>';
			echo		'</tr>';
		}
		echo		'</tbody>';
		echo	'</table>';
		echo '</div>';
	}
	
	//view this bekar's upcoming interviews
	if(isset($_GET['VIEWUPCOMINGINTERVIEWSFROMEMAIL'])){
		$VIEWUPCOMINGINTERVIEWSFROMEMAIL = $_GET['VIEWUPCOMINGINTERVIEWSFROMEMAIL'];
		echo '<br>';
		echo '<p class = "alert alert-info">This section shows your upcoming interviews. Prepare well and best of luck!</p>';
		echo '<div class = "well">';
		echo 	'<br>';
		echo 	'<table class = "table table-striped">';
		echo		'<thead>';
		echo 			'<tr>';
		echo				'<th class = "text-center">Job ID</th>';
		echo				'<th class = "text-center">Posted By</th>';
		echo				'<th class = "text-center">Position</th>';
		echo				'<th class = "text-center">Type</th>';
		echo				'<th class = "text-center">Salary</th>';
		echo				'<th class = "text-center">Circular</th>';
		echo				'<th class = "text-center">Interview Date</th>';
		echo				'<th class = "text-center">Interview Time</th>';
		echo			'</tr>';
		echo		'</thead>';
		echo		'<tbody class = "text-left">';
		//get all the jobs where this bekar has an upcoming interview
		$sql = "SELECT id, postedby, position, type, salary, circular, interviewdate, interviewtime FROM interviewcalls NATURAL JOIN jobs
				WHERE email = '$VIEWUPCOMINGINTERVIEWSFROMEMAIL' ORDER BY interviewdate";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			$id = $row['id'];
			$postedby = $row['postedby'];
			$position = $row['position'];
			$type = $row['type'];
			$salary = $row['salary'];
			$circular = $row['circular'];
			$interviewdate = $row['interviewdate'];
			$interviewtime = $row['interviewtime'];
			//get the name of the company who posted this job
			$sql = "SELECT name FROM users WHERE email = '$postedby'";
			$resultcompanyname = mysqli_fetch_assoc($connection->query($sql));
			$companyname = $resultcompanyname['name'];
			echo		'<tr>';
			echo			'<td class = "text-center">'.$id.'</td>';
			echo			'<td class = "text-center">
								<a href = "./viewcompanyprofile.html?email='.$postedby.'" target = "_blank">
									'.$companyname.
								'</a>';
							'</td>';
			echo			'<td class = "text-center">'.$position.'</td>';
			echo			'<td class = "text-center">'.$type.'</td>';
			echo			'<td class = "text-center">'.$salary.'</td>';
			echo			'<td class = "text-center">
								<a href = "'.$circular.'" download><span class = "glyphicon glyphicon-download"></span> Circular</a>
							</td>';
			echo			'<td class = "text-center">'.$interviewdate.'</td>';
			echo			'<td class = "text-center">'.$interviewtime.'</td>';
			echo		'</tr>';
		}
		echo		'</tbody>';
		echo	'</table>';
		echo '</div>';
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