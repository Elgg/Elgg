/**
 * Configure profile fields specific javascript functions
 */

import 'jquery';
import 'jquery-ui';
import Ajax from 'elgg/Ajax';
	
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
