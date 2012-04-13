$flair.search = {

	ajaxRequest: null,
	searchMode: "place",

	init: function (searchMode) {
	
		if(searchMode){
			this.searchMode = searchMode;
		}
		
		if($flair.lat==0 || $flair.lng==0){
			getLocation();        
			return;
		}
		
		if(this.ajaxRequest){
			this.ajaxRequest.abort();
		}

		searchStr=$flair.header.get();
		searchStr=$.trim(searchStr);
		if(searchStr=="search")searchStr="";
				
					var str = "";
				    searchStr = searchStr.toLowerCase();
					var searchWords = searchStr.split(" ");
					var lastWord = searchWords[searchWords.length-1];
					var searchStrLower = lastWord.toLowerCase();
					
					var bgColor1 = $flair.go.backGroundColor[0];		
					var bgColor2 = $flair.go.backGroundColor[1];
		
					var placeObj = $flair.go.placeObj;
					
					var foods = placeObj.foods;
					var recps = placeObj.recipeients;
					var thumbs = $tree.flairs;
					var str="<div style='text-align:center;' >";
					
					var typeFoods = [];
					
					var localSearchResults = {};
					var localSearchCount = 0;
					
					
					if(searchStr.search(" by")==-1){
						var placesArray = $flair.location.places;					
						for(pl in placesArray){
							var foods = placesArray[pl].foods;
							for(foodid in foods) {	
										if(searchStrLower==""){												
												
										}else{										
										var foodNameLower = foods[foodid]['name'];
										var searchRegex=new RegExp("\\b" + searchStrLower + "[^\b]*?\\b","gi");
										   var words=foodNameLower.match(searchRegex);
										   
										   if(words){			
												for(var wi=0;wi<words.length;wi++){
												  if(! localSearchResults[words[wi].toLowerCase()]){
													localSearchResults[words[wi].toLowerCase()] = true;
													str+=this.printFoodThumb(words[wi],localSearchCount);	
													localSearchCount++;
												  }
												}
												
										   }
										}
									}									
							}
							
							}else{
							
								for(rid in recps){
								
								  var recpNameLower = recps[rid]['name'];
								  
								  var searchRegex=new RegExp("\\b" + searchStrLower + "[^\b]*?\\b","gi");
								  var words=recpNameLower.match(searchRegex);
										 
								if(words){							  
								   if(!localSearchResults[recps[rid]['name'].toLowerCase()]){
									localSearchResults[recps[rid]['name'].toLowerCase()] = true;
									str+=this.printUserThumb(recps[rid]['name'],recps[rid]['uid']);
									localSearchCount++;
								    }
								
								
								  }
								}
							  
							}
							
							
							
							if(localSearchCount<10){
								if(localSearchCount==0 && searchStr.search(" by")!=-1 && lastWord!="By" && lastWord!="by"){
								
								 var flairFoodSplit = searchStr.split(" by ");
								 
								 str+=this.printUserThumb(flairFoodSplit[1],-1);	
								}
							
								for(var x=0;x<10;x++)
								{
								  str+=this.printFoodThumb("",x);	
								}
							}							
					
					str += "</div>";
					$flair.window.print("result",str);	
					str="";				
					return;
				
		
		url="http://flair.me/search.php";
		
		data="&lat=" + $flair.lat;
		data+="&lng=" + $flair.lng;		
		data+="&type=search";
		data+="&pid=" + $flair.go.id;	
		data+="&search=" + searchStr;	
		data+="&searchMode=" + this.searchMode;
		
		var that = this;
		if($flair.ajaxRequest){
			$flair.ajaxRequest.abort();
		}
		$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
			success: function(results){				
				
				var str='<div style="text-align:center;" >';				
				for (var obj=0;obj<results.length;obj++) {
					str += that.printThumb(results[obj],obj);
				}			
				
				str=str + "</div>";
				$flair.window.print('result',str);
										
			}
		});
	
	},
	
	userUpdate: function(word,recpid){
	  var searchWords = $flair.header.get().split(" ");
	   var lastWord = searchWords[searchWords.length-1];
	   var str="";
	   for(var x =0;x<searchWords.length-1;x++){
	     str=str + searchWords[x] + " ";
	   }
	   str=str + unescape(word);	   
	
	   $flair.header.set(str);	   
	   
	   var lowerstr = str.toLowerCase();
	   var flairFoodSplit = lowerstr.split(" by ");  
	   if(flairFoodSplit.length==2){
	     $flair.flair.go(flairFoodSplit[0],flairFoodSplit[1],unescape(recpid));
	   }	   
	},
	
	
	update:function(word) {
	
	   var searchWords = $flair.header.get().split(" ");
	   var lastWord = searchWords[searchWords.length-1];
	   var str="";
	   for(var x =0;x<searchWords.length-1;x++){
	     str=str + searchWords[x] + " ";
	   }
	   str=str + unescape(word) + " ";	   
	
	   $flair.header.set(str);
	
	},
	
  printUserThumb: function(username,userid) {				
					var str="";										
					 str=str + '<a onclick=$flair.search.userUpdate("' + escape(username) + '",' + userid + '); >';
					 str= str + '<div style="vertical-align:top;position:relative;" >';
					 str=str + '<div  class="search_result" >';
					 str=str + username;
					 str=str + '</div></div></a>';					 
					return str;
	},
	
	
	printFoodThumb: function(word,i) {				
					var str="";					
					
					 str=str + '<a onclick=$flair.search.update("' + escape(word) + '"); >';
					 str= str + '<div style="vertical-align:top;position:relative;" >';
					 str=str + '<div  class="search_result"  >';
					 str=str + word;
					 str=str + '</div></div></a>';					 
					return str;
	},
	
	printThumb: function(obj,i) {				
					var str="";
					var thumbs = $tree.flairs;
					var bgColor = $flair.go.backGroundColor[i];
					var iconObj = thumbs[obj.type];
					 if(iconObj) {icon = iconObj.icon; }else {icon = "" ;}
					 str=str + '<a onclick=$flair.search.update("' + escape(obj.name) + '"); >';
					 str= str + '<div style="vertical-align:top;position:relative;" ><div class="flair_thumb" style="background-color:' + bgColor + ';background-image:url(/images/icons/' + icon + ');" ></div>';
					 str=str + '<div  class="flair_thumb" style="background-color:#fff;color:#999;vertical-align:top;width:182px;min-height:80px;" >';
					 str=str + obj.name;
					 str=str + '</div></div></a>';					 
					return str;
	}
	
}