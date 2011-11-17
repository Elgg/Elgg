<?php
/**
 * Formats and list an unvalidated user.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail.Administration
 */

$user = elgg_extract('user', $vars);

$checkbox = elgg_view('input/checkbox', array(
	'name' => 'user_guids[]',
	'value' => $user->guid,
	'default' => false,
));

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
$menu = 'test';
$block = <<<___END
	<label>$user->username: "$user->name" &lt;$user->email&gt;</label>
	<div class="uservalidationbyemail-unvalidated-user-details">
		$created
	</div>
___END;

$menu = <<<__END
	<ul class="elgg-menu elgg-menu-general elgg-menu-hz float-alt">
		<li>$resend_email</li><li>$validate</li><li>$delete</li>
	</ul>
__END;

echo elgg_view_image_block($checkbox, $block, array('image_alt' => $menu));
