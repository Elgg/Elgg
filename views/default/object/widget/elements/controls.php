<?php
/**
 * Elgg widget controls
 *
 * @uses $vars['widget']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */

$widget = $vars['widget'];
$show_edit = elgg_extract('show_edit', $vars, true);

$params = array(
	'text' => ' ',
	'href' => "#elgg-widget-content-$widget->guid",
	'class' => 'elgg-widget-collapse-button elgg-toggler',
);
$collapse_link = elgg_view('output/url', $params);

$delete_link = $edit_link = '';
if ($widget->canEdit()) {
	$params = array(
		'text' => elgg_view_icon('delete-alt'),
		'title' => elgg_echo('widget:delete', array($widget->getTitle())),
		'href' => "action/widgets/delete?guid=$widget->guid",
		'is_action' => true,
		'class' => 'elgg-widget-delete-button',
		'id' => "elgg-widget-delete-button-$widget->guid"
	);
	$delete_link = elgg_view('output/url', $params);

	if ($show_edit) {
		$params = array(
			'text' => elgg_view_icon('settings-alt'),
			'title' => elgg_echo('widget:edit'),
			'href' => "#widget-edit-$widget->guid",
			'class' => "elgg-toggler elgg-widget-edit-button",
		);
		$edit_link = elgg_view('output/url', $params);
	}
}

echo <<<___END
	$collapse_link
	$delete_link
	$edit_link
___END;
