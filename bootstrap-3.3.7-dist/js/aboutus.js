$(window).on('load', startDoingStuffs);

function dropdownOnHover(){
	$('.dropdown').mouseover(function(){
		$(this).children('.dropdown-menu').show();
	});
	$('.dropdown').mouseout(function(){
		$(this).children('.dropdown-menu').hide();
	});
}

function startDoingStuffs(){
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	
	//make dropdown menus appear on hover
	dropdownOnHover();
	
}