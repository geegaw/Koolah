$(document).ready(function(){
	
	$('#reset').click(function(){
		resetForm( $('#firstSuperForm') );
		return false;
	});
	
	$('#save').click(function(){
		return validateFormAndReport( $('#firstSuperForm') );		
	});
	
})
