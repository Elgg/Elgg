<?php
/**
 * Elgg file plugin
 *
 * @package ElggFile
 */

elgg_register_event_handler('init', 'system', 'file_init');

/**
 * File plugin initialization functions.
 */
function file_init() {

	// register a library of helper functions
	elgg_register_library('elgg:file', elgg_get_plugins_path() . 'file/lib/file.php');

	// Site navigation
	$item = new ElggMenuItem('file', elgg_echo('file'), 'file/all');
	elgg_register_menu_item('site', $item);

	// Extend CSS
	elgg_extend_view('css/elgg', 'file/css');

	// add enclosure to rss item
	elgg_extend_view('extensions/item', 'file/enclosure');

	// extend group main page
	elgg_extend_view('groups/tool_latest', 'file/group_module');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('file', 'file_page_handler');

	// Add a new file widget
	elgg_register_widget_type('filerepo', elgg_echo("file"), elgg_echo("file:widget:description"));

	// Register URL handlers for files
	elgg_register_entity_url_handler('object', 'file', 'file_url_override');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'file_icon_url_override');

	// Register granular notification for this object type
	register_notification_object('object', 'file', elgg_echo('file:newupload'));

	// Listen to notification events and supply a more useful message
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'file_notify_message');

	// add the group files tool option
	add_group_tool_option('file', elgg_echo('groups:enablefiles'), true);

	// Register entity type for search
	elgg_register_entity_type('object', 'file');

	// add a file link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'file_owner_block_menu');

	// Register actions
	$action_path = elgg_get_plugins_path() . 'file/actions/file';
	elgg_register_action("file/upload", "$action_path/upload.php");
	elgg_register_action("file/delete", "$action_path/delete.php");
	// temporary - see #2010
	elgg_register_action("file/download", "$action_path/download.php");

	// embed support
	$item = ElggMenuItem::factory(array(
		'name' => 'file',
		'text' => elgg_echo('file'),
		'priority' => 10,
		'data' => array(
			'options' => array(
				'type' => 'object',
				'subtype' => 'file',
			),
		),
	));
	elgg_register_menu_item('embed', $item);

	$item = ElggMenuItem::factory(array(
		'name' => 'file_upload',
		'text' => elgg_echo('file:upload'),
		'priority' => 100,
		'data' => array(
			'view' => 'embed/file_upload/content',
		),
	));

	elgg_register_menu_item('embed', $item);
}

/**
 * Dispatches file pages.
 * URLs take the form of
 *  All files:       file/all
 *  User's files:    file/owner/<username>
 *  Friends' files:  file/friends/<username>
 *  View file:       file/view/<guid>/<title>
 *  New file:        file/add/<guid>
 *  Edit file:       file/edit/<guid>
 *  Group files:     file/group/<guid>/all
 *  Download:        file/download/<guid>
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function file_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$file_dir = elgg_get_plugins_path() . 'file/pages/file';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			file_register_toggle();
			include "$file_dir/owner.php";
			break;
		case 'friends':
			file_register_toggle();
			include "$file_dir/friends.php";
			break;
		case 'view':
		case 'read': // Elgg 1.7 compatibility
			set_input('guid', $page[1]);
			include "$file_dir/view.php";
			break;
		case 'add':
			include "$file_dir/upload.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$file_dir/edit.php";
			break;
		case 'search':
			file_register_toggle();
			include "$file_dir/search.php";
			break;
		case 'group':
			file_register_toggle();
			include "$file_dir/owner.php";
			break;
		case 'all':
			file_register_toggle();
			include "$file_dir/world.php";
			break;
		case 'download':
			set_input('guid', $page[1]);
			include "$file_dir/download.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Adds a toggle to extra menu for switching between list and gallery views
 */
function file_register_toggle() {
	$url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');

	if (get_input('list_type', 'list') == 'list') {
		$list_type = "gallery";
		$icon = elgg_view_icon('grid');
	} else {
		$list_type = "list";
		$icon = elgg_view_icon('list');
	}

	if (substr_count($url, '?')) {
		$url .= "&list_type=" . $list_type;
	} else {
		$url .= "?list_type=" . $list_type;
	}


	elgg_register_menu_item('extras', array(
		'name' => 'file_list',
		'text' => $icon,
		'href' => $url,
		'title' => elgg_echo("file:list:$list_type"),
		'priority' => 1000,
	));
}

/**
 * Creates the notification message body
 *
 * @param string $hook
 * @param string $entity_type
 * @param string $returnvalue
 * @param array  $params
 */
function file_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'file')) {
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return elgg_echo('file:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Add a menu item to the user ownerblock
 */
function file_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "file/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('file', elgg_echo('file'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->file_enable != "no") {
			$url = "file/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('file', elgg_echo('file:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Returns an overall file type from the mimetype
 *
 * @param string $mimetype The MIME type
 * @return string The overall type
 */
function file_get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
	}

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

// deprecated and will be removed
function get_general_file_type($mimetype) {
	elgg_deprecated_notice('Use file_get_simple_type() instead of get_general_file_type()', 1.8);
	return file_get_simple_type($mimetype);
}

/**
 * Returns a list of filetypes
 *
 * @param int       $container_guid The GUID of the container of the files
 * @param bool      $friends        Whether we're looking at the container or the container's friends
 * @return string The typecloud
 */
function file_get_type_cloud($container_guid = "", $friends = false) {

	$container_guids = $container_guid;

	if ($friends) {
		// tags interface does not support pulling tags on friends' content so
		// we need to grab all friends
		$friend_entities = get_user_friends($container_guid, "", 999999, 0);
		if ($friend_entities) {
			$friend_guids = array();
			foreach ($friend_entities as $friend) {
				$friend_guids[] = $friend->getGUID();
			}
		}
		$container_guids = $friend_guids;
	}

	elgg_register_tag_metadata_name('simpletype');
	$options = array(
		'type' => 'object',
		'subtype' => 'file',
		'container_guids' => $container_guids,
		'threshold' => 0,
		'limit' => 10,
		'tag_names' => array('simpletype')
	);
	$types = elgg_get_tags($options);

	$params = array(
		'friends' => $friends,
		'types' => $types,
	);

	return elgg_view('file/typecloud', $params);
}

function get_filetype_cloud($owner_guid = "", $friends = false) {
	elgg_deprecated_notice('Use file_get_type_cloud instead of get_filetype_cloud', 1.8);
	return file_get_type_cloud($owner_guid, $friends);
}

/**
 * Populates the ->getUrl() method for file objects
 *
 * @param ElggEntity $entity File entity
 * @return string File URL
 */
function file_url_override($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "file/view/" . $entity->getGUID() . "/" . $title;
}

/**
 * Override the default entity icon for files
 *
 * Plugins can override or extend the icons using the plugin hook: 'file:icon:url', 'override'
 *
 * @return string Relative URL
 */
function file_icon_url_override($hook, $type, $returnvalue, $params) {
	$file = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($file, 'object', 'file')) {

		// thumbnails get first priority
		if ($file->thumbnail) {
			$ts = (int)$file->icontime;
			return "mod/file/thumbnail.php?file_guid=$file->guid&size=$size&icontime=$ts";
		}

		$mapping = array(
			'application/excel' => 'excel',
			'application/msword' => 'word',
			'application/pdf' => 'pdf',
			'application/powerpoint' => 'ppt',
			'application/vnd.ms-excel' => 'excel',
			'application/vnd.ms-powerpoint' => 'ppt',
			'application/vnd.oasis.opendocument.text' => 'openoffice',
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
		);

		$mime = $file->mimetype;
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
		
		$url = "mod/file/graphics/icons/{$type}{$ext}.gif";
		$url = elgg_trigger_plugin_hook('file:icon:url', 'override', $params, $url);
		return $url;
	}
}