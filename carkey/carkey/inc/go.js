$flair.go = {
	domain: "http://flair.me/carkey/",
	history: 0,
	status:false,		
	backMode: false,
	backGroundColor: ["#dddddd","#eeeeee"],
	init: function(query) {		
		clearTimeout($flair.timeout);
	
		this.status=true;

		if(query=="") {
			this.load("home");
		}else{		
				var myVars=query.split("&");
				pageArr=myVars[0].split("=");page=pageArr[1];
				pageArr=myVars[1].split("=");title=pageArr[1];
				pageArr=myVars[2].split("=");id=pageArr[1];
				this.load(page,unescape(title),id);
		}
	
	},
	
	request: function(name){
		var currentURL = document.location.hash;
		var query = currentURL.substring(1);
		var myStringArray=query.split("&");
		for(var i=0;i<myStringArray.length;i++) {	
			var myVarArray = myStringArray[i].split("=");
			if(myVarArray[0]==name){
				return unescape(myVarArray[1]);
			}
		}
	
		return null;	
	},
	
	updateHistory: function(page,title,id){
			if(this.id!=id || this.title!=title ) {			 
					if(this.backMode){				 
						this.history=this.history-1;
						this.backMode=false;		
					}else{				 
						this.history++;	
					}		 
			}
			this.id=id;
			this.title=title;
			this.page=page;		 		 	
	},	
	
	flair: function(category) {
			$flair.flair.init(this.id,category);		
	},
	
	animateFlairThumbs: function() {
		$("#flair_thumb_" + this.flairCount).animate({opacity:1.0},100,function() {
					this.flairCount=this.flairCount+1;
					if(this.flairCount<this.flairMaxCount){
						this.animateFlairThumbs();
					}
				}.bind(this));
	},
	
	map: function (title,id) {		
		thisPlace=this.placeObj;
		var str="<div style='text-align:center;' >";
		str=str+'<div id="map_canvas_container" ><div id="map_canvas"  ></div></div>';
	    
			str=str + '<div style="text-align:center;" >';
				str=str + '<a href="http://maps.google.com/maps?saddr=Current Location&daddr=' + thisPlace.lat + ',' + thisPlace.lng + '" ><div class="flair_thumb" style="width:284px;color:#fff;background-color:' + this.backGroundColor[0] + '" >directions</div></a>';			
			str=str + '</div>';
		
		str=str + "</div>";
		
		
		this.updateHistory("map","Map",id);		
		$flair.window.printPage(str);		
	
		setTimeout(function(){
			$flair.map.showMap(thisPlace.lat,thisPlace.lng,title);		
		},500);
	
	
	
	},
	
	
	placeObj: {},
	
	place: function(title,id,passedPlaceObj) {
		this.updateHistory("place",title,id);		
		
			var places = $flair.location.places;		
			var thisPlace;			
		
		if(thisPlace){			
			this.placeObj = thisPlace;
			this.local=true;
		}else if(passedPlaceObj){
			this.local=false;
			this.placeObj = passedPlaceObj; 
		}else if($flair.location.places[id]){
			this.placeObj = $flair.location.places[id];	
		      //do nothing		
		}else {	
				this.local=false;
				this.updateHistory("place",title,id);		
				$flair.window.printPage('<div class="loading" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
				this.loadPlaceFromServer(id);
				return;
		}
		
		$flair.location.places[id]=this.placeObj;
		this.printPlace();
		
		},
		
		
		printPlace: function(){
		debugger;
			if(this.placeObj.id !== $flair.go.request("id")){
				return;
			}
		
		var str="<div style='text-align:center;vertical-align:top;position:relative;top:-10px;' >";		
		
		str += "<div style='padding:10px;background-color:#999;color:#fff;text-align:left;font-size:1.2em;padding-right:80px;' >" + this.placeObj.city + "</div>";				
		
		//	str=str+"<div  style=\"padding-top:1px;text-align:center;\" >";
				
		//		str += "<a href='#page=map&title=" + escape(title) + "&id=" + (id) + "' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[1] + ";' >map</div></a>";
			
			
		//	str += "<a href='#page=cast&title=Cast&id=" + (id) + "' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[1] +";' id='place_call' >cast</div></a>";
		
			
		//str += "<a onclick='$flair.go.call();' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[1] +";' id='place_call' >call</div></a>";
		
		str+="<div  id='map_canvas' style='height:150px;' ></div>";
		
			str = str + "<div id=\"flairs_holder\"  >";
			str=str + "</div>";			
		
		str += "</div>";	
	
		$flair.window.printPage(str);	
		
		  if(this.placeObj){
			this.placeFlairs(this.placeObj['stickers']);
			}
		$flair.timeout = setTimeout("$flair.go.placeMap();",500);
	

       
	},
	
	dial: function(phone){
	  window.location = "tel:" + phone;
	},
	
	call: function() {
		if(this.placeObj.phone){
		   this.dial(this.placeObj.phone);
		}else{		  
			$flair.window.print("place_call","<img  style='position:absolute;top:45px;left:40px;' src='images/ajax-loader.gif' >");		
			var that = this;	
				url="search.php";
				data="&type=updatePhone";
				data+="&id=" + this.placeObj.id;
					if($flair.ajaxRequest){
						$flair.ajaxRequest.abort();
					}
				$flair.ajaxRequest=$.ajax({
				type: "POST",
				url: $flair.go.domain + url,
				data:data,
				dataType: "json",
				success: function(place){
					$flair.window.print("place_call","call");
					if(place.id=that.placeObj.id && place.error==false){
						that.placeObj.phone=place.phone;						
						that.dial(that.placeObj.phone);
					}
				}
			});	
		}
	},
	
	loadPlaceFromServer: function(pid){	
		var that = this;	
		url="search.php";
		data="&type=search";
		data+="&searchMode=place";
		data+="&pid=" + pid;
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
				success: function(place){
					that.place(place.name,place.id,place);
				}
			});		
	},
	
	placeFlairs: function(flairsArray) {		
	
		if(!flairsArray){
		  flairsArray=[];
		}
		
		var str = "<div id='flairs_holder' style='text-align:center;' >";		
			
		this.preLoadImages=[];
		for(var i=0;i<flairsArray.length;i++) {
			str += this.printFlairList(flairsArray[i],i,flairsArray.length);			
		}
		
		str += "</div>";
		$flair.window.print("user_content_holder",str);		
		this.loadImages();
		
	},
	
	
	loadImages: function() {
		
		for(x in this.preLoadImages){		
			 var img=new Image();
				 img.id="img_" + this.preLoadImages[x].id; 
				 img.onload=function(){										 
					$("#" + this.id).css("background-image","url(" + this.src + ")");			
				 }
				 img.src=this.preLoadImages[x].src;
			
		}
	
	},
	
	
	printFlairList: function(flair,i,length) {
	  return $flair.thumbs.print(flair,i,length);
	},	
	
	
	
	placeMap: function() {	
		$flair.map.showMap(this.placeObj.lat,this.placeObj.lng,title);	
	},
	
	
	home: function(){
		$flair.home.init();
		
	},
	

	
	
	load: function(page,title,id) {	
	
		  this.page = page;
		  
		   $flair.footer.init(page);
	
	
		  if(page=="home"){
			this.home();
			return;
		  }
		  
	
		  
		  if(page=="local"){
			this.local(title,id);
			return;
		  }
		  
		  if(page=="place"){
			this.place(title,id);
			return;
		  }
		  
		  if(page=="map") {
		    this.map(title,id);
			return;
		  }
		  
		  if(page=="flair") {
		    this.flair(title,id);
			return;
		  }
		  
		  if(page=="user"){
			$flair.user.init(title,id);
			return;
		  }
		  
		  if(page=="friends"){
			$flair.user.friends(title,id);
			return;
		  }
		  
		   if(page=="food"){		   
			$flair.food.init(title,id);
			return;
		  }		  
		   
		   if(page=="cast"){		   
			$flair.cast.init(title,id);
			return;
		  }
		  
		   if(page=="role"){		   
			$flair.role.init(title,id);
			return;
		  }
		  
		    if(page=="settings"){		   
			$flair.settings.init(title,id);
			return;
		  }
		  
		  	if(page=="nearby"){		   
			$flair.nearby.init(title,id);
			return;
		  }
		  

		 url="search.php";

		 data="&search=" + title;

		 data+="&lat=" + $flair.lat;

		 data+="&lng=" + $flair.lng;

		 data+="&type=" + page;

		 data+="&id=" + id;
		 
		 that=this;

		 this.updateHistory(page,title,id);
		//show loading message
		$flair.window.printPage('<div class="loading" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			$flair.ajaxRequest=$.ajax({

			type: "POST",

			url: $flair.go.domain + url,

			data:data,

			dataType: "html",

			success: function(t){
				$flair.window.printPage(t,true);				
				that.status=false;								
			}

		});

	},

	

	back: function() {		

		this.backMode=true;

		window.history.back();

	},

	

	forward: function() {

		window.history.forward();

	},

	

	url: function(str){

		if(str!=""){

			window.location = str;

		}else{

			window.location = "#" + str;

		}

	}
}