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
	elgg_extend_view('css/admin', 'embed/css');

	if (elgg_is_logged_in()) {
		elgg_register_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');
	}
	elgg_register_plugin_hook_handler('register', 'menu:embed', 'embed_select_tab', 1000);

	// Page handler for the modal media embed
	elgg_register_page_handler('embed', 'embed_page_handler');
	
	$embed_js = elgg_get_simplecache_url('js', 'embed/embed');
	elgg_register_simplecache_view('js/embed/embed');
	elgg_register_js('elgg.embed', $embed_js, 'footer');
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

	$url = 'embed';

	$page_owner = elgg_get_page_owner_entity();
	if (elgg_instanceof($page_owner, 'group') && $page_owner->isMember()) {
		$url = 'embed?container_guid=' . $page_owner->getGUID();
	}

	$items[] = ElggMenuItem::factory(array(
		'name' => 'embed',
		'href' => $url,
		'text' => elgg_echo('embed:media'),
		'rel' => "embed-lightbox-{$vars['id']}",
		'link_class' => "elgg-longtext-control elgg-lightbox embed-control embed-control-{$vars['id']}",
		'priority' => 10,
	));

	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	elgg_load_js('jquery.form');
	elgg_load_js('elgg.embed');
	
	return $items;
}

/**
 * Select the correct embed tab for display
 *
 * @param string $hook
 * @param string $type
 * @param array $items
 * @param array $vars
 */
function embed_select_tab($hook, $type, $items, $vars) {

	// can this ba called from page handler instead?
	$page = get_input('page');
	$tab_name = array_pop(explode('/', $page));
	foreach ($items as $item) {
		if ($item->getName() == $tab_name) {
			$item->setSelected();
			elgg_set_config('embed_tab', $item);
		}
	}

	if (!elgg_get_config('embed_tab') && count($items) > 0) {
		$items[0]->setSelected();
		elgg_set_config('embed_tab', $items[0]);
	}
}

/**
 * Serves the content for the embed lightbox
 *
 * @param array $page URL segments
 */
function embed_page_handler($page) {

	$container_guid = (int)get_input('container_guid');
	if ($container_guid) {
		$container = get_entity($container_guid);

		if (elgg_instanceof($container, 'group') && $container->isMember()) {
			// embedding inside a group so save file to group files
			elgg_set_page_owner_guid($container_guid);
		}
	}

	echo elgg_view('embed/layout');

	// exit because this is in a modal display.
	exit;
}

/**
 * A special listing function for selectable content
 *
 * This calls a custom list view for entities. 
 *
 * @param array $entities Array of ElggEntity objects
 * @param array $vars     Display parameters
 * @return string
 */
function embed_list_items($entities, $vars = array()) {

	$defaults = array(
		'items' => $entities,
		'list_class' => 'elgg-list-entity',
	);

	$vars = array_merge($defaults, $vars);

	return elgg_view('embed/list', $vars);
}

/**
 * Set the options for the list of embedable content
 *
 * @param array $options
 * @return array
 */
function embed_get_list_options($options = array()) {

	$container_guids = array(elgg_get_logged_in_user_guid());
	if (elgg_get_page_owner_guid()) {
		$page_owner_guid = elgg_get_page_owner_guid();
		if ($page_owner_guid != elgg_get_logged_in_user_guid()) {
			$container_guids[] = $page_owner_guid;
		}
	}

	$defaults = array(
		'limit' => 6,
		'container_guids' => $container_guids,
		'item_class' => 'embed-item',
	);

	$options = array_merge($defaults, $options);

	return $options;
}
