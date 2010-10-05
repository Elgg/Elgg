<?php
/**
 * Admin area to view, validate, resend validation email, or delete unvalidated users.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

// @todo pagination would be nice.
// can't use elgg_list_entities() and friends because we don't use the default view for users.
$users = elgg_get_entities(array(
	'type' => 'user',
	'wheres' => array(uservalidationbyemail_get_unvalidated_users_sql_where()),
	'limit' => 9999,
));

if ($users) {
	foreach ($users as $user) {
		$form_body .= elgg_view('uservalidationbyemail/unvalidated_user', array('user' => $user));
	}
} else {
	echo elgg_echo('uservalidationbyemail:admin:no_unvalidated_users');
	return;
}

$form_body .= elgg_echo('uservalidationbyemail:admin:with_checked') . elgg_view('input/pulldown', array(
	'internalname' => 'action_type',
	'options_values' => array(
		'validate' => elgg_echo('uservalidationbyemail:admin:validate'),
		'resend_validation' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
		'delete' => elgg_echo('uservalidationbyemail:admin:delete'),
	)
));

$form_body .= '<br />' . elgg_view('input/button', array('value' => elgg_echo('submit')));

echo elgg_view('input/form', array(
	'action' => $vars['url'] . 'action/uservalidationbyemail/bulk_action',
	'body' => $form_body
));