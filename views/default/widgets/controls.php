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
<ul>
	<li>$delete_link</li>
	<li>$edit_link</li>
</ul>
___END;
