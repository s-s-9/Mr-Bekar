$(window).on('load', startDoingStuffs);

function dropdownOnHover(){
	$('.dropdown').mouseover(function(){
		$(this).children('.dropdown-menu').show();
	});
	$('.dropdown').mouseout(function(){
		$(this).children('.dropdown-menu').hide();
	});
}

//add map
function initMap() {
	//console.log('I was called');
	var mapLocation = new google.maps.LatLng(23.7822036,90.35953719999998);

	var mapOptions = {
	  center: mapLocation,
	  zoom: 15
	};
	
	var mapMap = new google.maps.Map(document.getElementById("mapContainer"), mapOptions);
	
	var mapMarker = new google.maps.Marker({
		position: mapLocation,
		map: mapMap,
		title:"Kallyanpur"
	});
	
	mapMarker.setMap(mapMap);	
}

google.maps.event.addDomListener(window, 'load', initMap);

function getUserMessage(){
	//add event listener to the submit button
	$('#contactForm').submit(function(event){
		//first prevent page reload
		event.preventDefault();
		//see what a user had to say
		var contactEmail = $('input[name=contactEmail]').val();
		var contactMessage = $('textarea[name=contactMessage]').val();
		console.log(contactEmail);
		console.log(contactMessage);
		
		$.ajax({
			url: "./php/contact.php",
			data: {
				CONTACTEMAIL: contactEmail, CONTACTMESSAGE: contactMessage
			},
			success:
				function(result){
					console.log(result);
				}
		});
		
		$('#contactMessageSent').modal('show');
		$('#contactModalClosed').click(function(){
			window.location.href = "contact.html";
		});
	});
}

function startDoingStuffs(){
	//stop reloading page when a link with href = "#" is clicked
	$('a[href = "#"]').click(function(event){
		event.preventDefault();
	});
	
	//get user's message in contact page
	getUserMessage();
	
	//make dropdown menus appear on hover
	dropdownOnHover();
	
}