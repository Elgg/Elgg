<?php
?>
//<script>
elgg.provide("elgg.lazy_hover");

elgg.lazy_hover.init = function() {
	$(".elgg-avatar > .elgg-icon-hover-menu").live('click', function(event) {
		var $button = $(this),
		    $placeholder = $(this).parent().find(".lazy-hover-placeholder"),
			$all_placeholders,
			$span;

		if ($placeholder.length) {
			// select all similar placeholders
			$all_placeholders = $(".lazy-hover-placeholder[rel='" + $placeholder.attr("rel") + "']");

			// find first form for this menu
			$span = $all_placeholders.find("span:first");
			if ($span.length) {
				elgg.get("lazy_hover", {
					data: $span.data('json'),
					success: function(data) {
						if (data) {
							// replace all existing placeholders with new menu
							$all_placeholders.replaceWith(data);

							// restart click events
							$button.click();
						}
					}
				});
			}
			// prevent other events
			event.stopImmediatePropagation();
		}
	});
};

// register init hook
// lower priority is required have the live click registration before other click events
elgg.register_hook_handler("init", "system", elgg.lazy_hover.init, 400);