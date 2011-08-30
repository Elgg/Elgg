//<script>
elgg.provide('elgg.embed');

elgg.embed.init = function() {

	// inserts the embed content into the textarea
	$(".embed_data").live('click', elgg.embed.insert);

	// caches the current textarea id
	$(".embed-control").live('click', function() {
		var classes = $(this).attr('class');
		var embedClass = classes.split(/[, ]+/).pop();
		var textAreaId = embedClass.substr(embedClass.indexOf('embed-control-') + "embed-control-".length);
		elgg.embed.textAreaId = textAreaId;
	});

	// special pagination helper for lightbox
	$('.embed-wrapper .elgg-pagination a').live('click', elgg.embed.pagination);

	$('.embed-section').live('click', elgg.embed.loadTab);

	$('.embed-upload .elgg-form').live('submit', elgg.embed.submit);
}

/**
 * Inserts data attached to an embed list item in textarea
 *
 * @todo generalize lightbox closing and wysiwyg refreshing
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.insert = function(event) {
	var textAreaId = elgg.embed.textAreaId;

	var content = $(this).data('embed_code');
	$('#' + textAreaId).val($('#' + textAreaId).val() + ' ' + content + ' ');

	<?php echo elgg_view('embed/custom_insert_js'); ?>

	$.fancybox.close();

	event.preventDefault();
}

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
					// @todo - really this should forward to what the registered defined
					// For example, forward to images tab if an image was uploaded
					var url = elgg.config.wwwroot + 'embed/embed?active_section=file';
					$('.embed-wrapper').parent().load(url);
				}
			}
		}
	});

	// this was bubbling up the DOM causing a submission
	event.preventDefault();
	event.stopPropagation();
}

/**
 * Loads the next chunk of content within the lightbox
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.pagination = function(event) {
	$('.embed-wrapper').parent().load($(this).attr('href'));
	event.preventDefault();
}

/**
 * Loads an embed tab
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.loadTab = function(event) {
	var section = $(this).attr('id');
	var url = elgg.config.wwwroot + 'embed/embed?active_section=' + section;
	$('.embed-wrapper').parent().load(url);
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.embed.init);
