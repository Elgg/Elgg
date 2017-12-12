<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.User
 */

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
	$config = _elgg_config();

	// Basic, check length
	if (!isset($config->minusername)) {
		$config->minusername = 4;
	}

	if (strlen($username) < $config->minusername) {
		$msg = elgg_echo('registration:usernametooshort', [$config->minusername]);
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
			$msg = htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
	$config = _elgg_config();

	if (!isset($config->min_password_length)) {
		$config->min_password_length = 6;
	}

	if (strlen($password) < $config->min_password_length) {
		$msg = elgg_echo('registration:passwordtooshort', [$config->min_password_length]);
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
 * @param string $subtype               Subtype of the user entity
 *
 * @return int|false The new user's GUID; false on failure
 * @throws RegistrationException
 */
function register_user($username, $password, $name, $email, $allow_multiple_emails = false, $subtype = null) {
	return _elgg_services()->usersTable->register($username, $password, $name, $email, $allow_multiple_emails, $subtype);
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 * @see elgg_validate_invite_code()
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
 * @see generate_invite_code()
 * @since 1.10
 */
function elgg_validate_invite_code($username, $code) {
	return _elgg_services()->usersTable->validateInviteCode($username, $code);
}

/**
 * Page handler for account related pages
 *
 * @param array  $page_elements Page elements
 * @param string $handler       The handler string
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

	if (_elgg_config()->walled_garden) {
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
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:user_hover'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 *
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
 * Avatar page handler
 *
 * /avatar/edit/<username>
 *
 * @param array $page URL segments
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
 * user page handler
 *
 * /user/view/<userguid>
 *
 * @param array $page url elements
 * @return bool
 * @access private
 */
function _elgg_user_page_handler($page) {
	echo elgg_view_resource('user/view', [
		'guid' => (int) elgg_extract(1, $page),
	]);
	return true;
}

/**
 * Register menu items for the page menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:page'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
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
	
	return $return;
}

/**
 * Register menu items for the topbar menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:topbar'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _elgg_user_topbar_menu($hook, $type, $return, $params) {
	
	$viewer = elgg_get_logged_in_user_entity();
	if (!$viewer) {
		return;
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'account',
		'text' => elgg_echo('account'),
		'href' => $viewer->getURL(),
		'icon' => elgg_view('output/img', [
			'src' => $viewer->getIconURL('small'),
			'alt' => $viewer->getDisplayName(),
		]),
		'icon_alt' => 'angle-down',
		'priority' => 800,
		'section' => 'alt',
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'usersettings',
		'parent_name' => 'account',
		'href' => "settings/user/{$viewer->username}",
		'text' => elgg_echo('settings'),
		'icon' => 'sliders',
		'priority' => 300,
		'section' => 'alt',
	]);
	
	if ($viewer->isAdmin()) {
		$return[] = \ElggMenuItem::factory([
			'name' => 'administration',
			'parent_name' => 'account',
			'href' => 'admin',
			'text' => elgg_echo('admin'),
			'icon' => 'cogs',
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
 * @param array  $params       supplied params
 *
 * @return void|array
 */
function _elgg_user_get_subscriber_unban_action($hook, $type, $return_value, $params) {
	
	if (!_elgg_config()->security_notify_user_ban) {
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
	
	if (!_elgg_config()->security_notify_user_ban) {
		return;
	}
	
	if (!($user instanceof \ElggUser)) {
		return;
	}
	
	$site = elgg_get_site_entity();
	$language = $user->getLanguage();
	
	$subject = elgg_echo('user:notification:ban:subject', [$site->name], $language);
	$body = elgg_echo('user:notification:ban:body', [
		$user->name,
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
	elgg_register_page_handler('user', '_elgg_user_page_handler');

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

	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'user_avatar_hook');

	elgg_register_action('user/changepassword', '', 'public');
	elgg_register_action('user/requestnewpassword', '', 'public');

	// Register the user type
	elgg_register_entity_type('user', '');

	elgg_register_plugin_hook_handler('entity:icon:file', 'user', '_elgg_user_set_icon_file');
	
	elgg_register_notification_event('user', '', ['unban']);
	elgg_register_plugin_hook_handler('get', 'subscriptions', '_elgg_user_get_subscriber_unban_action');
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
 * @codeCoverageIgnore
 */
function users_test($hook, $type, $value, $params) {
	$value[] = ElggCoreUserTest::class;
	return $value;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', 'users_init', 0);
	$hooks->registerHandler('unit_test', 'system', 'users_test');
};
