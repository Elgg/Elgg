	if ($.fn.ckeditorGet) {
		try {
			var editor = textArea.ckeditorGet();
			editor.insertHtml(content);
		} catch (e) {
			// do nothing.
		}
	}
