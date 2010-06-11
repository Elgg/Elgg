<?php
/**
 * Elgg profile comment wall
 */

if (isloggedin()) {
	echo elgg_view("profile/commentwall/commentwalladd");
}

echo elgg_view("profile/commentwall/commentwall", array('annotation' => $vars['comments']));
