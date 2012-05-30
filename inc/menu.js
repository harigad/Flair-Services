$flair.menu = {

	init: function (menuName) {
		this.hideMenu('main');
		this.hideMenu('cancel');
		this.showMenu(menuName);
		this.show();
	},
	
	showMenu: function (menuName) {
		$('#menu_' + menuName).show();
	},
	
	hideMenu: function (menuName) {
		$('#menu_' + menuName ).hide();	
	},
	
	hide: function () {
		this.hideMenu('main');
		this.hideMenu('cancel');
		$('#menu').css("opacity",0);	
	},
	
	show: function () {
		$('#menu').css("opacity",100);	
	}

}