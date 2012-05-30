$flair.map = {


	init: function (lat,lng,title,zoom,hideMarker) {
		var pos=new google.maps.LatLng(lat,lng);

		var myOptions = {
			zoom: 15,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: pos,
			mapTypeControl: false,
			streetViewControl: false,
			draggabledisable: true,
			disableDoubleClickZoom: true
		};
		
		$flair.window.fullScreen("<div id='map_canvas_large' ></div>",title);

		var largeMap = new google.maps.Map(document.getElementById("map_canvas_large"), myOptions);
    
		if(!hideMarker){	
			var marker = new google.maps.Marker({
				map:largeMap,
				position:pos
			});	
		}
		
		/*var userPos=new google.maps.LatLng(lat,lng);
		var userMarker = new google.maps.Marker({
        map:largeMap,
        position:userPos,
		icon: 'images/icons/blue-dot.png'
		
		});*/
  
		//var bounds = new google.maps.LatLngBounds();
		//bounds.extend(pos);
		//bounds.extend(userPos);
		//largeMap.fitBounds(bounds);
	
	
		/*
		var request = {
			origin:start, 
			destination:pos,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}
		});
		*/  				
	},

	showAddress: function (address) {

	

	console.log(address);

	
		that = this;
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {

			if (status == google.maps.GeocoderStatus.OK) {
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				that.showMap(latitude,longitude,$flair.go.title,11,true);
			} 
		});
	},
	
	
	showMap: function (placeLat,placeLng,title,zoom,hideMarker) {

		var pos=new google.maps.LatLng(placeLat,placeLng);
		var bounds = new google.maps.LatLngBounds();
	
		if(zoom){
			//do nothing;
		}else{
			zoom=15;
		}

		var myOptions = {
			zoom: zoom,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: pos,
			mapTypeControl: false,
			streetViewControl: false,
			draggabledisable: true,
			disableDoubleClickZoom: true
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		if(!hideMarker){
			var marker = new google.maps.Marker({
				map:map,
				position:pos
			});
		}		

		/*	
		var userPos=new google.maps.LatLng(lat,lng);
		var userMarker = new google.maps.Marker({
        map:map,
        position:userPos,
		icon: 'images/icons/blue-dot.png'
		
		});
	
		bounds.extend(pos);
		bounds.extend(userPos);
		map.fitBounds(bounds);
		*/
		google.maps.event.addListener(map, 'click', function() {
			$flair.go.url("#page=map&title=" + title + "&id=1");
		});

	}	
}