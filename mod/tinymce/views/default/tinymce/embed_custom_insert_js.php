	if (window.tinyMCE) {
		var editor = window.tinyMCE.get(textAreaName);
		
		if (editor) {
			editor.execCommand("mceInsertContent", true, content);
		}
	}
	