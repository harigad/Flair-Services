function openDialog(){
    FB.getLoginStatus(function(response) {
        if (response.session) {
            $('#main').hide();
            $('#create_new').show();
        } else {
            window.location="<?php echo $user->login(); ?>";
        }
    });
}

function closeDialog(){
    $('#create_new').hide();
    $('#main').show();
}

function hideBar(){   
    window.scrollTo(0, 1);
}

var thisDomain;
var page_content;
var default_content;
function init(){
    thisDomain="http://" + window.location.hostname;
    thisDomain=thisDomain.replace("#","");

	page_content=document.getElementById('page_content');
	default_content=document.getElementById('default_content');
	
    currentPage="default";

		//updateHome();
	
    service = new google.maps.places.PlacesService(document.getElementById('dummy_map_canvas'));
	
    
    $flair.location.init();
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();

	$flair.scroll.init();
	$flair.header.showSearch();
    
	urlWatch();
	$flair.scroll.enableLinksOnTap('header_content');
}


