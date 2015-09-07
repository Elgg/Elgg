define(function(require) {
	// guarantee hooks will be registered
	require('elgg/booted');

	var elgg = require('elgg');
	var $ = require('jquery');

	var config = {
		toolbar: [['Bold', 'Italic', 'Underline', 'RemoveFormat'], ['Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
		removeButtons: 'Subscript,Superscript', // To have Underline back
		allowedContent: true,
		baseHref: elgg.config.wwwroot,
		removePlugins: 'liststyle,contextmenu,tabletools,resize',
		extraPlugins: 'blockimagepaste',
		defaultLanguage: 'en',
		language: elgg.get_language(),
		skin: 'moono',
		uiColor: '#EEEEEE',
		contentsCss: elgg.get_simplecache_url('elgg/wysiwyg.css'),
		disableNativeSpellChecker: false,
		disableNativeTableHandles: false,
		removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
		autoGrow_maxHeight: $(window).height() - 100
	};

	return elgg.trigger_hook('config', 'ckeditor', {}, config);
});
