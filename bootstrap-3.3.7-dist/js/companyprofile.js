$(window).on('load', startDoingStuffs);

function getUserInfo(){
	//get user's email from server, as email is to uniqify users
	$.ajax({
		url: './php/companyprofile.php',
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
			url: './php/companyprofile.php',
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

function changeProfilePic(){
	//know when the upload button is clicked
	$('#uploadPictureForm').submit(function(event){
		console.log('form received');
		event.preventDefault();
		var file = $('#picture').prop('files')[0];
		var name = file.name;
		var ext = name.split('.');
		ext = ext[ext.length-1].toLowerCase();
		//if extension is not jpg, jpeg, or png, show error
		if(ext=='jpg' || ext=='jpeg' || ext=='png'){
			//if size is greater than 500 kb, show error
			var size = file.size;
			if(size<=500000){
				//make form data, so that the form can be passed to php using post
				var UPLOAD = new FormData(this);
				//add an additional field to the form to let php know which user we're dealing with
				UPLOAD.append("USEREMAILFORPHOTOUPLOAD", user);
				//send the request
				$.ajax({
					url: phpfile,
					type: "POST",	//must be post
					data: UPLOAD, 	//sending the form. tried sending multiple data, but couldn't retrieve them
					contentType: false,	//not sure, probably not must
					cache: false,	//not must
					processData: false,	//must
					success:
						function(result){
							//if upload was successful, update profile picture in the website
							if(result!='failedtoupdatedatabase'){
								console.log(result);
								//hide the photo upload modal
								$('#updateProfilePicModal').modal('hide');
								//show uploaded image as profile picture
								$('#profilePic').attr('src', result);
								//show this picture in full-screen when clicked
								$('#viewProfilePicImg').attr('src', result);
								//window.location.href = 'bakerprofile.html';
							}
							else{
								console.log(result);
							}
						}
				});
			}
			else{
				$('#fileSizeErrorModal').modal('show');
			}
		}
		else{
			$('#fileFormatErrorModal').modal('show');
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

function updateWebsite(){
	//add show the modal to update website when the edit button is clicked
	$('#updateWebsite').click(function(){
		$('#updateWebsiteModal').modal('show');
	});
	//when the user entered the url and submitted, catch that event
	$('#updateWebsiteForm').submit(function(event){
		event.preventDefault();
		var websiteurl = $('#websiteurl').val();
		console.log(websiteurl);
		//send this new url to php
		$.ajax({
			url: phpfile,
			data: {
				USEREMAILFORWEBSITEUPDATE: user, WEBSITEURL: websiteurl
			},
			success:
				function(result){
					//close the modal
					$('#updateWebsiteModal').modal('hide');
					//show it immediately
					showContactInfo();
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
	//make the logout button alive
	logoutButtonWorks();
	//show user's name at the top
	showUsersName();
	//view user's profile picture
	showProfilePic();
	//let the user change profile pic
	changeProfilePic();
	//show contact info 
	showContactInfo();
	//let the user update website
	updateWebsite();
	//view active jobs posted by this company
	viewActiveJobs();
	//view statistics about this company here
	viewStatsAboutCompany();
	//let the user search for other users
	searchAtNavbar();
}

function startDoingStuffs(){
	//this will be the php file where data will be sent
	phpfile = './php/companyprofile.php';
	//this will hold the user's email
	user = '';	
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	//know which user logged in, then start other works
	getUserInfo();
}