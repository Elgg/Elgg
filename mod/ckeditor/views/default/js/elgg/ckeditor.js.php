<?php
/**
 * CKEditor JavaScript
 */

$css_url = elgg_get_simplecache_url('css', 'wysiwyg');

?>
define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery'); require('jquery.ckeditor');
	var CKEDITOR = require('ckeditor');

	CKEDITOR.basePath = elgg.config.wwwroot + 'mod/ckeditor/vendors/ckeditor/';

elgg.provide('elgg.ckeditor');

/**
 * Toggles the CKEditor
 *
 * @param {Object} event
 * @return void
 */
elgg.ckeditor.toggleEditor = function(event) {
	event.preventDefault();
	
	var target = $(this).attr('href');
	
	if (!$(target).data('ckeditorInstance')) {
		$(target).ckeditor(elgg.ckeditor.wordCount, elgg.ckeditor.config);
		$(this).html(elgg.echo('ckeditor:remove'));
	} else {
		$(target).ckeditorGet().destroy();
		$(this).html(elgg.echo('ckeditor:add'));
	}
}

/**
 * Provides a live-updating word counter.
 *
 * @param {Object} event
 * @return void
 */
elgg.ckeditor.wordCount = function() {
	$('#cke_bottom_' + this.name).prepend(
		'<div id="cke_wordcount_' + this.name + '" class="cke_wordcount">' + 
			elgg.echo('ckeditor:word_count') + '0' +
		'</div>'   
	);
	this.document.on('keyup', function(event) {
		//show the number of words
		var words = this.getBody().getText().trim().split(' ').length;
		var text = elgg.echo('ckeditor:word_count') + words + ' ';
		$('#cke_wordcount_' + CKEDITOR.currentInstance.name).html(text);
	});
}

/**
 * CKEditor configuration
 *
 * You can find configuration information here:
 * http://docs.cksource.com/Talk:CKEditor_3.x/Developers_Guide
 */
elgg.ckeditor.config = {
	toolbar : [['Bold', 'Italic', 'Underline', '-', 'Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
	toolbarCanCollapse : false,
	baseHref : elgg.config.wwwroot,
	removePlugins : 'contextmenu,showborders',
	defaultLanguage : 'en',
	language : elgg.config.language,
	resize_maxWidth : '100%',
	skin : 'BootstrapCK-Skin',
	uiColor : '#EEEEEE',
	contentsCss : '<?php echo $css_url; ?>',
	disableNativeSpellChecker : false,
	removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target'
};

elgg.ckeditor.init = function() {
	$('.ckeditor-toggle-editor').live('click', elgg.ckeditor.toggleEditor);
	$('.elgg-input-longtext').ckeditor(elgg.ckeditor.wordCount, elgg.ckeditor.config);
}

elgg.register_hook_handler('init', 'system', elgg.ckeditor.init);

});
