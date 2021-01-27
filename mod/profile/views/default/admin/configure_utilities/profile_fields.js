/**
 * Configure profile fields specific javascript functions
 */
define(['jquery', 'elgg/Ajax', 'jquery-ui/widgets/sortable'], function($, Ajax) {
	
	// draggable profile field reordering.
	$('#elgg-profile-fields').sortable({
		items: 'li',
		handle: 'span.elgg-state-draggable',
		stop: function () {
			var ajax = new Ajax();
			ajax.action('profile/fields/reorder', {
				data: {
					fieldorder: $('#elgg-profile-fields').sortable('toArray').join(',')
				}
			});
		}
	});
});
