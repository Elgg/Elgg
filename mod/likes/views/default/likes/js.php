<?php
/**
 * Likes JavaScript extension for elgg.js
 */
?>
//<script>
/**
 * Repositions the likes popup
 *
 * @param {String} hook    'getOptions'
 * @param {String} type    'ui.popup'
 * @param {Object} params  An array of info about the target and source.
 * @param {Object} options Options to pass to
 *
 * @return {Object}
 */
elgg.ui.likesPopupHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-likes')) {
		options.my = 'right bottom';
		options.at = 'left top';
		return options;
	}
	return null;
};

elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.likesPopupHandler);

/**
 * Ajaxify the elgg like / dislike action
 */
elgg.provide('elgg.likes');

elgg.likes.init = function() {
	$('.elgg_like').live('click', elgg.likes.click);	
};

elgg.likes.click = function(e) {
	var $link = $(this); 
	var $li = $link.parent();
	var href = $link.attr("href");
	var entity_guid = $link.attr("id");
	elgg.action( href, {
		success: function(json) {}
	});	
	$.ajax({type: "GET",
		url: '<?php echo elgg_get_site_url()."ajax/view/likes/like";?>',
		data : {guid: entity_guid},
		dataType: "html",
		cache: false,
		success: function(htmlData) {
			if (htmlData.length > 0) {
				$li.empty();
				$li.html(htmlData);
			}
		}
	});
	e.preventDefault();
};
elgg.register_hook_handler('init', 'system', elgg.likes.init);