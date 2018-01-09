<?php
/**
 * Widget add panel
 *
 * @uses $vars['context']     The context for this widget layout
 * @uses $vars['owner_guid']  Container limit widgets for
 */

elgg_ajax_gatekeeper();

// restoring context stack
$context_stack = get_input('context_stack');
if (!empty($context_stack) && is_array($context_stack)) {
	elgg_set_context_stack($context_stack);
}

elgg_require_js('resources/widgets/add_panel');

$context = elgg_extract('context', $vars, get_input('context'));
$owner_guid = (int) elgg_extract('owner_guid', $vars, (int) get_input('owner_guid'));
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
	$add_link = elgg_http_add_url_query_elements('action/widgets/add', [
		'handler' => $handler,
		'page_owner_guid' => $owner_guid,
		'context' => $context,
		'show_access' => elgg_extract('show_access', $vars),
		'default_widgets' => elgg_in_context('default_widgets'),
	]);

	$action .= elgg_view('output/url', [
		'class' => 'elgg-button elgg-button-submit elgg-size-small',
		'text' => elgg_echo('add'),
		'href' => $add_link,
		'is_action' => true,
	]);
	$action .= '</div>';

	$description = "<h4>{$widget_type->name}</h4>";

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

$result .= "<ul>$list_items</ul>";

echo elgg_view_module('aside', elgg_echo('widgets:add'), $result, ['class' => 'elgg-widgets-add-panel']);
