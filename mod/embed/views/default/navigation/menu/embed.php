<?php
/**
 * Embed tabs
 *
 * @uses $vars['menu']['select']
 * @uses $vars['menu']['upload']
 */

$tabs = array();
foreach (array('select', 'upload') as $type) {
	foreach ($vars['menu'][$type] as $menu_item) {
		$tabs[] = array(
			'title' => $menu_item->getText(),
			'url' => 'embed/tab/' . $menu_item->getName(),
			'url_class' => 'embed-section',
			'selected' => $menu_item->getSelected(),
		);
	}
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
