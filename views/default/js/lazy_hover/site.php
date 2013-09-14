<?php
?>
//<script>
elgg.provide("elgg.lazy_hover");

elgg.lazy_hover.init = function() {
	$(".elgg-avatar > .elgg-icon-hover-menu").live('click', function(event) {
		var $button = $(this),
			$placeholder = $(this).parent().find(".elgg-menu-hover.elgg-ajax-loader"),
			$all_placeholders,
			$ul,
			action;

		if ($placeholder.length) {
			// select all similar placeholders
			$all_placeholders = $(".elgg-menu-hover[rel='" + $placeholder.attr("rel") + "']");

			// find the <ul> that contains data for this menu
			$ul = $all_placeholders.filter('[data-json]');
			action = elgg.get_site_url() + 'ajax/view/lazy_hover/user_hover';
			if ($ul.length) {
				elgg.get(action, {
					data: $ul.data('json'),
					success: function(data) {
						if (data) {
							// replace all existing placeholders with new menu
							$all_placeholders.removeClass('elgg-ajax-loader pvl')
								.html($(data).children());

							// show the new menu in the popup
							var $popup = $('.elgg-menu-hover:visible');
							if ($popup.attr("rel") === $placeholder.attr('rel')) {
								$popup.removeClass('elgg-ajax-loader pvl')
									.html($(data).children());
							}
						}
					}
				});
			}
		}
	});
};

// register init hook
// lower priority is required have the live click registration before other click events
elgg.register_hook_handler("init", "system", elgg.lazy_hover.init, 400);
