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
if (!$site) {
	throw new InstallationException("The system is missing an ElggSite entity!");
}
if (!($site instanceof ElggSite)) {
	throw new InstallationException("Passing a non-ElggSite to an ElggSite constructor!");
}

$site->description = get_input('sitedescription');
$site->name = strip_tags(get_input('sitename'));
$site->email = get_input('siteemail');

if (!$site->save()) {
	return elgg_error_response(elgg_echo('admin:configuration:fail'));
}

// allow new user registration?
$allow_registration = ('on' === get_input('allow_registration', false));
elgg_save_config('allow_registration', $allow_registration);

// require admin validation for new users?
$require_admin_validation = ('on' === get_input('require_admin_validation', false));
elgg_save_config('require_admin_validation', $require_admin_validation);

// notify admins about pending validation
$admin_validation_notification = get_input('admin_validation_notification');
if (empty($admin_validation_notification)) {
	elgg_remove_config('admin_validation_notification');
} else {
	elgg_save_config('admin_validation_notification', $admin_validation_notification);
}

// setup walled garden
$walled_garden = ('on' === get_input('walled_garden', false));
elgg_save_config('walled_garden', $walled_garden);

elgg_save_config('language', get_input('language'));

$allowed_languages = (array) get_input('allowed_languages', []);
$allowed_languages[] = 'en'; // always add English (as it's the ultimate fallback)
$allowed_languages[] = elgg_get_config('language'); // add default site language
elgg_save_config('allowed_languages', implode(',', array_unique($allowed_languages)));

$default_limit = (int) get_input('default_limit');
if ($default_limit < 1) {
	return elgg_error_response(elgg_echo('admin:configuration:default_limit'));
}

elgg_save_config('default_limit', $default_limit);

elgg_save_config('comment_box_collapses', (bool) get_input('comment_box_collapses'));
elgg_save_config('comments_latest_first', (bool) get_input('comments_latest_first'));
elgg_save_config('comments_per_page', (int) get_input('comments_per_page'));
elgg_save_config('pagination_behaviour', get_input('pagination_behaviour', 'ajax-replace'));

elgg_save_config('can_change_username', 'on' === get_input('can_change_username'));

if (!elgg()->config->hasInitialValue('simplecache_enabled')) {
	if ('on' === get_input('simplecache_enabled')) {
		elgg_enable_simplecache();
	} else {
		elgg_disable_simplecache();
	}
}

if ('on' === get_input('cache_symlink_enabled')) {
	if (!_elgg_symlink_cache()) {
		register_error(elgg_echo('installation:cache_symlink:error'));
	}
}

elgg_save_config('simplecache_minify_js', 'on' === get_input('simplecache_minify_js'));
elgg_save_config('simplecache_minify_css', 'on' === get_input('simplecache_minify_css'));

if ('on' === get_input('system_cache_enabled')) {
	elgg_enable_system_cache();
} else {
	elgg_disable_system_cache();
}

elgg_save_config('default_access', (int) get_input('default_access', ACCESS_PRIVATE));

$user_default_access = ('on' === get_input('allow_user_default_access'));
elgg_save_config('allow_user_default_access', $user_default_access);

if (!elgg()->config->hasInitialValue('debug')) {
	$debug = get_input('debug');
	if ($debug) {
		elgg_save_config('debug', $debug);
	} else {
		elgg_remove_config('debug');
	}
}

$remove_branding = ('on' === get_input('remove_branding', false));
elgg_save_config('remove_branding', $remove_branding);

elgg_save_config('email_html_part', (bool) get_input('email_html_part'));
elgg_save_config('email_html_part_images', get_input('email_html_part_images'));
elgg_save_config('enable_delayed_email', (bool) get_input('enable_delayed_email'));

$disable_rss = ('on' === get_input('disable_rss', false));
elgg_save_config('disable_rss', $disable_rss);

$friendly_time_number_of_days = get_input('friendly_time_number_of_days', 30);
if ($friendly_time_number_of_days === '') {
	$friendly_time_number_of_days = 30;
}
elgg_save_config('friendly_time_number_of_days', (int) $friendly_time_number_of_days);

return elgg_ok_response('', elgg_echo('admin:configuration:success'));
