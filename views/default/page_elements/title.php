<?php
/**
 * Elgg title element
 * @uses $vars['title'] The page title
 */

$page_owner = page_owner();
$page_owner_user = get_entity($page_owner);

$submenu = get_submenu(); // elgg_view('canvas_header/submenu');
if (!empty($submenu)) {
	$submenu = "<ul class='submenu extended'>" . $submenu . "</ul>";
}

if (($_SESSION['guid']) && ($page_owner && $page_owner_user->guid != $_SESSION['user']->getGUID())) {
	$info = "<h2>" . $vars['title'] . "</h2>";
	if($page_owner_user instanceOf ElggGroup) {
		$display = $info;
	} else {
		$display = $info;
	}
	if (!empty($submenu) && $vars['submenu'] == true) {
		// plugins can extend this to add menu options
		$display .= $submenu;
	}
} else {
	$info = "<h2>" . $vars['title'] . "</h2>";
	if($page_owner_user instanceOf ElggGroup) {
		$display = $info;
	} else {
		$display = $info;
	}
	if (!empty($submenu)  && $vars['submenu'] == true) {
		// plugins can extend this to add menu options
		$display .= $submenu;
	}
}


//print to screen
echo $display;