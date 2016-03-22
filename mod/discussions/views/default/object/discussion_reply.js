define(function (require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	var Reply = require('discussion/Reply');

	/**
	 * Initialize discussion reply inline editing
	 *
	 * @return void
	 */
	function init() {
		$(document).on('click', '.elgg-item-object-discussion_reply .elgg-menu-item-edit > a', function () {
			// store object as data in the edit link
			var dc = $(this).data('Reply'),
					guid;
			if (!dc) {
				guid = this.href.split('/').pop();
				dc = new Reply(guid);
				$(this).data('Reply', dc);
			}
			dc.toggleEdit();
			return false;
		});
	};

	elgg.register_hook_handler('init', 'system', init);

	return {
		Reply: Reply,
		init: init
	};
});