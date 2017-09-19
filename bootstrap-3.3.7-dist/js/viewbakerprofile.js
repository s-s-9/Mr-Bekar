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
	//make ajax call to get location of the image
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
			}
	});
}



function startWorkingConfidently(){
	//show user's name at the top
	showUsersName();
	//view user's profile picture
	showProfilePic();
	//show user's resume
	showResume();
	//show the user's skills
	showSkills();
	//show the user's languages
	showLanguages();
	//show the user's projects
	showProjects();
}

function startDoingStuffs(){
	//this will be the php file where data will be sent
	phpfile = './php/viewbakerprofile.php';
	//this will hold the user's email, which had been sent to the url
	user = location.search.substr(7);
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	startWorkingConfidently();
}