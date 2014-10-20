<?php
/**
 * Admin area to view, validate, resend validation email, or delete unvalidated users.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$limit = get_input('limit', elgg_get_config('default_limit'));
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

	echo elgg_autop(elgg_echo('uservalidationbyemail:admin:no_unvalidated_users'));
	return TRUE;
}

$options['count']  = FALSE;

$users = elgg_get_entities($options);

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'base_url' => 'admin/users/unvalidated',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

$bulk_actions_checkbox = '<label><input type="checkbox" id="uservalidationbyemail-checkall" />'	. elgg_echo('all') . '</label>';

$validate = elgg_view('output/url', array(
	'href' => 'action/uservalidationbyemail/validate/',
	'text' => elgg_echo('uservalidationbyemail:admin:validate'),
	'title' => elgg_echo('uservalidationbyemail:confirm_validate_checked'),
	'class' => 'uservalidationbyemail-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$resend_email = elgg_view('output/url', array(
	'href' => 'action/uservalidationbyemail/resend_validation/',
	'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
	'title' => elgg_echo('uservalidationbyemail:confirm_resend_validation_checked'),
	'class' => 'uservalidationbyemail-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$delete = elgg_view('output/url', array(
	'href' => 'action/uservalidationbyemail/delete/',
	'text' => elgg_echo('delete'),
	'title' => elgg_echo('uservalidationbyemail:confirm_delete_checked'),
	'class' => 'uservalidationbyemail-submit',
	'is_action' => true,
	'is_trusted' => true,
));

$bulk_actions = <<<___END
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$resend_email</li><li>$validate</li><li>$delete</li>
	</ul>

	$bulk_actions_checkbox
___END;

if (is_array($users) && count($users) > 0) {
	$html = '<ul class="elgg-list elgg-list-distinct">';
	foreach ($users as $user) {
		$html .= "<li id=\"unvalidated-user-{$user->guid}\" class=\"elgg-item uservalidationbyemail-unvalidated-user-item\">";
		$html .= elgg_view('uservalidationbyemail/unvalidated_user', array('user' => $user));
		$html .= '</li>';
	}
	$html .= '</ul>';
}

echo <<<___END
<div class="elgg-module elgg-module-inline uservalidation-module">
	<div class="elgg-head">
		$bulk_actions
	</div>
	<div class="elgg-body">
		$html
	</div>
</div>
___END;

if ($count > 5) {
	echo $bulk_actions;
}

echo $pagination;
