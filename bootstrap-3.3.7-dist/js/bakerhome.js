$(window).on('load', startDoingStuffs);

function getUserInfo(){
	//get user's email from server, as email is to uniqify users
	$.ajax({
		url: './php/bakerhome.php',
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
			url: './php/bakerhome.php',
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

function showJobs(sortby, order, startingjob){
	console.log('sorting by ' + sortby + ', ' + order + ', starting with ' + startingjob + ', jobs per page ' + jobsperpage);
	//get the jobs from database and add them to the table body
	$.ajax({
		url: phpfile,
		data: {
			GETJOBSFROMEMAIL: user, SORTBY: sortby, ORDER: order, STARTINGJOB: startingjob, JOBSPERPAGE: jobsperpage
		},
		success:
			function(result){
				//add the appropriate jobs to the table
				$('#jobsForThisBekar').html(result);
				//let the user apply for a job
				applyForJob();
			}
	});
}

function applyForJob(){
	//add click event listener to apply buttons
	$('.applyButton').click(function(){
		var jobId = $(this).siblings('.jobId').html();
		//notify server that this user applied for this job
		$.ajax({
			url: phpfile,
			data: {
				APPLIEDFORJOB: user, APPLIEDFORJOBID: jobId
			},
			success:
				function(result){
					//console.log(result);
					$('#applicationReceivedModal').modal('show');
					$('#applicationReceivedThanks').click(function(){
						location.reload();
					});
				}
		});
	});
}

function sortJobs(){
	$('.sortJobs').click(function(){
		//console.log('clicked');
		var clickedid = (this.id);
		sortingby = clickedid;
		if(sortbymap[clickedid]=='ASC'){
			sortbymap[clickedid] = 'DESC';
		}
		else if(sortbymap[clickedid]=='DESC'){
			sortbymap[clickedid] = 'ASC';
		}
		showJobs(clickedid, sortbymap[clickedid], 0);
		//start over from page 1 again
		$('.page-item').removeClass('active');
		$('#page1').addClass('active');
	});
}

function doThePagination(){
	//add event listeners to the buttons
	$('.page-link').click(function(){
		//make it active
		$('.page-item').removeClass('active');
		$(this).parent().addClass('active');
		var startingjob = (parseInt($(this).html())-1)*jobsperpage;
		//show the jobs that should be on this page
		showJobs(sortingby, sortbymap[sortingby], startingjob);
	});
}

function startWorkingConfidently(){
	//make the logout button alive
	logoutButtonWorks();
	//show jobs for this bekar
	showJobs('id', 'DESC', 0);
	//let the user search for other users
	searchAtNavbar();
	//sort jobs on click
	sortJobs();
	//setup pagination buttons
	setupPaginationButtons();
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
				if(result=='company'){
					window.location.href = 'companyhome.html';
				}
			}
	});
}

function setupPaginationButtons(){
	//get how many jobs there are for this bekar
	$.ajax({
		url: phpfile,
		data: {
			GETPAGINATIONBUTTONNUMBERSFROMEMAIL: user
		},
		success:
			function(result){
				var totaljobs = parseInt(result);
				//show the pagination buttons
				var totalpages = Math.ceil(totaljobs/jobsperpage);
				$('#paginationBrowseJobs').html('');
				$('#paginationBrowseJobs').append('<li class = "page-item active" id = "page1"><a class = "page-link" href = "#">1</a></li>');
				for(var i = 2; i<=totalpages; i++){
					$('#paginationBrowseJobs').append('<li class = "page-item"><a class = "page-link" href = "#">' + i + '</a></li>');
				}
				//add event listeners to these buttons
				doThePagination();
			}
	});
}

function startDoingStuffs(){
	//this will hold the states of each sortby component
	sortbymap = [];
	sortbymap['id'] = 'DESC';	sortbymap['postedby'] = 'ASC';	sortbymap['position'] = 'ASC';
	sortbymap['type'] = 'ASC';	sortbymap['salary'] = 'ASC';	sortbymap['deadline'] = 'ASC';
	//this will keep track of what we are sorting by currently
	sortingby = 'id';
	jobsperpage = 10;
	//this will hold the user's email
	user = '';	
	//this will hold the address of the php file
	phpfile = './php/bakerhome.php';
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	//know which user logged in, then start other works
	getUserInfo();
}