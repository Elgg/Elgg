<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(__FILE__) . "/engine/start.php");

// allow plugins to override the front page (return true to stop this front page code)
if (elgg_trigger_plugin_hook('index', 'system', null, FALSE) != FALSE) {
	exit;
}

if (isloggedin()) {
	forward('pg/activity/');
}

//Load the front page
$title = elgg_view_title(elgg_echo('content:latest'));
elgg_set_context('search');
$offset = (int)get_input('offset', 0);
$options = array(
		'limit' => 10,
		'offset' => $offset,
		'full_view' => FALSE,
		'allowed_types' => array('object','group')
);

$activity = elgg_list_registered_entities($options);
elgg_set_context('main');

global $autofeed;
$autofeed = FALSE;

// if drop-down login in header option not selected
$login_box = elgg_view('core/account/login_box');

$content = $title . $activity;
$params = array(
		'content' => $content,
		'sidebar' => $login_box
);
$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page(null, $body);
