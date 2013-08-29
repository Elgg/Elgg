<?php
?>
//<script>
elgg.provide("elgg.lazy_hover");

elgg.lazy_hover.init = function(){
	$(".elgg-avatar > .elgg-icon-hover-menu").live('click', function(event) {
		$button = $(this);
		$placeholder = $(this).parent().find(".lazy-hover-placeholder");
		if($placeholder.length) {
			// select all similar placeholders
			$all_placeholders = $(".lazy-hover-placeholder[rel='" + $placeholder.attr("rel") + "']");

			// find first form for this menu
			$form = $all_placeholders.find("form:first");
			if ($form.length) {
				action = $form.attr("action");
				data = $form.serializeArray();

				elgg.get(action, {
					data: data,
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