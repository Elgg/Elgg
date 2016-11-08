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
		bind: function (selector) {
			elggCKEditor.registerHandlers();
			CKEDITOR = elgg.trigger_hook('prepare', 'ckeditor', null, CKEDITOR);
			selector = selector || '.elgg-input-longtext';
			if ($(selector).length === 0) {
				return;
			}
			$(selector).not('[data-cke-init]')
					.attr('data-cke-init', true)
					.each(function () {
						var opts = $(this).data('editorOpts') || {};

						if (opts.disabled) {
							// Editor has been disabled
							return;
						}
						delete opts.disabled;

						var visual = opts.state !== 'html';
						delete opts.state;

						var config = $.extend({}, elggCKEditor.config, opts);
						$(this).data('elggCKEeditorConfig', config);

						if (!visual) {
							elggCKEditor.init(this, visual);
						} else {
							$(this).ckeditor(elggCKEditor.init, config);
						}
					});
		},
		/**
		 * Register event and hook handlers
		 * @return void
		 */
		registerHandlers: function () {
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
			elggCKEditor.registerHandlers = elgg.nullFunction;
		},
		/**
		 * Toggles the CKEditor
		 * Callback function for toggler click event
		 *
		 * @param {Object} event
		 * @return void
		 */
		toggleEditor: function (event) {
			event.preventDefault();
			var target = $(this).attr('href');
			elggCKEditor.toggle($(target)[0]);
		},
		/**
		 * Toggles the CKEditor
		 *
		 * @param {Object} textarea DOM element
		 * @returns {void}
		 */
		toggle: function (textarea) {
			$(textarea).each(function() {
				if (!$(this).data('ckeditorInstance')) {
					$(this).ckeditor(elggCKEditor.init, $(this).data('elggCKEeditorConfig'));
					$(this).data('toggler').html(elgg.echo('ckeditor:html'));
				} else {
					$(this).ckeditorGet().destroy();
					$(this).data('toggler').html(elgg.echo('ckeditor:visual'));
				}
			});
		},
		/**
		 * Resets the CKEditor
		 * Callback function for the reset event
		 *
		 * @param {Object} event
		 * @return void
		 */
		resetEditor: function (event) {
			event.preventDefault();
			elggCKEditor.reset(this);
		},
		/**
		 * Resets the CKEditor
		 *
		 * @param {Object} textarea DOM element
		 * @returns {void}
		 */
		reset: function (textarea) {
			$(textarea).each(function() {
				if ($(textarea).data('ckeditorInstance')) {
					$(textarea).ckeditorGet().setData('');
				}
			});
		},
		/**
		 * Focuses the CKEditor
		 * Callback function for the focus event
		 *
		 * @param {Object} event
		 * @return void
		 */
		focusEditor: function (event) {
			event.preventDefault();
			elggCKEditor.focus(this);
		},
		/**
		 * Focuses the CKEditor
		 *
		 * @param {Object} textarea DOM element
		 * @returns {void}
		 */
		focus: function (textarea) {
			if ($(textarea).first().data('ckeditorInstance')) {
				$(textarea).ckeditorGet().focus();
			}
		},
		/**
		 * Initializes the ckeditor module
		 *
		 * @param {Object}  textarea DOM element passed by ckeditor on init
		 * @param {Boolean} visual
		 * @return void
		 */
		init: function (textarea, visual) {
			var $toggler = $(textarea).data('toggler');

			if (!$toggler) {
				$toggler = $('.ckeditor-toggle-editor[href="#' + textarea.id + '"]');
				$(textarea).data('toggler', $toggler);
			}

			if (visual === false) {
				$toggler.html(elgg.echo('ckeditor:visual'));
			}

			// show the toggle-editor link which is hidden by default, so it will only show up if the editor is correctly loaded
			$toggler.show();
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

	$(document).on('reset', '[data-cke-init]', elggCKEditor.resetEditor);

	$(document).on('focus', '[data-cke-init]', elggCKEditor.focusEditor);

	return elggCKEditor;
});
