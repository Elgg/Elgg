<?php
/**
 * Admin sidebar menu
 */

$content = elgg_view_menu('page', array('sort_by' => 'name'));

echo elgg_view('layout/objects/module', array(
	'title' => elgg_echo('admin:menu'),
	'body' => $content,
	'class' => 'elgg-module-main',
));
