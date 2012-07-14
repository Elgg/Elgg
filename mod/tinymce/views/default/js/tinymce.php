elgg.provide('elgg.tinymce');

/**
 * Toggles the tinymce editor
 *
 * @param {Object} event
 * @return void
 */
elgg.tinymce.toggleEditor = function(event) {
	event.preventDefault();
	
	var target = $(this).attr('href');
	var id = $(target).attr('id');
	if (!tinyMCE.get(id)) {
		tinyMCE.execCommand('mceAddControl', false, id);
		$(this).html(elgg.echo('tinymce:remove'));
	} else {
		tinyMCE.execCommand('mceRemoveControl', false, id);
		$(this).html(elgg.echo('tinymce:add'));
	}
}

/**
 * TinyMCE initialization script
 *
 * You can find configuration information here:
 * http://tinymce.moxiecode.com/wiki.php/Configuration
 */
elgg.tinymce.init = function() { 

	$('.tinymce-toggle-editor').live('click', elgg.tinymce.toggleEditor);

	/*
	$('.elgg-input-longtext').parents('form').submit( function() {
		tinyMCE.triggerSave();
	});
	*/
	
	$(document).delegate('form', 'submit', function() {
		tinyMCE.triggerSave();
	});
}

elgg.register_hook_handler('init', 'system', elgg.tinymce.init);