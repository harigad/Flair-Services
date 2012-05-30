$flair.food = {
	init: function(title,sid) {	
		$flair.go.updateHistory("food",title,id);		
		this.sid=sid;
		this.title=title;
		
				var query=document.location.hash;
				var myVars=query.split("&");
				pageArr=myVars[3].split("=");this.type=pageArr[1];
				pageArr=myVars[4].split("=");this.placename=unescape(pageArr[1]);
				pageArr=myVars[5].split("=");this.pid=pageArr[1];
		
		this.print();
	},
	
	print: function() {
			var icon = this.type + ".png";
			var str  = "<div style='vertical-align:top;position:relative;text-align:center;' >";
					str +="<a href=\"#page=place&title=" + this.placename + "&id=" + this.pid + "\" >";					
						str +="<div class='flair_thumb' style='font-size:1.4em;color:#6996F5;background-color:#fff;width:282px;padding:10px;min-height:80px;text-align:center;vertical-align:top;' >";
							str += "@ " + this.placename;
						str += "</div>";
					str += "</a>";
			str += "</div>";
		
		$flair.window.printPage(str,true);
	},
	
	load: function() {
		var that = this;	
		var url="http://flair.me/search.php";
		data="&type=food";	
		data+="&id=" + this.fid;	
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(food){						
						if($flair.go.id==food.id){							
							that.food = food;
							that.print();
						}
				}
			});
	}
}