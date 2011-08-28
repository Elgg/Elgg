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
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');

	// Page handler for the modal media embed
	elgg_register_page_handler('embed', 'embed_page_handler');
	
	elgg_register_js('elgg.embed', 'js/embed/embed.js', 'footer');
}

/**
 * Add the embed menu item to the long text menu
 *
 * @param string $hook
 * @param string $type
 * @param array $items
 * @param array $vars
 * @return array
 */
function embed_longtext_menu($hook, $type, $items, $vars) {

	if (elgg_get_context() == 'embed') {
		return $items;
	}
	
	$items[] = ElggMenuItem::factory(array(
		'name' => 'embed',
		'href' => "embed",
		'text' => elgg_echo('media:insert'),
		'rel' => 'lightbox',
		'link_class' => "elgg-longtext-control elgg-lightbox embed-control embed-control-{$vars['id']}",
		'priority' => 10,
	));

	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	elgg_load_js('elgg.embed');
	
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

			// @todo the menu system is good for registering and sorting, but not great for
			// displaying tabs.
			// Pulling in the menu manually and passing it through the embed/tabs view.
			// We should work on making it easier to use tabs through the menu system, then fix
			// this mess.
			$menus = get_config('menus');
			$menu = $menus['embed:sections'];

			$sections = array();
			$upload_sections = array();

			foreach ($menu as $item) {
				switch ($item->section) {
					case 'upload':
						$upload_sections[$item->getName()] = array(
							'name' => $item->getText(),
						);
						break;

					default:
						$sections[$item->getName()] = array(
							'name' => $item->getText(),
						);
						break;
				}
			}

			$active_section = get_input('active_section', '');
			$active_section = preg_replace('[\W]', '', $active_section);
			$internal_id = get_input('internal_id', '');
			$internal_id = preg_replace('[\W]', '', $internal_id);

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