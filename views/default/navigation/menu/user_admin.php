<?php
/**
 * User admin menu
 *
 * @todo css/html clean up needed as this was pulled from early 1.8 profile code
 *
 * @uses vars['menu']
 * @uses vars['toggle']
 */

$toggle = elgg_get_array_value('toggle', $vars, false);

$id = '';

if ($toggle) {
	echo '<ul class="admin_menu">';
	echo '<li><a class="elgg-toggle" id="elgg-toggler-admin-menu">Admin options&hellip;</a>';
	$id = 'id="elgg-togglee-admin-menu"';
}
foreach ($vars['menu'] as $section => $menu_items) {
	echo "<ul class=\"admin_menu_options\" $id>";
	foreach ($menu_items as $menu_item) {
		echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	echo '</ul>';
}
if ($toggle) {
	echo '<li>';
	echo '<ul>';
}