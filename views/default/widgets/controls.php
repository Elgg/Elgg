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
	'class' => 'widget-collapse-button',
	'internalid' => "widget-collapse-button_$widget->guid"
);
$collapse_link = elgg_view('output/url', $params);

$params = array(
	'text' => ' ',
	'title' => elgg_echo('widget:delete', array($widget->getTitle())),
	'href' => elgg_get_site_url() . "action/widgets/delete?guid=$widget->guid",
	'is_action' => true,
	'class' => 'widget-delete-button',
	'internalid' => "widget-delete-button_$widget->guid"
);
$delete_link = elgg_view('output/url', $params);

$params = array(
	'text' => ' ',
	'title' => elgg_echo('widget:edit'),
	'href' => "#",
	'class' => 'widget-edit-button',
	'internalid' => "widget-edit-button_$widget->guid"
);
$edit_link = elgg_view('output/url', $params);

echo <<<___END
<div class="widget-controls">
	$collapse_link
	$delete_link
	$edit_link
</div>
___END;
