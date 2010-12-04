<?php
/**
 * Elgg user display
 *
 * @package Elgg
 * @subpackage Core
 */

if ($vars['full']) {
	echo elgg_view("profile/userdetails",$vars);
} else {
	if (get_input('listtype') == "gallery") {
		echo elgg_view('profile/gallery',$vars);
	} else {
		echo elgg_view("profile/listing",$vars);
	}
}
