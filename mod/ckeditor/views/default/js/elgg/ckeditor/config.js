define(function(require) {
	var elgg = require('elgg');
	var imageUploadUrl = elgg.normalize_url('action/ckeditor/upload');
	imageUploadUrl = elgg.security.addToken(elgg.ckeditor.imageUploadUrl);

	return {
		toolbar: [['Bold', 'Italic', 'Underline', '-', 'Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
		toolbarCanCollapse: false,
		baseHref: elgg.config.wwwroot,
		removePlugins: 'contextmenu,showborders',
		defaultLanguage: 'en',
		language: elgg.config.language,
		resize_maxWidth: '100%',
		skin: 'BootstrapCK-Skin',
		uiColor: '#EEEEEE',
		contentsCss: elgg.get_simplecache_url('css', 'elgg/wysiwyg.css'),
		disableNativeSpellChecker: false,
		removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
		filebrowserImageUploadUrl: imageUploadUrl
	};
});
