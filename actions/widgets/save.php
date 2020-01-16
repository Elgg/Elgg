<?php
/**
 * Elgg save widget settings action
 *
 * @uses int    $_REQUEST['guid']            The guid of the widget to save
 * @uses array  $_REQUEST['params']          An array of params to set on the widget.
 * @uses int    $_REQUEST['default_widgets'] Flag for if these settings are for default wigets.
 * @uses string $_REQUEST['context']         An optional context of the widget. Used to return
 *                                           the correct output if widget content changes
 *                                           depending on context.
 */

$guid = (int) get_input('guid');
$params = (array) get_input('params');
$default_widgets = (int) get_input('default_widgets', 0);
$context = get_input('context');

$widget = get_entity($guid);
if (!($widget instanceof \ElggWidget) || !$widget->saveSettings($params)) {
	return elgg_error_response(elgg_echo('widgets:save:failure'));
}

$context_stack = [];

if ($default_widgets) {
	$context_stack[] = 'default_widgets';
}
$context_stack[] = 'widgets';
if ($context) {
	$context_stack[] = $context;
}

foreach ($context_stack as $ctx) {
	elgg_push_context($ctx);
}

elgg_set_page_owner_guid($widget->getContainerGUID());

$output = [
	'content' => elgg_view('object/widget/elements/content', ['entity' => $widget]),
	'title' => $widget->getDisplayName(),
	'href' => $widget->getURL(),
];
foreach ($context_stack as $ctx) {
	elgg_pop_context();
}

return elgg_ok_response($output);
