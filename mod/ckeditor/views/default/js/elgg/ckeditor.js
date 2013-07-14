define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery'); require('jquery.ckeditor');
	var CKEDITOR = require('ckeditor');

	CKEDITOR.basePath = elgg.config.wwwroot + 'mod/ckeditor/vendors/ckeditor/';

	var elggCKEditor = {

		/**
		 * Toggles the CKEditor
		 *
		 * @param {Object} event
		 * @return void
		 */
		toggleEditor: function(event) {
			event.preventDefault();
	
			var target = $(this).attr('href');
	
			if (!$(target).data('ckeditorInstance')) {
				$(target).ckeditor(elggCKEditor.init, elggCKEditor.config);
				$(this).html(elgg.echo('ckeditor:remove'));
			} else {
				$(target).ckeditorGet().destroy();
				$(this).html(elgg.echo('ckeditor:add'));
			}
		},

		/**
		 * Configures the live-updating word counter and HTML writer
		 *
		 * @param {Object} event
		 * @return void
		 */
		init: function() {
			var editor = this;

			// #5689
			editor.dataProcessor.writer.setRules('p', {
				breakAfterOpen : false
			});

			if ($('#cke_wordcount_' + editor.name).length == 0) {
				$('#cke_bottom_' + editor.name).prepend(
					'<div id="cke_wordcount_' + editor.name + '" class="cke_wordcount">' + 
						elgg.echo('ckeditor:word_count') + '0' +
					'</div>'   
				);
			}

			editor.document.on('input', function() {
				elggCKEditor.updateCount(editor);
			});
			elggCKEditor.updateCount(editor);
		},

		/**
		 * Show the number of words
		 * 
		 * @param {Object} editor
		 * @return void
		 */
		updateCount: function(editor) {
			var words = editor.document.getBody().getText().trim();
			var count = words !== "" ? words.split(/\s+/).length : 0;
			var text = elgg.echo('ckeditor:word_count') + count + ' ';
			$('#cke_wordcount_' + editor.name).html(text);
		},

		/**
		 * CKEditor has decided using width and height as attributes on images isn't
		 * kosher and puts that in the style. This adds those back as attributes.
		 * This is from this patch: http://dev.ckeditor.com/attachment/ticket/5024/5024_5.patch
		 * 
		 * @param {Object} event
		 * @return void
		 */
		fixImageAttributes: function(event) {
			event.editor.dataProcessor.htmlFilter.addRules({
				elements: {
					img: function(element) {
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
		 * http://docs.cksource.com/Talk:CKEditor_3.x/Developers_Guide
		 */
		config: require('elgg/ckeditor/config')

	};

	CKEDITOR.on('instanceReady', elggCKEditor.fixImageAttributes);

	// Live handlers don't need to wait for domReady and only need to be registered once.
	$('.ckeditor-toggle-editor').live('click', elggCKEditor.toggleEditor);

	return elggCKEditor;
});
