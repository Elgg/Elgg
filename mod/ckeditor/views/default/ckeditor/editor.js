/**
 * This module can be used to bind CKEditor to a textarea
 * <code>
 *	  require(['elgg-ckeditor'], function(editor) {
 *	      editor.bind('textarea');
 *	  });
 * </code>
 */
define(['jquery', 'elgg', 'elgg/hooks', 'ckeditor/ckeditor'], function ($, elgg, hooks, CKEDITOR) {
	
	if (!document.getElementById('ckeditor-css')) {
		$('head').append('<link rel="stylesheet" type="text/css" id="ckeditor-css" href="' + elgg.get_simplecache_url('ckeditor/editor.css') + '">');
	}
	
	$(document).on('submit', 'form', function() {
		$(window).off('beforeunload.ckeditor');
	});
	
	return {
		init: function (selector, editor_type) {
			var $input = $(selector);
			if (!$input.length) {
				return;
			}
			
			editor_type = editor_type || $input.data().editorType || 'default';
			
			// store used editor type
			$input.data('editorType', editor_type);

			require(['ckeditor/config/' + editor_type], function (config) {
				config = hooks.trigger('config', 'ckeditor', {'editor': editor_type, 'selector': selector}, config);
			
				CKEDITOR.create(document.querySelector(selector), config)
					.then(editor => {
						window.editor = editor;
						
						// set classname based on type
						$(editor.ui.view.element).addClass('elgg-ckeditor-' + editor_type);

						editor.model.document.on('change:data', () => {
							editor.updateSourceElement();
							$(editor.sourceElement).data('dirty', true);
							$(editor.sourceElement).trigger('change');
						});
						
						$(window).on('beforeunload.ckeditor', function(event) {
							if ($(editor.sourceElement).data('dirty') && $(editor.sourceElement).closest('form').is(':visible')) {
								return true;
							}
						});
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
});
