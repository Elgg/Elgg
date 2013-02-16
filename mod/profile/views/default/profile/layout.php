<?php
/**
 * Profile layout
 * 
 * @uses $vars['entity']  The user
 */

// main profile page
$params = array(
	'content' => elgg_view('profile/wrapper'),
	'num_columns' => 3,
);
echo elgg_view_layout('widgets', $params);
