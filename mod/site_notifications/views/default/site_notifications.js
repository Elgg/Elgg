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
	
	var $item = $("#" + $(this).data().entityRef);
	$item.slideToggle('medium');
	var $menu = $(this).parents('.elgg-state-popped').eq(0);
	
	require(['elgg/popup'], function(popup) {
		popup.close($menu);
	});

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
	event.preventDefault();
	
	var $link = $(this);
	
	require(['elgg/Ajax'], function (Ajax) {
		var ajax = new Ajax();
		ajax.action('site_notifications/delete', {
			data: {
				guid: $link.data().guid,
			},
			complete: function() {
				location.href = $link.attr('href');
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

