/**
 * site notifications JavaScript
 */
elgg.provide('elgg.site_notifications');

elgg.site_notifications.init = function() {
	$(document).on('click', '.site-notifications-delete', elgg.site_notifications.delete);
	$(document).on('click', '.site-notifications-link', elgg.site_notifications.auto_delete);
	$(document).on('click', '#site-notifications-toggle', elgg.site_notifications.toggle_all);
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
};

/**
 * Delete notification for this link
 *
 * @param {Object} event
 *
 * @return void
 */
elgg.site_notifications.auto_delete = function(event) {
	var href = this.href;
	var id = this.id.replace("link", "delete");

	require(['elgg/spinner'], function (spinner) {
		elgg.action($('#' + id).prop('href'), {
			beforeSend: spinner.start,
			complete: function() {
				location.href = href;
			}
		});
	});

	return false;
};

/**
 * Toggle the checkboxes in the site notification listing
 *
 * @return void
 */
elgg.site_notifications.toggle_all = function() {
	$('.site-notifications-container input[type=checkbox]').click();
};

elgg.register_hook_handler('init', 'system', elgg.site_notifications.init);
