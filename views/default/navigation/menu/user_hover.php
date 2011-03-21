<?php
/**
 * User hover menu
 *
 * Register for the 'register', 'menu:user_hover' plugin hook to add to the user
 * hover menu. There are three sections: action, default, and admin.
 *
 * @uses $vars['menu']      Menu array provided by elgg_view_menu()
 */

$user = $vars['entity'];
$actions = elgg_extract('action', $vars['menu'], null);
$main = elgg_extract('default', $vars['menu'], null);
$admin = elgg_extract('admin', $vars['menu'], null);

echo '<ul class="elgg-menu elgg-menu-hover">';

// name and username
$name_link = elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => "<span class=\"elgg-heading-basic\">$user->name</span>&#64;$user->username",
));
echo "<li>$name_link</li>";

// actions
if (elgg_is_logged_in() && $actions) {
	echo '<li>';
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => "elgg-menu elgg-menu-hover-actions",
		'items' => $actions,
	));
	echo '</li>';
}

// main
if ($main) {
	echo '<li>';
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-hover-default',
		'items' => $main,
	));
	
	echo '</li>';
}

// admin
if (elgg_is_admin_logged_in() && $admin) {
	echo '<li>';
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-hover-admin',
		'items' => $admin,
	));
	
	echo '</li>';
}

echo '</ul>';
