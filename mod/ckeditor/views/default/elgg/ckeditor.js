/**
 * This module can be used to bind CKEditor to a textarea
 * <code>
 *	  require(['elgg/ckeditor'], function(editor) {
 *	      editor.bind('textarea');
 *	  });
 * </code>
 * 
 * @module elgg/ckeditor
 */
define(function (require) {
	var elgg = require('elgg');
	require('elgg/init');
	var $ = require('jquery');
	require('jquery.ckeditor');
	var CKEDITOR = require('ckeditor');
	var config = require('elgg/ckeditor/config');

	var elggCKEditor = {
		/**
		 * A flag that indicates whether handlers were registered
		 */
		ready: false,
		bind: function (selector) {
			elggCKEditor.registerHandlers();
			CKEDITOR = elgg.trigger_hook('prepare', 'ckeditor', null, CKEDITOR);
			selector = selector || '.elgg-input-longtext';
			if ($(selector).length === 0) {
				return;
			}
			$(selector)
					.not('[data-cke-init]')
					.attr('data-cke-init', true)
					.ckeditor(elggCKEditor.init, elggCKEditor.config);
		},
		/**
		 * Register event and hook handlers
		 * @return void
		 */
		registerHandlers: function () {
			if (elggCKEditor.ready) {
				return;
			}
			elgg.register_hook_handler('prepare', 'ckeditor', function (hook, type, params, CKEDITOR) {
				CKEDITOR.plugins.addExternal('blockimagepaste', elgg.get_simplecache_url('elgg/ckeditor/blockimagepaste.js'), '');
				CKEDITOR.on('instanceReady', elggCKEditor.fixImageAttributes);
				return CKEDITOR;
			});
			elgg.register_hook_handler('embed', 'editor', function (hook, type, params, value) {
				var textArea = $('#' + params.textAreaId);
				var content = params.content;
				if ($.fn.ckeditorGet) {
					try {
						var editor = textArea.ckeditorGet();
						editor.insertHtml(content);
						return false;
					} catch (e) {
						// do nothing.
					}
				}
			});
			elggCKEditor.ready = true;
		},
		/**
		 * Toggles the CKEditor
		 *
		 * @param {Object} event
		 * @return void
		 */
		toggleEditor: function (event) {
			event.preventDefault();
			var target = $(this).attr('href');
			if (!$(target).data('ckeditorInstance')) {
				$(target).ckeditor(elggCKEditor.init, elggCKEditor.config);
				$(this).html(elgg.echo('ckeditor:html'));
			} else {
				$(target).ckeditorGet().destroy();
				$(this).html(elgg.echo('ckeditor:visual'));
			}
		},
		/**
		 * Initializes the ckeditor module
		 *
		 * @param {Object} textarea DOM element passed by ckeditor on init
		 * @return void
		 */
		init: function (textarea) {
			// show the toggle-editor link which is hidden by default, so it will only show up if the editor is correctly loaded
			$('.ckeditor-toggle-editor[href="#' + textarea.id + '"]').show();
		},
		/**
		 * CKEditor has decided using width and height as attributes on images isn't
		 * kosher and puts that in the style. This adds those back as attributes.
		 * This is from this patch: http://dev.ckeditor.com/attachment/ticket/5024/5024_5.patch
		 *
		 * @param {Object} event
		 * @return void
		 */
		fixImageAttributes: function (event) {
			event.editor.dataProcessor.htmlFilter.addRules({
				elements: {
					img: function (element) {
						var style = element.attributes.style;
						if (style) {
							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style);
							var width = match && match[1];
							if (width) {
								element.attributes.width = width;
							}
							match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
							var height = match && match[1];
							if (height) {
								element.attributes.height = height;
							}
						}
					}
				}
			});
		},
		/**
		 * CKEditor configuration
		 *
		 * You can find configuration information here:
		 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
		 */
		config: config
	};

	elggCKEditor.bind('.elgg-input-longtext');

	$(document).on('click', '.ckeditor-toggle-editor', elggCKEditor.toggleEditor);

	return elggCKEditor;
});
