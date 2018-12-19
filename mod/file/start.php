<?php
/**
 * Elgg file plugin
 *
 * @package ElggFile
 */

/**
 * File plugin initialization functions
 *
 * @return void
 */
function file_init() {

	// Site navigation
	elgg_register_menu_item('site', [
		'name' => 'file',
		'icon' => 'files-o',
		'text' => elgg_echo('collection:object:file'),
		'href' => elgg_generate_url('default:object:file'),
	]);

	// Extend CSS
	elgg_extend_view('elgg.css', 'file/file.css');

	// add enclosure to rss item
	elgg_extend_view('extensions/item', 'file/enclosure');

	// Register URL handlers for files
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'file_set_icon_url');

	// Register for notifications
	elgg_register_notification_event('object', 'file', ['create']);
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:file', 'file_prepare_notification');

	// add the group files tool option
	elgg()->group_tools->register('file');

	// add a file link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'file_owner_block_menu');

	// cleanup thumbnails on delete. high priority because we want to try to make sure the
	// deletion will actually occur before we go through with this.
	elgg_register_event_handler('delete', 'object', 'file_handle_object_delete', 999);

	// embed support
	elgg_register_menu_item('embed', [
		'name' => 'file',
		'text' => elgg_echo('collection:object:file'),
		'priority' => 10,
		'data' => [
			'options' => [
				'type' => 'object',
				'subtype' => 'file',
			],
		],
	]);

	elgg_register_menu_item('embed', [
		'name' => 'file_upload',
		'text' => elgg_echo('add:object:file'),
		'priority' => 100,
		'data' => [
			'view' => 'embed/file_upload/content',
		],
	]);

	elgg_extend_view('theme_sandbox/icons', 'file/theme_sandbox/icons/files');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'object:file', 'Elgg\Values::getTrue');

	elgg_register_plugin_hook_handler('entity:icon:sizes', 'object', 'file_set_custom_icon_sizes');
	elgg_register_plugin_hook_handler('entity:icon:file', 'object', 'file_set_icon_file');

	elgg_register_plugin_hook_handler('seeds', 'database', 'file_register_db_seeds');
}

/**
 * Adds a toggle to filter menu for switching between list and gallery views
 *
 * @return void
 */
function file_register_toggle() {

	if (get_input('list_type', 'list') == 'list') {
		$list_type = 'gallery';
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = 'list';
		$icon = elgg_view_icon('list');
	}

	$url = elgg_http_add_url_query_elements(current_page_url(), ['list_type' => $list_type]);
	
	elgg_register_menu_item('filter:file', [
		'name' => 'file_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("file:list:$list_type"),
		'priority' => 1000,
	]);
}

/**
 * Prepare a notification message about a new file
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg\Notifications\Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg\Notifications\Notification
 */
function file_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = $entity->description;
	$title = $entity->getDisplayName();

	$notification->subject = elgg_echo('file:notify:subject', [$title], $language);
	$notification->body = elgg_echo('file:notify:body', [
		$owner->getDisplayName(),
		$title,
		$descr,
		$entity->getURL()
	], $language);
	$notification->summary = elgg_echo('file:notify:summary', [$title], $language);
	$notification->url = $entity->getURL();
	return $notification;
}

/**
 * Add a menu item to the user ownerblock
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return ElggMenuItem[]
 */
function file_owner_block_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if ($entity instanceof ElggUser) {
		$url = elgg_generate_url('collection:object:file:owner', ['username' => $entity->username]);
		$item = new ElggMenuItem('file', elgg_echo('collection:object:file'), $url);
		$return[] = $item;
	} elseif ($entity instanceof ElggGroup) {
		if ($entity->isToolEnabled('file')) {
			$url = elgg_generate_url('collection:object:file:group', ['guid' => $entity->guid]);
			$item = new ElggMenuItem('file', elgg_echo('collection:object:file:group'), $url);
			$return[] = $item;
		}
	}
	
	return $return;
}

/**
 * Override the default entity icon for files
 *
 * Plugins can override or extend the icons using the plugin hook: 'file:icon:url', 'override'
 *
 * @param string $hook   'entity:icon:url'
 * @param string $type   'object'
 * @param string $url    current return value
 * @param array  $params supplied params
 *
 * @return void|string
 */
function file_set_icon_url($hook, $type, $url, $params) {
	
	$file = elgg_extract('entity', $params);
	if (!$file instanceof ElggFile) {
		return;
	}
	
	$size = elgg_extract('size', $params, 'large');
	
	// thumbnails get first priority
	if ($file->hasIcon($size)) {
		return $file->getIcon($size)->getInlineURL(true);
	}

	$mapping = [
		'application/excel' => 'excel',
		'application/msword' => 'word',
		'application/ogg' => 'music',
		'application/pdf' => 'pdf',
		'application/powerpoint' => 'ppt',
		'application/vnd.ms-excel' => 'excel',
		'application/vnd.ms-powerpoint' => 'ppt',
		'application/vnd.oasis.opendocument.text' => 'openoffice',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
		'application/x-gzip' => 'archive',
		'application/x-rar-compressed' => 'archive',
		'application/x-stuffit' => 'archive',
		'application/zip' => 'archive',
		'text/directory' => 'vcard',
		'text/v-card' => 'vcard',
		'application' => 'application',
		'audio' => 'music',
		'text' => 'text',
		'video' => 'video',
	];

	$mime = $file->getMimeType();
	if ($mime) {
		$base_type = substr($mime, 0, strpos($mime, '/'));
	} else {
		$mime = 'none';
		$base_type = 'none';
	}

	if (isset($mapping[$mime])) {
		$type = $mapping[$mime];
	} elseif (isset($mapping[$base_type])) {
		$type = $mapping[$base_type];
	} else {
		$type = 'general';
	}

	if ($size == 'large') {
		$ext = '_lrg';
	} else {
		$ext = '';
	}

	$url = elgg_get_simplecache_url("file/icons/{$type}{$ext}.gif");
	$url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);
	
	return $url;
}

/**
 * Handle an object being deleted
 *
 * @param string     $event Event name
 * @param string     $type  Event type
 * @param ElggObject $file  The object deleted
 * @return void
 */
function file_handle_object_delete($event, $type, ElggObject $file) {
	if (!$file instanceof ElggFile) {
		return;
	}
	if (!$file->guid) {
		// this is an ElggFile used as temporary API
		return;
	}

	$file->deleteIcon();
}

/**
 * Set custom icon sizes for file objects
 *
 * @param string $hook   "entity:icon:url"
 * @param string $type   "object"
 * @param array  $return Sizes
 * @param array  $params Hook params
 * @return array
 */
function file_set_custom_icon_sizes($hook, $type, $return, $params) {

	$entity_subtype = elgg_extract('entity_subtype', $params);
	if ($entity_subtype !== 'file') {
		return;
	}

	$return['small'] = [
		'w' => 60,
		'h' => 60,
		'square' => true,
		'upscale' => true,
	];
	$return['medium'] = [
		'w' => 153,
		'h' => 153,
		'square' => true,
		'upscale' => true,
	];
	$return['large'] = [
		'w' => 600,
		'h' => 600,
		'upscale' => false,
	];
	
	return $return;
}

/**
 * Set custom file thumbnail location
 *
 * @param string    $hook   "entity:icon:file"
 * @param string    $type   "object"
 * @param \ElggIcon $icon   Icon file
 * @param array     $params Hook params
 * @return \ElggIcon
 */
function file_set_icon_file($hook, $type, $icon, $params) {

	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params, 'large');

	if (!($entity instanceof \ElggFile)) {
		return;
	}
	
	switch ($size) {
		case 'small' :
			$filename_prefix = 'thumb';
			$metadata_name = 'thumbnail';
			break;

		case 'medium' :
			$filename_prefix = 'smallthumb';
			$metadata_name = 'smallthumb';
			break;

		default :
			$filename_prefix = "{$size}thumb";
			$metadata_name = $filename_prefix;
			break;
	}

	$icon->owner_guid = $entity->owner_guid;
	if (isset($entity->$metadata_name)) {
		$icon->setFilename($entity->$metadata_name);
	} else {
		$filename = pathinfo($entity->getFilenameOnFilestore(), PATHINFO_FILENAME);
		$filename = "file/{$filename_prefix}{$filename}.jpg";
		$icon->setFilename($filename);
	}
	
	return $icon;
}

/**
 * Register database seed
 *
 * @elgg_plugin_hook seeds database
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function file_register_db_seeds(\Elgg\Hook $hook) {

	$seeds = $hook->getValue();

	$seeds[] = \Elgg\File\Seeder::class;

	return $seeds;
}

/**
 * Prepare the upload/edit form variables
 *
 * @param ElggFile $file the file to edit
 *
 * @return array
 */
function file_prepare_form_vars($file = null) {

	// input names => defaults
	$values = [
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $file,
	];

	if ($file) {
		foreach (array_keys($values) as $field) {
			if (isset($file->$field)) {
				$values[$field] = $file->$field;
			}
		}
	}

	if (elgg_is_sticky_form('file')) {
		$sticky_values = elgg_get_sticky_values('file');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('file');

	return $values;
}


return function() {
	elgg_register_event_handler('init', 'system', 'file_init');
};
