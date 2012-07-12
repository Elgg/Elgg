	if ($.fn.ckeditorGet) {
		try {
			var editor = $(textAreaId).ckeditorGet();
			editor.execCommand("inserthtml", content);
		} catch (e) {
			// do nothing.
		}
	}
