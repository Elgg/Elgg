
$(document).ready(function() {
	
	$("a.icon").hover(function(e) {										  
		
		var userdescription = this.rel;

		var dwidth = $(document).width();

		$("body").append("<div id='user_menu'>"+ userdescription + "</div>");

		var top = e.pageY - 10;
		var left = e.pageX - 10;
		
		if ((left + 10 + $("div#user_menu").width()) > (dwidth - 10)) {
			left = dwidth - $("div#user_menu").width() - 50;
		}

		$("div#user_menu")
			.css("top",(top) + "px")
			.css("left",(left) + "px")
			.fadeIn("medium");
			
		
		remove_user_menu();
			
    });
	
	function remove_user_menu() {

		$("div#user_menu").hover(function() {}, function () {
			$("div#user_menu").remove();
		});
	}
	

});
