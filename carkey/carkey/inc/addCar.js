$flair.addCar = {

	init: function(){
      this.print();
	},
	
	print: function(){
		var str = "";
		$flair.window.printFromDiv("signup1");
	   	    	
	},
	
	
	showError: function(str){
	 	$('#error').hide();
		$('#error').html(str);
		$('#error').fadeIn();
	},
	
	
	signup1: function(){
	var that = this;
 	var form = document.forms[0];
 	var zipcode = form.zipcode.value;
 	if(!this.isValidPostalCode(zipcode,"US")){
    	this.showError("Please enter a valid zipcode!");return;
 	} 
 
 	if(form.plate.value=="" || form.plate.value=="vehichle licence plate number"){
   		this.showError("Please enter a valid licence plate number!");return;
 	}
 	var geocoder = new google.maps.Geocoder();
 
    geocoder.geocode( { 'address': zipcode}, function (result, status) {
        var state = "N/A";
        for (var component in result[0]['address_components']) {
            for (var i in result[0]['address_components'][component]['types']) {
                if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1") {
                    state = result[0]['address_components'][component]['short_name'];
                    form.state.value=state;
                    that.loadPageFromForm();  
                }
            }
        }
  });
		
	
	},


   signup2: function() {
   	this.loadPageFromForm();
   
   },
		loadPageFromForm:function(){
		
		 var form = document.forms[0];
 		var data="";
 
	for(i=0; i<form.elements.length; i++){
		data=data + form.elements[i].name + "=" + form.elements[i].value + "&";
	}

	url = form.url.value;
			that = this;
			if($flair.ajaxRequest){
				$flair.ajaxRequest.abort();
			}
			
			
			$('#error').html("loading ... please wait");
			
			$flair.ajaxRequest=$.ajax({
			type: "POST",
			url: $flair.go.domain + url,
			data:data,
			dataType: "json",
				success: function(response){	
						if(response.status==1){
							$('#error').html("");
							$flair.window.printFromDiv("signup3",response);					     
						}else{
							that.showError(response.error);
						}
				}
			});
		
		
		},
	
	
	isValidPostalCode: function(postalCode, countryCode) {
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
	


}