<?php
/**
 * Admin control panel widget
 */

echo elgg_view_menu('admin_control_panel', [
	'class' => 'elgg-menu-hz',
	'item_class' => 'mrm',
	'items' => $items,
]);
