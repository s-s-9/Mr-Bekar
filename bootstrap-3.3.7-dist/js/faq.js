$(window).on('load', startDoingStuffs);

function dropdownOnHover(){
	$('.dropdown').mouseover(function(){
		$(this).children('.dropdown-menu').show();
	});
	$('.dropdown').mouseout(function(){
		$(this).children('.dropdown-menu').hide();
	});
}

function hideShowAnswers(){
	//fisrt hide all the answers
	$('.faqAnswer').hide();
	//if a question is clicked, show the answers
	$('.faqQuestion').click(function(){
		$(this).next().slideToggle('slow');
	});
}

function startDoingStuffs(){
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	
	//make dropdown menus appear on hover
	dropdownOnHover();
	
	//hide and show answers
	hideShowAnswers();
}