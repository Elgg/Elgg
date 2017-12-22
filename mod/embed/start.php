<?php
/**
 * Elgg media embed plugin
 *
 * @package ElggEmbed
 */

/**
 * Init function
 *
 * @return void
 */
function embed_init() {
	elgg_extend_view('elgg.css', 'embed/css');
	elgg_extend_view('admin.css', 'embed/css');

	if (elgg_is_logged_in()) {
		elgg_register_plugin_hook_handler('register', 'menu:longtext', 'embed_longtext_menu');
	}
	elgg_register_plugin_hook_handler('register', 'menu:embed', 'embed_select_tab', 1000);

	// Page handler for the modal media embed
	elgg_register_page_handler('embed', 'embed_page_handler');

	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'embed_set_thumbnail_url', 1000);
}

/**
 * Add the embed menu item to the long text menu
 *
 * @param string         $hook  'register'
 * @param string         $type  'menu:longtext'
 * @param ElggMenuItem[] $items current return value
 * @param array          $vars  supplied params
 *
 * @return void|ElggMenuItem[]
 */
function embed_longtext_menu($hook, $type, $items, $vars) {

	if (elgg_get_context() == 'embed') {
		return;
	}
	
	$id = elgg_extract('textarea_id', $vars);
	if ($id === null) {
		return;
	}

	$url = 'embed';

	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner instanceof ElggGroup && $page_owner->isMember()) {
		$url = elgg_http_add_url_query_elements($url, [
			'container_guid' => $page_owner->guid,
		]);
	}

	$items[] = ElggMenuItem::factory([
		'name' => 'embed',
		'href' => 'javascript:',
		'data-colorbox-opts' => json_encode([
			'href' => elgg_normalize_url($url),
		]),
		'text' => elgg_echo('embed:media'),
		'rel' => "embed-lightbox-{$id}",
		'link_class' => "elgg-longtext-control elgg-lightbox embed-control embed-control-{$id} elgg-lightbox",
		'deps' => ['elgg/embed'],
		'priority' => 10,
	]);

	return $items;
}

/**
 * Select the correct embed tab for display
 *
 * @param string         $hook  'register'
 * @param string         $type  'menu:embed'
 * @param ElggMenuItem[] $items current return value
 * @param array          $vars  supplied params
 *
 * @return ElggMenuItem[]
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
	
	return $items;
}

/**
 * Serves the content for the embed lightbox
 *
 * @param array $page URL segments
 *
 * @return true
 */
function embed_page_handler($page) {

	elgg_ajax_gatekeeper();

	$container_guid = (int) get_input('container_guid');
	if ($container_guid) {
		$container = get_entity($container_guid);

		if ($container instanceof ElggGroup && $container->isMember()) {
			// embedding inside a group so save file to group files
			elgg_set_page_owner_guid($container_guid);
		}
	}

	set_input('page', $page[1]);

	echo elgg_view('embed/layout');
	return true;
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
function embed_list_items($entities, $vars = []) {

	$defaults = [
		'items' => $entities,
		'list_class' => 'elgg-list-entity',
	];

	$vars = array_merge($defaults, $vars);

	return elgg_view('embed/list', $vars);
}

/**
 * Set the options for the list of embedable content
 *
 * @param array $options additional options
 *
 * @return array
 */
function embed_get_list_options($options = []) {

	$container_guids = [elgg_get_logged_in_user_guid()];
	if (elgg_get_page_owner_guid()) {
		$page_owner_guid = elgg_get_page_owner_guid();
		if ($page_owner_guid != elgg_get_logged_in_user_guid()) {
			$container_guids[] = $page_owner_guid;
		}
	}

	$defaults = [
		'limit' => 6,
		'container_guids' => $container_guids,
		'item_class' => 'embed-item',
		'no_results' => elgg_echo('notfound'),
	];

	$options = array_merge($defaults, $options);

	return $options;
}

/**
 * Substitutes thumbnail's inline URL with a permanent URL
 * Registered with a very late priority of 1000 to ensure we replace all previous values
 *
 * @param string $hook   "entity:icon:url"
 * @param string $type   "object"
 * @param string $return URL
 * @param array  $params Hook params
 * @return string
 */
function embed_set_thumbnail_url($hook, $type, $return, $params) {

	if (!elgg_in_context('embed')) {
		return;
	}
	
	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params);

	$thumbnail = $entity->getIcon($size);
	if (!$thumbnail->exists()) {
		return;
	}

	return elgg_get_embed_url($entity, $size);
}

return function() {
	elgg_register_event_handler('init', 'system', 'embed_init');
};
