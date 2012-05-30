$flair.cast = {

    init: function(title,id){
	    $flair.go.updateHistory("cast","Cast",id);		
		this.print();	
	},
	
	print: function(){	
	   this.placeObj=$flair.go.placeObj;		
	   var cast = this.placeObj.cast;	
	   var str="";
	   
	   for(var i=0;i<cast.length;i++){
	     if(cast[i].uid!=-1){
	     str += "<a href=\"#page=user&title=" + escape(cast[i].name) + "&id=" + cast[i].uid + "\" ><div style='margin-top:3px;margin-bottom:8px;margin-left:8px;margin-right:8px;vertical-align:top;position:relative;background-color:#fff;display:block;text-align:left;' >";
		   str += "<img src='" + cast[i].photo_big + "' style='-moz-border-radius: 2px;overflow:hidden;-webkit-border-radius: 2px;vertical-align:top;width:150px;' >";
		   str += "<span style='white-space: nowrap;overflow:hidden;display:inline-block;font-size:2.5em;font-family:verdana;color:#ddd;padding:5px;' >";
		       str += cast[i].name.split(" ")[0];
		   
			   str += "<br><span style='font-size:0.5em;font-family:verdana;color:#6996F5;padding:5px;' >";
		       str += cast[i].role;
			   str += "</span>";			   
		   
		   str += "</span>";
		 	   
		 str += "</div></a>";
		 }
	   }
	   
	   
	   
	   	     str += "<a href=\"#page=role&title=" + escape("New Cast Member") + "&id=" + this.placeObj.id + "\" ><div style='margin-top:3px;margin-bottom:8px;margin-left:8px;margin-right:8px;vertical-align:top;position:relative;background-color:#fff;display:block;text-align:left;' >";
		   str += "<div class='flair_thumb' style='font-size:3.0em;width:130px;vertical-align:top;margin-top:1px;margin-bottom:0px;margin-left:0px;margin-right:0px;' >+</div>";
		   str += "<span style='display:inline-block;font-size:2.5em;font-family:verdana;color:#ddd;padding:5px;' >";
		       str += "ADD";		   
			   str += "<br><span style='font-size:0.5em;font-family:verdana;color:#6996F5;padding:5px;' >";
		       str += "Myself";
			   str += "</span>";			   
		   
		   str += "</span>";
		 	   
		 str += "</div></a>";
	   
	   
	   
	   $flair.window.printPage(str);
	}
}