<?php
/**
 * Search for content in this project
 *
 * @uses vars['entity'] ElggGroup
 */

$url = elgg_get_site_url() . 'search';
$body = elgg_view_form('projects/search', array(
	'action' => $url,
	'method' => 'get',
	'disable_security' => true,
), $vars);

echo elgg_view_module('aside', elgg_echo('projects:search_in_project'), $body);
