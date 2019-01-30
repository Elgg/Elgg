/**
 * Embed module bootstraps the embed funtionality
 * Note that this module will initialize itself once elgg/ready module is loaded,
 * hence you do not need to call elgg/embed#init after requiring elgg/embed module.
 *
 * @module elgg/embed
 */
define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');
	var lightbox = require('elgg/lightbox');
	require('jquery.form');

	var embed = {
		/**
		 * Initializes the module
		 * @return void
		 */
		init: function () {

			// we only need to bind these events once
			embed.init = elgg.nullFunction;

			// inserts the embed content into the textarea
			$(document).on('click', ".embed-item", embed.insert);

			// caches the current textarea id
			$(document).on('click', ".embed-control", function () {
				var textAreaId = /embed-control-(\S)+/.exec($(this).attr('class'))[0];
				embed.textAreaId = textAreaId.substr("embed-control-".length);
			});
			// special pagination helper for lightbox
			$(document).on('click', '.embed-wrapper .elgg-pagination a', embed.forward);
			$(document).on('click', '.embed-section', embed.forward);
			$(document).on('submit', '.elgg-form-embed', embed.submit);
		},

		/**
		 * Inserts data attached to an embed list item in textarea
		 *
		 * @param {Object} event
		 * @return void
		 */
		insert: function (event) {
			var textAreaId = embed.textAreaId;
			var textArea = $('#' + textAreaId);
			// generalize this based on a css class attached to what should be inserted
			var content = ' ' + $(this).find(".embed-insert").parent().html() + ' ';
			var value = textArea.val();
			var result = textArea.val();
			// this is a temporary work-around for #3971
			if (content.indexOf('/serve-icon/') != -1) {
				content = content.replace(/\/serve-icon\/[0-9]*\/small/g, function replacer(match) {
					return match.replace('small', 'large');
				});
			}

			textArea.focus();
			if (!elgg.isNullOrUndefined(textArea.prop('selectionStart'))) {
				var cursorPos = textArea.prop('selectionStart');
				var textBefore = value.substring(0, cursorPos);
				var textAfter = value.substring(cursorPos, value.length);
				result = textBefore + content + textAfter;
			} else if (document.selection) {
				// IE compatibility
				var sel = document.selection.createRange();
				sel.text = content;
				result = textArea.val();
			}

			// See the ckeditor plugin for an example of this hook
			result = elgg.trigger_hook('embed', 'editor', {
				textAreaId: textAreaId,
				content: content,
				value: value,
				event: event
			}, result);
			if (result || result === '') {
				textArea.val(result);
			}

			lightbox.close();
			event.preventDefault();
		},

		/**
		 * Submit an upload form through Ajax
		 *
		 * Requires the jQuery Form Plugin. Because files cannot be uploaded with
		 * XMLHttpRequest, the plugin uses an invisible iframe. This results in the
		 * the X-Requested-With header not being set. To work around this, we are
		 * sending the header as a POST variable and Elgg's code checks for it in
		 * elgg_is_xhr().
		 *
		 * @param {Object} event
		 * @return bool
		 */
		submit: function (event) {
			$('.embed-wrapper .elgg-form-file-upload').hide();
			$('.embed-throbber').show();
			$(this).ajaxSubmit({
				dataType: 'json',
				data: {'X-Requested-With': 'XMLHttpRequest'},
				success: function (response, status, xhr) {
					if (response) {
						if (response.system_messages) {
							elgg.register_error(response.system_messages.error);
							elgg.system_message(response.system_messages.success);
						}
						if (response.status >= 0) {
							var forward = $('input[name=embed_forward]').val();
							var url = elgg.normalize_url('embed/' + forward);
							url = embed.addContainerGUID(url);
							$('.embed-wrapper').parent().load(url);
						} else {
							// incorrect response, presumably an error has been displayed
							$('.embed-throbber').hide();
							$('.embed-wrapper .elgg-form-file-upload').show();
						}
					} // ie 7 and 8 have a null response because of the use of an iFrame
					// so just show the list after upload.
					// http://jquery.malsup.com/form/#file-upload claims you can wrap JSON
					// in a textarea, but a quick test didn't work, and that is fairly
					// intrusive to the rest of the ajax system.
					else if (response === undefined && $.browser.msie) {
						var forward = $('input[name=embed_forward]').val();
						var url = elgg.normalize_url('embed/' + forward);
						url = embed.addContainerGUID(url);
						$('.embed-wrapper').parent().load(url);
					}
				},
				error: function (xhr, status) {
					elgg.register_error(elgg.echo('actiongatekeeper:uploadexceeded'));
					$('.embed-throbber').hide();
					$('.embed-wrapper .elgg-form-file-upload').show();
				}
			});
			// this was bubbling up the DOM causing a submission
			event.preventDefault();
			event.stopPropagation();
		},

		/**
		 * Loads content within the lightbox
		 *
		 * @param {Object} event
		 * @return void
		 */
		forward: function (event) {
			// make sure container guid is passed
			var url = $(this).attr('href');
			url = embed.addContainerGUID(url);
			$('.embed-wrapper').parent().load(url, function() {
				$(window).trigger('resize.lightbox');
			});
			event.preventDefault();
		},

		/**
		 * Adds the container guid to a URL
		 *
		 * @param {string} url
		 * @return string
		 */
		addContainerGUID: function (url) {
			if (url.indexOf('container_guid=') == -1) {
				var guid = $('input[name=embed_container_guid]').val();
				return url + '?container_guid=' + guid;
			} else {
				return url;
			}
		}
	};

	require(['elgg/init'], function () {
		embed.init();
	});

	return embed;
});

