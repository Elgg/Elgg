<?php
/**
 * Formats and list an unvalidated user.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$user = elgg_get_array_value('theuser', $vars);

// doesn't work.
//$checkbox = elgg_view('input/checkboxes', array(
//	'internalname' => 'user_guids',
//	'options' => array("$user->username - \"$user->name\" &lt;$user->email&gt;" => $user->guid)
//));
$checkbox = "<label><input type=\"checkbox\" value=\"$user->guid\" class=\"input-checkboxes\" name=\"user_guids[]\">"
	. "$user->username - \"$user->name\" &lt;$user->email&gt;</label>";

$created = elgg_echo('uservalidationbyemail:admin:user_created', array(elgg_view_friendly_time($user->time_created)));

$validate = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('uservalidationbyemail:confirm_validate_user', array($user->username)),
	'href' => "action/uservalidationbyemail/validate/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyemail:admin:validate')
));

$resend_email = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation', array($user->username)),
	'href' => "action/uservalidationbyemail/resend_validation/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyemail:admin:resend_validation')
));

$delete = elgg_view('output/confirmlink', array(
	'confirm' => elgg_echo('uservalidationbyemail:confirm_delete', array($user->username)),
	'href' => "action/uservalidationbyemail/delete/?user_guids[]=$user->guid",
	'text' => elgg_echo('uservalidationbyemail:admin:delete')
));

// @todo All of these hard coded styles need to be removed.
// they're here because you can't currently extend the admin css.
?>

<div class="admin_settings radius8" style="border: 1px solid black; padding: 5px;">
	<?php echo $checkbox; ?><br />

	<div class="uservalidationbyemail_unvalidated_controls" style="float: right">
		<?php echo "$resend_email | $validate | $delete"; ?>
	</div>

	<div class="uservalidationbyemail_unvalidated_user_details" style="margin-left: 15px; font-size: smaller;">
		<?php echo $created; ?>
	</div>
</div>
