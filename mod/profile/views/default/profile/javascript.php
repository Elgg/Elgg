<?php

	/**
	 * Elgg profile image Javascript
	 *
	 * @package ElggProfile
	 *
	 * @uses $vars['entity'] The user entity
	 */

//		header("Content-type: text/javascript");
//		header("Pragma: public");
//		header("Cache-Control: public");

?>

var submenuLayer = 1000;

function setup_avatar_menu(parent) {
	if (!parent) {
		parent = document;
	}

	// avatar image menu link
	$(parent).find("div.usericon img").mouseover(function() {
		// find nested avatar_menu_button and show
		$(this.parentNode.parentNode).children(".avatar_menu_button").show();
		$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow");
		//$(this.parentNode.parentNode).css("z-index", submenuLayer);
	})
	.mouseout(function() {
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block") {
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_on");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children(".avatar_menu_button").hide();
		}
		else {
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_on");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children(".avatar_menu_button").show();
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow");
		}
	});


	// avatar contextual menu
	$(".avatar_menu_button img").click(function(e) {

		var submenu = $(this).parent().parent().find("div.sub_menu");

		// close submenu if arrow is clicked & menu already open
		if(submenu.css('display') == "block") {
			//submenu.hide();
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

			// force z-index - workaround for IE z-index bug
			avatar.css("z-index",  submenuLayer);
			avatar.find("a.icon img").css("z-index",  submenuLayer);
			submenu.css("z-index", submenuLayer+1);

			submenuLayer++;

			// change arrow to 'on' state
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_on");
		}

		// hide any other open submenus and reset arrows
		$("div.sub_menu:visible").not(submenu).hide();
		$(".avatar_menu_button").removeClass("avatar_menu_arrow");
		$(".avatar_menu_button").removeClass("avatar_menu_arrow_on");
		$(".avatar_menu_button").removeClass("avatar_menu_arrow_hover");
		$(".avatar_menu_button").hide();
		$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_on");
		$(this.parentNode.parentNode).children("div.avatar_menu_button").show();
		//alert("submenuLayer = " +submenu.css("z-index"));
	})
	// hover arrow each time mouseover enters arrow graphic (eg. when menu is already shown)
	.mouseover(function() {
		$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_on");
		$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
		$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_hover");
	})
	// if menu not shown revert arrow, else show 'menu open' arrow
	.mouseout(function() {
		if($(this).parent().parent().find("div.sub_menu").css('display')!="block"){
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow");
		}
		else {
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow_hover");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").removeClass("avatar_menu_arrow");
			$(this.parentNode.parentNode).children("div.avatar_menu_button").addClass("avatar_menu_arrow_on");
		}
	});

	// hide avatar menu if click occurs outside of menu
	// and hide arrow button
	$(document).click(function(event) {
			var target = $(event.target);
			if (target.parents(".usericon").length == 0) {
				$(".usericon div.sub_menu").fadeOut();
				$(".avatar_menu_button").removeClass("avatar_menu_arrow");
				$(".avatar_menu_button").removeClass("avatar_menu_arrow_on");
				$(".avatar_menu_button").removeClass("avatar_menu_arrow_hover");
				$(".avatar_menu_button").hide();
			}
	});


}

$(document).ready(function() {

	setup_avatar_menu();

});
