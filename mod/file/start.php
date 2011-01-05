<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 */

	/**
	 * Override the ElggFile so that 
	 */
	class FilePluginFile extends ElggFile
	{
		protected function initialise_attributes()
		{
			parent::initialise_attributes();
			
			$this->attributes['subtype'] = "file";
		}
		
		public function __construct($guid = null) 
		{			
			parent::__construct($guid);
		}
	}
	

	/**
	 * File plugin initialisation functions.
	 */
	function file_init() 
	{
		global $CONFIG;
				
		// Set up menu (tools dropdown or other uses as defined by theme)
		if (isloggedin()) {
			add_menu(elgg_echo('file'), $CONFIG->wwwroot . "pg/file/owner/" . get_loggedin_user()->username);
		} else {
			add_menu(elgg_echo('file'), $CONFIG->wwwroot . "pg/file/all/" );
		}
				
		// Extend CSS
		elgg_extend_view('css', 'file/css');
		
		// Extend hover-over and profile menu	
		elgg_extend_view('profile/menu/links','file/menu');
		
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
		register_plugin_hook('notify:entity:message', 'object', 'file_notify_message');
		
		// add the group files tool option     
		add_group_tool_option('file',elgg_echo('groups:enablefiles'),true);
	    // extend group main page
		elgg_extend_view('groups/left_column','file/groupprofile_files');
		
		// Register entity type
		register_entity_type('object','file');
	}
	
	/**
	 * Sets up submenus for the file system.  Triggered on pagesetup.
	 *
	 */
	function file_submenus() {
		
		global $CONFIG;
		
		$page_owner = page_owner_entity();
		
		// Group submenu option	
			if ($page_owner instanceof ElggGroup && get_context() == "groups") {
    			if($page_owner->file_enable != "no"){ 
				    add_submenu_item(sprintf(elgg_echo("file:group"),$page_owner->name), $CONFIG->wwwroot . "pg/file/owner/" . $page_owner->username);
			    }
			}
			
		// General submenu options
		
			if (get_context() == "file") {
				if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
					add_submenu_item(sprintf(elgg_echo("file:yours"),$page_owner->name), $CONFIG->wwwroot . "pg/file/owner/" . $page_owner->username);
					add_submenu_item(sprintf(elgg_echo('file:yours:friends'),$page_owner->name), $CONFIG->wwwroot . "pg/file/friends/". $page_owner->username);
				} else if (page_owner()) {
					add_submenu_item(sprintf(elgg_echo("file:user"),$page_owner->name), $CONFIG->wwwroot . "pg/file/owner/" . $page_owner->username);
					if ($page_owner instanceof ElggUser) // This one's for users, not groups
						add_submenu_item(sprintf(elgg_echo('file:friends'),$page_owner->name), $CONFIG->wwwroot . "pg/file/friends/". $page_owner->username);
				}
				add_submenu_item(elgg_echo('file:all'), $CONFIG->wwwroot . "pg/file/all/");
				if (can_write_to_container($_SESSION['guid'], page_owner()) && isloggedin())
					add_submenu_item(elgg_echo('file:upload'), $CONFIG->wwwroot . "pg/file/new/". $page_owner->username);
			}
		
	}

	/**
	 * File page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function file_page_handler($page) {
		
		global $CONFIG;
		
		// group usernames
		if (substr_count($page[0], 'group:')) {
			preg_match('/group\:([0-9]+)/i', $page[0], $matches);
			$guid = $matches[1];
			if ($entity = get_entity($guid)) {
				file_url_forwarder($page);
			}
		}

		// user usernames
		$user = get_user_by_username($page[0]);
		if ($user) {
			file_url_forwarder($page);
		}
		
    	switch ($page[0]) {
			case "read":
				set_input('guid', $page[1]);
				require(dirname(dirname(dirname(__FILE__))) . "/entities/index.php");
				break;
			case "owner":
				set_input('username', $page[1]);
				require($CONFIG->pluginspath . "file/index.php");
				break;
			case "friends":
				set_input('username', $page[1]);
				require($CONFIG->pluginspath . "file/friends.php");
				break;
			case "all":
				require($CONFIG->pluginspath . "file/world.php");
				break;
			case "new":
				set_input('username', $page[1]);
				require($CONFIG->pluginspath . "file/upload.php");
				break;
			case "edit":
				set_input('file_guid', $page[1]);
				require($CONFIG->pluginspath . "file/edit.php");
				break;
			default:
				return false;
		}

		return true;
	}


	/**
	 * Forward to the new style of URLs
	 *
	 * @param string $page
	 */
	function file_url_forwarder($page) {
		global $CONFIG;

		if (!isset($page[1])) {
			$page[1] = 'owner';
		}

		switch ($page[1]) {
			case "read":
				$url = "{$CONFIG->wwwroot}pg/file/read/{$page[2]}/{$page[3]}";
				break;
			case "owner":
				$url = "{$CONFIG->wwwroot}pg/file/owner/{$page[0]}/";
				break;
			case "friends":
				$url = "{$CONFIG->wwwroot}pg/file/friends/{$page[0]}/";
				break;
			case "new":
				$url = "{$CONFIG->wwwroot}pg/file/new/{$page[0]}/";
				break;
		}

		register_error(elgg_echo("changebookmark"));
		forward($url);
	}

	/**
		 * Returns a more meaningful message
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
    */
		function file_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'file'))
			{
				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = $CONFIG->wwwroot . "pg/view/" . $entity->guid;
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
		$types = get_tags(0,10,'simpletype','object','file',$owner_guid);

		return elgg_view('file/typecloud',array('owner_guid' => $owner_guid, 'friend_guid' => $friendofguid, 'types' => $types));
	}
	
	/**
	 * Populates the ->getUrl() method for file objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
		function file_url($entity) {
			
			global $CONFIG;
			$title = $entity->title;
			$title = elgg_get_friendly_title($title);
			return $CONFIG->url . "pg/file/read/" . $entity->getGUID() . "/" . $title;
		}
	
	// Make sure test_init is called on initialisation
	register_elgg_event_handler('init','system','file_init');
	register_elgg_event_handler('pagesetup','system','file_submenus');
	
	// Register actions
	register_action("file/upload", false, $CONFIG->pluginspath . "file/actions/upload.php");
	register_action("file/save", false, $CONFIG->pluginspath . "file/actions/save.php");
	register_action("file/delete", false, $CONFIG->pluginspath. "file/actions/delete.php");

	// temporary - see #2010
	register_action("file/download", false, $CONFIG->pluginspath. "file/actions/download.php");
	
?>
