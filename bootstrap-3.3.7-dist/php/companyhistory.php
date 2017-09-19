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
	
	//reject an applicant
	if(isset($_GET['REJECTAPPLICANT']) && isset($_GET['REJECTFROM'])){
		$REJECTAPPLICANT = $_GET['REJECTAPPLICANT'];
		$REJECTFROM = $_GET['REJECTFROM'];
		$sql = "DELETE FROM pendingapplications WHERE email = '$REJECTAPPLICANT' AND id = '$REJECTFROM'";
		if($connection->query($sql)===true){
			echo 'rejected';
		}
		else{
			echo 'failedrejection';
		}
	}
	
	//note that a bekar has been called for interview
	if(isset($_POST['interviewDate']) && isset($_POST['interviewTime'])){
		$applicantemail = $_POST['applicantEmail'];
		$applicantemailat = str_replace('@', 'at', $applicantemail);
		$applicantemailat = str_replace('.', 'dot', $applicantemailat);
		$jobid = $_POST['jobId'];
		$interviewdate = $_POST['interviewDate'];
		$interviewtime = $_POST['interviewTime'];
		//delete the entry of this bekar for this job from pendingapplications and add entry to interviewcalls
		$sql = "DELETE FROM pendingapplications WHERE email = '$applicantemail' AND id = '$jobid'";
		if($connection->query($sql)===true){
			//echo 'removed from pendingapplications';
		}
		else{
			echo 'failedremovingfrompendingapplications';
		}
		$sql = "INSERT INTO interviewcalls (id, email, interviewdate, interviewtime)
				VALUES ('$jobid', '$applicantemail', '$interviewdate', '$interviewtime')";
		if($connection->query($sql)===true){
			//echo 'added entry to interviewcalls';
		}
		else{
			echo 'failedaddingtointerviewcalls';
		}
		//increment the number of interview calls for this company
		$sql = "SELECT postedby FROM jobs WHERE id = '$jobid'";
		$result = mysqli_fetch_assoc($connection->query($sql));
		$companyemail = $result['postedby'];
		$sql = "UPDATE companies SET interviewcalls = interviewcalls + 1 WHERE email = '$companyemail'";
		if($connection->query($sql)===true){
			//echo 'updated companies';
		}
		else{
			echo 'failedtoupdatecompanies';
		}
		echo $jobid.$applicantemailat;
	}
	
	//let the user call bekars for interviews
	if(isset($_GET['USEREMAILTOCALLFORINTERVIEW'])){
		$USEREMAILTOCALLFORINTERVIEW = $_GET['USEREMAILTOCALLFORINTERVIEW'];
		echo '<br>';
		echo '<p class = "alert alert-info">See who applied for each job you posted, review applicant profiles and make decisions.</p>';
		//get all the jobs from the pendingapplications table posted by this company
		$sql = "SELECT email, id, postedby, position, type, salary, deadline, circular 
				FROM pendingapplications NATURAL JOIN jobs WHERE postedby = '$USEREMAILTOCALLFORINTERVIEW'
				ORDER BY id";
		//get all the active jobs posted by this company
		$sql = "SELECT * FROM jobs WHERE postedby = '$USEREMAILTOCALLFORINTERVIEW'";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			$id = $row['id'];
			$position = $row['position'];
			$type = $row['type'];
			$salary = $row['salary'];
			$deadline = $row['deadline'];
			$circular = $row['circular'];
			//make the heading div for this job
			echo '<div class = "well">';
			echo 	'<br>';
			echo 	'<table class = "table table-striped">';
			echo		'<thead>';
			echo 			'<tr>';
			echo				'<th class = "text-center">Job ID</th>';
			echo				'<th class = "text-center">Position</th>';
			echo				'<th class = "text-center">Type</th>';
			echo				'<th class = "text-center">Salary</th>';
			echo				'<th class = "text-center">Deadline</th>';
			echo				'<th class = "text-center">Circular</th>';
			echo			'</tr>';
			echo		'</thead>';
			echo		'<tbody class = "text-left">';
			echo			'<tr>';
			echo				'<td class = "text-center">'.$id.'</td>';
			echo				'<td class = "text-center">'.$position.'</td>';
			echo				'<td class = "text-center">'.$type.'</td>';
			echo				'<td class = "text-center">'.$salary.'</td>';
			echo				'<td class = "text-center">'.$deadline.'</td>';
			echo				'<td class = "text-center"><a href = "'.$circular.'" download>
									<span class = "glyphicon glyphicon-download"></span>Circular</a>
								</td>';
			echo			'</tr>';
			echo		'</tbody>';
			echo	'</table>';
			echo	'<button type = "button" class = "btn btn-block btn-primary showApplicants">';
			echo		'<span class = "glyphicon glyphicon-chevron-down"></span>';
			echo	'</button>';
			echo '</div>';
			//get the current date
			$sql = "SELECT CURDATE() AS d";
			$dateresult = mysqli_fetch_assoc($connection->query($sql));
			$today = $dateresult['d'];
			//get all the pending applications for this job
			echo '<div class = "container collapse">';
			$sql = "SELECT email, name, id FROM pendingapplications NATURAL JOIN users WHERE id = '$id'";
			$applicants = $connection->query($sql);
			while($applicantrows = $applicants->fetch_assoc()){
				$email = $applicantrows['email'];
				$emailat = str_replace('@', 'at', $email);
				$emailat = str_replace('.', 'dot', $emailat);
				$name = $applicantrows['name'];
				echo	'<div class = "row" id = "rowFor'.$id.$emailat.'">';
				echo		'<div class = "text-left col-md-6 col-sm-6 col-xs-6">';
				echo			'<a href = "./viewbakerprofile.html?email='.$email.'" target = "_blank">'.$name.'</a>';
				echo		'</div>';
				echo		'<div class = "text-right col-md-2 col-md-offset-4 col-sm-3 col-sm-offset-3 col-xs-6 btn-group">';
				echo			'<button type = "button" class = "btn btn-success" data-toggle = "modal" data-target = "#job'.$id.'Modal">Call</button>';
				echo			'<button type = "button" class = "btn btn-danger rejectButton" id = "rejectFrom'.$id.'">Reject</button>';
				echo		'</div>';
				echo		'<div class = "modal fade class interviewModal" id = "job'.$id.'Modal">';
				echo			'<div class = "modal-dialog">';
				echo				'<div class = "modal-content">';
				echo					'<div class = "modal-header">';
				echo						'<button type = "button" class = "close" data-dismiss = "modal">&times;</button>';
				echo						'<h4 class = "modal-title">Call for Interview</h4>';
				echo					'</div>';
				echo					'<div class = "modal-body">';
				echo						'<form id = "job'.$id.'Form" class = "interviewForm">';
				echo							'<input type = "hidden" name = "applicantEmail" value = "'.$email.'">';
				echo							'<input type = "hidden" name = "jobId" value = "'.$id.'">';
				echo							'<div class = "form-group">';
				echo								'<label for = "interviewDate">Select a Date for Interview</label>';
				echo								'<input class = "form-control" min = "'.$today.'" max = "'.$deadline.'" type = "date" name = "interviewDate" required>';
				echo							'</div>';
				echo							'<div class = "form-group">';
				echo								'<label for = "interviewTime">Select a Time for Interview</label>';
				echo								'<input class = "form-control" type = "time" name = "interviewTime" required>';
				echo							'</div>';
				echo						'</form>';
				echo					'</div>';
				echo					'<div class = "modal-footer">';
				echo						'<button type = "submit" form = "job'.$id.'Form" class = "btn btn-success callButton">Call</button>';
				echo						'<button type = "button" class = "btn btn-primary" data-dismiss = "modal">Close</button>';
				echo					'</div>';
				echo				'</div>';
				echo			'</div>';
				echo		'</div>';
				echo	'</div>';
				echo	'<br>';
			}
			echo '</div>';
		}
	}
	
	//show upcoming interviews
	if(isset($_GET['SHOWUPCOMINGINTERVIEWS'])){
		$SHOWUPCOMINGINTERVIEWS = $_GET['SHOWUPCOMINGINTERVIEWS'];
		echo '<br>';
		echo '<p class = "alert alert-info">These are your upcoming interviews. Have mercy on these poor folks!</p>';
		echo '<div class = "well">';
		echo 	'<br>';
		echo 	'<table class = "table table-striped">';
		echo		'<thead>';
		echo 			'<tr>';
		echo				'<th class = "text-center">Job ID</th>';
		echo				'<th class = "text-center">Position</th>';
		echo				'<th class = "text-center">Applicant</th>';
		echo				'<th class = "text-center">Interview Date</th>';
		echo				'<th class = "text-center">Interview Time</th>';
		echo			'</tr>';
		echo		'</thead>';
		echo		'<tbody class = "text-left">';
		//get the interview schedules for this company
		$sql = "SELECT id, postedby, email, position, interviewdate, interviewtime FROM interviewcalls NATURAL JOIN jobs
				WHERE postedby = '$SHOWUPCOMINGINTERVIEWS' ORDER BY interviewdate, interviewtime";
		$result = $connection->query($sql);
		while($row = $result->fetch_assoc()){
			$id = $row['id'];
			$position = $row['position'];
			$applicantemail = $row['email'];
			$interviewdate = $row['interviewdate'];
			$interviewtime = $row['interviewtime'];
			//get the applicant's name from email
			$sql = "SELECT name FROM users WHERE email = '$applicantemail'";
			$applicantnameresult = mysqli_fetch_assoc($connection->query($sql));
			$applicantname = $applicantnameresult['name'];
			echo		'<tr>';
			echo			'<td class = "text-center">'.$id.'</td>';
			echo			'<td class = "text-center">'.$position.'</td>';
			echo			'<td class = "text-center">
								<a href = "./viewbakerprofile.html?email='.$applicantemail.'" target = "_blank">
									'.$applicantname.
								'</a>';
							'</td>';
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