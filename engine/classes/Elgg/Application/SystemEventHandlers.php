<?php

namespace Elgg\Application;

use Elgg\Notifications\CreateCommentEventHandler;
use Elgg\Notifications\UnbanUserEventHandler;
use Elgg\Notifications\MakeAdminUserEventHandler;
use Elgg\Notifications\RemoveAdminUserEventHandler;

/**
 * Contains the system event handlers
 *
 * @since 4.0
 */
class SystemEventHandlers {
	
	/**
	 * Initializes the system
	 *
	 * @return void
	 */
	public static function init() {
	
		elgg_register_entity_type('object', 'comment');
		elgg_register_entity_type('user', 'user');
		
		elgg_register_notification_method('email');
		if ((bool) elgg_get_config('enable_delayed_email')) {
			elgg_register_notification_method('delayed_email');
		}
		
		elgg_register_notification_event('object', 'comment', ['create'], CreateCommentEventHandler::class);
		elgg_register_notification_event('user', 'user', ['make_admin'], MakeAdminUserEventHandler::class);
		elgg_register_notification_event('user', 'user', ['remove_admin'], RemoveAdminUserEventHandler::class);
		elgg_register_notification_event('user', 'user', ['unban'], UnbanUserEventHandler::class);
		
		// if mb functions are available, set internal encoding to UTF8
		if (is_callable('mb_internal_encoding')) {
			mb_internal_encoding("UTF-8");
		}
	
		elgg_register_ajax_view('core/ajax/edit_comment');
		elgg_register_ajax_view('page/elements/comments');
		elgg_register_ajax_view('river/elements/responses');
		elgg_register_ajax_view('forms/admin/user/change_email');
		elgg_register_ajax_view('navigation/menu/user_hover/contents');
		elgg_register_ajax_view('notifications/subscriptions/details');
		elgg_register_ajax_view('object/plugin/details');
		
		elgg_extend_view('admin.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
		elgg_extend_view('core/settings/statistics', 'core/settings/statistics/online');
		elgg_extend_view('core/settings/statistics', 'core/settings/statistics/numentities');
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/username', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/name', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/password', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/email', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/language', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/default_access', 100);
		
		elgg_register_simplecache_view('admin.css');
		elgg_register_simplecache_view('resources/manifest.json');
		
		elgg_register_external_file('css', 'elgg.admin', elgg_get_simplecache_url('admin.css'));
		elgg_register_external_file('css', 'admin/users/unvalidated', elgg_get_simplecache_url('admin/users/unvalidated.css'));
		elgg_register_external_file('css', 'maintenance', elgg_get_simplecache_url('maintenance.css'));
		
		elgg_register_plugin_hook_handler('registeruser:validate:password', 'all', [_elgg_services()->passwordGenerator, 'registerUserPasswordValidation']);
		elgg_register_plugin_hook_handler('view_vars', 'input/password', [_elgg_services()->passwordGenerator, 'addInputRequirements']);
	
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
	 * Initializes the system (contains actions that require to be executed early [priority: 0])
	 *
	 * @return void
	 */
	public static function initEarly() {
		register_pam_handler('pam_auth_userpass');
	}
	
	/**
	 * Initializes the system (contains actions that require to be executed after regular priority [priority: 1000])
	 *
	 * @return void
	 */
	public static function initLate() {
		self::initWalledGarden();
	}

	/**
	 * Actions performed when the system is ready
	 *
	 * @return void
	 */
	public static function ready() {
		_elgg_services()->systemCache->init();
	}
	
	/**
	 * Initializes the walled garden logic
	 *
	 * @return void
	 */
	protected static function initWalledGarden() {
		if (!_elgg_services()->config->walled_garden) {
			return;
		}
	
		elgg_register_external_file('css', 'elgg.walled_garden', elgg_get_simplecache_url('walled_garden.css'));
	
		if (_elgg_services()->config->default_access == ACCESS_PUBLIC) {
			elgg_set_config('default_access', ACCESS_LOGGED_IN);
		}
	
		if (!elgg_is_logged_in()) {
			// override the front page
			elgg_register_route('index', [
				'path' => '/',
				'resource' => 'walled_garden',
			]);
		}
	}
}
