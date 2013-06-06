<?php
/**
 * Project social
 *
 * @package Coopfunding
 * @subpackage Projects.Social
 */

foreach (elgg_get_config('projects_social') as $share) {
	if (elgg_view_exists("projects_social/share/$share")) {
		$body .= elgg_view("projects_social/share/$share", $vars);
	}
}

echo elgg_view_module('aside', elgg_echo('projects:social'), $body);
