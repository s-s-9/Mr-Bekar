$(window).on('load', startDoingStuffs);

function getUserInfo(){
	//get user's email from server, as email is to uniqify users
	$.ajax({
		url: phpfile,
		data: {
			GETUSERNAME: '?'
		},
		success:
			function(result){
				console.log(result);
				//if result equals nobody, then no user is logged in, and we have to redirect to the home page
				if(result=='nobody'){
					window.location.href = 'index.html';
				}
				else{
					//save the user's email in user
					user = result;
					//start the other works
					startWorkingConfidently();
				}
			}
	});
}

function logoutButtonWorks(){
	$('#logoutButton').click(function(){
		$.ajax({
			url: phpfile,
			data: {
				LOGOUT: '?'
			},
			success:
				function(result){
					//redirect to home page
					window.location.href = 'index.html';
				}
		});	
	});
}

function searchAtNavbar(){
	//get whatever the user typed in the search box
	console.log('searching');
	//don't let the dropdown to be hidden if there are valid results
	$('.dropdown').on('hide.bs.dropdown', function(event){
		if($('#searchAtNavbarResults').html()!=''){
			event.preventDefault();
		}
	});
	//don't show the dropdown if there is no result to be shown
	$('.dropdown').on('show.bs.dropdown', function(event){
		if($('#searchAtNavbarResults').html()==''){
			event.preventDefault();
		}
	});
	$('#searchAtNavbar').keyup(function(){
		//save the thing that the user typed
		var searchedFor = (this.value);
		//if the user typed something, send it to ajax and retrieve results
		if(searchedFor.length>0){
			$.ajax({
				url: phpfile,
				data: {
					SEARCHATNAVBAR: searchedFor
				},
				success:
					function(result){
						//php should return a form; add it in the section under the search bar
						$('#searchAtNavbarResults').html(result);
						//view the results to the user
						$('.dropdown-toggle').dropdown('toggle');
					}
			});
		}
		//if the user typed nothing, remove things in the results section and hide the dropdown
		else{
			$('#searchAtNavbarResults').html('');
			$('.dropdown-toggle').dropdown('toggle');
		}
	});
}

function hideRejectedApplicants(){
	$('.rejectButton').click(function(){
		var rejectfrom = this.id.substring(10);
		var rejected = $(this).parent().prev().children(':first').attr('href').substring(30);
		//tell php to remove this entry from the penndingapplicationstable
		$.ajax({
			url: phpfile,
			data: {
				REJECTAPPLICANT: rejected, REJECTFROM: rejectfrom
			},
			success:
				function(result){
					//console.log(result);
				}
		});
		//hide this row
		$(this).parent().parent().hide();
	});
}

function scheduleInterview(){
	$('.interviewForm').submit(function(event){
		event.preventDefault();
		var UPLOAD = new FormData(this);
		$.ajax({
			url: phpfile,
			type: "POST",	//must be post
			data: UPLOAD, 	//sending the form. tried sending multiple data, but couldn't retrieve them
			contentType: false,	//not sure, probably not must
			cache: false,	//not must
			processData: false,	//must
			success:
				function(result){
					console.log(result);
					//result holds jobid+applicantemail of the job whose interview has been scheduled
					var rowtohide = '#rowFor' + result;
					//hide the row which contained the application we just scheduled interview for
					$(rowtohide).hide();
					//hide the modal
					$('.interviewModal').modal('hide');
					//update the upcoming interviews section
					showUpcomingInterviews();
				}
		});
		
	});
}

function callForInterview(){
	//get all the jobs for this company bekars applied for
	$.ajax({
		url: phpfile,
		data: {
			USEREMAILTOCALLFORINTERVIEW: user
		},
		success:
			function(result){
				$('#callForInterview').html(result);
				//show applicants on click
				$('.showApplicants').click(function(){
					$(this).parent().next().slideToggle();
				});
				//hide rejected applicants
				hideRejectedApplicants();
				//schedule the interview
				scheduleInterview();
			}
	});
}

function showUpcomingInterviews(){
	$.ajax({
		url: phpfile,
		data: {
			SHOWUPCOMINGINTERVIEWS: user
		},
		success:
			function(result){
				//console.log(result);
				$('#upcomingInterviews').html(result);
			}
	});
}

function startWorkingConfidently(){
	//make the logout button alive
	logoutButtonWorks();
	//let the company call for interviews
	callForInterview();
	//show upcoming interviews
	showUpcomingInterviews();
	//let the user search for other users
	searchAtNavbar();
}

function startDoingStuffs(){
	//this will hold the user's email
	user = '';	
	//this will hold the address of the php file
	phpfile = './php/companyhistory.php';
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	//know which user logged in, then start other works
	getUserInfo();
}