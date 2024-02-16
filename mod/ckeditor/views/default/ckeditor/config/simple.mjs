import 'jquery';
import elgg from 'elgg';
import base from 'ckeditor/config/base';
import mentions from 'ckeditor/config/mentions';
import file_upload from 'ckeditor/config/file_upload';

export default $.extend(base, mentions, file_upload, {
	toolbar: {
		items: elgg.data.ckeditor.toolbar_simple || [
			'Bold', 'Italic', 'Underline', 'Strikethrough', 'RemoveFormat'
		]
	}
});
