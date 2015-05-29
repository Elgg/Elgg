define(function(require) {
	var $ = require('jquery');
	var Reply = require('elgg/discussion/Reply');
	
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
});
