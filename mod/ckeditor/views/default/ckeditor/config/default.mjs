import 'jquery';
import elgg from 'elgg';
import base from 'ckeditor/config/base';
import mentions from 'ckeditor/config/mentions';
import file_upload from 'ckeditor/config/file_upload';
	
export default $.extend(base, mentions, file_upload, {
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
