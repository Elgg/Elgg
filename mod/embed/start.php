<?php
/**
 * Elgg media embed plugin
 *
 * @package ElggEmbed
 */

/**
 * Init function
 *
 */
function embed_init() {
	elgg_extend_view('css', 'embed/css');
	elgg_extend_view('js/initialise_elgg', 'embed/js');
	elgg_extend_view('metatags', 'embed/metatags');
	elgg_extend_view('input/longtext', 'embed/link', 1);

	// Page handler for the modal media embed
	register_page_handler('embed', 'embed_page_handler');
}

/**
 * Serves pages for upload and embed.
 *
 * @param $page
 */
function embed_page_handler($page) {
	if (!isset($page[0])) {
		$page[0] = 'embed';
	}

	switch($page[0]) {
		case 'upload':
			echo elgg_view('embed/upload');

			break;

		case 'embed':
		default:
			// trigger hook to get section tabs
			// use views for embed/section/
			//	listing
			//	item
			// default to embed/listing | item if not found.
			// @todo trigger for all right now. If we categorize these later we can trigger
			// for certain categories.
			$sections = trigger_plugin_hook('embed_get_sections', 'all', NULL, array());
			$upload_sections = trigger_plugin_hook('embed_get_upload_sections', 'all', NULL, array()); 
			
			elgg_sort_3d_array_by_value($sections, 'name');
			elgg_sort_3d_array_by_value($upload_sections, 'name');
			$active_section = get_input('active_section', NULL);
			$internal_name = get_input('internal_name', NULL);

			echo elgg_view('embed/embed', array(
				'sections' => $sections,
				'active_section' => $active_section,
				'upload_sections' => $upload_sections,
				'internal_name' => $internal_name
			));

			break;
	}

	// exit because this is in a modal display.
	exit;
}

register_elgg_event_handler('init', 'system', 'embed_init');