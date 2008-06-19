
	/**
	 * Elgg profile javascript library
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 */
	 
function remove_user_menu() {

	$("#user_menu").hover(function() {}, function () {
		$("#user_menu").remove();
	});
		
};

$(document).ready(function () {
	
	$("a.icon").hover(function(e) {										  
		
		var userdescription = this.rel;

		var dwidth = $(document).width();

		$("body").append("<div id='user_menu'>"+ userdescription + "</div>");

		var top = e.pageY - 10;
		var left = e.pageX - 10;
		
		if ((left + 10 + $("#user_menu").width()) > (dwidth - 10)) {
			left = dwidth - $("#user_menu").width() - 50;
		}

		$("#user_menu")
			.css("top",(top) + "px")
			.css("left",(left) + "px")
			.fadeIn("medium");
			
		
		remove_user_menu();
			
    });

});