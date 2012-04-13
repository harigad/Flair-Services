var currentState;
currentState="default";


var directionsDisplay,largeMap;
function direction_viewer(latPlace,lngPlace,title,zoom,hideMarker){
	$flair.map.init(latPlace,lngPlace,title,zoom,hideMarker);
}

function showAddress(address){
	$flair.map.showAddress(address);
}

function showMap(placeLat,placeLng,title,zoom,showmarker) {
	$flair.map.showMap(placeLat,placeLng,title,zoom,showmarker);
}

function addMarker(lat,lng){
    var pos=new google.maps.LatLng(lat,lng);

    var marker = new google.maps.Marker({
        map:map,
        position:pos
    });
}

function restoreSearchField(field,val,password){
    hideBar();
    if(field.value=="")   {
        field.value=val;
    }
}



function switchTab(tabName){
    $('#default_content_nearme').hide();
    $('#default_content_friends').hide();
    $('#default_content_' + tabName).fadeIn();
}


function new_work_cancel(){
 
   $('#searchinput').val(currentTitle);
 
     newWorkPlace=false;    

  $('#settings_job_description').html(settings_job_description);
  $('.headerBg').slideDown();
  $('#nominate_this').html('');
  $('#nominate_this').hide();
  $('#trending_canvas').show();
  cancel();
}

function nominate_this_cancel(){
    if(newFoodItem){
     $('#searchinput').val(currentTitle);
    // $('#searchinput').css("background-color","#3B5998");
    // $('#searchinput').css("color","#fff");
    newFoodItem=false;
    }
    $('.headerBg').slideDown();
    $('#nominate_this').hide();
    $('#trending_canvas').show();	
    cancel();
}

function nominate_this_confirm(noun,nounName,verb,verbName){
    newFoodItem=false;
    url="nominate.php";
    data="&noun=" + noun;
    data+="&nounName=" + nounName;
    data+="&verb=" + verb;
    data+="&verbName=" + verbName;
    data+="&currentPage=" + currentPage;
    $.ajax({
        type: "POST",
        url: url,
        data:data,
        dataType: "json",
        success: function(t){
            if(t.status){
                cancel();
                $('.headerBg').slideDown();
                gotoPage(currentPage,t.name,t.id);
            }else{
                alert(t.error);
            }
        }
    });

}

function workplace_this(noun,nounName,verb,verbName){    
  str="";

  str="<div class='nominate_this_verb' >I work</div><div class='nominate_this_at' >@</div><div class='nominate_this_verb' style='font-weight:bold;' >" + nounName + "</div>";
  str+="<div onclick='new_work_confirm(\"" + noun + "\",\"" + nounName + "\",\"" + verb + "\",\"" + verbName + "\");' class='nominate_this_nominate_btn' >Save</div>";
  str+="<div onclick='new_work_cancel();' class='nominate_this_cancel_btn' >Cancel</div>";
  $('#nominate_this').html(str);
  
  $('.headerBg').slideUp();
  $('#trending_canvas').hide();   
  $('#search_content').hide();
  $('#nominate_this').show();
   $('#page_content').show();
}







function nominate_this(noun,nounName,verb,verbName){	
	
	//if(verbName.search(searchStr)!=-1)
	//{
	//
	//	alert("sentences are not allowed!");
	//	return;
	//
	//}

    str="";	
    str="<div class='nominate_this_verb' >" + verbName +  "</div><div class='nominate_this_at' >@</div><div class='nominate_this_noun' >" + nounName + "</div>";
    str+="<div onclick='nominate_this_confirm(\"" + noun + "\",\"" + nounName + "\",\"" + verb + "\",\"" + verbName + "\");' class='nominate_this_nominate_btn' >Flair</div>";
    str+="<div onclick='nominate_this_cancel();' class='nominate_this_cancel_btn' >Cancel</div>";
	
	
    $('#nominate_this').html(str);

    $('.headerBg').slideUp();

    $('#trending_canvas').hide();   

    $('#search_content').hide();

    $('#nominate_this').show();

     $('#page_content').show();

}


var searchMode;
searchMode="place";

var newFoodItem=false;
var newWorkPlace=false;

var currentNewFoodSearchNoun;
function newFoodSearch(searchMode){
	var str="<div style='padding-top:40px;' >";
	str += "<div onclick='newFoodSearchProcess();' class='flair_thumb boy_thumb' ></div>";
	str += "<div onclick='newFoodSearchProcess();'class='flair_thumb girl_thumb' ></div>";
	str += "<div onclick='newFoodSearchProcess();' class='flair_thumb coffee' ></div>";
	
	str += "<div onclick='newFoodSearchProcess();'class='flair_thumb alchohol' ></div>";
	str += "<div onclick='newFoodSearchProcess();' class='flair_thumb dessert' ></div>";
	str += "<div onclick='newFoodSearchProcess();'class='flair_thumb burger' ></div>";
	
	str += "<div onclick='newFoodSearchProcess();' class='flair_thumb pizza' ></div>";
	str += "<div onclick='newFoodSearchProcess();'class='flair_thumb ambience' ></div>";
	str += "<div onclick='nominate_this_cancel();' class='flair_thumb cancel_thumb' ></div>";
	
	str += "</div>";
	
	
	$('#nominate_this').html(str);

    $('.headerBg').slideUp();

    $('#trending_canvas').hide();   

    $('#search_content').hide();

    $('#nominate_this').show();

     $('#page_content').show();

}


function newFoodSearchProcess(){
    newFoodItem=true;
    searchModeClick("food");
	$('.headerBg').slideDown();
	$('#nominate_this').hide();
    
	$('#page_content').show();
    $("#search_content_food").hide();
    $("#search_content_place").hide();
    $("#searchinput").focus();

}


var settings_job_description="";
function newWorkSearch(){  
  newWorkSearchFindPlace();
   settings_job_description=$('#settings_job_description').html();
   return;
  var str="<div style='padding-left:10px;padding-right:10px;'";
  str+="<div style='text-align:center;font-size:1.4em;font-weight:bold;' >Do you work for a cafe/bar/restaurant?</div>";
  str+="<div>";
    str+="<a href='#' onclick='newWorkSearchFindPlace();' ><div class='nominate_this_nominate_btn' >YES</div></a>";
    str+="<div class='nominate_this_nominate_btn' style='background-color:#009900;' >NO</div>";
  str+="</div>";
  str+="</div>";
  settings_job_description=$('#settings_job_description').html();
  $('#settings_job_description').hide();
  $('#settings_job_description').html(str);
  $('#settings_job_description').fadeIn(); 
}


function newWorkSearchFindPlace(){
  newWorkPlace=true;
  searchModeClick('newplace');
  $("#search_content_food").hide();
  $("#search_content_place").hide();
  $("#searchinput").focus();    
}

function new_work_confirm(noun,nounName,verb,verbName){
  newFoodItem=false;
  url="work.php";
  data="&noun=" + noun;
  data+="&nounName=" + nounName;
  data+="&currentPage=" + currentPage;
  $.ajax({
      type: "POST",
      url: url,
      data:data,
      dataType: "json",
      success: function(t){
          if(t.status){
            cancel();
              $('.headerBg').slideDown();
              gotoPage(t.page,t.name,t.id);  
          }else{
              alert(t.error);
          }
      }
  });

}




var currentTitle;
var currentPageId;
var stageforward;

var currentURL;
currentURL="noPage";

function urlWatch(){
    if(currentURL!= document.location.hash){
        currentURL = document.location.hash;
        //if there is not anchor, the loads the default section
        if(!currentURL){
		   query="";
           $flair.go.init("");
        }else
        {
            query=currentURL.substring(1);
        }
	
		if(query!=""){
			$flair.go.init(query);
		}

	}
	
setTimeout("urlWatch();",300);
}

var currentPage;
currentPage="default";

var searchType;
searchType="";

var foodOrPlace;
pageOrPlace=""

function clearSearchField(field,val,password){
	$flair.window.showSearch();
}

function home(){
	window.location="#";
}

function cancel(){
	$flair.window.hideSearch();
}

function search(){
	$flair.search.init();
}


