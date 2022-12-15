<?php
/**
 * Updates the basic settings for the primary site object.
 *
 * Basic site settings are saved as metadata on the site object,
 * with the exception of the default language, which is saved in
 * the config table.
 */

use Elgg\Exceptions\Configuration\InstallationException;

$site = elgg_get_site_entity();

$site->description = get_input('sitedescription');
$site->name = strip_tags(get_input('sitename', ''));
$site->email = get_input('siteemail');

if (!$site->save()) {
	return elgg_error_response(elgg_echo('admin:configuration:fail'));
}

// allow new user registration?
$allow_registration = (get_input('allow_registration', false) === 'on');
elgg_save_config('allow_registration', $allow_registration);

// require admin validation for new users?
$require_admin_validation = (get_input('require_admin_validation', false) === 'on');
elgg_save_config('require_admin_validation', $require_admin_validation);

// notify admins about pending validation
$admin_validation_notification = get_input('admin_validation_notification');
if (empty($admin_validation_notification)) {
	elgg_remove_config('admin_validation_notification');
} else {
	elgg_save_config('admin_validation_notification', $admin_validation_notification);
}

// remove unvalidated users after x days
$remove_unvalidated_users_days = (int) get_input('remove_unvalidated_users_days');
if ($remove_unvalidated_users_days < 1) {
	elgg_remove_config('remove_unvalidated_users_days');
} else {
	elgg_save_config('remove_unvalidated_users_days', $remove_unvalidated_users_days);
}

// setup walled garden
$walled_garden = (get_input('walled_garden', false) === 'on');
elgg_save_config('walled_garden', $walled_garden);

elgg_save_config('language', get_input('language'));

$allowed_languages = (array) get_input('allowed_languages', []);
$allowed_languages[] = 'en'; // always add English (as it's the ultimate fallback)
$allowed_languages[] = elgg_get_config('language'); // add default site language
elgg_save_config('allowed_languages', implode(',', array_unique($allowed_languages)));

elgg_save_config('who_can_change_language', get_input('who_can_change_language'));

$default_limit = (int) get_input('default_limit');
if ($default_limit < 1) {
	return elgg_error_response(elgg_echo('admin:configuration:default_limit'));
}

elgg_save_config('default_limit', $default_limit);

$comments_max_depth = (int) get_input('comments_max_depth');
if (!in_array($comments_max_depth, [0,2,3,4])) {
	$comments_max_depth = 0;
}

elgg_save_config('comments_max_depth', $comments_max_depth);
elgg_save_config('comment_box_collapses', (bool) get_input('comment_box_collapses'));
elgg_save_config('comments_group_only', (bool) get_input('comments_group_only'));
elgg_save_config('comments_latest_first', (bool) get_input('comments_latest_first'));
elgg_save_config('comments_per_page', (int) get_input('comments_per_page'));
elgg_save_config('pagination_behaviour', get_input('pagination_behaviour', 'ajax-replace'));
elgg_save_config('mentions_display_format', get_input('mentions_display_format'));

elgg_save_config('user_joined_river', get_input('user_joined_river') === 'on');
elgg_save_config('can_change_username', get_input('can_change_username') === 'on');

if (!elgg()->config->hasInitialValue('simplecache_enabled')) {
	if (get_input('simplecache_enabled') === 'on') {
		elgg_enable_simplecache();
	} else {
		elgg_disable_simplecache();
	}
}

if (get_input('cache_symlink_enabled') === 'on') {
	if (!_elgg_symlink_cache()) {
		elgg_register_error_message(elgg_echo('installation:cache_symlink:error'));
	}
}

elgg_save_config('simplecache_minify_js', get_input('simplecache_minify_js') === 'on');
elgg_save_config('simplecache_minify_css', get_input('simplecache_minify_css') === 'on');

if (get_input('system_cache_enabled') === 'on') {
	elgg_enable_system_cache();
} else {
	elgg_disable_system_cache();
}

elgg_save_config('default_access', (int) get_input('default_access', ACCESS_PRIVATE));

$user_default_access = (get_input('allow_user_default_access') === 'on');
elgg_save_config('allow_user_default_access', $user_default_access);

if (!elgg()->config->hasInitialValue('debug')) {
	$debug = get_input('debug');
	if ($debug) {
		elgg_save_config('debug', $debug);
	} else {
		elgg_remove_config('debug');
	}
}

$remove_branding = (get_input('remove_branding', false) === 'on');
elgg_save_config('remove_branding', $remove_branding);

elgg_save_config('email_html_part', (bool) get_input('email_html_part'));
elgg_save_config('email_html_part_images', get_input('email_html_part_images'));
elgg_save_config('enable_delayed_email', (bool) get_input('enable_delayed_email'));

$disable_rss = (get_input('disable_rss', false) === 'on');
elgg_save_config('disable_rss', $disable_rss);

$friendly_time_number_of_days = get_input('friendly_time_number_of_days', 30);
if ($friendly_time_number_of_days === '') {
	$friendly_time_number_of_days = 30;
}

elgg_save_config('friendly_time_number_of_days', (int) $friendly_time_number_of_days);
elgg_save_config('message_delay', (int) get_input('message_delay', 6));

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('admin:configuration:success'));
