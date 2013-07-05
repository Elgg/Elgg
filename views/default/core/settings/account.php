<?php
/**
 * Account settings form wrapper
 * 
 * @package Elgg
 * @subpackage Core
 */

$action_url = elgg_get_site_url();
if (elgg_get_config('https_login')) {
	$action_url = str_replace("http:", "https:", $action_url);
}
$action_url .= 'action/usersettings/save';

echo elgg_view_form('usersettings/save', array(
	'class' => 'elgg-form-alt',
	'action' => $action_url,
));
