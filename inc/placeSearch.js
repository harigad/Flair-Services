$flair.placeSearch = {

  init: function(searchMode){
     
	$flair.window.showSearch('place');
  
	var str ="";
	 str += "<div style='text-align:center;color:#999;margin-top:50px;font-size:1.4em;' >";
	   str += "Find your restaurant/cafe.<br><b>example: Starbucks irving texas</b>";
	 str += "</div>";
     $flair.window.print("result",str);
  },
  
  process: function(){
 
		that = this;	
		url="http://flair.me/search.php";
		var data="&type=search";
		data+="&search=" + $flair.header.get();
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
						
				}
			});		
	
 
 
 
  
  }
}