<?php
/**
 * Insert embed media from embed plugin
 *
 * This JavaScript view is included in the view js/embed/embed
 */
?>
	if ($.fn.ckeditorGet) {
		try {
			var editor = textArea.ckeditorGet();
			editor.insertHtml(content);
		} catch (e) {
			// do nothing.
		}
	}
