<?php
/**
 * Elgg personal notifications
 *
 * @uses $vars['user'] ElggUser
 */

	
echo elgg_view('subscriptions/form/additions', $vars);
	
// Display a description

echo elgg_view_form('notificationsettings/save', array(
	'class' => 'elgg-form-alt',
	'user' => $vars['user']
));
