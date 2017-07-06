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

	function set_state(guid, state) {
		$('.elgg-menu-item-likes > a[data-likes-guid=' + guid + ']')
			.html(STATES[state].html)
			.prop('title', STATES[state].title)
			.data('likesState', state);
	}

	function set_counts(guid, num_likes) {
		var num_likes_echo_key = (num_likes == 1) ? 'likes:userlikedthis' : 'likes:userslikedthis';
		var li_modifier = num_likes > 0 ? 'removeClass' : 'addClass';

		$('.elgg-menu-item-likes-count [data-likes-guid=' + guid + ']').each(function () {
			$(this)
				.text(elgg.echo(num_likes_echo_key, [num_likes]))
				.parent()[li_modifier]('hidden');
		});
	}

	$(document).on('click', '.elgg-menu-item-likes a', function () {
		// warning: data is "live" and reflects changes from set_liked_state()
		var data = $(this).data(),
			guid = data.likesGuid,
			current_state = data.likesState;

		// optimistic
		set_state(guid, STATES[current_state].next_state);

		ajax.action(STATES[current_state].action, {
			data: {guid: guid}
		}).done(function (output, statusText, jqXHR) {
			if (jqXHR.AjaxData.status == -1) {
				// roll back state
				set_state(guid, current_state);
			}
		});

		return false;
	});

	// Any Ajax operation can return likes data
	elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'all', function (hook, type, params, value) {
		if (value.likes_status) {
			var status = value.likes_status;
			set_state(status.guid, status.is_liked ? 'liked' : 'unliked');
			set_counts(status.guid, status.count);
		}
	});
});
