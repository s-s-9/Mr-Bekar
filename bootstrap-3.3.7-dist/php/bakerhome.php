<?php
	session_start();
	
	//this will hold the number of jobs eligible for this bekar
	$jobstoshow = 0;
	
	//query that merges job requirements
	$sql = "SELECT jobskills.dbm, jobskills.gmd, jobskills.mad, jobskills.net, 
			jobskills.oop, jobskills.prs, jobskills.wdb, jobskills.wdf, 
			joblanguages.cor, joblanguages.cpp, joblanguages.jav, joblanguages.pyt, 
			joblanguages.htm, joblanguages.css, joblanguages.php, joblanguages.msq, 
			joblanguages.csh, joblanguages.rub, joblanguages.per, joblanguages.njs, 
			joblanguages.dot, joblanguages.mon, joblanguages.vba, joblanguages.fba, 
			jobs.id FROM jobskills NATURAL JOIN joblanguages NATURAL JOIN jobs"; 
	
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
	
	//return how many jobs there are for this bekar
	if(isset($_GET['GETPAGINATIONBUTTONNUMBERSFROMEMAIL'])){
		$jobs = 0;
		$GETPAGINATIONBUTTONNUMBERSFROMEMAIL = $_GET['GETPAGINATIONBUTTONNUMBERSFROMEMAIL'];
		//get this user's skills and languages from the database
		$sql = "SELECT * FROM bekarskills WHERE email = '$GETPAGINATIONBUTTONNUMBERSFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$dbm = $result['dbm'];	$gmd = $result['gmd'];	$mad = $result['mad'];	$net = $result['net'];
		$oop = $result['oop'];	$prs = $result['prs'];	$wdb = $result['wdb'];	$wdf = $result['wdf'];
		$sql = "SELECT * FROM bekarlanguages WHERE email = '$GETPAGINATIONBUTTONNUMBERSFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$cor = $result['cor'];	$cpp = $result['cpp'];	$jav = $result['jav'];	$pyt = $result['pyt'];
		$htm = $result['htm'];	$css = $result['css'];	$php = $result['php'];	$msq = $result['msq'];
		$csh = $result['csh'];	$rub = $result['rub'];	$per = $result['per'];	$njs = $result['njs'];
		$dot = $result['dot'];	$mon = $result['mon'];	$vba = $result['vba'];	$fba = $result['fba'];
		//get all the jobs that this user qualifies for
		$sql = "SELECT jobs.id AS id FROM jobskills NATURAL JOIN joblanguages NATURAL JOIN jobs WHERE
			    dbm<='$dbm' AND gmd<='$gmd' AND mad<='$mad' AND net<='$net' AND oop<='$oop' AND prs<='$prs' AND wdb<='$wdb' AND wdf<='$wdf' AND 
			    cor<='$cor' AND cpp<='$cpp' AND jav<='$jav' AND pyt<='$pyt' AND htm<='$htm' AND css<='$css' AND php<='$php' AND msq<='$msq' AND 
			    csh<='$csh' AND rub<='$rub' AND per<='$per' AND njs<='$njs' AND dot<='$dot' AND mon<='$mon' AND vba<='$vba' AND fba<='$fba'"; 
		$result = $connection->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$jobid = $row['id'];
				//see if this user has already applied for this job
				$sql = "SELECT * FROM pendingapplications WHERE id = '$jobid' AND email = '$GETPAGINATIONBUTTONNUMBERSFROMEMAIL'";
				$pendingapplications = $connection->query($sql);
				if($pendingapplications->num_rows==0){
					//he hasn't. now see if this user has already been called for an interview for this post
					$sql = "SELECT * FROM interviewcalls WHERE id = '$jobid' AND email = '$GETPAGINATIONBUTTONNUMBERSFROMEMAIL'";
					$interviewcalls = $connection->query($sql);
					if($interviewcalls->num_rows==0){
						$jobs+=1;
					}
					else{
						//echo '0';
					}
				}
				else{
					//echo '0';
				}
			}
		}
		else{
			//echo '0';
		}
		echo $jobs;
	}
	
	//view the jobs this user is eligible for
	if(isset($_GET['GETJOBSFROMEMAIL']) && isset($_GET['SORTBY']) && isset($_GET['ORDER']) 
		&& isset($_GET['STARTINGJOB']) && isset($_GET['JOBSPERPAGE'])){
		$GETJOBSFROMEMAIL = $_GET['GETJOBSFROMEMAIL'];
		$SORTBY = $_GET['SORTBY'];
		$ORDER = $_GET['ORDER'];
		$STARTINGJOB = $_GET['STARTINGJOB'];
		$JOBSPERPAGE = $_GET['JOBSPERPAGE'];
		//get this user's skills and languages from the database
		$sql = "SELECT * FROM bekarskills WHERE email = '$GETJOBSFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$dbm = $result['dbm'];	$gmd = $result['gmd'];	$mad = $result['mad'];	$net = $result['net'];
		$oop = $result['oop'];	$prs = $result['prs'];	$wdb = $result['wdb'];	$wdf = $result['wdf'];
		$sql = "SELECT * FROM bekarlanguages WHERE email = '$GETJOBSFROMEMAIL'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$cor = $result['cor'];	$cpp = $result['cpp'];	$jav = $result['jav'];	$pyt = $result['pyt'];
		$htm = $result['htm'];	$css = $result['css'];	$php = $result['php'];	$msq = $result['msq'];
		$csh = $result['csh'];	$rub = $result['rub'];	$per = $result['per'];	$njs = $result['njs'];
		$dot = $result['dot'];	$mon = $result['mon'];	$vba = $result['vba'];	$fba = $result['fba'];
		//get all the jobs that this user qualifies for
		$sql = "SELECT jobs.id AS id FROM jobskills NATURAL JOIN joblanguages NATURAL JOIN jobs WHERE
			    dbm<='$dbm' AND gmd<='$gmd' AND mad<='$mad' AND net<='$net' AND oop<='$oop' AND prs<='$prs' AND wdb<='$wdb' AND wdf<='$wdf' AND 
			    cor<='$cor' AND cpp<='$cpp' AND jav<='$jav' AND pyt<='$pyt' AND htm<='$htm' AND css<='$css' AND php<='$php' AND msq<='$msq' AND 
			    csh<='$csh' AND rub<='$rub' AND per<='$per' AND njs<='$njs' AND dot<='$dot' AND mon<='$mon' AND vba<='$vba' AND fba<='$fba'
				ORDER BY $SORTBY $ORDER LIMIT $STARTINGJOB, $JOBSPERPAGE"; 
		$result = $connection->query($sql);
		//if (!$result) {
			//trigger_error('Invalid query: ' . $connection->error);
		//}
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$jobid = $row['id'];
				//see if this user has already applied for this job
				$sql = "SELECT * FROM pendingapplications WHERE id = '$jobid' AND email = '$GETJOBSFROMEMAIL'";
				$pendingapplications = $connection->query($sql);
				if($pendingapplications->num_rows==0){
					//he hasn't. now see if this user has already been called for an interview for this post
					$sql = "SELECT * FROM interviewcalls WHERE id = '$jobid' AND email = '$GETJOBSFROMEMAIL'";
					$interviewcalls = $connection->query($sql);
					if($interviewcalls->num_rows==0){
						//now actually view the job
						$sql = "SELECT * FROM jobs WHERE id = '$jobid'";
						$actualjob = mysqli_fetch_assoc($connection->query($sql));
						echo '<tr>';
						echo 	'<td class = "jobId">'.$jobid.'</td>';
						//show profile link to the company that posted the job
						$companymail = $actualjob['postedby'];
						$sql = "SELECT name FROM users WHERE email = '$companymail'";
						$companyname = mysqli_fetch_assoc($connection->query($sql));
						echo 	'<td>
									<a href = "./viewcompanyprofile.html?email='.$companymail.'" target = "_blank">'.
									$companyname['name'].'</a>'.
								'</td>';
						echo 	'<td>'.$actualjob['position'].'</td>';
						echo 	'<td>'.$actualjob['type'].'</td>';
						echo 	'<td>'.$actualjob['salary'].'</td>';
						echo 	'<td>'.$actualjob['deadline'].'</td>';
						echo 	'<td>'.'<a href = "'.$actualjob['circular'].'" download>
											<span class = "glyphicon glyphicon-download">Circular</span>
										</a>
								</td>';
						echo 	'<td class = "applyButton"><button class = "btn-link">
								 <span class = "glyphicon glyphicon-user"></span></button></td>';
						echo '</tr>';
					}
					else{
						//echo '0';
					}
				}
				else{
					//echo '0';
				}
			}
		}
		else{
			//echo '0';
		}
	}
	
	//if a user applied for a job, add an entry to the pendingapplications table
	if(isset($_GET['APPLIEDFORJOB']) && isset($_GET['APPLIEDFORJOBID'])){
		$APPLIEDFORJOB = $_GET['APPLIEDFORJOB'];
		$APPLIEDFORJOBID = $_GET['APPLIEDFORJOBID'];
		$sql = "INSERT INTO pendingapplications (id, email) VALUES ('$APPLIEDFORJOBID', '$APPLIEDFORJOB')";
		if($connection->query($sql)===true){
			echo 'applicationpending';
		}
		else{
			echo 'applicationnotpending';
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