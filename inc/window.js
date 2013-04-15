$flair.window = {
	
	hideBar: function() {
		  window.scrollTo(0, 1);
	},

	fullScreen: function(str,title,fade,menu) {
		//hideBar
		this.hideBar();
	
		//hide header
		$flair.header.hide();
		
		//hide page		
		$('#search_content').hide();			
		$('#page_content').hide();			
			
		//set full screen text
		this.print('fullScreen',str);		
			
		//hide for full screen fade in effect
		$('#fullScreen').hide();		
		
		//show full screen
		if(fade){
			$('#fullScreen').fadeIn("fast");	
		}else{
		    
			$('#fullScreen').show();
		}
		 $('#header_fullscreen').fadeIn("fast");
		
	},
		
	cancelFullScreen: function(noFade) {
		 $('#header_fullscreen').hide();
		var map_canvas = $("#map_canvas");
		if(map_canvas){
			$("#map_canvas").hide();
		}
	
		//hideBar
		this.hideBar();
		
		//reset search screen
		$('#search_content').hide();
		$flair.header.hideCancel();
		
		//hide full screen
		$('#fullScreen').hide();			
		
		//hide page
		$('#searchinput').blur();
		$('#search_content').hide();			
		
		//show header
		if($flair.go.history){			
			//setTitle
			$flair.header.set($flair.go.title);		
			$flair.header.show();		
		}else{
			$flair.header.hide($flair.go.title);		
		}
		
		
		$('#page_content').fadeIn('fast');
		
		//show main menu
		$flair.menu.init('main');
		
		//reset page scroll
		$flair.scroll.scrollTo(0);
		
		if(map_canvas){
			setTimeout('$("#map_canvas").show();',500);
		}
	
	
	},	

	printPage: function(str,noFade) {
		
	
		//Hide
		$('#page_content').hide();
		
		//print data
		this.print('page_content',str);
		
		//scroll to Top
		$flair.scroll.scrollTo(0);
		
		////reset page
		this.cancelFullScreen(noFade);		
		
				var temp=document.getElementById('page_content');
				var x = temp.getElementsByTagName("script");
					for(var i=0;i<x.length;i++)
					{	
						eval(x[i].text);
					}
		
	},
	
	getAnimateTime: function() {
		return this.animateTime+1000;
	},
	
	onChange: function(){
	   if(this.mode==='place'){
	      $flair.placeSearch.process();
	   }else{
	      $flair.search.process();
	   }
	
	},	
	
	showSearch: function(id) {		
	    this.mode=id;
		$('#page_content').hide();		 		 
	   	$('#result').html("");
		$('#search_content').show();
		$flair.header.set("");
		$flair.header.showCancel();

		$('#searchinput').focus();
		
	},
	
	hideSearch: function() {		
		this.cancelFullScreen();		
	},
	
	print: function(divName,str) {		
		$("#" + divName).html(str);		
		$flair.scroll.enableLinksOnTap(divName);
		
		/*var animateTime=0;
				var classes = $('.flair_thumb').each( function () {				
				animateTime=animateTime+50;
				$(this).animate({opacity:1.0},animateTime);
			});
		*/
		
	}	
}