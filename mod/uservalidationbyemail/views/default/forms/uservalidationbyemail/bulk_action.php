<?php
/**
 * Admin area to view, validate, resend validation email, or delete unvalidated users.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

// can't use elgg_list_entities() and friends because we don't use the default view for users.
$ia = elgg_set_ignore_access(TRUE);
$hidden_entities = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

$options = array(
	'type' => 'user',
	'metadata_name' => 'validated',
	'metadata_value' => 0,
	'limit' => $limit,
	'offset' => $offset
);
$users = elgg_get_entities_from_metadata($options);

$options['count'] = TRUE;
$count = elgg_get_entities_from_metadata($options);

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);

// setup pagination
$pagination = elgg_view('navigation/pagination', array(
	'baseurl' => elgg_get_site_url() . 'pg/admin/users/unvalidated',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

echo $pagination;

if ($users) {
	foreach ($users as $user) {
		$form_body .= elgg_view('uservalidationbyemail/unvalidated_user', array('theuser' => $user));
	}
} else {
	echo elgg_echo('uservalidationbyemail:admin:no_unvalidated_users');
	return;
}

$form_body .= elgg_echo('uservalidationbyemail:admin:with_checked') . elgg_view('input/dropdown', array(
	'internalname' => 'action_type',
	'options_values' => array(
		'validate' => elgg_echo('uservalidationbyemail:admin:validate'),
		'resend_validation' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
		'delete' => elgg_echo('uservalidationbyemail:admin:delete'),
	),
	'value' => 'resend_validation',
));

$form_body .= '<br />' . elgg_view('input/submit', array('value' => elgg_echo('submit')));

echo $form_body;