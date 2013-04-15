$flair.nearby = {

	init: function(title,id) {	
		$flair.go.updateHistory("user",title,id);
		$flair.login.init(this.begin(title,id));
	},	

	begin: function(title,id){
		var that = this;
			var flairs = that.flairs;
			str = "";
		
			$flair.window.printPage('<div class="loading" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
			that.load(id);
	
	},
	
	load: function() {
		var that = this;	
		var url="search.php";
		data="&type=nearby";	
		data+="&lat=" + $flair.lat;
		data+="&lng=" + $flair.lng;
		
		if($flair.login.accessToken){
		  data+="&accessToken=" + $flair.login.accessToken;	
		}
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: $flair.go.domain + url,
			data:data,
			dataType: "json",
				success: function(flairs){	
					that.nearbyFlairs=flairs;
					that.print();
				}
			});
	
	},
	
	print: function() {
	
		var str = "";
		
		str += "<div id='user_content_holder' >";
					
		str += "</div>";
		
		$flair.window.printPage(str);
		$flair.go.placeFlairs(this.nearbyFlairs);
	
	
	}

}