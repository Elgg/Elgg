/**
 * Insert embed media from embed plugin
 *
 * This JavaScript view is extending the view embed/embed.js
 */
require(['elgg', 'jquery'], function (elgg, $) {
	elgg.register_hook_handler('embed', 'editor', function(hook, type, params, value) {
		var textArea = $('#' + params.textAreaId);
		var content = params.content;
		if ($.fn.ckeditorGet) {
			try {
				var editor = textArea.ckeditorGet();
				editor.insertHtml(content);
				return false;
			} catch (e) {
				// do nothing.
			}
		}
	});
});
