<?php

$layout_name = elgg_extract('layout', $vars, 'default', false);
unset($vars['layout']);

$page_title = elgg_extract('page_title', $vars, '', false);
unset($vars['page_title']);

$page_shell = elgg_extract('page_shell', $vars, 'default', false);
unset($vars['page_shell']);

$entity = elgg_extract('entity', $vars);
$page_owner = elgg_extract('page_owner', $vars);

$layout = elgg_view_layout($layout_name, $vars);

echo elgg_view_page($page_title, $layout, $page_shell, [
	'entity' => $entity,
	'page_owner' => $page_owner,
]);