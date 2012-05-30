$flair.role =  {

  searchResults: "",
  
  searchName: "example : StarBucks",
  
  originalSearchName: "example : StarBucks",
  
  searchCity: "City or Zipcode", 
  
  originalSearchCity: "City or Zipcode",


  placeObj: {},
  
  searchResults: "<div style='font-size:1.1em;padding:15px;color:#999;' >Which Restaurant/Cafe do you work for?</div>",   
 
  init:function(title,id){
   $flair.go.updateHistory("role",title,id);  
   $flair.login.init(this.begin(title,id));
  },  

  showSearch: function() { 
     var str="<div style='text-align:center;' >";
	  str += "<input type='text' onblur='restoreField(this,\"" + this.originalSearchName + "\");' onfocus='clearField(this,\"" + this.originalSearchName + "\");' id='name_input' class='placeSearchInput' ><br>";
	  str += "<input type='text' onblur='restoreField(this,\"" + this.originalSearchCity + "\");' onfocus='clearField(this,\"" + this.originalSearchCity + "\");' id='city_input' class='placeSearchInput' >";
	  str += "<a onclick='$flair.role.search();' ><div style='font-size:1.6em;margin-left:auto;margin-right:auto;display:inline-block;padding:5px;' >Search</div></a>";
	  str += "</div>";
	  str += "<div id='result' >";
	  
	  str += this.searchResults;
	  
	  str + "</div>";
	  $flair.window.printPage(str);
	  
	  $('#name_input').val(this.searchName);
	  $('#city_input').val(this.searchCity);
	  
	
	  
  },
  
  
  search: function(){  	
        $('#name_input').blur();		
        $('#city_input').blur();
		
		$('#result').html("<img  style='position:relative;top:45px;left:50%;' src='images/ajax-loader.gif' >");
  
		that = this;	
		url="http://flair.me/search.php";
		var data="&type=search";
		data+="&search=" + $('#name_input').val();
		data+="&searchMode=place";
		data+="&city=" + $('#city_input').val();
				
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			
			var str="";
			var that=this;
			
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(places){	
				  that.searchName = $('#name_input').val();
				  that.searchCity = $('#city_input').val();
				  
				   for(place in places){
				    str += "<a href='#page=role&title=new cast member&id=" + places[place].id + "' ><div style='border-bottom:1px solid #eee;padding:10px;padding-left:15px;width:280px;overflow:hidden;white-space: nowrap;' ><span style='font-size:1.2em;' >" + places[place].name + "</span><br><span style='font-size:0.8em;color:#999;'>" + places[place].vicinity + "</span></div></a>";				   
				   }	
				   
				   	that.searchResults = str;
				   $('#result').html(str);
				}
			});	
  },
  
  
 loadPlaceFromServer: function(pid){	
		var that = this;	
		url="search.php";
		data="&type=search";
		data+="&searchMode=place";
		data+="&pid=" + pid;
		
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
				success: function(place){
				    that.placeObj=place;
					that.print();
				}
			});		
	},
  
  
  
  newCode: function() {
  
  $flair.newFlair=true;
	var str="";
	 str += "<div class='flair_thumb' style='color:#eee;display:block;width:auto;background-color:#999;' ><span style='line-height:2.0em;font-size:1.5em;' >Generating verification code";
		 str += "<br><img src='images/ajax-loader.gif' ></span></div>";
		 $flair.window.print("role_container",str);
		 
		 url="search.php";
		 data+="&type=role";
		 data+="&pid=" + this.placeObj.id;
		 
		 if($flair.login.accessToken){
		  data+="&accessToken=" + $flair.login.accessToken;	
		 }
		
		 if($flair.ajaxRequest)
		 {
				$flair.ajaxRequest.abort();
		 }		
		 
		 var that = this;
		 
		 $flair.ajaxRequest=$.ajax({
			type: "POST",
			url: $flair.go.domain + url,
			data:data,
			dataType: "json",
			success: function(t){
			 if(t.status===true){
			   var str="";
			   	 str +="<div class='flair_thumb' style='margin-top:5px;margin-bottom:5px;height:auto;width:auto;display:block;background-color:#999;color:#eee;font-size:2.5em;line-height:2.5em;' >" + t.code + "</div>";
				 
				 str += "<div style='font-size:1.5em;color:#666;text-align:center;' >Please call <b>1-800-567-999</b> from <b>" + that.placeObj.name + "</b> and enter the above verification code.</div>";
				 
			 $flair.window.print("role_container",str);
			 }
			}

		});
		 
  },
  
  no: function() {
  
  $flair.newFlair=true;
     
	  var url="search.php";
		 var data="&type=role";
		 data+="&pid=" + this.placeObj.id
		 data+="&action=delete";
		 
		 if($flair.login.accessToken){
		  data+="&accessToken=" + $flair.login.accessToken;	
		 }
		
		 if($flair.ajaxRequest)
		 {
				$flair.ajaxRequest.abort();
		 }		
		 
		 var that = this;
		 
		 $flair.ajaxRequest=$.ajax({
			type: "POST",
			url: $flair.go.domain + url,
			data:data,
			dataType: "json",
			success: function(t){
			}

		});
		 
  
  
	$flair.go.back();
  
  
  
  },
  
  
  print: function() {  
  
    var str="";
	    str += "<div style='margin-left:8px;margin-right:8px;' >";
		str += "<div>";
		  str += "<div class='flair_thumb' style='vertical-align:top;width:80px;height:80px;' id='map_canvas' ></div>";
			  
		   str += "<span style='vertical-align:top;width:190px;display:inline-block;font-size:2.5em;font-family:verdana;color:#ddd;padding:5px;white-space: nowrap;overflow:hidden;' >";
		       str += this.placeObj.name;		   
			   str += "<br><span style='font-size:0.5em;font-family:verdana;color:#6996F5;padding:5px;' >";
		       str += this.placeObj.city;
			   str += "</span>";			   
		   
		   str += "</span>";		 	   
		 str += "</div>";
		 
		   str +="<div id='role_container' >";
		   
			 str +="<div class='flair_thumb' style='margin-top:5px;margin-bottom:5px;height:auto;width:auto;display:block;background-color:#999;color:#eee;font-size:1.5em;line-height:1.5em;' >I certify that I am a cast member of this " + this.placeObj.name + "</div>";
		   
		      str += "<a onclick='$flair.role.no();' ><div class='flair_thumb' style='width:130px;background-color:#770000;color:#fff;' >NO</div></a>";
		
		 str += "<a onclick='$flair.role.newCode();' <div class='flair_thumb' style='width:130px;background-color:#6996F5;color:#fff;' >YES</div></a>";
		
		   str +="</div>";
		
		 
		 
		str += "</div>";
		
	
		
		
		
		$flair.window.printPage(str);
		
		var that = this;
		
		setTimeout(function(){
			$flair.map.showMap(that.placeObj.lat,that.placeObj.lng,that.placeObj.name);		
		},500);
	
  
  },
  
    
  begin: function(title,id){
	var placeObj;

	if(id=="me"){
	  if($flair.login.isCastMember()){	  
	    this.printDetails();
	  }else{
		this.showSearch();
	  }
	  return;
	}
	
	
	if(id===$flair.go.placeObj.id){
	  if(this.placeObj.id!=id){
		this.placeObj=$flair.go.placeObj;
	  }
	  this.print();
	}else{
	     var str="";
	    str += "<div style='margin-left:8px;margin-right:8px;' >";
		str += "<div>";
		  str += "<div class='flair_thumb' style='vertical-align:top;width:80px;height:80px;' id='map_canvas' ></div>";
			  
		   str += "<span style='vertical-align:top;width:190px;display:inline-block;font-size:2.5em;font-family:verdana;color:#ddd;padding:5px;white-space: nowrap;overflow:hidden;' >";
		       str += "loading.."	   
			   str += "<br><span style='font-size:0.5em;font-family:verdana;color:#6996F5;padding:5px;' >";
		       str += "";
			   str += "</span>";			   
		   
		   str += "</span>";		 	   
		 str += "</div>";
		 
		 str += "</div>";
		
	  $flair.window.printPage(str);
	  this.loadPlaceFromServer(id);
	
	}
  
  
  }
  




}