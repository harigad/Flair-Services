$flair.thumbs = {

  print: function(flair,i,length){
 		var images=[];
			
			if(flair.approved){
			   
			   var foodColor = "#6996F5";
			   var peopleColor = "#115599";
			
			}else{
			
		       var foodColor = "#666666";
			   var peopleColor = "#666666";
			
			}
	
			var thumbs = $tree.flairs;
			var icon = flair.type + ".png";
			var borderWidth=1;
			
			
			if(length==(i+1)){			  
			  var borderWidth=0;
			  
			}
			
			
			var str  = "<div style='border:0px solid #eee;border-bottom-width:" + borderWidth + "px;vertical-align:top;position:relative;' >";		
					if(flair.approved){
					str +="<a href=\"#page=user&title=" + flair.username + "&id=" + flair.user + "\" >";
					}
					str +="<div class='flair_thumb left_thumb'  id='img_" + flair.sid + "'  ></div>";
					if(flair.approved){
					str +="</a>";
					}
					  
						str +="<div class='flair_thumb right_thumb' style='height:auto;background-color:#fff;color:#999;padding:10px;min-height:80px;text-align:left;line-height:1.0em;vertical-align:top;' >";
						if(flair.approved){
						str += "<a href=\"#page=user&title=" + flair.username + "&id=" + flair.user + "\" >";
						}
						str += "<div style='height:30px;white-space: nowrap;overflow:hidden;font-size:3.0em;font-family:verdana;color:#ddd;padding-bottom:0px;' ><img src='" + flair.userphoto + "' style='width:30px;margin-right:0px;margin-bottom:0px;' >" + flair.username.split(" ")[0] + "</div>";
						if(flair.approved){
						str += "</a>";
					    }
							str += "<div style='font-size:1.2em;' >";				
								if(flair.approved){
								str += "<a href=\"#page=place&title=" + flair.placename + "&id=" + flair.pid + "&type=" + flair.type + "&place=" + flair.placename + "&pid=" + flair.pid + "\" >";
								}
								str += "<span style='color:#333;color:" + foodColor + ";line-height:1.4em;' >" + flair.name + "</span>";
								if(flair.approved){
								str += "</a>";
								}
								str += "<span style='color:#999;font-weight:normal;' ><i> by </i></span>";
								if(flair.approved){
								str += "<a href=\"#page=user&title=" + flair.recipientname + "&id="+ flair.recipient + "&pid=" + flair.pid + "&placename=" + flair.placename + "\" >";
								}
								str += "<span style='color:" + peopleColor + ";' >" + flair.recipientname + "</span>";
								if(flair.approved){
								str += "</a>";
								}
								str += "<span style='font-weight:normal;color:#999;text-transform:lowercase;' > near " + flair.city + "</span>";	
								
						//	str += "<div style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;' ><span>like</span><span style='padding-left:10px;' >comment</span>";
							
							str += "<div style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;' ><span id=\"like_div_" + flair.sid + "\" onclick=\"$flair.likes.onClick(" + flair.sid + ");\"'>like</span><span style='padding-left:10px;' onclick=\"$flair.comments.onClick();\">comment</span>";
							
							if(!flair.approved && flair.user==$flair.login.user.id){
							  str +="<span style='padding-left:10px;color:#990000;' >delete</span>";
							}
							//edited
							str += "<div id=\"Commentdiv\" style='padding-top:10px;text-align:left;font-size:0.8em;color:#6996F5;'><span id=\"CommentId\" style=\"padding-top:0px\"></span><table width=\"200\" id=\"CommentTab\" style='padding-top:0px;text-align:left;' ></table></div>";
							
							
							str +="</div>";
						
							str += "</div>";
						str += "</div>";
					
			
			
	
			
			str += "</div>";
			
			var thisImage = {};
			thisImage['id'] = flair.sid;
			thisImage['src'] = "images/icons/" + flair.icon;			
			$flair.go.preLoadImages.push(thisImage);
			return str;
  
  
  
  
  }


}