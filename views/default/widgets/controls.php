<?php
/**
 * Elgg widget controls
 *
 * @package Elgg
 * @subpackage Core
 */

$widget = $vars['widget'];

$params = array(
	'text' => 'delete',
	'href' => '#', //elgg_get_site_url() . "action/widgets/delete?guid=$widget->guid",
	'is_action' => true,
	'class' => 'widget_delete',
	'internalid' => "widget_delete_$widget->guid"
);
$delete_link = elgg_view('output/url', $params);

$params = array(
	'text' => 'edit',
	'href' => elgg_get_site_url() . "#",
	'is_action' => true,
);
$edit_link = elgg_view('output/url', $params);

echo <<<___END
<ul>
	<li>$delete_link</li>
	<li>$edit_link</li>
</ul>
___END;
