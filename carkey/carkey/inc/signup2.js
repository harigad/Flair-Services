$signup2 = {


submit: function(){
	var form = document.forms.signup;
 	var data="";
 
	for(i=0; i<form.elements.length; i++){
		data=data + form.elements[i].name + "=" + form.elements[i].value + "&";
	}

	url = form.action;
				
			if(ajaxRequest){
				ajaxRequest.abort();
			}
			
			$('#error').html("");
			
			ajaxRequest=$.ajax({
			type: "POST",
			url: url,
			data:data,
			dataType: "json",
				success: function(data){	
						
						if(data['status']===1){
							showError('yahoo');
						}else{
							showError(data['error']);
						}
						
				}
			});

}














}