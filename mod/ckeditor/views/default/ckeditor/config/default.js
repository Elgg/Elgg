define(['jquery', 'elgg', 'ckeditor/config/base', 'ckeditor/config/mentions', 'ckeditor/config/file_upload'], function($, elgg, base, mentions, file_upload) {
	
	return $.extend(base, mentions, file_upload, {
		toolbar: {
			items: elgg.data.ckeditor.toolbar_default || [
				'Bold', 'Italic', 'Underline', 'Strikethrough',
				'|',
				'NumberedList', 'BulletedList', 'outdent', 'indent', 'alignment',
				'|',
				'Link', 'imageUpload', 'blockQuote', 'insertTable', 'undo', 'redo',
				'|',
				'RemoveFormat', 'sourceEditing'
			]
		},
		table: {
			contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties']
		},
		alignment: {
			options: ['left', 'center', 'right']
		}
	});
});
