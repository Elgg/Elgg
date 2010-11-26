<?php

	/**
	 * Elgg wire plugin
	 * The wire is simple twitter like plugin that allows users to post notes to the wire
	 *
	 * @package ElggTheWire
	 */

	/**
	 * thewire initialisation
	 *
	 * These parameters are required for the event API, but we won't use them:
	 *
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */

		function thewire_init() {

			// Load system configuration
				global $CONFIG;

			// Set up menu for logged in users
				if (isloggedin()) {

					add_menu(elgg_echo('thewire'), $CONFIG->wwwroot . "pg/thewire/all/");

				}

			// Extend system CSS with our own styles, which are defined in the thewire/css view
				elgg_extend_view('css','thewire/css');

			//extend views
				elgg_extend_view('profile/status', 'thewire/profile_status');

			// Register a page handler, so we can have nice URLs
				register_page_handler('thewire','thewire_page_handler');

			// Register a URL handler for thewire posts
				register_entity_url_handler('thewire_url','object','thewire');

			// Your thewire widget
				add_widget_type('thewire',elgg_echo("thewire"),elgg_echo("thewire:yourdesc"));

			// Register entity type
				register_entity_type('object','thewire');

			// Listen for SMS create event
			register_elgg_event_handler('create','object','thewire_incoming_sms');

			// Register granular notification for this type
			if (is_callable('register_notification_object'))
				register_notification_object('object', 'thewire', elgg_echo('thewire:newpost'));

			// Listen to notification events and supply a more useful message for SMS'
			register_plugin_hook('notify:entity:message', 'object', 'thewire_notify_message');

			// allow tweets
			register_plugin_hook('plugin_list', 'twitter_service', 'thewire_get_plugins_twitter_service');
		}

		function thewire_pagesetup() {

			global $CONFIG;

			//add submenu options
				if (get_context() == "thewire") {
					if (isloggedin ()) {
						add_submenu_item(elgg_echo('thewire:yours'),$CONFIG->wwwroot."pg/thewire/owner/" . $_SESSION['user']->username);
						add_submenu_item(elgg_echo('thewire:friends'),$CONFIG->wwwroot."pg/thewire/friends/" . $_SESSION['user']->username);
					}
					add_submenu_item(elgg_echo('thewire:everyone'),$CONFIG->wwwroot."pg/thewire/all/");
				}

		}

		/**
		 * thewire page handler; allows the use of fancy URLs
		 *
		 * @param array $page From the page_handler function
		 * @return true|false Depending on success
		 */
		function thewire_page_handler($page) {

			// user usernames
			$user = get_user_by_username($page[0]);
			if ($user) {
				thewire_url_forwarder($page);
			}

			switch ($page[0]) {
				case "owner":
					set_input('username', $page[1]);
					require dirname(__FILE__) . "/index.php";
					break;
				case "friends":
					set_input('username', $page[1]);
					require dirname(__FILE__) . "/friends.php";
					break;
				case "all";
					require dirname(__FILE__) . "/everyone.php";
					break;
				case "reply";
					set_input('wire_username', $page[1]);
					require dirname(__FILE__) . "/add.php";
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
		function thewire_url_forwarder($page) {
			global $CONFIG;

			if (!isset($page[1])) {
				$page[1] = 'owner';
			}

			switch ($page[1]) {
				case "owner":
					$url = "{$CONFIG->wwwroot}pg/thewire/owner/{$page[0]}/";
					break;
			}

			register_error(elgg_echo("changebookmark"));
			forward($url);
		}

		function thewire_url($thewirepost) {

			global $CONFIG;
			return $CONFIG->url . "pg/thewire/owner/" . $thewirepost->getOwnerEntity()->username;

		}

		/**
		 * Returns a more meaningful message for SMS messages.
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
		 */
		function thewire_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'thewire'))
			{
				$descr = $entity->description;
				if ($method == 'sms') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ': ' . $descr;
				}
				if ($method == 'email') {
					$owner = $entity->getOwnerEntity();
					return $owner->username . ': ' . $descr . "\n\n" . $entity->getURL();
				}
			}
			return null;
		}

		/**
		 * Create a new wire post.
		 *
		 * @param string $post The post
		 * @param int $access_id Public/private etc
		 * @param int $parent Parent post (if any)
		 * @param string $method The method (default: 'site')
		 * @return bool
		 */
		function thewire_save_post($post, $access_id, $parent=0, $method = "site")
		{

			global $SESSION;

			// Initialise a new ElggObject
			$thewire = new ElggObject();

			// Tell the system it's a thewire post
			$thewire->subtype = "thewire";

			// Set its owner to the current user
			$thewire->owner_guid = get_loggedin_userid();

			// For now, set its access to public (we'll add an access dropdown shortly)
			$thewire->access_id = $access_id;

			// Set its description appropriately
			$thewire->description = elgg_substr(strip_tags($post), 0, 160);

			// add some metadata
			$thewire->method = $method; //method, e.g. via site, sms etc
			$thewire->parent = $parent; //used if the note is a reply

			//save
			$save = $thewire->save();

			if ($save) {
				add_to_river('river/object/thewire/create','create',$SESSION['user']->guid,$thewire->guid);

				// tweet
				$params = array(
					'plugin' => 'thewire',
					'message' => $thewire->description
				);

				trigger_plugin_hook('tweet', 'twitter_service', $params);
			}

			return $save;

		}

		/**
		 * Listen and process incoming SMS'
		 */
		function thewire_incoming_sms($event, $object_type, $object)
		{
			if (($object) && ($object->subtype == get_subtype_id('object', 'sms')))
			{
				// Get user from phone number
				if ((is_plugin_enabled('smsclient')) && (is_plugin_enabled('smslogin')))
				{
					// By this stage the owner should be logged in (requires SMS Login)
					if (thewire_save_post($object->description, get_default_access(), 0, 'sms'))
						return false;

				}
			}

			return true; // always create the shout even if it can't be sent
		}

		function thewire_get_plugins_twitter_service($hook, $type, $value, $params) {
			$value['thewire'] = array(
				'name' => elgg_echo('thewire'),
				'description' => elgg_echo('thewire:twitterservice:desc')
			);

			return $value;
		}

	// Make sure the thewire initialisation function is called on initialisation
		register_elgg_event_handler('init','system','thewire_init');
		register_elgg_event_handler('pagesetup','system','thewire_pagesetup');

	// Register actions
		global $CONFIG;
		register_action("thewire/add",false,$CONFIG->pluginspath . "thewire/actions/add.php");
		register_action("thewire/delete",false,$CONFIG->pluginspath . "thewire/actions/delete.php");

?>
