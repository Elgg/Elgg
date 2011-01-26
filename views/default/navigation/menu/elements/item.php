<?php

$item = $vars['item'];

$class = '';
$link_class = 'elgg-menu-closed';
if ($item->getSelected()) {
	$class = 'class="elgg-state-selected"';
	$link_class = 'elgg-menu-opened';
}

$link_vars = array();

$children = $item->getChildren();
if ($children) {
	$link_vars['class'] = "elgg-menu-parent $link_class";
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
