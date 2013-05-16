	if (CKEDITOR) {
		try {
			CKEDITOR.instances[textAreaId].insertHtml(content, 'html');
		} catch (e) {
			// do nothing.
		}
	}

