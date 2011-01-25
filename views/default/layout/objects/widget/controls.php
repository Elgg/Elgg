<?php
/**
 * Elgg widget controls
 *
 * @uses $vars['widget']
 * @uses $vars['show_edit'] Whether to show the edit button (true)
 */

$widget = $vars['widget'];
$show_edit = elgg_get_array_value('show_edit', $vars, true);

$params = array(
	'text' => ' ',
	'href' => "#",
	'class' => 'elgg-widget-collapse-button',
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

	if ($show_edit) {
		$params = array(
			'text' => ' ',
			'title' => elgg_echo('widget:edit'),
			'href' => "#",
			'class' => 'elgg-widget-edit-button elgg-toggle',
			'internalid' => "elgg-toggler-widget-$widget->guid"
		);
		$edit_link = elgg_view('output/url', $params);
	}
}

echo <<<___END
	$collapse_link
	$delete_link
	$edit_link
___END;
