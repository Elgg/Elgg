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
	'wheres' => uservalidationbyemail_get_unvalidated_users_sql_where(),
	'limit' => $limit,
	'offset' => $offset,
	'count' => TRUE,
);
$count = elgg_get_entities($options);

if (!$count) {
	access_show_hidden_entities($hidden_entities);
	elgg_set_ignore_access($ia);

	echo autop(elgg_echo('uservalidationbyemail:admin:no_unvalidated_users'));
	return TRUE;
}

$options['count']  = FALSE;

$users = elgg_get_entities($options);

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'baseurl' => $vars['url'] . '/uservalidationbyemail/admin',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

echo $pagination;

$bulk_actions_checkbox = '<label><input type="checkbox" class="unvalidated-users-checkall" />'
	. elgg_echo('uservalidationbyemail:check_all') . '</label>';

$validate = elgg_view('output/url', array(
	'is_action' => TRUE,
	'js' => 'title="' . elgg_echo('uservalidationbyemail:confirm_validate_checked') . '"',
	'href' => $vars['url'] . "action/uservalidationbyemail/validate/",
	'text' => elgg_echo('uservalidationbyemail:admin:validate'),
	'class' => 'unvalidated-users-bulk-post',
));

$resend_email = elgg_view('output/url', array(
	'is_action' => TRUE,
	'js' => 'title="' . elgg_echo('uservalidationbyemail:confirm_resend_validation_checked') . '"',
	'href' => $vars['url'] . "action/uservalidationbyemail/resend_validation/",
	'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
	'class' => 'unvalidated-users-bulk-post',
));

$delete = elgg_view('output/url', array(
	'is_action' => TRUE,
	'js' => 'title="' . elgg_echo('uservalidationbyemail:confirm_delete_checked') . '"',
	'href' => $vars['url'] . "action/uservalidationbyemail/delete/",
	'text' => elgg_echo('uservalidationbyemail:admin:delete'),
	'class' => 'unvalidated-users-bulk-post',
));

$bulk_actions = <<<___END
<div class="uvbe_bulk_actions">
	<div class="uvbe_admin_controls">
		$resend_email | $validate | $delete
	</div>

	$bulk_actions_checkbox
</div>
___END;

//$bulk_actions = elgg_view('page_elements/contentwrapper', array('body' => $bulk_actions));

echo $bulk_actions;


foreach ($users as $user) {
	echo elgg_view('uservalidationbyemail/unvalidated_user', array('user' => $user));
}

if ($count > 5) {
	echo $bulk_actions;
}

echo $pagination;