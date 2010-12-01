<?php
	/**
	 * Elgg file browser
	 *
	 * @package ElggFile
	 */

	/**
	 * Override the ElggFile so that
	 */
	class FilePluginFile extends ElggFile {
		protected function initialise_attributes() {
			parent::initialise_attributes();

			$this->attributes['subtype'] = "file";
		}

		public function __construct($guid = null) {
			parent::__construct($guid);
		}
	}


	/**
	 * File plugin initialisation functions.
	 */
	function file_init() {
		global $CONFIG;

		// Set up menu (tools dropdown)
		add_menu(elgg_echo('files'), "pg/file/");

		// Extend CSS
		elgg_extend_view('css/screen', 'file/css');

		// extend group main page
		elgg_extend_view('groups/tool_latest','file/groupprofile_files');

		// Register a page handler, so we can have nice URLs
		register_page_handler('file','file_page_handler');

		// Add a new file widget
		add_widget_type('filerepo',elgg_echo("file"),elgg_echo("file:widget:description"));

		// Register a URL handler for files
		register_entity_url_handler('file_url','object','file');

		// Register granular notification for this object type
		if (is_callable('register_notification_object')) {
			register_notification_object('object', 'file', elgg_echo('file:newupload'));
		}

		// Listen to notification events and supply a more useful message
		elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'file_notify_message');

		// add the group files tool option
		add_group_tool_option('file',elgg_echo('groups:enablefiles'),true);

		// Register entity type
		register_entity_type('object','file');

		// embed support
		elgg_register_plugin_hook_handler('embed_get_sections', 'all', 'file_embed_get_sections');
		elgg_register_plugin_hook_handler('embed_get_items', 'file', 'file_embed_get_items');
		elgg_register_plugin_hook_handler('embed_get_upload_sections', 'all', 'file_embed_get_upload_sections');

	}

	/**
	 * Sets up submenus for the file system.  Triggered on pagesetup.
	 *
	 */
	function file_submenus() {

		global $CONFIG;

		$page_owner = elgg_get_page_owner();

		// Group submenu option
			if ($page_owner instanceof ElggGroup && elgg_get_context() == "groups") {
				if($page_owner->file_enable != "no"){
					add_submenu_item(elgg_echo("file:group",array($page_owner->name)), $CONFIG->wwwroot . "pg/file/" . $page_owner->username);
				}
			}
	}

	/**
	 * File page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function file_page_handler($page) {

		global $CONFIG;

		// The username should be the file we're getting
		if (isset($page[0])) {
			set_input('username',$page[0]);
		}

		if (isset($page[1])) {
			switch($page[1]) {
				case "read":
					set_input('guid',$page[2]);
					include(dirname(dirname(dirname(__FILE__))) . "/pages/entities/index.php");
				break;
				case "friends":
					include($CONFIG->pluginspath . "file/friends.php");
				  break;
				case "world":
					include($CONFIG->pluginspath . "file/world.php");
				  break;
				case "new":
					include($CONFIG->pluginspath . "file/upload.php");
				  break;
			}
		} else {
			// Include the standard profile index
			include($CONFIG->pluginspath . "file/index.php");
		}

	}

	/**
		 * Returns a more meaningful message
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
	*/
		function file_notify_message($hook, $entity_type, $returnvalue, $params) {
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'file'))
			{
				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = elgg_get_site_url() . "pg/view/" . $entity->guid;
				if ($method == 'sms') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' ' . elgg_echo("file:via") . ': ' . $url . ' (' . $title . ')';
				}
				if ($method == 'email') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' ' . elgg_echo("file:via") . ': ' . $entity->title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
				if ($method == 'web') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' ' . elgg_echo("file:via") . ': ' . $entity->title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
			}
			return null;
		}

	/**
	 * Returns an overall file type from the mimetype
	 *
	 * @param string $mimetype The MIME type
	 * @return string The overall type
	 */
	function get_general_file_type($mimetype) {

		switch($mimetype) {
			case "application/msword":
				return "document";
				break;
			case "application/pdf":
				return "document";
				break;
		}

		if (substr_count($mimetype,'text/'))
			return "document";

		if (substr_count($mimetype,'audio/'))
			return "audio";

		if (substr_count($mimetype,'image/'))
			return "image";

		if (substr_count($mimetype,'video/'))
			return "video";

		if (substr_count($mimetype,'opendocument'))
			return "document";

		return "general";
	}

	/**
	 * Returns a list of filetypes to search specifically on
	 *
	 * @param int|array $owner_guid The GUID(s) of the owner(s) of the files
	 * @param true|false $friends Whether we're looking at the owner or the owner's friends
	 * @return string The typecloud
	 */
	function get_filetype_cloud($owner_guid = "", $friends = false) {

		if ($friends) {
			if ($friendslist = get_user_friends($user_guid, "", 999999, 0)) {
				$friendguids = array();
				foreach($friendslist as $friend) {
					$friendguids[] = $friend->getGUID();
				}
			}
			$friendofguid = $owner_guid;
			$owner_guid = $friendguids;
		} else {
			$friendofguid = false;
		}

		elgg_register_tag_metadata_name('simpletype');
		$options = array(
			'type' => 'object',
			'subtype' => 'file',
			'owner_guid' => $owner_guid,
			'threshold' => 0,
			'limit' => 10,
			'tag_names' => array('simpletype')
		);
		$types = elgg_get_tags($options);

		return elgg_view('file/typecloud',array('owner_guid' => $owner_guid, 'friend_guid' => $friendofguid, 'types' => $types));
	}

	/**
	 * Register file as an embed type.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $value
	 * @param unknown_type $params
	 */
	function file_embed_get_sections($hook, $type, $value, $params) {
		$value['file'] = array(
			'name' => elgg_echo('file'),
			'layout' => 'list',
			'icon_size' => 'small',
		);

		return $value;
	}

	/**
	 * Return a list of files for embedding
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $value
	 * @param unknown_type $params
	 */
	function file_embed_get_items($hook, $type, $value, $params) {
		$options = array(
			'owner_guid' => get_loggedin_userid(),
			'type_subtype_pair' => array('object' => 'file'),
			'count' => TRUE
		);

		if ($count = elgg_get_entities($options)) {
			$value['count'] += $count;

			unset($options['count']);
			$options['offset'] = $params['offset'];
			$options['limit'] = $params['limit'];

			$items = elgg_get_entities($options);

			$value['items'] = array_merge($items, $value['items']);
		}

		return $value;
	}

	/**
	 * Register file as an embed type.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $type
	 * @param unknown_type $value
	 * @param unknown_type $params
	 */
	function file_embed_get_upload_sections($hook, $type, $value, $params) {
		$value['file'] = array(
			'name' => elgg_echo('file'),
			'view' => 'file/embed_upload'
		);

		return $value;
	}


	/**
	 * Populates the ->getUrl() method for file objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
		function file_url($entity) {
			$title = $entity->title;
			$title = elgg_get_friendly_title($title);
			return "pg/file/" . $entity->getOwnerEntity()->username . "/read/" . $entity->getGUID() . "/" . $title;
		}

	// Make sure test_init is called on initialisation
	elgg_register_event_handler('init','system','file_init');
	elgg_register_event_handler('pagesetup','system','file_submenus');

	// Register actions
	elgg_register_action("file/upload", $CONFIG->pluginspath . "file/actions/upload.php");
	elgg_register_action("file/save", $CONFIG->pluginspath . "file/actions/save.php");
	elgg_register_action("file/delete", $CONFIG->pluginspath. "file/actions/delete.php");

	// temporary - see #2010
	elgg_register_action("file/download", $CONFIG->pluginspath. "file/actions/download.php");

?>
