<?php
/**
 * Elgg media embed plugin
 *
 * @package ElggEmbed
 */


elgg_register_event_handler('init', 'system', 'embed_init');

/**
 * Init function
 */
function embed_init() {
	elgg_extend_view('css/elgg', 'embed/css');
	elgg_extend_view('js/elgg', 'embed/js');
	elgg_extend_view('js/elgg', 'embed/lightbox_init');
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');

	// Page handler for the modal media embed
	elgg_register_page_handler('embed', 'embed_page_handler');
	
	elgg_register_js('elgg.embed', 'mod/embed/js/embed.js', 'footer');
}

function embed_longtext_menu($hook, $type, $items, $vars) {
	// yeah this is naughty.  embed and ecml might want to merge.
	if (elgg_is_active_plugin('ecml')) {
		$active_section = 'active_section=web_services&';
	} else {
		$active_section = '';
	}
	
	$items[] = ElggMenuItem::factory(array(
		'name' => 'embed',
		'href' => "embed?{$active_section}internal_id={$vars['id']}",
		'text' => elgg_echo('media:insert'),
		'rel' => 'facebox',
		'link_class' => 'elgg-longtext-control',
		'priority' => 1,
	));
	
	return $items;
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

	switch ($page[0]) {
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
			$sections = elgg_trigger_plugin_hook('embed_get_sections', 'all', NULL, array());
			$upload_sections = elgg_trigger_plugin_hook('embed_get_upload_sections', 'all', NULL, array()); 
			
			elgg_sort_3d_array_by_value($sections, 'name');
			elgg_sort_3d_array_by_value($upload_sections, 'name');
			$active_section = get_input('active_section', NULL);
			$internal_id = get_input('internal_id', NULL);

			echo elgg_view('embed/embed', array(
				'sections' => $sections,
				'active_section' => $active_section,
				'upload_sections' => $upload_sections,
				'internal_id' => $internal_id
			));
			break;
	}

	// exit because this is in a modal display.
	exit;
}
