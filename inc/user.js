$flair.user = {
	
	users: {},
	user: {},
	noticeHolder: false,
	
	me: function() {	
		this.state="";
		this.begin(this.user.name,this.user.id);
	},
	
	init: function(title,id) {	
		$flair.go.updateHistory("user",title,id);
		$flair.login.init(this.begin(title,id));
	},	
	
	getRecievedFlairs: function() {
		return [];
	},	
	
	
	 isCastMember: function(uid){
	 if(this.getPid()){
	   return true;
	 }else{
	  return false;
	  }
  	},
  	
  getPid: function(){
   try{
   		return this.user.place.pid;
   }catch(e){
   		return false;
   }  
  },
	
	rec: function() {
		 this.state="rec";
		 if($flair.login.isCastMember()!==true){	
			$flair.role.showSearch();
			return;
		 }		
		  
		 str += "<div id='user_content_holder' >";
					
		str += "</div>";
		  
		 $flair.window.printPage(str);	
		 var rec=this.getRecievedFlairs();
		 if(rec.length>0){
		 	$flair.go.placeFlairs(rec);
		 }else{
		   		var msg="<div style='text-align:center;font-size:2.0em;color:#999;padding-top:40px;' >No Flairs to Show You.</div>";
		 }
		 $flair.window.print('user_content_holder',msg);
		 
	},
	
	begin: function(title,id){
		var that = this;

			var flairs = that.flairs;
			str = "";
		
			$flair.window.printPage('<div style="vertical-align:top;" ><div style="background-color:#eee;vertical-align:top;text-align:left;margin-left:0px;margin-bottom:0px;display:inline-block;position:relative;top:-10px;" ><img src="' + $flair.go.request("userPhoto") + '" style="width:320px;"  ></div><div id="left_user_content_holder_main" ><div class="loading" style="height:40px;background-position:center;background-repeat:no-repeat;" ></div></div>',true);
			 
		
			if(that.users[id] && !$flair.newFlair){		
			  that.user = that.users[id];	
			  that.print();
			}else if(id == "null"){
			  user = new Object();
			  user.id=id;
			  user.name=title;
			  user.place = new Object();
			  user.place.pid=$flair.go.request("pid");
			  user.place.name=$flair.go.request("placename");
			  user.place.role="";
			  that.user = user;
			
			  that.printNonUser(title);
			}else {
			  that.loadUser(id);return;
			}
	
	},	
	
	friends: function(title,id,loggedIn) {
	  $flair.go.updateHistory("friends",title,id);
	  $flair.login.init(this.friendsBegin(title,id));
	 },
	 	 
	 friendsBegin: function(title,id){
	  
	  $flair.window.printPage('<div class="loading" id="user_content_holder" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
	 
	     url="search.php";
		 data="&search=" + title;
		 data+="&lat=" + $flair.lat;
		 data+="&lng=" + $flair.lng;
		 data+="&type=friends";
		 data+="&id=" + id;
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

			success: function(t){
			    $flair.user.friendsFlairs=t;
				$flair.go.placeFlairs(t);				
				$flair.go.status=false;								
			}

		});
	
	},
	
	printNonUser: function(title) {
		var str="";
		                                                          
		str += "<div style='vertical-align:top;' ><div style='vertical-align:top;text-align:left;position:relative;top:-10px;height:150px;background-color:#ccc;' ></div>"; 
		
		str += this.printRole();
		
		str += "<div id='flairs_holder' style='text-align:center;' >";		
		str += "</div>";		
		$flair.window.printPage(str);;
	
	},
	
	notice:function(passed,total){
		that = this;
		if(that.noticeHolder==false){
			var str = "";
			if(that.user.id==$flair.login.user.id){
				str  = "Currently you have " ;
			}else{
				str = "Currently " + this.user.name + " has ";
			}
		
			str = str + passed + " flairs accepted out of " + total + " flairs." ;
			//$('#notice_holder').hide();
			$('#notice_holder').html(str);
			$('#notice_holder').slideToggle('slow');
		}else{
			$('#notice_holder').slideToggle('slow');
		}
	},
	
	printRole: function(){

	var str="";
	var strTitle = "";
	var strURL = "";
	
	var extra ="";
	
	 	  if(this.isCastMember()===true){
		    strTitle = "<span style='font-size:1.4em;white-space:nowrap;' >" + this.user.place.name + "</span>";
		    strTitle = strTitle + "<br><span style='color:#999;white-space:nowrap;' >" + this.user.place.role + "</span>";
		    strURL = "#page=place&title=" + escape(strTitle) + "&id=" + this.user.place.pid;
		    if(this.user.id===$flair.login.user.id){
		    
		    	extra = "<a href='#page=role&title=My Title (Role)&id=me' ><div style='background-color:#eee;position:absolute;right:0px;top:0px;height:50px;width:100px;background-image:url(images/settings2.png);background-position:center center;background-repeat:no-repeat;' ></div></a>";
		    
		   		//	 strURL = "";
		    }
		    
		  }else if($flair.login.isActivationPending()){
		   strTitle = "<span style='line-height:40px;' >Verification Pending for " + $flair.login.user.place.name + "</span>";
		   strURL = "#page=role&title=Verification&id=" + $flair.login.user.place.activation_pid;
		  }else if($flair.login.user.id===this.user.id){
		   strTitle = "<span style='line-height:40px;' >My Settings</span>";
		   strURL = "#page=role&title=New Cast Member&id=me";
		  }
		  
		  
		 if(strTitle && strURL){
		 str += "<a href='" + strURL + "' ><div style='background-color:#eee;position:relative;top:-10px;font-size:1.4em;border:0px;vertical-align:top;padding:5px;' ><img src='images/oscar_48.png' style='float:left;heignt:15px;vertical-align:top;' >";
		 str += strTitle ;
		 str += extra + "<div style='clear:both;' ></div></div></a>";
		}else{
			str = "";			
		}
		return str;	
	},
	
	
	print: function(){
		
		if($flair.go.id!=this.user.id){
			return;
		}
		
		var str="";
		                                                          
		str += this.printRole();
		
		str += "<div id='user_content_holder' >";
					
		str += "</div>";
		
		$flair.window.print("left_user_content_holder_main",str);
		
		if(this.state==="rec"){
			this.rec();
		}else{			
			$flair.go.placeFlairs(this.user.flairs);
		}
		
		
		 var rec=this.getRecievedFlairs();
		 if(this.user.id===$flair.login.user.id || rec.length>0){
		 	//do nothing
		 }else{
		   	$('#footer').hide();
		 }
			
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
		var url="search.php";
		data="&type=user";	
		data+="&id=" + id;	
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
				success: function(user){	
						if($flair.go.id==user.id){
							if($flair.login.user.id==user.id){
							  $flair.login.user=user;
							}
							that.users[user.id] = user;
							that.user = user;
							that.print();
							$flair.newFlair=false;
						}
				}
			});
	},
	
	
	
  isLoggedIn: function (title,id){ 	
    var that = this;
	
	FB.getLoginStatus(function(response) {	
		if (response.status == 'connected') {
			$flair.user.accessToken = FB.getSession().access_token;
			$flair.user.init(title,id,true);
		} else if (response.status === 'not_authorized') {		  
		    $flair.user.FBLogin();			
	  } else {
			$flair.user.FBLogin();			
	  }
 });
 

	
  },
  
  
  FBLogin: function() {
	  var str="";
	  str ="<a onclick='$flair.user.FBLoginProcess();' ><div style='margin-top:0px;margin-left:8px;margin-right:8px;' ><div style='height:256px;background-position:50% 50%;background-repeat:no-repeat;background-image:url(http://icons.iconarchive.com/icons/artua/mac/256/Lock-icon.png);' ></div>";
	  
		  str+="<div style='text-align:center;padding:10px;padding-left:20px;padding-right:20px;font-size:2.4em;margin-top:10px;font-weight:bold;background-color:#eee;margin-left:auto;margin-right:auto;width:150px;-moz-border-radius: 4px;-webkit-border-radius: 4px;' >Login</div>";
		  
	  str+="</div></a>";
	  
	  $flair.window.printPage(str);
  
  },
  
  FBLoginProcess: function() {
	FB.login(
        function(response) 
		{
						if (response.status == 'connected') {
							$flair.user.accessToken = FB.getSession().access_token;					
							reload();						
                         } else {
                             $flair.go.back();
                         }
        },
        { scope: "email,publish_stream" }
     );
  }
 
}