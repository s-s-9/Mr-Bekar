$(window).on('load', startDoingStuffs);

function showUsersName(){
	//make ajax call to get the name from email
	$.ajax({
		url: phpfile,
		data: {
			GETNAMEFROMEMAIL: user
		},
		success:
			function(result){
				console.log(result);
				$('#showNameInProfile').html(result);
			}
	});
}

function showProfilePic(){
	//make ajax call get location of the image
	$.ajax({
		url: phpfile,
		data: {
			GETPICTUREFROMEMAIL: user
		},
		success:
			function(result){
				console.log(result);
				$('#profilePic').attr('src', result);
				$('#viewProfilePicImg').attr('src', result);
			}
	});
}

function showContactInfo(){
	//add this company's email to the contact section
	$('#companyEmail').html(user);
	//get this company's website from server
	$.ajax({
		url: phpfile,
		data: {
			GETWEBSITEFROMEMAIL: user
		},
		success:
			function(result){
				//add this company's website to the contact section
				$('#companyWebsite').html(result);
				//if result was not unavailable, then add the link to the href
				if(result!='unavailable'){
					$('#companyWebsite').prop('href', result);
				}
			}
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

function viewActiveJobs(){
	//grab the jobs posted by this company from server
	$.ajax({
		url: phpfile,
		data: {
			VIEWJOBSPOSTEDFROMEMAIL: user
		},
		success:
			function(result){
				$('#jobsPostedByThisCompany').append(result);
			}
	});
}

function viewStatsAboutCompany(){
	$.ajax({
		url: phpfile,
		data: {
			VIEWSTATSFROMEMAIL: user
		},
		success:
			function(result){
				$('#companyStats').html(result);
			}
	});
}

function startWorkingConfidently(){
	//show user's name at the top
	showUsersName();
	//view user's profile picture
	showProfilePic();
	//show contact info 
	showContactInfo();
	//view active jobs posted by this company
	viewActiveJobs();
	//view statistics about this company here
	viewStatsAboutCompany();
	//let the user search for other users
	searchAtNavbar();
}

function startDoingStuffs(){
	//this will be the php file where data will be sent
	phpfile = './php/viewcompanyprofile.php';
	//this will hold the user's email, which had been sent to the url
	user = location.search.substr(7);
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	startWorkingConfidently();
}