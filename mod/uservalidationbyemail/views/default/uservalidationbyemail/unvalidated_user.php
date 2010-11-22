<?php
/**
 * Formats and list an unvalidated user.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$user = (isset($vars['user'])) ? $vars['user'] : NULL;

if (!$user) {
	return;
}

// doesn't work.
//$checkbox = elgg_view('input/checkboxes', array(
//	'internalname' => 'user_guids',
//	'options' => array("$user->username - \"$user->name\" &lt;$user->email&gt;" => $user->guid)
//));
$checkbox = "<label><input type=\"checkbox\" value=\"$user->guid\" class=\"input_checkboxes\" name=\"user_guids[]\">"
	. "$user->username - \"$user->name\" &lt;$user->email&gt;</label>";

$created = sprintf(elgg_echo('uservalidationbyemail:admin:user_created'), elgg_view_friendly_time($user->time_created));

$validate = elgg_view('output/confirmlink', array(
	'confirm' => sprintf(elgg_echo('uservalidationbyemail:confirm_validate_user'), $user->username),
	'href' => $vars['url'] . "action/uservalidationbyemail/validate/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyemail:admin:validate')
));

$resend_email = elgg_view('output/confirmlink', array(
	'confirm' => sprintf(elgg_echo('uservalidationbyemail:confirm_resend_validation'), $user->username),
	'href' => $vars['url'] . "action/uservalidationbyemail/resend_validation/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyemail:admin:resend_validation')
));

$delete = elgg_view('output/confirmlink', array(
	'confirm' => sprintf(elgg_echo('uservalidationbyemail:confirm_delete'), $user->username),
	'href' => $vars['url'] . "action/uservalidationbyemail/delete/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyemail:admin:delete')
));

?>

<div class="uvbe_unvalided_user">
	<?php echo $checkbox; ?><br />

	<div class="uvbe_admin_controls">
		<?php echo "$resend_email | $validate | $delete"; ?>
	</div>

	<div class="uvbe_unvalidated_user_details">
		<?php echo $created; ?>
	</div>
</div>
