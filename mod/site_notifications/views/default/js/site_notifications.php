<?php
/**
 * site notifications JavaScript
 */
?>

elgg.provide('elgg.site_notifications');

elgg.site_notifications.init = function() {
	$('.site-notifications-delete').live('click', elgg.site_notifications.delete);
	$('.site-notifications-link').live('click', elgg.site_notifications.auto_delete);
	$('#site-notifications-toggle').live('click', elgg.site_notifications.toggle_all);
};

/**
 * Delete notification asynchronously when delete button clicked
 *
 * @param {Object} event
 *
 * @return void
 */
elgg.site_notifications.delete = function(event) {
	
	var $item = $(this).closest('.elgg-item');
	$item.slideToggle('medium');

	elgg.action($(this).attr('href'), {
		success: function(json) {
			if (json.system_messages.error.length) {
				// Something went wrong, so undo the optimistic changes
				$item.slideToggle('medium');
			}
		},
		error: function() {
			// Something went wrong, so undo the optimistic changes
			$item.slideToggle('medium');
		}
	});

	event.preventDefault();
}

/**
 * Delete notification for this link
 *
 * @param {Object} event
 *
 * @return void
 */
elgg.site_notifications.auto_delete = function(event) {
	var id = $(this).attr('id');
	id = id.replace("link", "delete");
	elgg.action($('#' + id).attr('href'), {});
}

/**
 * Toggle the checkboxes in the site notification listing
 *
 * @return void
 */
elgg.site_notifications.toggle_all = function() {
	$('.site-notifications-container input[type=checkbox]').click();
};

elgg.register_hook_handler('init', 'system', elgg.site_notifications.init);
