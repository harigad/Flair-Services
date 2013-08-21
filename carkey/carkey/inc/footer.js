$flair.footer = {


	init: function(page){
		 if(page==="user"){
		  
		  	this.show("user");
		  
		  }else if(page==="place"){
		  
		  	this.show("place");
		  
		  }else{
		  
		  	this.hide();
		  
		  }
	},
	
	show: function(page){
	
		$("#footer").hide();
	
	},
	
	hide: function(){
	
		$("#footer").hide();
	
	},		


	left: function(){
		if($flair.go.page=="user"){
			$flair.user.me();
		}else{
		    $flair.go.printPlace();
		}
	},
	
	
	right: function() {
		if($flair.go.page=="user"){
			$flair.user.rec();
		}else{
			$flair.cast.print();
		}
	
	}

}