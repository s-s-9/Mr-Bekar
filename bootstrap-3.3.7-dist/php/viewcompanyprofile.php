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