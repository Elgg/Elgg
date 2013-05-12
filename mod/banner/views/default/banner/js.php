$(function() {
	 $('.elgg-banner-close').click(function() {
	 	
		$(this).parent().fadeOut();
		date = new Date();
		date.setDate(date.getDate() + 365); 
		console.log(date.toGMTString());
		document.cookie = "banner=" + $(this).data("timestamp") + ";expires="+date.toGMTString();
		  
	});
});

