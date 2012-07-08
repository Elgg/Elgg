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
		$(target).ckeditor();
		$(this).html(elgg.echo('ckeditor:remove'));
	} else {
		$(target).ckeditorGet().destroy();
		$(this).html(elgg.echo('ckeditor:add'));
	}
}

/**
 * CKEditor initialization script
 *
 * You can find configuration information here:
 * http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
 */
elgg.ckeditor.init = function() {

	$('.ckeditor-toggle-editor').live('click', elgg.ckeditor.toggleEditor);
	
	$('.elgg-input-longtext').ckeditor(function() {
		//TODO word count code
	}, {
		toolbar : 'Basic',
		uiColor : '#EEEEEE',
		language : elgg.config.language,
	});
/*
	tinyMCE.init({
		mode : "specific_textareas",
		editor_selector : "elgg-input-longtext",
		theme : "advanced",
		plugins : "lists,spellchecker,autosave,fullscreen,paste",
		relative_urls : false,
		remove_script_host : false,
		document_base_url : elgg.config.wwwroot,
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,bullist,numlist,undo,redo,link,unlink,image,blockquote,code,pastetext,pasteword,more,fullscreen",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_path : true,
		width : "100%",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		setup : function(ed) {
			//show the number of words
			ed.onLoadContent.add(function(ed, o) {
				var strip = (tinyMCE.activeEditor.getContent()).replace(/(&lt;([^&gt;]+)&gt;)/ig,"");
				var text = elgg.echo('tinymce:word_count') + strip.split(' ').length + ' ';
				tinymce.DOM.setHTML(tinymce.DOM.get(tinyMCE.activeEditor.id + '_path_row'), text);
			});

			ed.onKeyUp.add(function(ed, e) {
				var strip = (tinyMCE.activeEditor.getContent()).replace(/(&lt;([^&gt;]+)&gt;)/ig,"");
				var text = elgg.echo('tinymce:word_count') + strip.split(' ').length + ' ';
				tinymce.DOM.setHTML(tinymce.DOM.get(tinyMCE.activeEditor.id + '_path_row'), text);
			});
		},
		content_css: elgg.config.wwwroot + 'mod/tinymce/css/elgg_tinymce.css'
	});
*/
}

elgg.register_hook_handler('init', 'system', elgg.ckeditor.init);
