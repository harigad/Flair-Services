$flair.user = {
	
	users: {},
	user: {},
	noticeHolder: false,
	
	init: function(title,id) {		
		$flair.go.updateHistory("user",title,id);		
		
		var flairs = this.flairs;
		str = "";
		
		if(this.users[id] && !$flair.newFlair){		
			this.user = this.users[id];	
			this.print();
		}else if(id == "null"){
			this.printNonUser(title);
		}else {
			$flair.window.printPage('<div class="loading" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
			this.loadUser(id);return;
		}
	},
	
	
	friends: function(title,id) {
	  $flair.go.updateHistory("friends",title,id);
	  
	  $flair.window.printPage('<div class="loading" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
	  
	   url="http://flair.me/search.php";
		 data="&search=" + title;
		 data+="&lat=" + $flair.lat;
		 data+="&lng=" + $flair.lng;
		 data+="&type=friends";
		 data+="&id=" + id;
		 
		

			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			$flair.ajaxRequest=$.ajax({

			type: "POST",

			url: url,

			data:data,

			dataType: "json",

			success: function(t){
			
				str = "<div id='flairs_holder' style='text-align:center;' ></div>";		
		
				$flair.window.printPage(str);
				$flair.go.placeFlairs(t);
			
				$flair.go.status=false;								
			}

		});
	
	
	
	},
	
	printNonUser: function(title) {
		var str="";
		                                                          
		str += "<div style='vertical-align:top;' ><div style='vertical-align:top;text-align:left;margin-left:8px;margin-bottom:8px;display:inline-block;' ><img src='/images/icons/offline-user-icon.png' style='width:100px;'  ></div>"; 
		str += "<a href='' ><div class='flair_thumb' style='background-color:transparent;font-size:30px;font-weight:bold;line-height:20px;color:#6996F5;' >0<hr style='margin-top:10px;margin-bottom:10px;background-color:#6996F5;color:#6996F5;' />0</div></a></div>";
		str += "<div id='flairs_holder' style='text-align:center;' >";		
		str += "</div>";		
		$flair.window.printPage(str);;
	
	},
	
	notice:function(passed,total){
		that = this;
		if(that.noticeHolder==false){
			var str = "";
			if(that.user.id=="me"){
				str  = "Currently you have " ;
			}else{
				str = "Currently " + this.user.name + " has";
			}
		
			str = str + passed + " flairs accepted out of " + total + " flairs." ;
			$('#notice_holder').hide();
			$('#notice_holder').html(str);
			$('#notice_holder').slideToggle('slow');
		}else{
			$('#notice_holder').slideToggle('slow');
		}
	},
	
	print: function(){
		if($flair.go.id!=this.user.id){
			return;
		}
		
		var str="";
		                                                          
		str += "<div style='vertical-align:top;' ><div style='vertical-align:top;text-align:left;margin-left:8px;margin-bottom:8px;display:inline-block;' ><img src='" + this.user.photo_big + "' style='height:100px;'  ></div>"; 
		str += "<a href='' onclick='$flair.user.notice(" + this.user.flair_count  + "," + this.user.place_count + ");' ><div class='flair_thumb' style='background-color:transparent;font-size:30px;font-weight:bold;line-height:20px;color:#6996F5;' >" + this.user.flair_count  + "<hr style='margin-top:10px;margin-bottom:10px;background-color:#6996F5;color:#6996F5;' />" + this.user.place_count + "</div></a></div>";
		str += "<div id='notice_holder' style='display:none;text-align:left;padding:10px;margin-left:8px;margin-right:8px;color:#6996F5;' ></div>";		
		str += "<div id='flairs_holder' style='text-align:center;' >";		
		str += "</div>";		
		$flair.window.printPage(str);
		$flair.go.placeFlairs(this.user.flairs);
		
	},
	
	thumb: function(flair) {
		icon = flair['type'] + ".png";	
				str = "<div style='vertical-align:top;position:relative;' >";
					str += "<a href=\"#page=food&title=" + flair['name'] + "&id=" + flair['sid'] + "&type=" + flair['type'] + "&place=" + flair['placename'] + "&pid=" + flair['pid'] + "\" ><div class='flair_thumb left_thumb' style='margin-right:0px;background-image:url(/images/icons/" + icon + ");' ></div></a>";
					str += "<a href=\"#page=place&title=" + flair['placename'] + "&id=" + flair['pid'] + "\" >";
						str += "<div class='flair_thumb right_thumb' style='background-color:#fff;color:#999;padding:10px;min-height:80px;text-align:left;line-height:1.4em;vertical-align:top;' >";
							str += "<div style='font-size:1.2em;' >";					
								str += "<span style='color:#666;' >" + flair['name'] + "</span>";
								str += "<span style='color:#ccc;' > by </span>";
								str += "<span style='color:#6996F5;' >" + " James Peters " + "</span>";
								str += "<span style='color:#ccc;' > in " + flair['city'] +  "</span>";
							str += "</div>";								
						str += "</div>";		
					str += "</a>";	
					
				str += "</div>";
				return str;
	},
	
	loadUser: function(id) {
		var that = this;	
		var url="http://flair.me/search.php";
		data="&type=user";	
		data+="&id=" + id;	
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(user){						
						if($flair.go.id==user.id){
							that.users[user.id] = user;
							that.user = user;
							that.print();
							$flair.newFlair=false;
						}
				}
			});
	}
}