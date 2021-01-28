define(['jquery', 'elgg'], function($, elgg) {

	return elgg.trigger_hook('config', 'ckeditor', {'editor': 'default'}, {
		toolbar: [['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'], ['NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
		removeButtons: 'Subscript,Superscript', // To have Underline back
		allowedContent: true,
		entities: false,
		baseHref: elgg.get_site_url(),
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
