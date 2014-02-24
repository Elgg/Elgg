/**
 * Insert embed media from embed plugin
 *
 * This JavaScript view is extending the view js/embed/embed
 */

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
