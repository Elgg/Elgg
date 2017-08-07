<?php
/**
 * Updates the basic settings for the primary site object.
 *
 * Basic site settings are saved as metadata on the site object,
 * with the exception of the default language, which is saved in
 * the config table.
 *
 * @package Elgg.Core
 * @subpackage Administration.Site
 */

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
$site->save();

// allow new user registration?
$allow_registration = ('on' === get_input('allow_registration', false));
elgg_save_config('allow_registration', $allow_registration);

// setup walled garden
$walled_garden = ('on' === get_input('walled_garden', false));
elgg_save_config('walled_garden', $walled_garden);

elgg_save_config('language', get_input('language'));

$default_limit = (int) get_input('default_limit');
if ($default_limit < 1) {
	return elgg_error_response(elgg_echo('admin:configuration:default_limit'));
}

elgg_save_config('default_limit', $default_limit);

$forward = elgg_normalize_site_url(get_input('after_save')) ?: REFERER;
return elgg_ok_response('', elgg_echo('admin:configuration:success'), $forward);
