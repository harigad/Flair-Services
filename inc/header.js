$flair.header = {	
	status: true,		
	
	showDefaultMenu: function () {				
		$('#header_default_left_btn').hide();		
		$('#home_menu').hide();	
	},

	hideCancel: function() {	
		$flair.search.searchMode="place";
		$flair.header.hideSearch();
		//setTitle		this.set($flair.go.title);
	},
		
	showSearch: function () {
		$('#fullScreen').hide();
		this.show();
		$('#logo').hide();
		$('#home_menu').hide();		
		$('#search_menu').show();	
		$('#header_default_search_btn').show();			
		$('#header_default_left_btn').hide();		
	},		
	
	setLogo: function (str) {		
		$('#logo').html(str);
	},
	
	hideSearch: function () {		
		$('#search_menu').hide();
		if($flair.go.id=="home"){
			$('#home_menu').hide();									
			$('#header_default_left_btn').hide();
			//this.setLogo('<img src="/images/logos/flair_logo.png" style="height:50px;" >');
			this.setLogo("Flair");			
		}else{			
			this.setLogo($flair.go.title);			
			$('#header_default_left_btn').show();		
			$('#home_menu').show();		
		}
		$('#logo').show();
	},	
	
	show: function() {		
		$('#header_fullscreen').html("");
		$('#header_default').show();
	},
	
	hide: function(title) {
		$('#header_default').hide();
	},
	
	get: function () {
		return $('#searchinput').val();
	},
	
	set: function(title) {	
		$('#searchinput').val(title);
	},
	
	showCancel: function() {				  
		$flair.header.showSearch();	
	}	

}