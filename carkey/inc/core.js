var geocoder = new google.maps.Geocoder();
  
function clearField(field,val){

  if(field.value==val){
    field.value="";
  //  field.style.color="#666";
  }

}


function restoreField(field,val){
  
  if(field.value==""){
    field.value=val;
   // field.style.color="#aaa";
  }

}

function signup1(){

 var form = document.forms.signup;
 var zipcode = form.zipcode.value;
 if(!isValidPostalCode(zipcode,"US")){
    showError("Please enter a valid zipcode!");return;
 } 
 
    geocoder.geocode( { 'address': zipcode}, function (result, status) {
        var state = "N/A";
        for (var component in result[0]['address_components']) {
            for (var i in result[0]['address_components'][component]['types']) {
                if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1") {
                    state = result[0]['address_components'][component]['short_name'];
                    form.state.value=state;
                    //$("#header").toggle('slide');
                    form.submit();
                }
            }
        }
  });
  
  
}

function showError(str){
	$('#error').html(str);
}

function isValidPostalCode(postalCode, countryCode) {
    switch (countryCode) {
        case "US":
            postalCodeRegex = /^([0-9]{5})(?:[-\s]*([0-9]{4}))?$/;
            break;
        case "CA":
            postalCodeRegex = /^([A-Z][0-9][A-Z])\s*([0-9][A-Z][0-9])$/;
            break;
        default:
            postalCodeRegex = /^(?:[A-Z0-9]+([- ]?[A-Z0-9]+)*)?$/;
    }
    return postalCodeRegex.test(postalCode);
}
 

