<?php
/**
 * Install database page
 *
 * @uses $vars['failure'] Settings file exists but something went wrong
 */

if (isset($vars['failure']) && $vars['failure']) {
	echo autop(elgg_echo('install:database:error'));
	$vars['refresh'] = TRUE;
	$vars['advance'] = FALSE;
	echo elgg_view('install/nav', $vars);
} else {
	echo autop(elgg_echo('install:database:instructions'));
	echo elgg_view('install/forms/database', $vars);
}