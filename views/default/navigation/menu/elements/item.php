<?php

$item = $vars['item'];

$class = '';
if ($item->getSelected()) {
	$class = 'class="selected"';
}

$link_vars = array();

$children = $item->getChildren();
if ($children) {
	$link_vars['class'] = 'elgg-menu-parent elgg-menu-closed';
}

echo "<li $class>";
echo $item->getLink($link_vars);
if ($children) {
	echo elgg_view('navigation/menu/elements/group', array(
		'items' => $children,
		'class' => 'elgg-menu elgg-child-menu',
	));
}
echo '</li>';
