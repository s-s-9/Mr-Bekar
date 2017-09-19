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

function viewPendingApplications(){
	//retrieve data from server
	$.ajax({
		url: phpfile,
		data: {
			VIEWPENDINGAPPLICATIONSFROMEMAIL: user
		},
		success:
			function(result){
				$('#pendingApplications').html(result);
			}
	});
}

function viewUpcomingInterviews(){
	//retrieve data from server
	$.ajax({
		url: phpfile,
		data: {
			VIEWUPCOMINGINTERVIEWSFROMEMAIL: user
		},
		success:
			function(result){
				$('#upcomingInterviews').html(result);
			}
	});
}

function startWorkingConfidently(){
	//make the logout button alive
	logoutButtonWorks();
	//view pending applications of this bekar
	viewPendingApplications();
	//view upcoming interviews of this bekar
	viewUpcomingInterviews();
	//let the user search for other users
	searchAtNavbar();
}

function startDoingStuffs(){
	//this will hold the user's email
	user = '';	
	//this will hold the address of the php file
	phpfile = './php/bakerhistory.php';
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	//know which user logged in, then start other works
	getUserInfo();
}