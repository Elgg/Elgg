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
	'class' => 'elgg-widget-collapse-button',
//	'internalid' => "elgg-toggler-widget-$widget->guid"
//	'internalid' => "elgg-widget-collapse-button-$widget->guid"
);
$collapse_link = elgg_view('output/url', $params);

$delete_link = $edit_link = '';
if ($widget->canEdit()) {
	$params = array(
		'text' => ' ',
		'title' => elgg_echo('widget:delete', array($widget->getTitle())),
		'href' => elgg_get_site_url() . "action/widgets/delete?guid=$widget->guid",
		'is_action' => true,
		'class' => 'elgg-widget-delete-button',
		'internalid' => "elgg-widget-delete-button-$widget->guid"
	);
	$delete_link = elgg_view('output/url', $params);

	$params = array(
		'text' => ' ',
		'title' => elgg_echo('widget:edit'),
		'href' => "#",
		'class' => 'elgg-widget-edit-button elgg-toggle',
		'internalid' => "elgg-toggler-widget-$widget->guid"
	);
	$edit_link = elgg_view('output/url', $params);
}

echo <<<___END
<div class="elgg-widget-controls">
	$collapse_link
	$delete_link
	$edit_link
</div>
___END;
