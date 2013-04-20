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
			if ($('#cke_wordcount_'+this.name).length == 0) {
				$('#cke_bottom_' + this.name).prepend(
					'<div id="cke_wordcount_' + this.name + '" class="cke_wordcount">' + 
						elgg.echo('ckeditor:word_count') + '0' +
					'</div>'   
				);
			}
			this.document.on('keyup', function(event) {
				//show the number of words
				var words = this.getBody().getText().trim().split(' ').length;
				var text = elgg.echo('ckeditor:word_count') + words + ' ';
				$('#cke_wordcount_' + CKEDITOR.currentInstance.name).html(text);
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

	// Live handlers don't need to wait for domReady and only need to be registered once.
	$('.ckeditor-toggle-editor').live('click', elggCKEditor.toggleEditor);

	return elggCKEditor;
});
