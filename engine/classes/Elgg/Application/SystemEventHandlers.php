<?php

namespace Elgg\Application;

use Elgg\Notifications\Handlers\AddUser;
use Elgg\Notifications\Handlers\AdminValidation;
use Elgg\Notifications\Handlers\BanUser;
use Elgg\Notifications\Handlers\ChangeUserPassword;
use Elgg\Notifications\Handlers\ConfirmEmailChange;
use Elgg\Notifications\Handlers\ConfirmPasswordChange;
use Elgg\Notifications\Handlers\CreateComment;
use Elgg\Notifications\Handlers\MakeAdminUser;
use Elgg\Notifications\Handlers\Mentions;
use Elgg\Notifications\Handlers\RemoveAdminUser;
use Elgg\Notifications\Handlers\RequestUserPassword;
use Elgg\Notifications\Handlers\ResetUserPassword;
use Elgg\Notifications\Handlers\UnbanUser;
use Elgg\Notifications\Handlers\ValidateUser;

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
		elgg_register_notification_method('email');
		if ((bool) elgg_get_config('enable_delayed_email')) {
			elgg_register_notification_method('delayed_email');
		}
		
		elgg_register_notification_event('object', 'comment', ['create'], CreateComment::class);
		elgg_register_notification_event('object', 'comment', ['mentions'], Mentions::class);
		elgg_register_notification_event('user', 'user', ['admin_validation'], AdminValidation::class);
		elgg_register_notification_event('user', 'user', ['ban'], BanUser::class);
		elgg_register_notification_event('user', 'user', ['changepassword'], ChangeUserPassword::class);
		elgg_register_notification_event('user', 'user', ['email_change'], ConfirmEmailChange::class);
		elgg_register_notification_event('user', 'user', ['make_admin'], MakeAdminUser::class);
		elgg_register_notification_event('user', 'user', ['password_change'], ConfirmPasswordChange::class);
		elgg_register_notification_event('user', 'user', ['remove_admin'], RemoveAdminUser::class);
		elgg_register_notification_event('user', 'user', ['requestnewpassword'], RequestUserPassword::class);
		elgg_register_notification_event('user', 'user', ['resetpassword'], ResetUserPassword::class);
		elgg_register_notification_event('user', 'user', ['unban'], UnbanUser::class);
		elgg_register_notification_event('user', 'user', ['useradd'], AddUser::class);
		elgg_register_notification_event('user', 'user', ['validate'], ValidateUser::class);
		
		// if mb functions are available, set internal encoding to UTF8
		if (is_callable('mb_internal_encoding')) {
			mb_internal_encoding('UTF-8');
		}
	
		elgg_register_ajax_view('admin/users/listing/details');
		elgg_register_ajax_view('core/ajax/edit_comment');
		elgg_register_ajax_view('forms/admin/user/change_email');
		elgg_register_ajax_view('forms/comment/save');
		elgg_register_ajax_view('forms/entity/chooserestoredestination');
		elgg_register_ajax_view('navigation/menu/user_hover/contents');
		elgg_register_ajax_view('notifications/subscriptions/details');
		elgg_register_ajax_view('object/plugin/details');
		elgg_register_ajax_view('object/widget/edit');
		elgg_register_ajax_view('page/elements/comments');
		elgg_register_ajax_view('page/layouts/widgets/add_panel');
		elgg_register_ajax_view('river/elements/responses');
		
		elgg_extend_view('admin.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
		elgg_extend_view('core/settings/statistics', 'core/settings/statistics/online');
		elgg_extend_view('core/settings/statistics', 'core/settings/statistics/numentities');
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/username', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/name', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/email', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/password', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/color_scheme', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/language', 100);
		elgg_extend_view('forms/usersettings/save', 'core/settings/account/default_access', 100);
		
		elgg_register_simplecache_view('admin.css');
		elgg_register_simplecache_view('resources/manifest.json');
		
		elgg_register_external_file('css', 'elgg.admin', elgg_get_simplecache_url('admin.css'));
		elgg_register_external_file('css', 'admin/users/unvalidated', elgg_get_simplecache_url('admin/users/unvalidated.css'));
		elgg_register_external_file('css', 'maintenance', elgg_get_simplecache_url('maintenance.css'));
		
		elgg_register_event_handler('registeruser:validate:password', 'all', [_elgg_services()->passwordGenerator, 'registerUserPasswordValidation']);
		elgg_register_event_handler('view_vars', 'input/password', [_elgg_services()->passwordGenerator, 'addInputRequirements']);
	
		$widgets = ['online_users', 'new_users', 'content_stats', 'banned_users', 'admin_welcome', 'cron_status', 'elgg_blog'];
		foreach ($widgets as $widget) {
			elgg_register_widget_type([
				'id' => $widget,
				'name' => elgg_echo("admin:widget:{$widget}"),
				'description' => elgg_echo("admin:widget:{$widget}:help"),
				'context' => ['admin'],
			]);
		}
	}
	
	/**
	 * Initializes the system (contains actions that require to be executed early [priority: 0])
	 *
	 * @return void
	 */
	public static function initEarly() {
		elgg_register_pam_handler(\Elgg\PAM\User\Password::class);
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
		_elgg_services()->views->cacheConfiguration();
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
