define(['jquery', 'elgg', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, elgg, base, mentions, file_upload) {
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: elgg.data.ckeditor.toolbar_simple || [
				'Bold', 'Italic', 'Underline', 'Strikethrough', 'RemoveFormat'
			]
		}
	});
});
