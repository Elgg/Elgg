<?php

/**
 * Outputs object summary menu
 *
 * @uses $vars['menu']    Menu
 * @uses $vars['entity']  Entity
 */

$entity = elgg_extract('entity', $vars);
$menu = elgg_extract('menu', $vars);
if (!isset($menu)) {
	$params = $vars;
	$params['class'] = 'elgg-menu-hz';
	$params['sort_by'] = 'prioriryt';
	$menu = elgg_view_menu('list_item', $params);
}

if (!$menu) {
	return;
}
?>
<div class="elgg-listing-summary-menu"><?= $menu ?></div>