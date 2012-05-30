$flair = {};

$flair.lat=32.8958850;
$flair.lng=-96.9693660;

$flair.flair = {


	init: function(iconid,pid,pidName) {	
	  $flair.login.init(this.begin(iconid,pid,pidName));
	},


	begin: function(iconid,pid,pidName) {
	 
	  this.iconid=iconid;
	  
	  if(!pid){
		this.places(iconid);return;
	  }
	  
	
	  var that = this;
	  
			that.iconid=iconid;
			that.pid=pid;
			that.pidName=unescape(pidName);
			that.food();
		
	},
	
	
	refreshHomePlaces: function() {	  
		var str="";						
			
			str += "<div  class='flair_thumb' style='position:relative;' ><img  style='position:absolute;top:45px;left:40px;' src='/images/ajax-loader.gif' ></div>";
			
			for(var i=0;i<21;i++) {
				bgColor=$flair.go.backGroundColor[i];
				str += "<div class='flair_thumb' style='position:relative;' ></div>";
			}			
	

			$flair.window.print('places_holder',str);
			
		$flair.location.loadPlaces();	
	},	
	
	
	places: function() {
		var iconid = this.iconid;
	  	var places = $flair.location.places;
			
			var str="";
	
			var bgColor = $flair.go.backGroundColor[1];
			
			str +="<div id='places_holder' style='margin-left:0px;margin-right:0px;>";
	
		   // str += "<a onclick='$flair.flair.refreshHomePlaces();' ><div id='home_refresh' class='flair_thumb' style='background-color:" + bgColor + ";'  >reload</div></a>";
	
			for (place in places)
			{   var colorId=parseInt(place)+1;
			
				var bgColor = $flair.go.backGroundColor[colorId%2];
				var thisPage=places[place].page;
				if(!thisPage)thisPage="place";
				  str += "<a onclick='$flair.flair.init(\"" + iconid + "\",\"" + places[place].id + "\",\"" + escape(places[place].name) + "\");' ><div class='flair_thumb' style='background-color:" + bgColor + ";'  >" + places[place].name + "</div></a>";
				
			}
			
			var remainder = places.length;
			for (var i=0;i<(21-remainder);i++)
			{
				var bgColor = $flair.go.backGroundColor[i%2];
				str += "<div class='flair_thumb' style='background-color:" + bgColor + ";'  ></div>";
			}
			
			str +="</div>";
			
			$flair.window.fullScreen(str);			
			
			if(places.length==0){			
			  this.refreshHomePlaces();
			}
	},	
	
	
	food: function() {		
			$flair.search.init();
	},	
	
	people: function() {
		this.searchType="people";		
		$flair.window.showSearch(10);
	},
	
	processSearch: function(fid,fname,ftype){			
		if(this.searchType=="food"){
			this.fid=fid;
			this.fname=unescape(fname);
				if(fid==''){
					this.fname = $flair.header.get();
					this.fid=-1;
				}
			this.ftype=ftype;
			
			this.flairWindow();return;
		}else{		
			this.recp=fid;
			this.recpName=unescape(fname);
				if(fid==-1){
					this.recpName = $flair.header.get();
				}
		}
		
			
			
		this.searchType=null;	
		this.go();
	},
		
	flairWindow: function() {
		var str="";
		str="<div style='text-align:center;' >";
			str += "<div>";
			str += "<div class='flair_thumb left_thumb' style='background-image:url(/images/icons/" + this.ftype + ".png);' ></div>";			
			str += "<div class='flair_thumb right_thumb' style='background-color:#f1f1f1;font-size:1.4em;color:#aaa;' >" + this.fname + "</div>";
			str += "</div>";
			
				str +="<div style='text-align:center;padding:20px;color:#eee;font-size:1.4em;' >by</div>";
			
			str += "<a onclick='$flair.flair.people();' ><div>";
			str += "<div class='flair_thumb left_thumb' style='background-image:url(/images/icons/10.png);' ></div>";			
			str += "<div class='flair_thumb right_thumb' style='color:#6996F5;font-size:1.6em;background-color:#f1f1f1;' >Add Person</div>";
			str += "</div></a>";
			
			
		str+="</div>";
		$flair.window.fullScreen(str,"",false,true);			

	},		
		

	flairThumbs: function (category) {						
            var str="<div style='text-align:center;' >";
			if(category){
				var types = $tree["flairs_" + category];	
			}else{
				var types = $tree["flairs"];	
			}
			for (var type in types)
			{
			
				bgColor=$flair.go.backGroundColor[0];
				var thisPage=types[type].page;
				
				var icon = types[type].id + ".png";
					var iconObject= types[type].icon;
					if(iconObject){
					str += "<a onclick='$flair.flair.process(\"" + types[type].id + "\");' ><div id='flair_thumb_" + type + "' class='flair_thumb' style='opacity:1;background-color:" + bgColor + ";background-image:url(/images/icons/" + icon + ");' ></div></a>";
					}else{				
					str += "<a onclick='$flair.flair.process(\"" + types[type].id + "\");'  ><div id='flair_thumb_" + type + "' style='opacity:1;background-color:" + bgColor + ";color:#fff;' class='flair_thumb'  >" + types[type].name + "</div></a>";
					}
			}
			str=str + "</div>";

		$flair.window.fullScreen(str,"",false,true);			
	},
	
	cancel: function() {		
		
		$flair.window.cancelFullScreen();
		$flair.header.setLogo("");
		$flair.window.printPage("<div style='text-align:center;' ><img style='margin-left:auto;margin-right:auto;margin-top:100px;' src='/images/ajax-loader.gif' ></div>");
		
	},
		
	go: function(verbName,peopleName,people) {
	$flair.newFlair=true;
	
		var  url="nominate.php";
		var  data="&noun=" + this.pid;
		data+="&verb=" + -1;
		data+="&verbName=" + verbName;
		data+="&verbType=" + this.ftype;
		data+="&people=" + people;
		data+="&peopleName=" + peopleName;
		data+="&iconid=" + this.iconid;
		if($flair.login.accessToken){
		  data+="&accessToken=" + $flair.login.accessToken;	
		}

		var that = this;
		
		$flair.go.url("#page=place&title=" + escape(that.pidName) + "&pid=" + that.pid);
		
			flairAjaxRequest=$.ajax({
			type: "POST",
			url: $flair.go.domain + url,
			data:data,
			dataType: "json",
				success: function(t){				    
					if(t.status!=1){			
						//Display Verification Needed Message
						$flair.window.fullScreen(t.message,t.title,true,false);						
					}else{
						//that.updateThumbs(t);				
					    //$flair.go.place(that.pidName,that.pid);
						//$flair.go.url("#page=place&title=" + escape(that.pidName) + "&pid=" + that.pid);
					}
				}
			});	
	},
	
	updateThumbs: function(t) {	
		var places = $flair.location.places;
		
		    for (place in places)
			{			
				if(places[place].id==pid){
					places[place]=place;
				}			
			}
			$flair.go.url("#page=place&title=" + escape(this.pidName) + "&pid=" + this.pid);
			
	}
	
}