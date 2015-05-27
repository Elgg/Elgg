<?php
/**
 * Members index
 *
 * @uses $vars['page'] The URL segment after members/
 */

$tabs = elgg_trigger_plugin_hook('members:config', 'tabs', null, array());

foreach ($tabs as $type => $values) {
	$tabs[$type]['selected'] = ($vars['page'] == $type);
}
$filter = elgg_view('navigation/tabs', array('tabs' => $tabs));

$params = array(
	'options' => array('type' => 'user', 'full_view' => false),
);

$content = elgg_trigger_plugin_hook('members:list', $vars['page'], $params, null);
if ($content === null) {
	forward('', '404');
}

$title = elgg_echo("members:title:{$vars['page']}");

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'title' => $title,
	'filter' => $filter,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
