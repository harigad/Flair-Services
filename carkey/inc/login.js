$flair.login = {

  init:function(callBack){
  var that = this;
	        FB.getLoginStatus(function(response) {
	     	if (response.status == 'connected') {
			   that.accessToken = response.authResponse.accessToken;
			   
			   if(!that.user){
			   
			   that.loadMe(callBack);
			   
			   }else{
			        if(that.hasCars()){
				 		try{ callBack();	}catch(e){}	
				 	}else{
				 			$flair.addCar.init();
				 	}
				 	
			   }
			}else{		
			 that.show();
			 }
			});
  },
  
  
  hasCars: function(){
  
    if(this.user.cars.length>0){
    	return true;
    }else{
    	return false;
    }
  
  },

  
  show:function(){
   $('#canvas').hide();
   $('#login_container').show();	  
   $('#login').show();	
	
	$('#login').animate({
	top : "-120px"
	
	},2000,function(){
	
	$('#login_btn').fadeIn();
	
	});
	
  },


  success: function() {  
	$('#login').fadeOut('fast',function(){
	  $('#canvas').fadeIn('slow',function(){
		$('#login_container').hide();	  
	  });
	});
	
	
  },
  
  
  loadMe: function(callBack) {
  
  	url=$flair.go.domain + "search.php";
		var data="&type=user";		
	
		data+="&id=me";
		
		if($flair.login.accessToken){
		  data+="&accessToken=" + $flair.login.accessToken;	
		}	
			
			var str="";
			var that=this;
			
			$flair.meRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(user){	
				  that.user=user;				  	
		   
				     if(that.hasCars()){
				   		  try{callBack(); 	}catch(e){}	
				   	 }else{
				   	 	$flair.addCar.init();
				   	 }
				   
				  
				}
				
				});
				
				
				},
  
  
  
   submit: function() {
   var that = this;
	FB.login(
        function(response) 
		{
						if (response.status == 'connected') {
						    that.accessToken = FB.getSession().access_token;
							that.success();						
                         } else {
                             //$flair.go.back();
                         }
        },
        { scope: "email,publish_stream" }
     );
  },  
     
  isCastMember: function(uid){
	 if(this.getPid()){
	   return true;
	 }else{
	  return false;
	  }
  },
  
  isActivationPending: function(){
  	try{
		return this.user.place.activation_pid;
	}catch(e){
		return false;
	}  
  
  },
    
  getRole: function(){
  	try{
		return this.user.place.role;
	}catch(e){
		return false;
	}  
  },
  
  updateRole: function(role){
  	this.user.place.role=role;  
  	
  		url="http://flair.me/search.php";
		var data="&type=updateRole&role=" + role;		
	
		if($flair.login.accessToken){
		  data+="&accessToken=" + $flair.login.accessToken;	
		}	
		
			$flair.meRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(user){	
				}
				
				});
  	
  	
  	
  },  
  
  getPid: function(){
   try{
   		return this.user.place.pid;
   }catch(e){
   		return false;
   }  
  }

}