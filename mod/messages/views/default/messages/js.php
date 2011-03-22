
$(function() {
	$("#messages-toggle").click(function() {
		$('input[type=checkbox]').click();
	});
	
	$("#messages-show-reply").click(function() {
		$('#messages-reply-form').slideToggle('medium');
	});
});
