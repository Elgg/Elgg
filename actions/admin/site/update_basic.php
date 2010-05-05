<?php
/**
 * Elgg update site action
 *
 * This is an update version of the sitesettings/install action
 * which is used by the admin panel to modify basic settings.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

admin_gatekeeper();

if (datalist_get('default_site')) {
	$site = get_entity(datalist_get('default_site'));
	if (!($site instanceof ElggSite)) {
		throw new InstallationException(elgg_echo('InvalidParameterException:NonElggSite'));
	}

	$site->description = get_input('sitedescription');
	$site->name = get_input('sitename');
	$site->email = get_input('siteemail');
	$site->save();

	set_config('language', get_input('language'), $site->getGUID());

	forward($_SERVER['HTTP_REFERER']);
	exit;
}