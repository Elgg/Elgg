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

	// special pagination helper for lightbox
	$('.embed-wrapper .elgg-pagination a').live('click', elgg.embed.pagination);

	$('.embed-section').live('click', elgg.embed.loadTab);
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
