<?php

	/**
	 * Elgg profile image Javascript
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Pete Harris <pete@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

		header("Content-type: text/javascript");

?>

function setup_avatar_menu() {

	// avatar image menu link
	$("div.usericon img").mouseover(function() {
		// find nested avatar_menu_button and show
		$(this.parentNode.parentNode).children("[class=avatar_menu_button]").show();
	})
	.mouseout(function() { 
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block"){
			$(this.parentNode.parentNode).children("[class=avatar_menu_button]").hide();
		}
		else {
			$(this.parentNode.parentNode).children("[class=avatar_menu_button]").show();
		}
	});


	// avatar contextual menu
	$(".avatar_menu_button img.arrow").click(function(e) { 
		
		var submenu = $(this).parent().parent().find("div.sub_menu");
		
		// close submenu if arrow is clicked & menu already open
		if(submenu.css('display') == "block") {
			submenu.hide(); 		
			$(this).attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow_hover.gif');									
		}
		else {
			// get avatar dimensions
			var avatar = $(this).parent().parent().parent().find("div.usericon");
			//alert( "avatarWidth: " + avatar.width() + ", avatarHeight: " + avatar.height() );
			
			// move submenu position so it aligns with arrow graphic
			if (e.pageX < 840) { // popup menu to left of arrow if we're at edge of page
			submenu.css("top",(avatar.height()) + "px")
					.css("left",(avatar.width()-15) + "px")
					.fadeIn('normal');	
			}	
			else {
			submenu.css("top",(avatar.height()) + "px")
					.css("left",(avatar.width()-166) + "px")
					.fadeIn('normal');		
			}	
			// change arrow to 'on' state
			$(this).attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow_open.gif');	
		}
		
		// hide any other open submenus and reset arrows
		$("div.sub_menu:visible").not(submenu).hide();
		$(".usericon img.arrow").not(this).attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow.gif');
		$(".avatar_menu_button").not(this).hide();
	})
	// hover arrow each time mouseover enters arrow graphic (eg. when menu is already shown)
	.mouseover(function() { $(this).attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow_hover.gif'); })
	// if menu not shown revert arrow, else show 'menu open' arrow
	.mouseout(function() { 
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block"){
			$(this).attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow.gif');
		}
		else {
			$(this).attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow_open.gif');
		}
	});
	
	// hide avatar menu if click occurs outside of menu	
	// and hide arrow button						
	$(document).click(function(event) { 		
			var target = $(event.target);
			if (target.parents(".usericon").length == 0) {				
				$(".usericon div.sub_menu").fadeOut();
				$(".usericon img.arrow").attr('src','<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow.gif');
				$(".avatar_menu_button").hide();
			}
	});			   
	

}

$(document).ready(function() {

	setup_avatar_menu();						   
								   
});
