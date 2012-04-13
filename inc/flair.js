$flair = {};

$flair.lat=32.8958850;
$flair.lng=-96.9693660;

$flair.flair = {
	init: function(pid,category) {
		this.pid=pid;
		this.food();
	},	
	
	food: function() {		
			this.searchType="food";		
			$flair.window.showSearch(2);
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
		var html = $('#place_flairs').html();
		html = "<div style='text-align:center;' ><div style='style='vertical-align:top;position:relative;' ><div style='background-color:" + $flair.go.backGroundColor[1] + ";' class='flair_thumb loading' ></div><div  class='flair_thumb' style='background-color:#fff;color:#999;vertical-align:top;width:182px;padding:10px;line-height:80px;min-height:80px;text-align:center;' >please wait..</div></div></div>" + html;
		$flair.window.print("place_flairs",html);
		$flair.window.cancelFullScreen();
	},
		
	go: function(verbName,peopleName,people) {
		var  url="http://flair.me/nominate.php";
		var  data="&noun=" + this.pid;
		data+="&verb=" + -1;
		data+="&verbName=" + verbName;
		data+="&verbType=" + this.ftype;
		data+="&people=" + people;
		data+="&peopleName=" + peopleName;

		var that = this;
		
		that.cancel();
		
			flairAjaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(t){
				    $flair.newFlair=true;
					that.updateThumbs(t);				
					if(t.status!=1){			
						//Display Verification Needed Message
						$flair.window.fullScreen(t.message,t.title,true,false);						
					}else{
						$flair.user.loadUser("me");
					}
				}
			});	
	},
	
	updateThumbs: function(t) {	
		var places = $flair.location.places;
		var pid = t.pid;
		var thisPlace;
		var stickers;
			for (place in places)
			{			
				if(places[place].id==pid){
					thisPlace=places[place];
				}			
			}		
			
			if(thisPlace){	
				thisPlace.stickers = t.stickers;
				thisPlace.foods = t.foods;
			}
			
			if(pid == $flair.go.id){
				$flair.go.placeFlairs(thisPlace.stickers);
			}
	}
	
}