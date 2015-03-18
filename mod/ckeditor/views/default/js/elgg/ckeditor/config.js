/**
 * This code is inserted into the js/elgg/ckeditor view.
 *
 * Extend (append) this view to add code to alter the config variable.
 * The variables elgg, $, and CKEDITOR are available in scope.
 *
 * You can find configuration information here:
 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
 */

var config = {
	toolbar: [
		['Bold', 'Italic', 'Underline', 'RemoveFormat'],
		['Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink',
			'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']
	],
	removeButtons: 'Subscript,Superscript', // To have Underline back
	allowedContent: true,
	baseHref: elgg.config.wwwroot,
	removePlugins: 'contextmenu,tabletools,resize',
	extraPlugins: 'blockimagepaste',
	defaultLanguage: 'en',
	language: elgg.config.language,
	skin: 'moono',
	uiColor: '#EEEEEE',
	contentsCss: elgg.get_simplecache_url('css', 'elgg/wysiwyg.css'),
	disableNativeSpellChecker: false,
	disableNativeTableHandles: false,
	removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
	autoGrow_maxHeight: $(window).height() - 100
};
