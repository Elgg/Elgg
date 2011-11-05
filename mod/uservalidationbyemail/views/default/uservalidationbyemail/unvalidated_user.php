<?php
/**
 * Formats and list an unvalidated user.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$user = elgg_extract('user', $vars);

// doesn't work.
//$checkbox = elgg_view('input/checkboxes', array(
//	'name' => 'user_guids',
//	'options' => array("$user->username - \"$user->name\" &lt;$user->email&gt;" => $user->guid)
//));
$checkbox = "<label><input type=\"checkbox\" value=\"$user->guid\" class=\"elgg-input-checkboxes\" name=\"user_guids[]\" />"
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

$block = <<<___END
<div class="admin-settings radius8 pas baa">
	$checkbox<br />

	<ul class="uservalidationbyemail-controls right">
		<li>$resend_email</li><li>$validate</li><li>$delete</li>
	</ul>

	<div class="uservalidationbyemail-unvalidated-user-details mll small">
		$created
	</div>
</div>
___END;

echo elgg_view_image_block('', $block);
