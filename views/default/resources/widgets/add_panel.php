<?php
/**
 * Widget add panel
 *
 * @uses $vars['context']             (string) The context for this widget layout
 * @uses $vars['owner_guid']          (int) Container limit widgets for
 * @uses $vars['new_widget_column']   (int) The target column for new widgets
 * @uses $vars['new_widget_position'] (string) The target position for new widgets ('top' | 'bottom')
 */

elgg_ajax_gatekeeper();

// restoring context stack
$context_stack = get_input('context_stack');
if (!empty($context_stack) && is_array($context_stack)) {
	elgg_set_context_stack($context_stack);
}

elgg_require_js('resources/widgets/add_panel');

$context = (string) elgg_extract('context', $vars, get_input('context'));
$owner_guid = (int) elgg_extract('owner_guid', $vars, (int) get_input('owner_guid'));
$new_widget_column = elgg_extract('new_widget_column', $vars, get_input('new_widget_column'));
$new_widget_position = elgg_extract('new_widget_position', $vars, get_input('new_widget_position'));

elgg_entity_gatekeeper($owner_guid);

$owner = get_entity($owner_guid);

elgg_set_page_owner_guid($owner->guid);

$widgets = elgg_get_widgets($owner->guid, $context);
$widget_types = elgg_get_widget_types([
	'context' => $context,
	'container' => $owner,
]);
uasort($widget_types, function ($a, $b) {
	return strcmp($a->name, $b->name);
});

$current_handlers = [];
foreach ($widgets as $column_widgets) {
	foreach ($column_widgets as $widget) {
		$current_handlers[] = $widget->handler;
	}
}

$result = elgg_format_element('p', [
	'class' => 'elgg-text-help',
], elgg_echo('widgets:add:description'));

$list_items = '';
foreach ($widget_types as $handler => $widget_type) {
	$class = [];
	// check if widget added and only one instance allowed
	if (!$widget_type->multiple && in_array($handler, $current_handlers)) {
		$class[] = 'elgg-state-unavailable';
	} else {
		$class[] = 'elgg-state-available';
	}
	
	$class[] = $widget_type->multiple ? 'elgg-widget-multiple' : 'elgg-widget-single';
	
	$action = '<div class="elgg-widgets-add-actions elgg-level">';
	if (!$widget_type->multiple) {
		$action .= elgg_format_element('span', ['class' => 'elgg-quiet'], elgg_echo('widget:unavailable'));
	}
	
	$action .= elgg_view('output/url', [
		'class' => ['elgg-button', 'elgg-button-submit', 'elgg-size-small'],
		'text' => elgg_echo('add'),
		'href' => elgg_generate_action_url('widgets/add', [
			'handler' => $handler,
			'page_owner_guid' => $owner_guid,
			'context' => $context,
			'show_access' => elgg_extract('show_access', $vars, get_input('show_access')),
			'default_widgets' => elgg_in_context('default_widgets'),
			'new_widget_column' => $new_widget_column,
			'new_widget_position' => $new_widget_position,
		]),
	]);
	$action .= '</div>';
	
	$description = elgg_format_element('h4', [], $widget_type->name);
	
	if ($widget_type->description) {
		$description .= elgg_format_element('div', ['class' => 'elgg-quiet'], $widget_type->description);
	}

	$description = elgg_format_element('div', [
		'class' => 'elgg-widgets-add-description',
	], $description);

	$item_content = elgg_format_element('div', [
		'class' => 'elgg-level',
	], $description . $action);

	$list_items .= elgg_format_element('li', [
		'class' => $class,
		'data-elgg-widget-type' => $handler,
	], $item_content);
}

$result .= elgg_format_element('ul', [], $list_items);

$search_box = elgg_view('input/text', [
	'name' => 'widget_search',
	'title' => elgg_echo('search'),
	'placeholder' => elgg_echo('search'),
]);

echo elgg_view_module('info', elgg_echo('widgets:add'), $result, [
	'class' => 'elgg-widgets-add-panel',
	'menu' => $search_box,
]);
