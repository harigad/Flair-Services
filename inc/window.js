$flair.window = {
	
	hideBar: function() {
		  window.scrollTo(0, 1);
	},

	fullScreen: function(str,title,fade,menu) {
		//hideBar
		this.hideBar();
	
		//hide header
		$flair.header.hide(title);
		
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
		
	},
		
	cancelFullScreen: function(noFade) {
		
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
		
		
		$('#page_content').show();
		
		//show main menu
		$flair.menu.init('main');
		
		//reset page scroll
		$flair.scroll.scrollTo(0);
		
		if(map_canvas){
			setTimeout('$("#map_canvas").show();',500);
		}
	
	
	},	

	printPage: function(str,noFade) {
		
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
	
	
	showSearch: function(id) {		
		$('#page_content').hide();		 		 
	   	
		$('#search_content').show();
		$flair.header.set("");
		$flair.header.showCancel();

		$('#searchinput').focus();
		$flair.search.init(id);
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