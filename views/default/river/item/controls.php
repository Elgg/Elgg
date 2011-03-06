<?php
/**
 * Controls on an river item
 *
 * @uses $vars['item']
 */

echo elgg_view_menu('river', array(
	'item' => $vars['item'],
	'sort_by' => 'priority',
));
