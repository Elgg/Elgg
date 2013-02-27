//<script>
elgg.provide('elgg.embed');

elgg.embed.init = function() {

	// inserts the embed content into the textarea
	$(".embed-item").live('click', elgg.embed.insert);

	// caches the current textarea id
	$(".embed-control").live('click', function() {
		var classes = $(this).attr('class');
		var embedClass = classes.split(/[, ]+/).pop();
		var textAreaId = embedClass.substr(embedClass.indexOf('embed-control-') + "embed-control-".length);
		elgg.embed.textAreaId = textAreaId;
	});

	// special pagination helper for lightbox
	$('.embed-wrapper .elgg-pagination a').live('click', elgg.embed.forward);

	$('.embed-section').live('click', elgg.embed.forward);

	$('.elgg-form-embed').live('submit', elgg.embed.submit);
};

/**
 * Inserts data attached to an embed list item in textarea
 *
 * @todo generalize lightbox closing
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.insert = function(event) {
	var textAreaId = elgg.embed.textAreaId;
	var textArea = $('#' + textAreaId);

	// generalize this based on a css class attached to what should be inserted
	var content = ' ' + $(this).find(".embed-insert").parent().html() + ' ';

	// this is a temporary work-around for #3971
	if (content.indexOf('thumbnail.php') != -1) {
		content = content.replace('size=small', 'size=medium');
	}

	textArea.val(textArea.val() + content);
	textArea.focus();
	
<?php
// See the TinyMCE plugin for an example of this view
echo elgg_view('embed/custom_insert_js');
?>

	$.fancybox.close();

	event.preventDefault();
};

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
elgg.embed.submit = function(event) {
	$('.embed-wrapper .elgg-form-file-upload').hide();
	$('.embed-throbber').show();
	
	$(this).ajaxSubmit({
		dataType : 'json',
		data     : { 'X-Requested-With' : 'XMLHttpRequest'},
		success  : function(response) {
			if (response) {
				if (response.system_messages) {
					elgg.register_error(response.system_messages.error);
					elgg.system_message(response.system_messages.success);
				}
				if (response.status >= 0) {
					var forward = $('input[name=embed_forward]').val();
					var url = elgg.normalize_url('embed/tab/' + forward);
					url = elgg.embed.addContainerGUID(url);
					$('.embed-wrapper').parent().load(url);
				} else {
					// incorrect response, presumably an error has been displayed
					$('.embed-throbber').hide();
					$('.embed-wrapper .elgg-form-file-upload').show();
				}
			}

			// ie 7 and 8 have a null response because of the use of an iFrame
			// so just show the list after upload.
			// http://jquery.malsup.com/form/#file-upload claims you can wrap JSON
			// in a textarea, but a quick test didn't work, and that is fairly
			// intrusive to the rest of the ajax system.
			else if (response === undefined && $.browser.msie) {
				var forward = $('input[name=embed_forward]').val();
				var url = elgg.normalize_url('embed/tab/' + forward);
				url = elgg.embed.addContainerGUID(url);
				$('.embed-wrapper').parent().load(url);
			}
		},
		error    : function(xhr, status) {
			// @todo nothing for now
		}
	});

	// this was bubbling up the DOM causing a submission
	event.preventDefault();
	event.stopPropagation();
};

/**
 * Loads content within the lightbox
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.forward = function(event) {
	// make sure container guid is passed
	var url = $(this).attr('href');
	url = elgg.embed.addContainerGUID(url);

	$('.embed-wrapper').parent().load(url);
	event.preventDefault();
};

/**
 * Adds the container guid to a URL
 *
 * @param {string} url
 * @return string
 */
elgg.embed.addContainerGUID = function(url) {
	if (url.indexOf('container_guid=') == -1) {
		var guid = $('input[name=embed_container_guid]').val();
		return url + '?container_guid=' + guid;
	} else {
		return url;
	}
};

elgg.register_hook_handler('init', 'system', elgg.embed.init);
