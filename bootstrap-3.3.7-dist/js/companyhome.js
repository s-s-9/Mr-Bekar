$(window).on('load', startDoingStuffs);

function getUserInfo(){
	//get user's email from server, as email is to uniqify users
	$.ajax({
		url: './php/companyhome.php',
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
					//don't let companies come to bekar's home and vice versa
					moveAwayIntruders();
					//start the other works
					startWorkingConfidently();
				}
			}
	});
}

function logoutButtonWorks(){
	$('#logoutButton').click(function(){
		$.ajax({
			url: './php/companyhome.php',
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

function bringOutPostJobForm(){
	//initially hide the job post form
	$('#postajobformcontainer').hide();
	$('#postajobbutton').click(function(){
		$('#postajobformcontainer').slideToggle();
	});
}

function restrictNumberofSkills(){
	//don't let the user select more than 3 of these
	var skillsSelected = 0;
	//if an input is changed, see if it got checked or unchecked
	$('#skillsForaJob input[type=checkbox]').change(function(){
		if($(this)[0].checked==false){
			//decrement skillsSelected
			skillsSelected--;
			//enable all checkboxes
			$('#skillsForaJob input[type=checkbox]').attr('disabled', false);
		}
		//if this got checked, see if 3 has been checked and disable checkboxes accordingly
		else{
			//increment skillssSelected
			skillsSelected++;
			if(skillsSelected==3){
				//first disable all checkboxes
				$('#skillsForaJob input[type=checkbox]').attr('disabled', true);
				//then enable only the ones that are selected
				$('#skillsForaJob input[type=checkbox]:checked').attr('disabled', false);
			}
		}
	});
}

function restrictNumberofLanguages(){
	//don't let the user select more than 5 of these
	var languagesSelected = 0;
	//if an input is changed, see if it got checked or unchecked
	$('#languagesForaJob input[type=checkbox]').change(function(){
		if($(this)[0].checked==false){
			//decrement languagesSelected
			languagesSelected--;
			//enable all checkboxes
			$('#languagesForaJob input[type=checkbox]').attr('disabled', false);
		}
		//if this got checked, see if 5 has been checked and disable checkboxes accordingly
		else{
			//increment languagesSelected
			languagesSelected++;
			if(languagesSelected==5){
				//first disable all checkboxes
				$('#languagesForaJob input[type=checkbox]').attr('disabled', true);
				//then enable only the ones that are selected
				$('#languagesForaJob input[type=checkbox]:checked').attr('disabled', false);
			}
		}
	});
}

function postJob(){
	$('#postajobform').submit(function(event){
		event.preventDefault();
		console.log('form received');
		//get the skills required for this project
		var skillsArray = [];
		$('#skillsForaJob input[type=checkbox]:checked').each(function(){
			skillsArray.push(this.value);
		});
		//add these skills in the form
		for(var i = 0; i<skillsArray.length; i++){
			$('#jobskill' + parseInt(i)).val(skillsArray[i]);
		}
		//get the languages required for this project
		var languagesArray = [];
		$('#languagesForaJob input[type=checkbox]:checked').each(function(){
			languagesArray.push(this.value);
		});
		//add these languages in the form
		for(var i = 0; i<languagesArray.length; i++){
			$('#joblanguage' + parseInt(i)).val(languagesArray[i]);
		}
		var file = $('#jobcircular').prop('files')[0];
		var name = file.name;
		var ext = name.split('.');
		ext = ext[ext.length-1].toLowerCase();
		//if extension is not pdf, doc or docx, show error
		if(ext=='pdf' || ext=='doc' || ext=='docx'){
			//if size is greater than 500 kb, show error
			var size = file.size;
			if(size<=500000){
				//make form data, so that the form can be passed to php using post
				var UPLOAD = new FormData(this);
				//add an additional field to the form to let php know which user we're dealing with
				UPLOAD.append("USEREMAILFORJOBUPLOAD", user);
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
							//show the job posted modal
							$('#jobPostedModal').modal('show');
							//reload the page when user clicks thanks
							$('#jobPostedThanks').click(function(){
								location.reload();
							});
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

//get the current date from server
function getCurrentDate(){
	$.ajax({
		url: phpfile,
		data: {
			GETCURRENTDATE: '?'
		},
		success:
			function(result){
				//restrict job deadline using this date
				$('#jobdeadline').prop('min', result);
			}
	});
}

function startWorkingConfidently(){
	//make the logout button alive
	logoutButtonWorks();
	//get the current date to restrict deadlines
	getCurrentDate();
	//bring out the form to post a job on the click of a button
	bringOutPostJobForm();
	//don't allow selecting more than 3 skills for a job
	restrictNumberofSkills();
	//don't allow selecting more than 5 languages for a job
	restrictNumberofLanguages();
	//post a job when the user clicks post
	postJob();
	//let the user search for other users
	searchAtNavbar();
}

function moveAwayIntruders(){
	//get the role of this user and redirect accordingly
	$.ajax({
		url: phpfile,
		data: {
			GETROLEFROMEMAIL: user
		},
		success:
			function(result){
				if(result=='baker'){
					window.location.href = 'bakerhome.html';
				}
			}
	});
}

function startDoingStuffs(){
	//this will hold the user's email
	user = '';	
	//this will hold the address of the php file
	phpfile = './php/companyhome.php';
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	//know which user logged in, then start other works
	getUserInfo();
}