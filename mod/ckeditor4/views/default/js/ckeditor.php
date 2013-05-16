<?php
/**
 * CKEditor JavaScript
 */

$css_url = elgg_get_simplecache_url('css', 'wysiwyg');

?>
elgg.provide('elgg.ckeditor');

/**
 * Toggles the CKEditor
 *
 * @param {Object} event
 * @return void
 */
elgg.ckeditor.toggleEditor = function(event) {
	event.preventDefault();
	
	var target = $(this).attr('href').substr(1);

	if (!CKEDITOR.instances[target]) {
		CKEDITOR.replace($('#'+target)[0], elgg.ckeditor.config);
		$(this).html(elgg.echo('ckeditor:remove'));
	} else {
		CKEDITOR.instances[target].destroy();
		$(this).html(elgg.echo('ckeditor:add'));
	}
}

/**
 * Counts the number of words into an editor
 *
 * @param {Object} event
 * @return void
 */

/**
 * CKEditor configuration
 *
 * You can find configuration information here:
 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
 */
elgg.ckeditor.config = {
	toolbar : [['Bold', 'Italic', 'Underline', '-', 'Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
	toolbarCanCollapse : false,
	baseHref : elgg.config.wwwroot,
	extraPlugins : 'autogrow,confighelper,wordcount',
	removePlugins : 'contextmenu,showborders,tabletools,resize',
	uiColor : '#EEEEEE',
	contentsCss : '<?php echo $css_url; ?>',
	disableNativeSpellChecker : false,
	removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
	autoGrow_maxHeight : 800,
};

elgg.ckeditor.init = function() {

	elgg.ckeditor.config.language = elgg.get_language();

	$('.ckeditor-toggle-editor').live('click', elgg.ckeditor.toggleEditor);
	$('.elgg-input-longtext').each(function() {
		CKEDITOR.replace(this, elgg.ckeditor.config);
	});
}

elgg.register_hook_handler('init', 'system', elgg.ckeditor.init);
