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

echo '<ul class="elgg-menu elgg-menu-hover dropdown-menu nav flex-column">';

// name and username
$name_link = elgg_view('output/url', [
	'href' => $user->getURL(),
	'text' => "<span class=\"elgg-heading-basic d-block\">$user->name</span><span class=\"d-block\">&#64;$user->username</span>",
	'is_trusted' => true,
	'class' => 'nav-link',
]);
echo "<li class=\"nav-item\">$name_link</li>";

// actions
if (elgg_is_logged_in() && $actions) {
	echo '<li class="dropdown-divider"></li>';
	echo '<li class="nav-item">';
	
	echo elgg_view('navigation/menu/elements/section', [
		'class' => "elgg-menu elgg-menu-hover-actions nav flex-column",
		'items' => $actions,
	]);
	echo '</li>';
}

// main
if ($main) {
	echo '<li class="dropdown-divider"></li>';

	echo '<li class="nav-item">';
	
	echo elgg_view('navigation/menu/elements/section', [
		'class' => 'elgg-menu elgg-menu-hover-default nav flex-column',
		'items' => $main,
	]);
	
	echo '</li>';
}

// admin
if (elgg_is_admin_logged_in() && $admin) {
	echo '<li class="dropdown-divider"></li>';
	echo '<li class="nav-item">';
	
	echo elgg_view('navigation/menu/elements/section', [
		'class' => 'elgg-menu elgg-menu-hover-admin nav flex-column',
		'items' => $admin,
	]);
	
	echo '</li>';
}

echo '</ul>';
