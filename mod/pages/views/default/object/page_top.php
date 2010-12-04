<?php
/**
 * View for page object
 *
 * @package ElggPages
 */

if ($vars['full']) {
	echo elgg_view("pages/pageprofile", $vars);
} else {
	if (get_input('listtype') == "gallery") {
		echo elgg_view('pages/pagegallery', $vars);
	} else {
		echo elgg_view("pages/pagelisting", $vars);
	}
}