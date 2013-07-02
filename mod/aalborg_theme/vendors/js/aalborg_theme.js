$(document).ready(function(){
	// remove autofocus to avoid pagejump
	$(".elgg-form-login input").removeClass("elgg-autofocus");
	
	// iOS Hover Event Class Fix
	$('.elgg-page').attr("onclick", "return true");
});
