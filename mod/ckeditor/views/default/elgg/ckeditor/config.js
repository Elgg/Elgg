define(function(require) {
	require('elgg/init');
	var elgg = require('elgg');
	var $ = require('jquery');

	return elgg.trigger_hook('config', 'ckeditor', {'editor': 'default'}, {
		toolbar: [['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'], ['NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
		removeButtons: 'Subscript,Superscript', // To have Underline back
		allowedContent: true,
		baseHref: elgg.get_site_url(),
		removePlugins: 'liststyle,contextmenu,tabletools,tableselection',
		extraPlugins: 'blockimagepaste',
		defaultLanguage: 'en',
		language: elgg.get_language(),
		skin: 'moono-lisa',
		contentsCss: elgg.get_simplecache_url('elgg/wysiwyg.css'),
		disableNativeSpellChecker: false,
		disableNativeTableHandles: false,
		removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
		customConfig: false, //no additional config.js
		stylesSet: false, //no additional styles.js
	});
});

