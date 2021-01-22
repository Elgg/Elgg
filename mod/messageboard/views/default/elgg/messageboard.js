/**
 * Messageboard module
 */
define(['jquery', 'elgg/Ajax'], function ($, Ajax) {
	
	var submitPost = function(event) {
		event.preventDefault();
		
		var form = $(this);
		var ajax = new Ajax();
		
		ajax.action('messageboard/add', {
			data: ajax.objectify(this),
			success: function(data) {
				// the action always returns the full ul and li wrapped annotation.
				var ul = form.next('ul.elgg-list-annotation');

				if (ul.length < 1) {
					form.parent().append(data);
				} else {
					ul.prepend($(data).find('li:first'));
				}
				form.find('textarea').val('');
			}
		});
	};
	
	$(document).on('submit', '.elgg-form-messageboard-add', submitPost);
});
