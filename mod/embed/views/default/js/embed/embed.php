elgg.provide('elgg.embed');

elgg.embed.init = function() {

	// inserts the embed content into the textarea
	$(".embed-wrapper .elgg-list-item").live('click', elgg.embed.insert);

	// caches the current textarea id
	$(".embed-control").live('click', function() {
		var classes = $(this).attr('class');
		var class = classes.split(/[, ]+/).pop();
		var textAreaId = class.substr(class.indexOf('embed-control-') + "embed-control-".length);
		elgg.embed.textAreaId = textAreaId;
	});
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

elgg.register_hook_handler('init', 'system', elgg.embed.init);
