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
	elgg_register_plugin_hook_handler('entity:url', 'object', 'file_set_url');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'file_set_icon_url');

	// Register for notifications
	elgg_register_notification_event('object', 'file', array('create'));
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:file', 'file_prepare_notification');

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

	elgg_extend_view('theme_sandbox/icons', 'file/theme_sandbox/icons/files');
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
	$title = $entity->title;

	$notification->subject = elgg_echo('file:notify:subject', array($entity->title), $language);
	$notification->body = elgg_echo('file:notify:body', array(
		$owner->name,
		$title,
		$descr,
		$entity->getURL()
	), $language);
	$notification->summary = elgg_echo('file:notify:summary', array($entity->title), $language);

	return $notification;
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
 * Registers page menu items for file type filtering and returns a view
 *
 * @param int       $container_guid The GUID of the container of the files
 * @param bool      $friends        Whether we're looking at the container or the container's friends
 * 
 * @return string The typecloud
 */
function file_get_type_cloud($container_guid = "", $friends = false) {

	$container_guids = $container_guid;
	$container = get_entity($container_guid);

	if ($friends && $container) {
		// tags interface does not support pulling tags on friends' content so
		// we need to grab all friends
		$friend_entities = $container->getFriends(array('limit' => 0));
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

	if ($types) {
		$all = new stdClass;
		$all->tag = 'all';
		elgg_register_menu_item('page', array(
			'name' => 'file:all',
			'text' => elgg_echo('all'),
			'href' =>  file_type_cloud_get_url($all, $friends),
		));
		
		foreach ($types as $type) {
			elgg_register_menu_item('page', array(
				'name' => "file:$type->tag",
				'text' => elgg_echo("file:type:$type->tag"),
				'href' =>  file_type_cloud_get_url($type, $friends),
			));
		}
	}
	
	// returning the view is needed for BC
	$params = array(
		'friends' => $friends,
		'types' => $types,
	);

	return elgg_view('file/typecloud', $params);
}

function file_type_cloud_get_url($type, $friends) {
	$url = elgg_get_site_url() . 'file/search?subtype=file';

	if ($type->tag != "all") {
		$url .= "&md_type=simpletype&tag=" . urlencode($type->tag);
	}

	if ($friends) {
		$url .= "&friends=$friends";
	}

	if ($type->tag == "image") {
		$url .= "&list_type=gallery";
	}

	if (elgg_get_page_owner_guid()) {
		$url .= "&page_owner=" . elgg_get_page_owner_guid();
	}

	return $url;
}

function get_filetype_cloud($owner_guid = "", $friends = false) {
	elgg_deprecated_notice('Use file_get_type_cloud instead of get_filetype_cloud', 1.8);
	return file_get_type_cloud($owner_guid, $friends);
}

/**
 * Populates the ->getUrl() method for file objects
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string File URL
 */
function file_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'file')) {
		$title = elgg_get_friendly_title($entity->title);
		return "file/view/" . $entity->getGUID() . "/" . $title;
	}
}

/**
 * Override the default entity icon for files
 *
 * Plugins can override or extend the icons using the plugin hook: 'file:icon:url', 'override'
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string Relative URL
 */
function file_set_icon_url($hook, $type, $url, $params) {
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
