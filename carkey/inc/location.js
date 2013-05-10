$flair.location = {
	
	places: "",
	
	
	init: function () {			
		that = this;
		if (navigator.geolocation) {
			navigator.geolocation.watchPosition(that.set, that.forceLocation,{timeout:60000});
		}else{
			that.forceLocation();
		}
	},	
	
	set: function (position) {					
			$flair.lat=position.coords.latitude;
			$flair.lng=position.coords.longitude;
			if($flair.location.places=="")
			{$flair.location.loadPlaces(true);}
	},
	
	forceLocation: function () {
		var str="";
		str=str + "<div id='maincontent' >";
		str=str + "<div class='text' >Your device does not seem to have GPS enabled! Please enter a valid address below.</div>";
		str=str + "<div class='inputCover' ><input type='text' id='forceLocationInputField' value=''  onfocus='clearField(this,\"Enter your address\");' ></div>";
		str=str + "<a  onclick='forceLocationProcess(document.getElementById(\"forceLocationInputField\").value);' ><div class='btn' >UPDATE LOCATION</div></a>";
		str=str + "</div>";
    
		$flair.window.fullScreen(str,"Your Location");	
	},
	
	forceLocationProcess: function (addr){
		$.ajax({
			type: "POST",
			url: "http://flair.me/plugins/updateLocation.php",
			dataType: "html",
			data: "addr=" + addr,
			success: function(t){
				if(t=='pass'){
					currentForceLocationAddress=addr;
					slide();
					ajax_getURL(get_anchor(window.location));
				}else{
					alert(t);
				}
			}
		});
	},
	
	
	loadPlaces: function (dontPrint) {			
		that = this;	
		url="http://flair.me/search.php";
		var data="&type=search";
		data+="&searchMode=place";
		data+="&lat=" + $flair.lat;
		data+="&lng=" + $flair.lng;
		
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
	
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(t){			         
						$flair.location.places = t;
						if(dontPrint!==true){
							$flair.flair.places();
						}
						
				}
			});		
	}
}