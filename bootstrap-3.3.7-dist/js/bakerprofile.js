$(window).on('load', startDoingStuffs);

function getUserInfo(){
	//get user's email from server, as email is to uniqify users
	$.ajax({
		url: './php/bakerprofile.php',
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
			url: './php/bakerprofile.php',
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

function showResume(){
	//initially hide both the resume link and the you haven't uploaded resume yet message
	$('#noResumeWarning').hide();
	$('#resumeLink').hide();
	//see if the user has uploaded a resume
	$.ajax({
		url: phpfile,
		data: {
			GETRESUMEFROMEMAIL: user
		},
		success:
			function(result){
				//if resume is unavailable, show the resume not available section
				if(result=='unavailable'){
					$('#noResumeWarning').show();
				}
				else{
					//add link to the uploaded resume
					$('#resumeLink').attr('href', result);
					//make file name appear at the link
					$('#resumeLink').html('<span class = "glyphicon glyphicon-download"></span> Resume');
					//show downloadable resume
					$('#resumeLink').show();
				}
			}
	});
}

function uploadResume(){
	//know when the upload button is clicked
	$('#uploadResumeForm').submit(function(event){
		console.log('form for resume received');
		event.preventDefault();
		var file = $('#resume').prop('files')[0];
		var name = file.name;
		var ext = name.split('.');
		ext = ext[ext.length-1].toLowerCase();
		//if extension is not pdf or doc, show error
		if(ext=='pdf' || ext=='doc' || ext=='docx'){
			//if size is greater than 500 kb, show error
			var size = file.size;
			if(size<=500000){
				//make form data, so that the form can be passed to php using post
				var UPLOAD = new FormData(this);
				//add an additional field to the form to let php know which user we're dealing with
				UPLOAD.append("USEREMAILFORRESUMEUPLOAD", user);
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
								//hide the resume upload modal
								$('#updateResumeModal').modal('hide');
								//hide resume not available section
								$('#noResumeWarning').hide();
								//add link to the uploaded resume
								$('#resumeLink').attr('href', result);
								//make file name appear at the link
								$('#resumeLink').html(result);
								//show downloadable resume in resume section
								$('#resumeLink').show();
								window.location.href = 'bakerprofile.html';
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

function showSkills(){
	//make ajax call to get the skills
	$.ajax({
		url: phpfile,
		data: {
			USEREMAILFORSKILLSHOW: user
		},
		success:
			function(result){
				//mapping of skill to index
				var skillsArray = ['Database Management', 'Game Development', 'Mobile Application Development', 'Networking', 
				'Object Orinted Programming', 'Problem Solving', 'Web Development(Back End)', 'Web Development (Front End)'];
				//this will hold the skills in the form of a list
				var dt = '';
				//for each 1 in the result string, add html inside skillList
				for(var i = 0; i<result.length; i++){
					if(result[i]=='1'){
						//add a list item
						dt = dt + '<dt>' + skillsArray[i] + '</dt>'
					}
				}
				//finally, add this html to the skillList
				$('#skillList').html(dt);
			}
	});
}

function updateSkills(){
	//don't let the user select more than 5 of these
	var skillsSelected = 0;
	//if an input is changed, see if it got checked or unchecked
	$('#updateSkillsForm input[type=checkbox]').change(function(){
		if($(this)[0].checked==false){
			//decrement skillsSelected
			skillsSelected--;
			//enable all checkboxes
			$('#updateSkillsForm input[type=checkbox]').attr('disabled', false);
		}
		//if this got checked, see if 5 has been checked and disable checkboxes accordingly
		else{
			//increment skillsSelected
			skillsSelected++;
			if(skillsSelected==5){
				//first disable all checkboxes
				$('#updateSkillsForm input[type=checkbox]').attr('disabled', true);
				//then enable only the ones that are selected
				$('#updateSkillsForm input[type=checkbox]:checked').attr('disabled', false);
			}
		}
	} );
	//add submit event listener to the form
	$('#updateSkillsForm').submit(function(event){
		event.preventDefault();
		//get the checked values
		var skillsArray = [];
		$('#updateSkillsForm input[type=checkbox]:checked').each(function(){
			skillsArray.push(this.value);
		});
		//console.log(skillsArray);
		//send this skills array to php
		$.ajax({
			url: phpfile,
			data: {
				UPDATESKILLS: skillsArray, USEREMAILFORSKILLUPDATE: user
			},
			success:
				function(result){
					console.log(result);
					//hide the modal
					$('#updateSkillsModal').modal('hide');
					//show the user's skills
					showSkills();
				}
		});
	});
}

function showLanguages(){
	//make ajax call to get the languages
	$.ajax({
		url: phpfile,
		data: {
			USEREMAILFORLANGUAGESHOW: user
		},
		success:
			function(result){
				//mapping of language to index
				var languagesArray = ['C', 'C++', 'Java', 'Python', 'HTML5', 'CSS3', 'PHP', 'MySql', 
									  'C#', 'Ruby', 'Perl', 'Node JS', '.NET', 'MongoDB', 'Visual Basic', 'Firebase'];
				//this will hold the languages in the form of a list
				var dt = '';
				//for each 1 in the result string, add html inside skillList
				for(var i = 0; i<result.length; i++){
					if(result[i]=='1'){
						//add a list item
						dt = dt + '<dt>' + languagesArray[i] + '</dt>'
					}
				}
				//finally, add this html to the languageList
				$('#languageList').html(dt);
			}
	});
}

function updateLanguages(){
	//don't let the user select more than 10 of these
	var languagesSelected = 0;
	//if an input is changed, see if it got checked or unchecked
	$('#updateLanguagesForm input[type=checkbox]').change(function(){
		if($(this)[0].checked==false){
			//decrement languagesSelected
			languagesSelected--;
			//enable all checkboxes
			$('#updateLanguagesForm input[type=checkbox]').attr('disabled', false);
		}
		//if this got checked, see if 10 has been checked and disable checkboxes accordingly
		else{
			//increment languagesSelected
			languagesSelected++;
			if(languagesSelected==10){
				//first disable all checkboxes
				$('#updateLanguagesForm input[type=checkbox]').attr('disabled', true);
				//then enable only the ones that are selected
				$('#updateLanguagesForm input[type=checkbox]:checked').attr('disabled', false);
			}
		}
	} );
	//add submit event listener to the form
	$('#updateLanguagesForm').submit(function(event){
		event.preventDefault();
		//get the checked values
		var languagesArray = [];
		$('#updateLanguagesForm input[type=checkbox]:checked').each(function(){
			languagesArray.push(this.value);
		});
		//console.log(languagesArray);
		//send this languages array to php
		$.ajax({
			url: phpfile,
			data: {
				UPDATELANGUAGES: languagesArray, USEREMAILFORLANGUAGEUPDATE: user
			},
			success:
				function(result){
					console.log(result);
					//hide the modal
					$('#updateLanguagesModal').modal('hide');
					//show the user's languages
					showLanguages();
				}
		});
	});
}

function showUserSkillsInProjectUpload(){
	//call ajax to get this user's skills
	$.ajax({
		url: phpfile,
		data: {
			USEREMAILFORSHOWINGSKILLSINPROJECTUPLOAD: user
		},
		success:
			function(result){
				var selectOptions = "";
				var skillsArrayShort = ['dbm', 'gmd', 'mad', 'net', 'oop', 'prs', 'wdb', 'wdf'];
				var skillsArray = ['Database Management', 'Game Development', 'Mobile Application Development', 'Networking', 
				'Object Orinted Programming', 'Problem Solving', 'Web Development(Back End)', 'Web Development (Front End)'];
				for(var i = 0; i<result.length; i++){
					if(result[i]=='1'){
						selectOptions = selectOptions + '<option value = "' + skillsArrayShort[i] + '">';
						selectOptions = selectOptions + skillsArray[i] + '</option>';
					}
				}
				$('#projectSkills').html(selectOptions);
			}
	});
}

function showProjects(){
	//make ajax call to retrieve all projects of this user
	$.ajax({
		url: phpfile,
		data: {
			USEREMAILFORSHOWINGPROJECTS: user
		},
		success:
			function(result){
				$('#showProjects').html(result);
				//let the user delete a project
				deleteProject();
			}
	});
}

function deleteProject(){
	$('.deleteProjectButton').click(function(){
		console.log($(this).siblings('a').attr('href'));
		var projectArea = $(this).parent();
		//show the are you sure you want to delete modal
		$('#confirmDeleteProjectModal').modal('show');
		//if the user confirmed by clicking the delete button
		$('#confirmDeleteButton').click(function(){
			//remove this project's row from the database and delete the project file
			$.ajax({
				url: phpfile,
				data: {
					USEREMAILFORPROJECTDELETE: user
				},
				success:
					function(result){
						//console.log(result);
					}
			});
			projectArea.slideUp();
		});
	});
}

function uploadProject(){
	//set event listener on form submit
	$('#updateProjectForm').submit(function(event){
		event.preventDefault();
		console.log('form submitted');
		var file = $('#projectFile').prop('files')[0];
		var name = file.name;
		var ext = name.split('.');
		ext = ext[ext.length-1].toLowerCase();
		//if extension is not rar or zip, show error
		if(ext=='rar' || ext=='zip'){
			//if size is greater than 500 kb, show error
			var size = file.size;
			if(size<=500000){
				//make form data, so that the form can be passed to php using post
				var UPLOAD = new FormData(this);
				//add an additional field to the form to let php know which user we're dealing with
				UPLOAD.append("USEREMAILFORPROJECTUPLOAD", user);
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
							//if upload was successful, show project in the website
							if(result!='failedtoupdatedatabase'){
								//console.log(result);
								//hide the project upload modal
								$('#updateProjectModal').modal('hide');
								//window.location.href = 'bakerprofile.html';
								showProjects();
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

function startWorkingConfidently(){
	//make the logout button alive
	logoutButtonWorks();
	//show user's name at the top
	showUsersName();
	//view user's profile picture
	showProfilePic();
	//let the user change profile pic
	changeProfilePic();
	//show user's resume
	showResume();
	//let the user upload resume
	uploadResume();
	//show the user's skills
	showSkills();
	//add or remove skills
	updateSkills();
	//show the user's skills
	showLanguages();
	//add or remove skills
	updateLanguages();
	//show the user's projects
	showProjects();
	//show this user's skills in the select dropdown when uploading projects
	showUserSkillsInProjectUpload();
	//let the user upload a new project
	uploadProject();
	//let the user search for other users
	searchAtNavbar();
}

function startDoingStuffs(){
	//this will be the php file where data will be sent
	phpfile = './php/bakerprofile.php';
	//this will hold the user's email
	user = '';	
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	//know which user logged in, then start other works
	getUserInfo();
}