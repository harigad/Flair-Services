$flair.go = {

	history: 0,
	status:false,		
	backMode: false,
	backGroundColor: ["#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa","#999999","#aaaaaa"],

	init: function(query) {		
		clearTimeout($flair.timeout);
	
		this.status=true;

		if(query=="") {
			this.home();
		}else{		
				var myVars=query.split("&");
				pageArr=myVars[0].split("=");page=pageArr[1];
				pageArr=myVars[1].split("=");title=pageArr[1];
				pageArr=myVars[2].split("=");id=pageArr[1];
				this.load(page,unescape(title),id);
		}
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
		str=str+'<div id="map_canvas_container" style="overflow:hidden;-moz-border-radius: 3px;-webkit-border-radius: 3px;height:300px;margin-bottom:1px;" ><div id="map_canvas"  ></div></div>';
	    
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
			var places = $flair.location.places;		
			var thisPlace;
			for (place in places)
			{			
				if(places[place].id==id){
					thisPlace=places[place];
				}			
			}		
			
		if(thisPlace){			
			this.placeObj = thisPlace;
			this.local=true;
		}else if(passedPlaceObj){
			this.local=false;
			this.placeObj = passedPlaceObj; 
		}else{	
				this.local=false;
				this.updateHistory("place",title,id);		
				$flair.window.printPage('<div class="loading" style="height:100px;background-position:center;background-repeat:no-repeat;" ></div>',true);
				this.loadPlaceFromServer(id);
				return;
		}
		
		var str="<div style='text-align:center;vertical-align:top;' >";						
		//str=str+'<div id="map_canvas_container" ><div id="map_canvas" ></div></div>';
		str=str+"<div  style=\"padding-top:1px;text-align:center;\" >";
				if(this.local){
				str += "<a onclick='$flair.go.flair();' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[0] + ";color:#fff;' >flair</div></a>";
				}else{
				str += "<div class='flair_thumb' style='background-color:" + this.backGroundColor[0] + ";color:#fff;opacity:0.5;' >flair</div>";
				}
				str += "<a href='#page=map&title=" + escape(title) + "&id=" + (id) + "' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[0] + ";color:#fff;' >map</div></a>";
			
				str += "<a onclick='$flair.go.call();' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[1] +";color:#fff;' id='place_call' >call</div></a>";
						
			str = str + "<div id=\"flairs_holder\"  >";
			str=str + "</div>";			
		
		str += "</div>";	
	
		this.updateHistory("place",title,id);		
		$flair.window.printPage(str);	
		
			this.placeFlairs(this.placeObj['stickers']);
		//$flair.timeout = setTimeout("$flair.go.placeMap();",500);
       
	},
	
	call: function() {
		if(this.placeObj.phone){
		   window.location = "tel:" + this.placeObj.phone;
		}else{		  
			$flair.window.print("place_call","<img  style='position:absolute;top:45px;left:40px;' src='/images/ajax-loader.gif' >");		
			var that = this;	
				url="http://flair.me/search.php";
				data="&type=updatePhone";
				data+="&id=" + this.placeObj.id;
					if($flair.ajaxRequest){
						$flair.ajaxRequest.abort();
					}
				$flair.ajaxRequest=$.ajax({
				type: "POST",
				url: url,
				data:data,
				dataType: "json",
				success: function(place){
					$flair.window.print("place_call","call");
					if(place.id=that.placeObj.id && place.error==false){
						that.placeObj.phone=place.phone;						
						window.location = "tel:" + that.placeObj.phone;
					}
				}
			});	
		}
	},
	
	loadPlaceFromServer: function(pid){	
		var that = this;	
		url="http://flair.me/search.php";
		data="&type=search";
		data+="&searchMode=place";
		data+="&pid=" + pid;
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(place){
					that.place(place.name,place.id,place);
				}
			});		
	},
	
	placeFlairs: function(flairsArray) {	

		var str = "";	
		this.preLoadImages=[];
		for(var i=0;i<flairsArray.length;i++) {
			str += this.printFlairList(flairsArray[i],i,flairsArray.length);			
		}
		
		$flair.window.print("flairs_holder",str);		
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
		var str="<div style='text-align:center;' >";						
		
			str += "<a onclick=\"$flair.go.refreshHomePlaces();\" ><div id='home_refresh'  class='flair_thumb' >refresh</div></a>";
			str += "<a href=\"#page=friends&title=friends&id=friends\" ><div class='flair_thumb' >friends</div></a>";
			str += "<a href=\"#page=user&title=me&id=me\" ><div class='flair_thumb' >me</div></a>";
			
		str += "<div id='home_places' >";	
			for(var i=0;i<21;i++) {		
				bgColor=this.backGroundColor[i];
				str += "<div class='flair_thumb' style='color:#fff;background-color:" + bgColor + ";position:relative;' ></div>";
			}		
		str += "</div>";
		
		this.updateHistory("home","Flair","home");		
		$flair.window.printPage(str);
		
		this.homePlaces();		
	},
	
	refreshHomePlaces: function() {
		
	    $flair.window.print("home_refresh","<img  style='position:absolute;top:45px;left:40px;' src='/images/ajax-loader.gif' >");
		var str="";						
			
			for(var i=0;i<21;i++) {
				bgColor=this.backGroundColor[i];
				str += "<div class='flair_thumb' style='color:#fff;background-color:" + bgColor + ";position:relative;' ></div>";
			}			
	
			$('#home_places').html(str);
			$flair.window.print('home_places',str);
			
		$flair.location.loadPlaces();	
	},	
	
	homePlaces: function() {
			var places = $flair.location.places;
			
			var str="";
	
	
			for (place in places)
			{
				var bgColor = this.backGroundColor[place];
				var thisPage=places[place].page;
				if(!thisPage)thisPage="place";
				  str += "<a href=\"#page=" + thisPage +  "&title=" + escape(places[place].name) + "&id=" + places[place].id + "\" ><div class='flair_thumb' style='color:#fff;background-color:" + bgColor + ";'  >" + places[place].name + "</div></a>";
				//str += "<div style='vertical-align:top;position:relative;' ><div class='flair_thumb' style='background-color:" + this.backGroundColor[place] + ";' ></div><div class='flair_thumb' style='background-color:#fff;color:#999;vertical-align:top;width:182px;padding:10px;line-height:80px;min-height:80px;' ></div></div>";
			}
			
			var remainder = places.length % 3;
			for (var i=0;i<(3-remainder);i++)
			{
				var bgColor = this.backGroundColor[places.length+i];
				str += "<div class='flair_thumb' style='color:#fff;background-color:" + bgColor + ";'  ></div>";
			}
			
			$flair.window.print('home_places',str);			
			$flair.window.print("home_refresh","refresh");
	},
	
	load: function(page,title,id) {	

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
		  

		 url="http://flair.me/search.php";

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

			url: url,

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