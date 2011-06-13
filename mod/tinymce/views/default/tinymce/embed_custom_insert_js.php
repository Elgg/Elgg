	if (window.tinyMCE) {
		var editor = window.tinyMCE.get(textAreaId);
		
		if (editor) {
			editor.execCommand("mceInsertContent", true, content);
		}
	}
	