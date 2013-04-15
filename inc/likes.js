$flair.likes = {

  data: {},

  load: function() {
   
  },  
   
   
  onClick: function(sid) {  
	if(this.data[sid]===null || this.data[sid]===undefined){	
	  this.data[sid]={};
	  this.data[sid].status=false;
	}
	
	if(this.data[sid].status===false){
	  $("#like_div_" + sid).html("unlike"); 
	  this.data[sid].status=true;
	  this.update(sid,true);
	}else{
	  $("#like_div_" + sid).html("like");
	  this.data[sid].status=false;
	  this.update(sid,false);
	}
  
  },
  
  
  update: function(sid,status){
  
    //ajax call to server to update wether User liked this Flair or Not
  
  
  }
  

}