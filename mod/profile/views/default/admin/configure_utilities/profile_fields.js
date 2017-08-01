/**
 * Configure profile fields specific javascript functions
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	
	// draggable profile field reordering.
	$('#elgg-profile-fields').sortable({
		items: 'li',
		handle: 'span.elgg-state-draggable',
		stop: moveProfileField
	});

	/**
	 * Save the plugin profile order after a move event.
	 *
	 * @param {Object} e  Event object.
	 * @param {Object} ui jQueryUI object
	 * @return void
	 */
	function moveProfileField (e, ui) {
		var orderArr = $('#elgg-profile-fields').sortable('toArray');
		var orderStr = orderArr.join(',');

		elgg.action('profile/fields/reorder', {
			fieldorder: orderStr
		});
	}
});
