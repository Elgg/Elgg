/**
 * Likes module
 */
define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');

	var ajax = new Ajax();

	/**
	 * @see \Elgg\Likes\JsConfigHandler
	 */
	var STATES = elgg.data.likes_states;

	function update_like_menu_item(guid, menu_item) {
		$('.elgg-menu-item-likes > a[data-likes-guid=' + guid + ']').each(function(){
			$(this).html(menu_item);
		})
	}

	function set_counts(guid, num_likes, new_value) {
		var li_modifier = num_likes > 0 ? 'removeClass' : 'addClass';

		$('.elgg-menu-item-likes-count [data-likes-guid=' + guid + ']').each(function () {
			$(this)
				.html(new_value)
				.parent()[li_modifier]('hidden');
		});
	}

	$(document).on('click', '.elgg-menu-item-likes a', function () {
		// warning: data is "live" and reflects changes from set_liked_state()
		var data = $(this).data(),
			guid = data.likesGuid,
			current_state = data.likesState;

		ajax.action(STATES[current_state].action, {
			data: {guid: guid}
		});

		return false;
	});

	// Any Ajax operation can return likes data
	elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'all', function (hook, type, params, value) {
		if (value.likes_status) {
			var status = value.likes_status;
			update_like_menu_item(status.guid, status.like_menu_item);
			set_counts(status.guid, status.count, status.count_menu_item);
		}
	});
});
