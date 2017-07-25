<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.User
 */

/**
 * Return the user specific details of a user by a row.
 *
 * @param int $guid The \ElggUser guid
 *
 * @return mixed
 * @access private
 */
function get_user_entity_as_row($guid) {
	return _elgg_services()->usersTable->getRow($guid);
}

/**
 * Disables all of a user's entities
 *
 * @param int $owner_guid The owner GUID
 *
 * @return bool Depending on success
 */
function disable_user_entities($owner_guid) {
	return _elgg_services()->usersTable->disableEntities($owner_guid);
}

/**
 * Ban a user
 *
 * @param int    $user_guid The user guid
 * @param string $reason    A reason
 *
 * @return bool
 */
function ban_user($user_guid, $reason = "") {
	return _elgg_services()->usersTable->ban($user_guid, $reason);
}

/**
 * Unban a user.
 *
 * @param int $user_guid Unban a user.
 *
 * @return bool
 */
function unban_user($user_guid) {
	return _elgg_services()->usersTable->unban($user_guid);
}

/**
 * Makes user $guid an admin.
 *
 * @param int $user_guid User guid
 *
 * @return bool
 */
function make_user_admin($user_guid) {
	return _elgg_services()->usersTable->makeAdmin($user_guid);
}

/**
 * Removes user $guid's admin flag.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function remove_user_admin($user_guid) {
	return _elgg_services()->usersTable->removeAdmin($user_guid);
}

/**
 * Get a user object from a GUID.
 *
 * This function returns an \ElggUser from a given GUID.
 *
 * @param int $guid The GUID
 *
 * @return \ElggUser|false
 */
function get_user($guid) {
	return _elgg_services()->entityTable->get($guid, 'user');
}

/**
 * Get user by username
 *
 * @param string $username The user's username
 *
 * @return \ElggUser|false Depending on success
 */
function get_user_by_username($username) {
	return _elgg_services()->usersTable->getByUsername($username);
}

/**
 * Get user by persistent login password
 *
 * @param string $hash Hash of the persistent login password
 *
 * @return \ElggUser
 */
function get_user_by_code($hash) {
	return _elgg_services()->persistentLogin->getUserFromHash($hash);
}

/**
 * Get an array of users from an email address
 *
 * @param string $email Email address.
 *
 * @return array
 */
function get_user_by_email($email) {
	return _elgg_services()->usersTable->getByEmail($email);
}

/**
 * Return users (or the number of them) who have been active within a recent period.
 *
 * @param array $options Array of options with keys:
 *
 *   seconds (int)  => Length of period (default 600 = 10min)
 *   limit   (int)  => Limit (default from settings)
 *   offset  (int)  => Offset (default 0)
 *   count   (bool) => Return a count instead of users? (default false)
 *
 * @return \ElggUser[]|int
 */
function find_active_users(array $options = []) {
	return _elgg_services()->usersTable->findActive($options);
}

/**
 * Generate and send a password request email to a given user's registered email address.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function send_new_password_request($user_guid) {
	return _elgg_services()->passwords->sendNewPasswordRequest($user_guid);
}

/**
 * Low level function to reset a given user's password.
 *
 * This can only be called from execute_new_password_request().
 *
 * @param int    $user_guid The user.
 * @param string $password  Text (which will then be converted into a hash and stored)
 *
 * @return bool
 */
function force_user_password_reset($user_guid, $password) {
	return _elgg_services()->passwords->forcePasswordReset($user_guid, $password);
}

/**
 * Validate and change password for a user.
 *
 * @param int    $user_guid The user id
 * @param string $conf_code Confirmation code as sent in the request email.
 * @param string $password  Optional new password, if not randomly generated.
 *
 * @return bool True on success
 */
function execute_new_password_request($user_guid, $conf_code, $password = null) {
	return _elgg_services()->passwords->executeNewPasswordReset($user_guid, $conf_code, $password);
}

/**
 * Generate a random 12 character clear text password.
 *
 * @return string
 */
function generate_random_cleartext_password() {
	return _elgg_services()->crypto->getRandomString(12, \ElggCrypto::CHARS_PASSWORD);
}



/**
 * Simple function which ensures that a username contains only valid characters.
 *
 * This should only permit chars that are valid on the file system as well.
 *
 * @param string $username Username
 *
 * @return bool
 * @throws RegistrationException on invalid
 */
function validate_username($username) {
	global $CONFIG;

	// Basic, check length
	if (!isset($CONFIG->minusername)) {
		$CONFIG->minusername = 4;
	}

	if (strlen($username) < $CONFIG->minusername) {
		$msg = elgg_echo('registration:usernametooshort', [$CONFIG->minusername]);
		throw new \RegistrationException($msg);
	}

	// username in the database has a limit of 128 characters
	if (strlen($username) > 128) {
		$msg = elgg_echo('registration:usernametoolong', [128]);
		throw new \RegistrationException($msg);
	}

	// Blacklist for bad characters (partially nicked from mediawiki)
	$blacklist = '/[' .
		'\x{0080}-\x{009f}' . // iso-8859-1 control chars
		'\x{00a0}' .          // non-breaking space
		'\x{2000}-\x{200f}' . // various whitespace
		'\x{2028}-\x{202f}' . // breaks and control chars
		'\x{3000}' .          // ideographic space
		'\x{e000}-\x{f8ff}' . // private use
		']/u';

	if (preg_match($blacklist, $username)) {
		// @todo error message needs work
		throw new \RegistrationException(elgg_echo('registration:invalidchars'));
	}

	// Belts and braces
	// @todo Tidy into main unicode
	$blacklist2 = '\'/\\"*& ?#%^(){}[]~?<>;|¬`@+=';

	$blacklist2 = elgg_trigger_plugin_hook('username:character_blacklist', 'user',
		['blacklist' => $blacklist2], $blacklist2);

	for ($n = 0; $n < strlen($blacklist2); $n++) {
		if (strpos($username, $blacklist2[$n]) !== false) {
			$msg = elgg_echo('registration:invalidchars', [$blacklist2[$n], $blacklist2]);
			$msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
			throw new \RegistrationException($msg);
		}
	}

	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:username', 'all',
		['username' => $username], $result);
}

/**
 * Simple validation of a password.
 *
 * @param string $password Clear text password
 *
 * @return bool
 * @throws RegistrationException on invalid
 */
function validate_password($password) {
	global $CONFIG;

	if (!isset($CONFIG->min_password_length)) {
		$CONFIG->min_password_length = 6;
	}

	if (strlen($password) < $CONFIG->min_password_length) {
		$msg = elgg_echo('registration:passwordtooshort', [$CONFIG->min_password_length]);
		throw new \RegistrationException($msg);
	}

	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:password', 'all',
		['password' => $password], $result);
}

/**
 * Simple validation of a email.
 *
 * @param string $address Email address
 *
 * @throws RegistrationException on invalid
 * @return bool
 */
function validate_email_address($address) {
	if (!is_email_address($address)) {
		throw new \RegistrationException(elgg_echo('registration:notemail'));
	}

	// Got here, so lets try a hook (defaulting to ok)
	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:email', 'all',
		['email' => $address], $result);
}

/**
 * Registers a user, returning false if the username already exists
 *
 * @param string $username              The username of the new user
 * @param string $password              The password
 * @param string $name                  The user's display name
 * @param string $email                 The user's email address
 * @param bool   $allow_multiple_emails Allow the same email address to be
 *                                      registered multiple times?
 *
 * @return int|false The new user's GUID; false on failure
 * @throws RegistrationException
 */
function register_user($username, $password, $name, $email, $allow_multiple_emails = false) {
	return _elgg_services()->usersTable->register($username, $password, $name, $email, $allow_multiple_emails);
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 * @see elgg_validate_invite_code
 */
function generate_invite_code($username) {
	return _elgg_services()->usersTable->generateInviteCode($username);
}

/**
 * Validate a user's invite code
 *
 * @param string $username The username
 * @param string $code     The invite code
 *
 * @return bool
 * @see generate_invite_code
 * @since 1.10
 */
function elgg_validate_invite_code($username, $code) {
	return _elgg_services()->usersTable->validateInviteCode($username, $code);
}

/**
 * Set the validation status for a user.
 *
 * @param int    $user_guid The user's GUID
 * @param bool   $status    Validated (true) or unvalidated (false)
 * @param string $method    Optional method to say how a user was validated
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_validation_status($user_guid, $status, $method = '') {
	return _elgg_services()->usersTable->setValidationStatus($user_guid, $status, $method);
}

/**
 * Gets the validation status of a user.
 *
 * @param int $user_guid The user's GUID
 * @return bool|null Null means status was not set for this user.
 * @since 1.8.0
 */
function elgg_get_user_validation_status($user_guid) {
	return _elgg_services()->usersTable->getValidationStatus($user_guid);
}

/**
 * Page handler for account related pages
 *
 * @param array  $page_elements Page elements
 * @param string $handler The handler string
 *
 * @return bool
 * @access private
 */
function elgg_user_account_page_handler($page_elements, $handler) {

	switch ($handler) {
		case 'login':
			echo elgg_view_resource("account/login");
			break;
		case 'forgotpassword':
			echo elgg_view_resource("account/forgotten_password");
			break;
		case 'changepassword':
			echo elgg_view_resource("account/change_password");
			break;
		case 'register':
			echo elgg_view_resource("account/register");
			break;
		default:
			return false;
	}

	return true;
}

/**
 * Returns site's registration URL
 * Triggers a 'registration_url', 'site' plugin hook that can be used by
 * plugins to alter the default registration URL and append query elements, such as
 * an invitation code and inviting user's guid
 *
 * @param array  $query    An array of query elements
 * @param string $fragment Fragment identifier
 * @return string
 */
function elgg_get_registration_url(array $query = [], $fragment = '') {
	$url = elgg_normalize_url('register');
	$url = elgg_http_add_url_query_elements($url, $query) . $fragment;
	return elgg_trigger_plugin_hook('registration_url', 'site', $query, $url);
}

/**
 * Returns site's login URL
 * Triggers a 'login_url', 'site' plugin hook that can be used by
 * plugins to alter the default login URL
 *
 * @param array  $query    An array of query elements
 * @param string $fragment Fragment identifier (e.g. #login-dropdown-box)
 * @return string
 */
function elgg_get_login_url(array $query = [], $fragment = '') {
	$url = elgg_normalize_url('login');
	$url = elgg_http_add_url_query_elements($url, $query) . $fragment;
	return elgg_trigger_plugin_hook('login_url', 'site', $query, $url);
}

/**
 * Sets the last action time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 * @return void
 */
function set_last_action($user_guid) {
	$user = get_user($user_guid);
	if (!$user) {
		return;
	}
	_elgg_services()->usersTable->setLastAction($user);
}

/**
 * Sets the last logon time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 * @return void
 */
function set_last_login($user_guid) {
	$user = get_user($user_guid);
	if (!$user) {
		return;
	}
	_elgg_services()->usersTable->setLastLogin($user);
}

/**
 * Set user avatar URL
 * Replaces user avatar URL with a public URL when walled garden is disabled
 *
 * @param string $hook   "entity:icon:url"
 * @param string $type   "user"
 * @param string $return Icon URL
 * @param array  $params Hook params
 * @return string
 * @access private
 */
function user_avatar_hook($hook, $type, $return, $params) {
	$user = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params, 'medium');

	if (!$user instanceof ElggUser) {
		return;
	}

	if (elgg_get_config('walled_garden')) {
		return;
	}

	if (!$user->hasIcon($size, 'icon')) {
		return;
	}
	
	$icon = $user->getIcon($size, 'icon');
	return elgg_get_inline_url($icon, false);
}

/**
 * Setup the default user hover menu
 * @access private
 */
function elgg_user_hover_menu($hook, $type, $return, $params) {
	$user = elgg_extract('entity', $params);
	/* @var \ElggUser $user */

	if (!$user instanceof \ElggUser) {
		return;
	}

	if (!elgg_is_logged_in()) {
		return;
	}
	
	if ($user->canEdit()) {
		$return[] = ElggMenuItem::factory([
			'name' => 'profile:edit',
			'text' => elgg_echo('profile:edit'),
			'icon' => 'address-card',
			'href' => "profile/$user->username/edit",
			'section' => (elgg_get_logged_in_user_guid() == $user->guid)? 'action' : 'admin',
		]);

		$return[] = ElggMenuItem::factory([
			'name' => 'avatar:edit',
			'text' => elgg_echo('avatar:edit'),
			'icon' => 'image',
			'href' => "avatar/edit/$user->username",
			'section' => (elgg_get_logged_in_user_guid() == $user->guid)? 'action' : 'admin',
		]);
	}

	// prevent admins from banning or deleting themselves
	if (elgg_get_logged_in_user_guid() == $user->guid) {
		return $return;
	}

	if (!elgg_is_admin_logged_in()) {
		return $return;
	}
	
	// following items are admin only
	if (!$user->isBanned()) {
		$return[] = ElggMenuItem::factory([
			'name' => 'ban',
			'text' => elgg_echo('ban'),
			'icon' => 'ban',
			'href' => "action/admin/user/ban?guid={$user->guid}",
			'confirm' => true,
			'section' => 'admin',
		]);
	} else {
		$return[] = ElggMenuItem::factory([
			'name' => 'unban',
			'text' => elgg_echo('unban'),
			'icon' => 'ban',
			'href' => "action/admin/user/unban?guid={$user->guid}",
			'confirm' => true,
			'section' => 'admin',
		]);
	}
	
	$return[] = ElggMenuItem::factory([
		'name' => 'delete',
		'text' => elgg_echo('delete'),
		'icon' => 'delete',
		'href' => "action/admin/user/delete?guid={$user->guid}",
		'confirm' => true,
		'section' => 'admin',
	]);
	
	$return[] = ElggMenuItem::factory([
		'name' => 'resetpassword',
		'text' => elgg_echo('resetpassword'),
		'icon' => 'refresh',
		'href' => "action/admin/user/resetpassword?guid={$user->guid}",
		'confirm' => true,
		'section' => 'admin',
	]);
	
	if (!$user->isAdmin()) {
		$return[] = ElggMenuItem::factory([
			'name' => 'makeadmin',
			'text' => elgg_echo('makeadmin'),
			'icon' => 'level-up',
			'href' => "action/admin/user/makeadmin?guid={$user->guid}",
			'confirm' => true,
			'section' => 'admin',
		]);
	} else {
		$return[] = ElggMenuItem::factory([
			'name' => 'removeadmin',
			'text' => elgg_echo('removeadmin'),
			'icon' => 'level-down',
			'href' => "action/admin/user/removeadmin?guid={$user->guid}",
			'confirm' => true,
			'section' => 'admin',
		]);
	}
	
	$return[] = ElggMenuItem::factory([
		'name' => 'settings:edit',
		'text' => elgg_echo('settings:edit'),
		'icon' => 'cogs',
		'href' => "settings/user/$user->username",
		'section' => 'admin',
	]);

	return $return;
}

/**
 * Setup the menu shown with an entity
 *
 * @param string $hook
 * @param string $type
 * @param array $return
 * @param array $params
 * @return array
 *
 * @access private
 */
function elgg_users_setup_entity_menu($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'user')) {
		return $return;
	}
	/* @var \ElggUser $entity */

	if ($entity->isBanned()) {
		$banned = elgg_echo('banned');
		$options = [
			'name' => 'banned',
			'text' => "<span>$banned</span>",
			'href' => false,
			'priority' => 0,
		];
		$return = [\ElggMenuItem::factory($options)];
	} else {
		$return = [];
		$location = $entity->location;
		if (is_string($location) && $location !== '') {
			$location = htmlspecialchars($location, ENT_QUOTES, 'UTF-8', false);
			$options = [
				'name' => 'location',
				'text' => "<span>$location</span>",
				'href' => false,
				'priority' => 150,
			];
			$return[] = \ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
 * add and delete fields.
 *
 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
 * other plugins have initialised.
 * @access private
 */
function elgg_profile_fields_setup() {
	global $CONFIG;

	$profile_defaults =  [
		'description' => 'longtext',
		'briefdescription' => 'text',
		'location' => 'location',
		'interests' => 'tags',
		'skills' => 'tags',
		'contactemail' => 'email',
		'phone' => 'text',
		'mobile' => 'text',
		'website' => 'url',
		'twitter' => 'text',
	];

	$loaded_defaults = [];
	$fieldlist = elgg_get_config('profile_custom_fields');
	if ($fieldlist || $fieldlist === '0') {
		$fieldlistarray = explode(',', $fieldlist);
		foreach ($fieldlistarray as $listitem) {
			if ($translation = elgg_get_config("admin_defined_profile_{$listitem}")) {
				$type = elgg_get_config("admin_defined_profile_type_{$listitem}");
				$loaded_defaults["admin_defined_profile_{$listitem}"] = $type;
				add_translation(get_current_language(), ["profile:admin_defined_profile_{$listitem}" => $translation]);
			}
		}
	}

	if (count($loaded_defaults)) {
		$CONFIG->profile_using_custom = true;
		$profile_defaults = $loaded_defaults;
	}

	$CONFIG->profile_fields = elgg_trigger_plugin_hook('profile:fields', 'profile', null, $profile_defaults);

	// register any tag metadata names
	foreach ($CONFIG->profile_fields as $name => $type) {
		if ($type == 'tags' || $type == 'location' || $type == 'tag') {
			elgg_register_tag_metadata_name($name);
			// register a tag name translation
			add_translation(get_current_language(), ["tag_names:$name" => elgg_echo("profile:$name")]);
		}
	}
}

/**
 * Avatar page handler
 *
 * /avatar/edit/<username>
 *
 * @param array $page
 * @return bool
 * @access private
 */
function elgg_avatar_page_handler($page) {
	$user = get_user_by_username(elgg_extract(1, $page));
	if ($user) {
		elgg_set_page_owner_guid($user->getGUID());
	}

	if ($page[0] == 'edit') {
		echo elgg_view_resource("avatar/edit");
		return true;
	}
}

/**
 * Profile page handler
 *
 * @param array $page
 * @return bool
 * @access private
 */
function elgg_profile_page_handler($page) {
	$user = get_user_by_username($page[0]);
	elgg_set_page_owner_guid($user->guid);

	if ($page[1] == 'edit') {
		echo elgg_view_resource("profile/edit");
		return true;
	}
	return false;
}

/**
 * Register menu items for the page menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 *
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_user_page_menu($hook, $type, $return, $params) {
	
	$owner = elgg_get_page_owner_entity();
	if (!$owner) {
		return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'edit_avatar',
		'href' => "avatar/edit/{$owner->username}",
		'text' => elgg_echo('avatar:edit'),
		'section' => '1_profile',
		'contexts' => ['settings'],
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'edit_profile',
		'href' => "profile/{$owner->username}/edit",
		'text' => elgg_echo('profile:edit'),
		'section' => '1_profile',
		'contexts' => ['settings'],
	]);
	
	return $return;
}

/**
 * Register menu items for the topbar menu
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 *
 * @return array
 *
 * @access private
 *
 * @since 3.0
 */
function _elgg_user_topbar_menu($hook, $type, $return, $params) {
	
	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		return;
	}

	$toggle = elgg_view_icon('chevron-down', ['class' => 'elgg-state-closed']);
	$toggle .= elgg_view_icon('chevron-up', ['class' => 'elgg-state-opened']);

	// If JS fails here, allow easy access to place where they can upgrade/flush caches
	$href = elgg_is_admin_logged_in() ? elgg_get_login_url() : '#';

	$return[] = \ElggMenuItem::factory([
		'name' => 'account',
		'text' => elgg_echo('account') . $toggle,
		'href' => $href,
		'priority' => 800,
		'section' => 'alt',
		'child_menu' => [
			'display' => 'dropdown',
			'class' => 'elgg-topbar-child-menu',
			'data-position' => json_encode([
				'at' => 'right+10px bottom',
				'my' => 'right top',
				'collision' => 'fit fit',
			]),
		],
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'usersettings',
		'parent_name' => 'account',
		'href' => "settings/user/{$viewer->username}",
		'text' => elgg_echo('settings'),
		'icon' => 'cogs',
		'priority' => 300,
		'section' => 'alt',
	]);
	
	if ($viewer->isAdmin()) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'administration',
			'parent_name' => 'account',
			'href' => 'admin',
			'text' => elgg_echo('admin'),
			'icon' => 'tasks',
			'priority' => 800,
			'section' => 'alt',
		]);
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'logout',
		'parent_name' => 'account',
		'href' => 'action/logout',
		'text' => elgg_echo('logout'),
		'icon' => 'sign-out',
		'is_action' => true,
		'priority' => 900,
		'section' => 'alt',
	]);
	
	return $return;
}

/**
 * Set user icon file
 *
 * @param string    $hook   "entity:icon:file"
 * @param string    $type   "user"
 * @param \ElggIcon $icon   Icon file
 * @param array     $params Hook params
 * @return \ElggIcon
 */
function _elgg_user_set_icon_file($hook, $type, $icon, $params) {

	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params, 'medium');

	$icon->owner_guid = $entity->guid;
	$icon->setFilename("profile/{$entity->guid}{$size}.jpg");
	
	return $icon;
}

/**
 * Add the user to the subscribers when (un)banning the account
 *
 * @param string $hook         'get'
 * @param string $type         'subscribers'
 * @param array  $return_value current subscribers
 * @param arary  $params       supplied params
 *
 * @return void|array
 */
function _elgg_user_get_subscriber_unban_action($hook, $type, $return_value, $params) {
	
	if (!elgg_get_config('security_notify_user_ban')) {
		return;
	}
	
	$event = elgg_extract('event', $params);
	if (!($event instanceof \Elgg\Notifications\Event)) {
		return;
	}
	
	if ($event->getAction() !== 'unban') {
		return;
	}
	
	$user = $event->getObject();
	if (!($user instanceof \ElggUser)) {
		return;
	}
	
	$return_value[$user->getGUID()] = ['email'];
	
	return $return_value;
}

/**
 * Send a notification to the user that the account was banned
 *
 * Note: this can't be handled by the delayed notification system as it won't send notifications to banned users
 *
 * @param string    $event 'ban'
 * @param string    $type  'user'
 * @param \ElggUser $user  the user being banned
 *
 * @return void
 */
function _elgg_user_ban_notification($event, $type, $user) {
	
	if (!elgg_get_config('security_notify_user_ban')) {
		return;
	}
	
	if (!($user instanceof \ElggUser)) {
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$subject = elgg_echo('user:notification:ban:subject', [$site->name], $language);
	$body = elgg_echo('user:notification:ban:body', [
		$recipient->name,
		$site->name,
		$site->getURL(),
	], $language);
	
	$params = [
		'action' => 'ban',
		'object' => $user,
	];
	
	notify_user($user->getGUID(), $site->getGUID(), $subject, $body, $params, ['email']);
}

/**
 * Prepare the notification content for the user being unbanned
 *
 * @param string                           $hook         'prepare'
 * @param string                           $type         'notification:unban:user:'
 * @param \Elgg\Notifications\Notification $return_value current notification content
 * @param array                            $params       supplied params
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_user_prepare_unban_notification($hook, $type, $return_value, $params) {
	
	if (!($return_value instanceof \Elgg\Notifications\Notification)) {
		return;
	}
	
	$recipient = elgg_extract('recipient', $params);
	$object = elgg_extract('object', $params);
	$language = elgg_extract('language', $params);
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() !== $object->getGUID()) {
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('user:notification:unban:subject', [$site->name], $language);
	$return_value->body = elgg_echo('user:notification:unban:body', [
		$recipient->name,
		$site->name,
		$site->getURL(),
	], $language);

	$return_value->url = $recipient->getURL();
	
	return $return_value;
}

/**
 * Users initialisation function, which establishes the page handler
 *
 * @return void
 * @access private
 */
function users_init() {

	elgg_register_page_handler('register', 'elgg_user_account_page_handler');
	elgg_register_page_handler('forgotpassword', 'elgg_user_account_page_handler');
	elgg_register_page_handler('changepassword', 'elgg_user_account_page_handler');
	elgg_register_page_handler('login', 'elgg_user_account_page_handler');
	elgg_register_page_handler('avatar', 'elgg_avatar_page_handler');
	elgg_register_page_handler('profile', 'elgg_profile_page_handler');

	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'elgg_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_elgg_user_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_elgg_user_topbar_menu');

	elgg_register_action('login', '', 'public');
	elgg_register_action('logout');
	elgg_register_action('register', '', 'public');
	elgg_register_action('useradd', '', 'admin');
	elgg_register_action('avatar/upload');
	elgg_register_action('avatar/crop');
	elgg_register_action('avatar/remove');
	elgg_register_action('profile/edit');

	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'user_avatar_hook');

	elgg_register_action('user/changepassword', '', 'public');
	elgg_register_action('user/requestnewpassword', '', 'public');

	// Register the user type
	elgg_register_entity_type('user', '');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'elgg_users_setup_entity_menu', 501);

	elgg_register_plugin_hook_handler('entity:icon:file', 'user', '_elgg_user_set_icon_file');
	
	elgg_register_notification_event('user', '', ['unban']);
	elgg_register_plugin_hook_handler('get', 'subscriptions', '_elgg_user_get_subscriber_ban_action');
	elgg_register_event_handler('ban', 'user', '_elgg_user_ban_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:unban:user:', '_elgg_user_prepare_unban_notification');
	
}

/**
 * Runs unit tests for \ElggUser
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function users_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggUserTest.php";
	return $value;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', 'users_init', 0);
	$events->registerHandler('init', 'system', 'elgg_profile_fields_setup', 10000); // Ensure this runs after other plugins
	$hooks->registerHandler('unit_test', 'system', 'users_test');
};
