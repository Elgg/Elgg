<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 * @uses $vars['menu']['more']
 */

echo '<ul class="elgg-menu elgg-menu-site clearfix">';
foreach ($vars['menu']['default'] as $menu_item) {
	echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
}

if (isset($vars['menu']['more'])) {
	$more = elgg_echo('more');
	$arrow = elgg_view_icon('arrow-s');
	echo '<li class="elgg-more">';
	echo "<a class='subnav' title=\"$more\">$arrow $more</a>";
	echo '<ul>';
	foreach ($vars['menu']['more'] as $menu_item) {
		echo elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
	}
	echo '</ul>';
	echo '</li>';
}
echo '</ul>';
