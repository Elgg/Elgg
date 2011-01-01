<?php
/**
 * Members sidebar
 */

// Tag search
$params = array(
	'method' => 'get',
	'action' => elgg_get_site_url() . 'pg/members/search/tag/',
	'disable_security' => true,
);
$body = elgg_view_form('members/tag_search', $params);

$params = array(
	'title' => elgg_echo('members:searchtag'),
	'body' => $body,
	'class' => 'elgg-aside-module',
);
echo elgg_view('layout/objects/module', $params);


// name search
$params = array(
	'method' => 'get',
	'action' => elgg_get_site_url() . 'pg/members/search/name/',
	'disable_security' => true,
);
$body = elgg_view_form('members/name_search', $params);

$params = array(
	'title' => elgg_echo('members:searchname'),
	'body' => $body,
	'class' => 'elgg-aside-module',
);
echo elgg_view('layout/objects/module', $params);
