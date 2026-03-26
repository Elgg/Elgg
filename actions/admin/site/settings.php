<?php
/**
 * Updates the settings for the site
 */

$site = elgg_get_site_entity();

$site_name = strip_tags(get_input('sitename', ''));
if (empty($site_name)) {
	return elgg_error_response(elgg_echo('admin:configuration:fail'));
}

$site->description = get_input('sitedescription');
$site->name = $site_name;
$site->email = get_input('siteemail');

if (!$site->save()) {
	return elgg_error_response(elgg_echo('admin:configuration:fail'));
}

$allowed_languages = (array) get_input('allowed_languages', []);
$allowed_languages[] = 'en'; // always add English (as it's the ultimate fallback)
$allowed_languages[] = elgg_get_config('language'); // add default site language
$allowed_languages = implode(',', array_unique($allowed_languages));

$default_limit = (int) get_input('default_limit');
if ($default_limit < 1) {
	$default_limit = 10;
}

$comments_max_depth = (int) get_input('comments_max_depth');
if (!in_array($comments_max_depth, [0,2,3,4])) {
	$comments_max_depth = 0;
}

$trash_retention = (int) get_input('trash_retention', 30);
if ($trash_retention < 0) {
	$trash_retention = 30;
}

$friendly_time_number_of_days = (int) get_input('friendly_time_number_of_days', 30);
if ($friendly_time_number_of_days < 0) {
	$friendly_time_number_of_days = 30;
}

elgg_save_config('admin_validation_notification', (bool) get_input('admin_validation_notification'));
elgg_save_config('allow_registration', (bool) get_input('allow_registration'));
elgg_save_config('allow_user_default_access', (bool) get_input('allow_user_default_access'));
elgg_save_config('allowed_languages', $allowed_languages);
elgg_save_config('can_change_username', (bool) get_input('can_change_username'));
elgg_save_config('comment_box_collapses', (bool) get_input('comment_box_collapses'));
elgg_save_config('comments_group_only', (bool) get_input('comments_group_only'));
elgg_save_config('comments_latest_first', (bool) get_input('comments_latest_first'));
elgg_save_config('comments_max_depth', $comments_max_depth);
elgg_save_config('comments_per_page', (int) get_input('comments_per_page'));
elgg_save_config('color_schemes_enabled', (bool) get_input('color_schemes_enabled'));
elgg_save_config('default_access', (int) get_input('default_access', ACCESS_PRIVATE));
elgg_save_config('default_limit', $default_limit);
elgg_save_config('disable_rss', (bool) get_input('disable_rss'));
elgg_save_config('email_html_part', (bool) get_input('email_html_part'));
elgg_save_config('email_html_part_images', get_input('email_html_part_images'));
elgg_save_config('enable_delayed_email', (bool) get_input('enable_delayed_email'));
elgg_save_config('friendly_time_number_of_days', $friendly_time_number_of_days);
elgg_save_config('language', get_input('language'));
elgg_save_config('mentions_display_format', get_input('mentions_display_format'));
elgg_save_config('message_delay', (int) get_input('message_delay', 6));
elgg_save_config('pagination_behaviour', get_input('pagination_behaviour', 'ajax-replace'));
elgg_save_config('remove_branding', (bool) get_input('remove_branding'));
elgg_save_config('require_admin_validation', (bool) get_input('require_admin_validation'));
elgg_save_config('simplecache_minify_css', (bool) get_input('simplecache_minify_css'));
elgg_save_config('simplecache_minify_js', (bool) get_input('simplecache_minify_js'));
elgg_save_config('system_cache_enabled', (bool) get_input('system_cache_enabled'));
elgg_save_config('trash_enabled', (bool) get_input('trash_enabled'));
elgg_save_config('trash_retention', $trash_retention);
elgg_save_config('user_joined_river', (bool) get_input('user_joined_river'));
elgg_save_config('walled_garden', (bool) get_input('walled_garden'));
elgg_save_config('who_can_change_language', get_input('who_can_change_language'));

$remove_unvalidated_users_days = (int) get_input('remove_unvalidated_users_days');
if ($remove_unvalidated_users_days < 1) {
	elgg_remove_config('remove_unvalidated_users_days');
} else {
	elgg_save_config('remove_unvalidated_users_days', $remove_unvalidated_users_days);
}

if (!elgg()->config->hasInitialValue('simplecache_enabled')) {
	if (get_input('simplecache_enabled')) {
		_elgg_services()->simpleCache->enable();
	} else {
		_elgg_services()->simpleCache->disable();
	}
}

if (get_input('cache_symlink_enabled')) {
	if (!_elgg_services()->simpleCache->createSymbolicLink()) {
		elgg_register_error_message(elgg_echo('installation:cache_symlink:error'));
	}
}

if (!elgg()->config->hasInitialValue('debug')) {
	$debug = get_input('debug');
	if ($debug) {
		elgg_save_config('debug', $debug);
	} else {
		elgg_remove_config('debug');
	}
}

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('admin:configuration:success'));
