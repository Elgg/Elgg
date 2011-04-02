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

if (!$count = elgg_get_entities($options)) {
	access_show_hidden_entities($hidden_entities);
	elgg_set_ignore_access($ia);

	echo elgg_view('page_elements/contentwrapper', array(
		'body' => elgg_echo('uservalidationbyemail:admin:no_unvalidated_users')
	));
	return;
}

$options['count']  = FALSE;

$users = elgg_get_entities($options);

access_show_hidden_entities($hidden_entities);
elgg_set_ignore_access($ia);

// setup pagination
$pagination = elgg_view('navigation/pagination',array(
	'baseurl' => $vars['url'] . '/pg/uservalidationbyemail/admin/',
	'offset' => $offset,
	'count' => $count,
	'limit' => $limit,
));

echo $pagination;

$bulk_actions_checkbox = '<label><input type="checkbox" class="unvalidated_users_checkall" />'
	. elgg_echo('uservalidationbyemail:check_all') . '</label>';

$validate = elgg_view('output/url', array(
	'is_action' => TRUE,
	'js' => 'title="' . elgg_echo('uservalidationbyemail:confirm_validate_checked') . '"',
	'href' => $vars['url'] . "action/uservalidationbyemail/validate/",
	'text' => elgg_echo('uservalidationbyemail:admin:validate'),
	'class' => 'unvalidated_users_bulk_post',
));

$resend_email = elgg_view('output/url', array(
	'is_action' => TRUE,
	'js' => 'title="' . elgg_echo('uservalidationbyemail:confirm_resend_validation_checked') . '"',
	'href' => $vars['url'] . "action/uservalidationbyemail/resend_validation/",
	'text' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
	'class' => 'unvalidated_users_bulk_post',
));

$delete = elgg_view('output/url', array(
	'is_action' => TRUE,
	'js' => 'title="' . elgg_echo('uservalidationbyemail:confirm_delete_checked') . '"',
	'href' => $vars['url'] . "action/uservalidationbyemail/delete/",
	'text' => elgg_echo('uservalidationbyemail:admin:delete'),
	'class' => 'unvalidated_users_bulk_post',
));

$bulk_actions = <<<___END
<div class="uvbe_bulk_actions">
	<div class="uvbe_admin_controls">
		$resend_email | $validate | $delete
	</div>

	$bulk_actions_checkbox
</div>
___END;

$bulk_actions = elgg_view('page_elements/contentwrapper', array('body' => $bulk_actions));

echo $bulk_actions;

foreach ($users as $user) {
	$form_body .= elgg_view('uservalidationbyemail/unvalidated_user', array('user' => $user));
}

$form_body = elgg_view("page_elements/contentwrapper", array('body' => $form_body));

echo elgg_view('input/form', array(
	'action' => $vars['url'] . 'action/uservalidationbyemail/bulk_action',
	'body' => $form_body,
	'internalname' => 'unvalidated_users',
));

if ($count > 5) {
	echo $bulk_actions;
}

echo $pagination;

?>

<script type="text/javascript">
$(function() {
	$('.unvalidated_users_checkall').click(function() {
		checked = $(this).attr('checked');
		$('form[name=unvalidated_users]').find('input[type=checkbox]').attr('checked', checked);
	});

	$('.unvalidated_users_bulk_post').click(function(event) {
		event.preventDefault();

		// check if there are selected users
		if ($('form[name=unvalidated_users]').find('input[type=checkbox]:checked').length < 1) {
			return false;
		}

		// confirmation
		if (!confirm($(this).attr('title'))) {
			return false;
		}

		$('form[name=unvalidated_users]').attr('action', $(this).attr('href')).submit();
	});
});

</script>