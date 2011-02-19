<?php
/**
 * Elgg messageboard widget view
 *
 *
 * @package ElggMessageBoard
 */

$user = elgg_get_page_owner_entity();
$num_display = 5;

if (isset($vars['entity']->num_display)) {
	$num_display = $vars['entity']->num_display;
}

if (elgg_is_logged_in()) {
	echo elgg_view_form('messageboard/add', array('class' => 'elgg-messageboard'));
}

//this for the first time the page loads, grab the latest messages.
$contents = $user->getAnnotations('messageboard', $num_display, 0, 'desc');

if ($contents) {
	echo elgg_view('messageboard/messageboard', array('annotation' => $contents));
}