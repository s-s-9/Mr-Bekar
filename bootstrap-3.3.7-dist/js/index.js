$(window).on('load', startDoingStuffs);

function loginValidation(){
	//initially disable the login button if any field is empty
	if($('#emailLogin').val().length==0 || $('#passwordLogin').val().length==0){
		$('#loginSubmit').attr('disabled', true);
	} 
	//keep track of all the fields
	var validEmail = 0;
	var validPassword = 0;
	//add event listeners to input fields of username and password 
	$('#emailLogin').keyup(function(){
		var emailLoginVal = $(this).val();
		//if this is empty, disable login button and add the cross icon
		if(emailLoginVal.length==0){
			validEmail = 0;
			$('#loginSubmit').attr('disabled', true);
			$('#loginEmailValidity').removeClass('glyphicon-ok');
			$('#loginEmailValidity').addClass('glyphicon-remove');
		}
		//if this is valid, mark this as valid, add the correct icon and check if the other one is valid too
		else{
			validEmail = 1;
			$('#loginEmailValidity').removeClass('glyphicon-remove');
			$('#loginEmailValidity').addClass('glyphicon-ok');
			if(validPassword==1){
				$('#loginSubmit').attr('disabled', false);
			}
		}
	});
	$('#passwordLogin').keyup(function(){
		var passwordLoginVal = $(this).val();
		//if this is empty, disable login button and add the cross icon
		if(passwordLoginVal.length==0){
			validPassword = 0;
			$('#loginSubmit').attr('disabled', true);
			$('#loginPasswordValidity').removeClass('glyphicon-ok');
			$('#loginPasswordValidity').addClass('glyphicon-remove');
		}
		//if this is valid, mark this as valid, add the correct icon and check if the other one is valid too
		else{
			validPassword = 1;
			$('#loginPasswordValidity').removeClass('glyphicon-remove');
			$('#loginPasswordValidity').addClass('glyphicon-ok');
			if(validEmail==1){
				$('#loginSubmit').attr('disabled', false);
			}
		}
	});
}

function signupValidation(){
	//initially disable the signup button if all fields are empty
	if($('#nameSignup').val().length==0 || $('#birthdaySignup').val().length==0 ||  
	   $('#emailSignup').val().length==0 || $('#passwordSignup').val().length==0){
		$('#signupSubmit').attr('disabled', true);
	} 
	//keep track of all the fields
	var validName = 0;
	var validBirthday = 0;
	var validEmail = 0;
	var validPassword = 0;
	var roleSignup = 'baker';
	//if user is company, it won't have birthday and gender
	$('#roleSignup').change(function(){
		roleSignup = $(this).val();
		if(roleSignup=='company'){
			$('#birthdaySignupFormGroup').toggle();
			$('#genderSignupFormGroup').toggle();
			//if name, email and password are valid, then enable sign up button
			if(validName==1 && validEmail==1 && validPassword==1){
				$('#signupSubmit').attr('disabled', false);
			}
		}
		else{
			$('#birthdaySignupFormGroup').toggle();
			$('#genderSignupFormGroup').toggle();
			//if everything is valid, then enable sign up button
			if(validName==1 && validBirthday==1 && validEmail==1 && validPassword==1){
				$('#signupSubmit').attr('disabled', false);
			}
			else{
				$('#signupSubmit').attr('disabled', true);
			}
		}
	});
	//add event listeners to all the input fields
	$('#nameSignup').keyup(function(){
		var nameSignupVal = $(this).val();
		//if this is empty, disable signup button and add the cross icon
		if(nameSignupVal.length==0){
			validName = 0;
			$('#signupSubmit').attr('disabled', true);
			$('#signupNameValidity').removeClass('glyphicon-ok');
			$('#signupNameValidity').addClass('glyphicon-remove');
		}
		//if this is valid, mark this as valid, add the correct icon and check if the others are valid too
		else{
			validName = 1;
			$('#signupNameValidity').removeClass('glyphicon-remove');
			$('#signupNameValidity').addClass('glyphicon-ok');
			if(roleSignup=='company'){
				if(validEmail==1 && validPassword==1){
					$('#signupSubmit').attr('disabled', false);
				}
			}
			else{
				if(validBirthday==1 && validEmail==1 && validPassword==1){
					$('#signupSubmit').attr('disabled', false);
				}
			}
		}
	});
	$('#birthdaySignup').change(function(){
		var birthdaySignupVal = $(this).val();
		//if this is empty, disable signup button and add the cross icon
		if(birthdaySignupVal.length==0){
			validBirthday = 0;
			$('#signupSubmit').attr('disabled', true);
			$('#signupBirthdayValidity').removeClass('glyphicon-ok');
			$('#signupBirthdayValidity').addClass('glyphicon-remove');
		}
		//if this is valid, mark this as valid, add the correct icon and check if the others are valid too
		else{
			validBirthday = 1;
			$('#signupBirthdayValidity').removeClass('glyphicon-remove');
			$('#signupBirthdayValidity').addClass('glyphicon-ok');
			if(validName==1 && validEmail==1 && validPassword==1){
				$('#signupSubmit').attr('disabled', false);
			}
		}
	});
	$('#emailSignup').keyup(function(){
		var emailSignupVal = $(this).val();
		//if this is empty, disable signup button and add the cross icon
		if(emailSignupVal.length==0){
			validEmail = 0;
			$('#signupSubmit').attr('disabled', true);
			$('#signupEmailValidity').removeClass('glyphicon-ok');
			$('#signupEmailValidity').addClass('glyphicon-remove');
		}
		//if this is valid, check if it's taken, add the correct icon if it's not and check if the others are valid too
		else{
			//make ajax request to check if this email is taken
			$.ajax({
				url: "./php/index.php",
				data: {
					DOESTHISEMAILEXIST: emailSignupVal
				},
				success: 
					function(result){
						console.log(result);
						if(result=="accept"){
							validEmail = 1;
							$('#signupEmailValidity').removeClass('glyphicon-remove');
							$('#signupEmailValidity').addClass('glyphicon-ok');
							if(roleSignup=='company'){
								if(validName==1 && validPassword==1){
									$('#signupSubmit').attr('disabled', false);
								}
							}
							else{
								if(validName==1 && validBirthday==1 && validPassword==1){
									$('#signupSubmit').attr('disabled', false);
								}
							}
						}
						else if(result=="decline"){
							validEmail = 0;
							$('#signupSubmit').attr('disabled', true);
							$('#signupEmailValidity').removeClass('glyphicon-ok');
							$('#signupEmailValidity').addClass('glyphicon-remove');
						}
					}
			});
			
		}
	});
	$('#passwordSignup').keyup(function(){
		var passwordSignupVal = $(this).val();
		//if this is empty, disable signup button and add the cross icon
		if(passwordSignupVal.length==0){
			validPassword = 0;
			$('#signupSubmit').attr('disabled', true);
			$('#signupPasswordValidity').removeClass('glyphicon-ok');
			$('#signupPasswordValidity').addClass('glyphicon-remove');
		}
		//if this is valid, mark this as valid, add the correct icon and check if the others are valid too
		else{
			validPassword = 1;
			$('#signupPasswordValidity').removeClass('glyphicon-remove');
			$('#signupPasswordValidity').addClass('glyphicon-ok');
			if(roleSignup=='company'){
				if(validName==1 && validEmail==1){
					$('#signupSubmit').attr('disabled', false);
				}
			}
			else{
				if(validName==1 && validBirthday==1 && validEmail==1){
					$('#signupSubmit').attr('disabled', false);
				}
			}
		}
	});
}

function clickedLoginSubmit(event){
	//prevent page reload on form submission
	event.preventDefault();
	//take all the data entered by the user
	var emailLogin = $('#emailLogin').val();
	var passwordLogin = $('#passwordLogin').val();
	//send these data to the server
	$.ajax({
		url: "./php/index.php",
		data: {
			EMAILLOGIN: emailLogin, PASSWORDLOGIN: passwordLogin
		},
		success:
			function(result){
				console.log(result);
				if(result=="matched"){
					//see if this user is bekar or company
					$.ajax({
						url: './php/index.php',
						data: {
							BEKARORCOMPANY: emailLogin
						},
						success:
							function(result){
								//if user is bekar, take him to bekar's home, else company's home
								if(result=='baker'){
									$(location).attr('href', './bakerhome.html');
								}
								else if(result=='company'){
									$(location).attr('href', './companyhome.html');
								}
							}
					});
				}
				else{
					//tell the user that login failed
					$('#loginFailure').modal('show');
				}
			}
	});
}

function clickedSignupSubmit(event){
	//prevent page reload on form submission
	event.preventDefault();
	//take all the data entered by the user
	var nameSignup = $('#nameSignup').val();
	var birthdaySignup = $('#birthdaySignup').val();
	var genderSignup = $('input[name=genderSignup]:checked').val();
	var emailSignup = $('#emailSignup').val();
	var passwordSignup = $('#passwordSignup').val();
	var roleSignup = $('#roleSignup').val();
	//send these data to the server
	$.ajax({
		url: "./php/index.php",
		data: {
			NAMESIGNUP: nameSignup, BIRTHDAYSIGNUP: birthdaySignup, GENDERSIGNUP: genderSignup,
			EMAILSIGNUP: emailSignup, PASSWORDSIGNUP: passwordSignup, ROLESIGNUP: roleSignup
		},
		success:
			function(result){
				console.log(result);
				//if(result=="registered"){
					//tell the user that registration was successful
					$('#registrationSuccess').modal('show');
					//go to index page as soon as user closes the modal
					$('#closeRegistrationSuccessful').click(function(){
						$(location).attr('href', './index.html');
					});
				//}
				//else{
					
				//}
			}
	});
}

function dropdownOnHover(){
	$('.dropdown').mouseover(function(){
		$(this).addClass('active');
		$(this).children('.dropdown-menu').show();
	});
	$('.dropdown').mouseout(function(){
		$(this).removeClass('active');
		$(this).children('.dropdown-menu').hide();
	});
}

function takeAwayLoggedinUsers(){
	$.ajax({
		url: './php/index.php',
		data: {
			GETUSERNAME: '?'
		},
		success:
			function(result){
				console.log(result);
				if(result!='nobody'){
					//call another ajax to know this user's role and redirect to the proper page
					$.ajax({
						url: './php/index.php',
						data: {
							BEKARORCOMPANY: result
						},
						success:
							function(result){
								if(result=='baker'){
									$(location).attr('href', 'bakerhome.html');
								}
								else if(result=='company'){
									$(location).attr('href', 'companyhome.html');
								}
							}
					});
				}
			}
	});
}

function removeOldJobs(){
	$.ajax({
		url: './php/index.php',
		data: {
			REMOVEOLDJOBS: '?'
		},
		success:
			function(result){
				console.log(result);
			}
	});
}

function startDoingStuffs(){
	//don't let a logged in user stay in this page
	takeAwayLoggedinUsers();
	//remove old jobs from the database and server
	removeOldJobs();
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	
	//set event listeners to login and sign up buttons
	$('#loginButton').click(loginValidation);
	$('#signupButton').click(signupValidation);
	
	//take care of user submitting the login/signup button
	$('#loginSubmit').click(clickedLoginSubmit);
	$('#signupSubmit').click(clickedSignupSubmit);
	
	//make dropdown menus appear on hover
	dropdownOnHover();
}