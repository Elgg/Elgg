<?php
/**
 * Elgg admin functions.
 *
 * Admin pages
 * Plugins no not need to provide their own page handler to add a page to the
 * admin area. A view placed at admin/<section>/<subsection> can be access
 * at http://example.org/admin/<section>/<subsection>. The title of the page
 * will be elgg_echo('admin:<section>:<subsection>').
 *
 * Admin notices
 * System messages (success and error messages) are used in both the main site
 * and the admin area. There is a special presistent message for the admin area
 * called an admin notice. It should be used when a plugin requires an
 * administrator to take an action. @see elgg_add_admin_notice()
 */

use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\LoginException;
use Elgg\Http\ResponseBuilder;

/**
 * Get the admin users
 *
 * @param array $options Options array, @see elgg_get_entities() for parameters
 *
 * @return mixed Array of admin users or false on failure. If a count, returns int.
 * @since 1.8.0
 */
function elgg_get_admins(array $options = []) {
	$options['type'] = 'user';
	$options['metadata_name_value_pairs'] = elgg_extract('metadata_name_value_pairs', $options, []);
	
	$options['metadata_name_value_pairs']['admin'] = 'yes';

	return elgg_get_entities($options);
}

/**
 * Write a persistent message to the admin view.
 * Useful to alert the admin to take a certain action.
 * The id is a unique ID that can be cleared once the admin
 * completes the action.
 *
 * eg: add_admin_notice('twitter_services_no_api',
 * 	'Before your users can use Twitter services on this site, you must set up
 * 	the Twitter API key in the <a href="link">Twitter Services Settings</a>');
 *
 * @param string $id      A unique ID that your plugin can remember
 * @param string $message Body of the message
 *
 * @return ElggAdminNotice|bool
 * @since 1.8.0
 */
function elgg_add_admin_notice($id, $message) {
	return _elgg_services()->adminNotices->add($id, $message);
}

/**
 * Remove an admin notice by ID.
 *
 * @param string $id The unique ID assigned in add_admin_notice()
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_delete_admin_notice($id) {
	return _elgg_services()->adminNotices->delete($id);
}

/**
 * Get admin notices. An admin must be logged in since the notices are private.
 *
 * @param array $options Query options
 *
 * @return \ElggObject[]|int|mixed Admin notices
 * @since 1.8.0
 */
function elgg_get_admin_notices(array $options = []) {
	return _elgg_services()->adminNotices->find($options);
}

/**
 * Check if an admin notice is currently active. (Ignores access)
 *
 * @param string $id The unique ID used to register the notice.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_admin_notice_exists($id) {
	return _elgg_services()->adminNotices->exists($id);
}

/**
 * Add an admin notice when a new \ElggUpgrade object is created.
 *
 * @param \Elgg\Event $event 'create', 'object'
 *
 * @return void
 *
 * @internal
 */
function _elgg_create_notice_of_pending_upgrade(\Elgg\Event $event) {
	if (!$event->getObject() instanceof \ElggUpgrade) {
		return;
	}
	
	// Link to the Upgrades section
	$link = elgg_view('output/url', [
		'href' => 'admin/upgrades',
		'text' => elgg_echo('admin:view_upgrades'),
	]);

	$message = elgg_echo('admin:pending_upgrades');

	elgg_add_admin_notice('pending_upgrades', "$message $link");
}

/**
 * Initialize the admin backend.
 * @return void
 * @internal
 */
function _elgg_admin_init() {
	// maintenance mode
	if (elgg_get_config('elgg_maintenance_mode', null)) {
		elgg_register_plugin_hook_handler('route', 'all', '_elgg_admin_maintenance_handler', 600);
		elgg_register_plugin_hook_handler('action', 'all', '_elgg_admin_maintenance_action_check', 600);
		elgg_register_external_file('css', 'maintenance', elgg_get_simplecache_url('maintenance.css'));
	}

	// widgets
	$widgets = ['online_users', 'new_users', 'content_stats', 'banned_users', 'admin_welcome', 'control_panel', 'cron_status'];
	foreach ($widgets as $widget) {
		elgg_register_widget_type(
				$widget,
				elgg_echo("admin:widget:$widget"),
				elgg_echo("admin:widget:$widget:help"),
				['admin']
		);
	}
}

/**
 * When in maintenance mode, should the given URL be handled normally?
 *
 * @param string $current_url Current page URL
 * @return bool
 *
 * @internal
 */
function _elgg_admin_maintenance_allow_url($current_url) {
	$site_path = preg_replace('~^https?~', '', elgg_get_site_url());
	$current_path = preg_replace('~^https?~', '', $current_url);
	if (0 === elgg_strpos($current_path, $site_path)) {
		$current_path = ($current_path === $site_path) ? '' : elgg_substr($current_path, elgg_strlen($site_path));
	} else {
		$current_path = false;
	}

	// allow plugins to control access for specific URLs/paths
	$params = [
		'current_path' => $current_path,
		'current_url' => $current_url,
	];
	return (bool) elgg_trigger_plugin_hook('maintenance:allow', 'url', $params, false);
}

/**
 * Handle requests when in maintenance mode
 *
 * @param \Elgg\Hook $hook 'route', 'all'
 *
 * @return void|false
 *
 * @internal
 */
function _elgg_admin_maintenance_handler(\Elgg\Hook $hook) {
	if (elgg_is_admin_logged_in()) {
		return;
	}

	$info = $hook->getValue();
	
	if ($info['identifier'] == 'action' && $info['segments'][0] == 'login') {
		return;
	}

	if (_elgg_admin_maintenance_allow_url(current_page_url())) {
		return;
	}

	echo elgg_view_resource('maintenance');

	return false;
}

/**
 * Prevent non-admins from using actions
 *
 * @param \Elgg\Hook $hook 'action', 'all'
 *
 * @return bool
 * @internal
 */
function _elgg_admin_maintenance_action_check(\Elgg\Hook $hook) {
	if (elgg_is_admin_logged_in()) {
		return true;
	}

	if ($hook->getType() == 'login') {
		$username = get_input('username');

		$user = get_user_by_username($username);

		if (!$user) {
			$users = get_user_by_email($username);
			if (!empty($users)) {
				$user = $users[0];
			}
		}

		if ($user && $user->isAdmin()) {
			return true;
		}
	}

	if (_elgg_admin_maintenance_allow_url(current_page_url())) {
		return true;
	}

	register_error(elgg_echo('actionunauthorized'));

	return false;
}

/**
 * Adds default admin widgets to the admin dashboard.
 *
 * @param \Elgg\Event $event 'make_admin', 'user'
 *
 * @return void
 * @internal
 */
function _elgg_add_admin_widgets(\Elgg\Event $event) {
	$user = $event->getObject();
	
	elgg_call(ELGG_IGNORE_ACCESS, function() use ($user) {
		// check if the user already has widgets
		if (elgg_get_widgets($user->guid, 'admin')) {
			return;
		}
	
		// In the form column => array of handlers in order, top to bottom
		$adminWidgets = [
			1 => ['control_panel', 'admin_welcome'],
			2 => ['online_users', 'new_users', 'content_stats'],
		];
	
		foreach ($adminWidgets as $column => $handlers) {
			foreach ($handlers as $position => $handler) {
				$guid = elgg_create_widget($user->getGUID(), $handler, 'admin');
				if ($guid !== false) {
					$widget = get_entity($guid);
					/* @var \ElggWidget $widget */
					$widget->move($column, $position);
				}
			}
		}
	});
}

/**
 * Add the current site admins to the subscribers when making/removing an admin user
 *
 * @param \Elgg\Hook $hook 'get', 'subscribers'
 *
 * @return void|array
 */
function _elgg_admin_get_admin_subscribers_admin_action(\Elgg\Hook $hook) {
	
	if (!_elgg_config()->security_notify_admins) {
		return;
	}
	
	$event = $hook->getParam('event');
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}
	
	if (!in_array($event->getAction(), ['make_admin', 'remove_admin'])) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	/* @var $admin_batch \Elgg\BatchResult */
	$admin_batch = elgg_get_admins([
		'limit' => false,
		'wheres' => [
			function (QueryBuilder $qb, $main_alias) use ($user) {
				return $qb->compare("{$main_alias}.guid", '!=', $user->guid, ELGG_VALUE_GUID);
			},
		],
		'batch' => true,
	]);
	
	$return_value = $hook->getValue();
	
	/* @var $admin \ElggUser */
	foreach ($admin_batch as $admin) {
		$return_value[$admin->guid] = ['email'];
	}
	
	return $return_value;
}

/**
 * Prepare the notification content for site admins about making a site admin
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:make_admin:user:'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_admin_notification_make_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() === $object->getGUID()) {
		// recipient is the user being acted on, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:make_admin:admin:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:make_admin:admin:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$object->getDisplayName(),
		$site->getDisplayName(),
		$object->getURL(),
		$site->getURL(),
	], $language);

	$return_value->url = elgg_normalize_url('admin/users/admins');
	
	return $return_value;
}

/**
 * Prepare the notification content for site admins about removing a site admin
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:remove_admin:user:user'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_admin_notification_remove_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() === $object->getGUID()) {
		// recipient is the user being acted on, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:remove_admin:admin:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:remove_admin:admin:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$object->getDisplayName(),
		$site->getDisplayName(),
		$object->getURL(),
		$site->getURL(),
	], $language);

	$return_value->url = elgg_normalize_url('admin/users/admins');
	
	return $return_value;
}

/**
 * Add the user to the subscribers when making/removing the admin role
 *
 * @param \Elgg\Hook $hook 'get', 'subscribers'
 *
 * @return void|array
 */
function _elgg_admin_get_user_subscriber_admin_action(\Elgg\Hook $hook) {
	
	if (!_elgg_config()->security_notify_user_admin) {
		return;
	}
	
	$event = $hook->getParam('event');
	if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
		return;
	}
	
	if (!in_array($event->getAction(), ['make_admin', 'remove_admin'])) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof \ElggUser) {
		return;
	}
	
	$return_value = $hook->getValue();
	
	$return_value[$user->guid] = ['email'];
	
	return $return_value;
}

/**
 * Prepare the notification content for the user being made as a site admins
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:make_admin:user:user'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_user_notification_make_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->guid !== $object->guid) {
		// recipient is some other user, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:make_admin:user:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:make_admin:user:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$site->getDisplayName(),
		$site->getURL(),
	], $language);

	$return_value->url = elgg_normalize_url('admin');
	
	return $return_value;
}

/**
 * Prepare the notification content for the user being removed as a site admins
 *
 * @param \Elgg\Hook $hook 'prepare', 'notification:remove_admin:user:user'
 *
 * @return void|\Elgg\Notifications\Notification
 */
function _elgg_admin_prepare_user_notification_remove_admin(\Elgg\Hook $hook) {
	
	$return_value = $hook->getValue();
	if (!$return_value instanceof \Elgg\Notifications\Notification) {
		return;
	}
	
	$recipient = $hook->getParam('recipient');
	$object = $hook->getParam('object');
	$actor = $hook->getParam('sender');
	$language = $hook->getParam('language');
	
	if (!($recipient instanceof ElggUser) || !($object instanceof ElggUser) || !($actor instanceof ElggUser)) {
		return;
	}
	
	if ($recipient->getGUID() !== $object->getGUID()) {
		// recipient is some other user, this is handled elsewhere
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$return_value->subject = elgg_echo('admin:notification:remove_admin:user:subject', [$site->getDisplayName()], $language);
	$return_value->body = elgg_echo('admin:notification:remove_admin:user:body', [
		$recipient->getDisplayName(),
		$actor->getDisplayName(),
		$site->getDisplayName(),
		$site->getURL(),
	], $language);

	$return_value->url = '';
	
	return $return_value;
}

/**
 * Check if new users need to be validated by an administrator
 *
 * @param \Elgg\Hook $hook 'register', 'user'
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_check_admin_validation(\Elgg\Hook $hook) {
	
	if (!(bool) elgg_get_config('require_admin_validation')) {
		return;
	}
	
	$user = $hook->getUserParam();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
		
		if ($user->isEnabled()) {
			// disable the user until validation
			$user->disable('admin_validation_required', false);
		}
		
		// set validation status
		$user->setValidationStatus(false);
		
		// store a flag in session so we can forward the user correctly
		$session = elgg_get_session();
		$session->set('admin_validation', true);
		
		if (elgg_get_config('admin_validation_notification') === 'direct') {
			_elgg_admin_notify_admins_pending_user_validation();
		}
	});
}

/**
 * Prevent unvalidated users from logging in
 *
 * @param \Elgg\Event $event 'login:before', 'user'
 *
 * @return void
 * @throws LoginException
 * @internal
 * @since 3.2
 */
function _elgg_admin_user_validation_login_attempt(\Elgg\Event $event) {
	
	if (!(bool) elgg_get_config('require_admin_validation')) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
		if ($user->isEnabled() && $user->isValidated() !== false) {
			return;
		}
		
		throw new LoginException(elgg_echo('LoginException:AdminValidationPending'));
	});
}

/**
 * Send a notification to all admins that there are pending user validations
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_notify_admins_pending_user_validation() {
	
	if (empty(elgg_get_config('admin_validation_notification'))) {
		return;
	}
	
	$unvalidated_count = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
		return elgg_count_entities([
			'type' => 'user',
			'metadata_name_value_pairs' => [
				'validated' => 0,
			],
		]);
	});
	if (empty($unvalidated_count)) {
		// shouldn't be able to get here because this function is triggered when a user is marked as unvalidated
		return;
	}
	
	$site = elgg_get_site_entity();
	$admins = elgg_get_admins([
		'limit' => false,
		'batch' => true,
	]);
	
	$url = elgg_normalize_url('admin/users/unvalidated');
	
	/* @var $admin ElggUser */
	foreach ($admins as $admin) {
		$user_setting = $admin->getPrivateSetting('admin_validation_notification');
		if (isset($user_setting) && !(bool) $user_setting) {
			continue;
		}
		
		$subject = elgg_echo('admin:notification:unvalidated_users:subject', [$site->getDisplayName()], $admin->getLanguage());
		$body = elgg_echo('admin:notification:unvalidated_users:body', [
			$admin->getDisplayName(),
			$unvalidated_count,
			$site->getDisplayName(),
			$url,
		], $admin->getLanguage());
		
		$params = [
			'action' => 'admin:unvalidated',
			'object' => $admin,
		];
		notify_user($admin->guid, $site->guid, $subject, $body, $params, ['email']);
	}
}

/**
 * Save a setting related to admin approval of new users
 *
 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_save_notification_setting(\Elgg\Hook $hook) {
	
	$user = $hook->getUserParam();
	if (!$user instanceof ElggUser || !$user->isAdmin()) {
		return;
	}
	
	$request = $hook->getParam('request');
	if (!$request instanceof \Elgg\Request) {
		return;
	}
	
	$value = (bool) $request->getParam('admin_validation_notification', true);
	$user->setPrivateSetting('admin_validation_notification', $value);
}

/**
 * Set the correct forward url after user registration
 *
 * @param \Elgg\Hook $hook 'response', 'action:register'
 *
 * @return void|ResponseBuilder
 * @internal
 * @since 3.2
 */
function _elgg_admin_set_registration_forward_url(\Elgg\Hook $hook) {
	
	$response = $hook->getValue();
	if (!$response instanceof ResponseBuilder) {
		return;
	}
	
	$session = elgg_get_session();
	if (!$session->get('admin_validation')) {
		return;
	}
	
	// if other plugins already have set forwarding, don't do anything
	if (!empty($response->getForwardURL()) && $response->getForwardURL() !== REFERER) {
		return;
	}
	
	$response->setForwardURL(elgg_generate_url('account:validation:pending'));
	
	return $response;
}

/**
 * Notify the user that their account is approved
 *
 * @param \Elgg\Event $event 'validate:after', 'user'
 *
 * @return void
 * @internal
 * @since 3.2
 */
function _elgg_admin_user_validation_notification(\Elgg\Event $event) {
	
	if (!(bool) elgg_get_config('require_admin_validation')) {
		return;
	}
	
	$user = $event->getObject();
	if (!$user instanceof ElggUser) {
		return;
	}
	
	$site = elgg_get_site_entity();
	
	$subject = elgg_echo('account:notification:validation:subject', [$site->getDisplayName()], $user->getLanguage());
	$body = elgg_echo('account:notification:validation:body', [
		$user->getDisplayName(),
		$site->getDisplayName(),
		$site->getURL(),
	], $user->getLanguage());
	
	$params = [
		'action' => 'account:validated',
		'object' => $user,
	];
	
	notify_user($user->guid, $site->guid, $subject, $body, $params, ['email']);
}
