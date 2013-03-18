<?php
/**
 * Project search
 *
 * @package Coopfunding
 * @subpackage Projects
 */
$url = elgg_get_site_url() . 'projects/search';
$body = elgg_view_form('projects/find', array(
	'action' => $url,
	'method' => 'get',
	'disable_security' => true,
));

echo elgg_view_module('aside', elgg_echo('projects:searchtag'), $body);
