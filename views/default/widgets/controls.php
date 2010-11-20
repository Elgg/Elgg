<?php
/**
 * Elgg widget controls
 *
 * @package Elgg
 * @subpackage Core
 */

$widget = $vars['widget'];

$params = array(
	'text' => ' ',
	'href' => "#",
	'class' => 'widget_collapse_button',
	'internalid' => "widget_collapse_button_$widget->guid"
);
$collapse_link = elgg_view('output/url', $params);

$params = array(
	'text' => ' ',
	'title' => elgg_echo('widget:delete', array($widget->getTitle())),
	'href' => elgg_get_site_url() . "action/widgets/delete?guid=$widget->guid",
	'is_action' => true,
	'class' => 'widget_delete_button',
	'internalid' => "widget_delete_button_$widget->guid"
);
$delete_link = elgg_view('output/url', $params);

$params = array(
	'text' => ' ',
	'title' => elgg_echo('widget:edit'),
	'href' => "#",
	'class' => 'widget_edit_button',
	'internalid' => "widget_edit_button_$widget->guid"
);
$edit_link = elgg_view('output/url', $params);

echo <<<___END
<div class="widget_controls">
	$collapse_link
	$delete_link
	$edit_link
</div>
___END;
