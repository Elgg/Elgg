<?php
/**
 * Elgg personal notifications
 *
 * @uses $vars['user'] ElggUser that owns the notification settings
 */

// @todo is this a view for extensions?
echo elgg_view('subscriptions/form/additions', $vars);

$form_vars = array('class' => 'elgg-form-alt');
echo elgg_view_form('notificationsettings/save', $form_vars, $vars);
