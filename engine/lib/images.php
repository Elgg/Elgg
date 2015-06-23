<?php

return function(\Elgg\EventsService $events) {
	$events->registerHandler('init', 'system', 'images_init');
};

/**
 * Initialize image related features
 */
function images_init() {
	elgg_register_page_handler('image', '_images_page_handler');

	elgg_register_action('image/upload');
	elgg_register_action('image/crop');
	elgg_register_action('image/remove');
}

/**
 * Images page handler
 *
 * /image/edit/<guid>
 * /image/view/<guid>/<size>/<icontime>
 *
 * @param array $page
 * @return bool
 * @access private
 */
function _images_page_handler($page) {
	set_input('guid', $page[1]);

	switch ($page[0]) {
		case 'edit':
			echo elgg_view_resource("image/edit");
			return true;
			break;
		case 'view':
		default:
			set_input('size', $page[2]);
			echo elgg_view_resource("image/view");
			return true;
	}
}
