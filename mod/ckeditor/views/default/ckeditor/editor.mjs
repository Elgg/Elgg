/**
 * This module can be used to bind CKEditor to a textarea
 */

import 'jquery';
import './ckeditor.js';
import elgg from 'elgg';
import hooks from 'elgg/hooks';

if (!document.getElementById('ckeditor-css')) {
	$('head').append('<link rel="stylesheet" type="text/css" id="ckeditor-css" href="' + elgg.get_simplecache_url('ckeditor/editor.css') + '">');
}

$(document).on('submit', 'form', function() {
	$(window).off('beforeunload.ckeditor');
});

export default {
	init: function (selector, editor_type) {
		var $input = $(selector);
		if (!$input.length) {
			return;
		}
		
		editor_type = editor_type || $input.data().editorType || 'default';
		
		// store used editor type
		$input.data('editorType', editor_type);

		import('ckeditor/config/' + editor_type).then((config) => {
			config = hooks.trigger('config', 'ckeditor', {'editor': editor_type, 'selector': selector}, config.default);
			
			ClassicEditor.create(document.querySelector(selector), config)
				.then(editor => {
					window.editor = editor;
					
					// set classname based on type
					$(editor.ui.view.element).addClass('elgg-ckeditor-' + editor_type);

					editor.model.document.on('change:data', () => {
						editor.updateSourceElement();
						$(editor.sourceElement).data('dirty', true);
						$(editor.sourceElement).trigger('change');
					});
					
					editor.keystrokes.set('Ctrl+Enter', (event, cancel ) => {
						var $submit_button = $(editor.sourceElement).closest('form').find('button[type="submit"]').eq(0);
						if ($submit_button.length) {
							$submit_button.trigger('click');
						}
						
						cancel();
					});
					
					$(window).on('beforeunload.ckeditor', function(event) {
						if ($(editor.sourceElement).data('dirty') && $(editor.sourceElement).closest('form').is(':visible')) {
							return true;
						}
					});
					
					if ($input.is(':focus')) {
						editor.focus();
					}
				});
		});
	},
	destroy: function (selector) {
		var $input = $(selector);
		if (!$input.length) {
			return;
		}
		
		var $editable = $input.next().find('.ck-editor__editable');
		if (!$editable.length) {
			return;
		}
		
		$editable[0].ckeditorInstance.destroy();
	}
};
