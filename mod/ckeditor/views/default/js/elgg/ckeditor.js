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
				$(target).ckeditor(elggCKEditor.wordCount, elggCKEditor.config);
				$(this).html(elgg.echo('ckeditor:remove'));
			} else {
				$(target).ckeditorGet().destroy();
				$(this).html(elgg.echo('ckeditor:add'));
			}
		},

		/**
		 * Provides a live-updating word counter.
		 *
		 * @param {Object} event
		 * @return void
		 */
		wordCount: function() {
			var editor = this;

			if ($('#cke_wordcount_' + editor.name).length == 0) {
				$('#cke_bottom_' + editor.name).prepend(
					'<div id="cke_wordcount_' + editor.name + '" class="cke_wordcount">' + 
						elgg.echo('ckeditor:word_count') + '0' +
					'</div>'   
				);
			}

			editor.document.on('keyup', function() {elggCKEditor.updateCount(editor)});
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
			var count = words !== "" ? words.split(' ').length : 0;
			var text = elgg.echo('ckeditor:word_count') + count + ' ';
			$('#cke_wordcount_' + editor.name).html(text);
		},


		/**
		 * CKEditor configuration
		 *
		 * You can find configuration information here:
		 * http://docs.cksource.com/Talk:CKEditor_3.x/Developers_Guide
		 */
		config: require('elgg/ckeditor/config')

	};

	// Live handlers don't need to wait for domReady and only need to be registered once.
	$('.ckeditor-toggle-editor').live('click', elggCKEditor.toggleEditor);

	return elggCKEditor;
});
