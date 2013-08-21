$flair.profile = {	
	
	print: function(user){
		var str="";
	 		str += this.printCars(user);
			str += this.printShares(user);
			str += this.printCheckins(user);
			str += this.printBumpers(user);
		return str;
	},

	printCars: function(user){
		var str="";
			for(var i=0;i<user.cars.length;i++){	
				str += "<div class='inner_content' style='background-color:#eee;font-size:3.0em;font-weight:bold;' >"; 
				str += this.printLeft("<img src='images/logo_small.png' width='50px;' style='vertical-align:baseline;' >","",user);
				var dataArr=[];
				var dataObj={};
					dataObj["name"]=user.cars[i].model + "<br><span style='font-size:0.3em;color:#999;font-weight:norma;' >Since " + user.cars[i].since + "</span>";					
					dataObj["url"]="#page=car&title=" + user.cars[i].model + "&id=" + user.cars[i].moid;
				dataArr.push(dataObj);
				str += this.printRight(dataArr);
				str += "</div>";
			}
		return str;
	},

	printShares: function(user){
		var str = "<div  class='inner_content'>";
			str += this.printLeft("shares :","#page=share_edit",user) + "</div>";
		return str;
	},	
	
	printCheckins: function(user){
		var str = "<div  class='inner_content'>";
			str += this.printLeft("checkins :","#page=checkin_edit",user) + "</div>";
		return str;
	},	
	
	printBumpers: function(user){
		var str = "";
		
		return str;
	},
	
	printRight: function(dataArr){
	 var str="";
	 	str = "<div style='width:200px;display:inline-block;' >";
	 	
	 	for (x in dataArr)
   	     {
   	      url = dataArr[x]["url"];
   	      dataStr = dataArr[x]["name"];
   	     
	 	   if(url){
	 			str += "<a href=\"" + url + "\" >";
	 		}
	 			str += dataStr;
	 			
	 		if(url){
	 			str += "</a>";
	 		}
	     }			
	 	str += "</div>";	
	 return str;
	},
	
	printLeft: function(dataStr,url,user){
	 var str="";
	 	str = "<div style='text-align:right;width:80px;display:inline-block;' >";
	 	    if(url){
	 			str += "<a href=\"" + url + "\" >";
	 		}
	 			str += dataStr;
	 		if(url){
	 			str += "</a>";
	 		}	
	 	str += "</div>";	
	 return str;
	}

}