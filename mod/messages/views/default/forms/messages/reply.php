<?php
/**
 * Reply form
 *
 * @uses $vars['message']
 */

// fix for RE: RE: RE: that builds on replies
$reply_title = $vars['message']->title;
if (strncmp($reply_title, "RE:", 3) != 0) {
	$reply_title = "RE: " . $reply_title;
}

$username = '';
$user = get_user($vars['message']->fromId);
if ($user) {
	$username = $user->username;
}

echo elgg_view('input/hidden', array(
	'name' => 'recipient_username',
	'value' => $username,
));

echo elgg_view('input/hidden', array(
	'name' => 'original_guid',
	'value' => $vars['message']->guid,
));
?>

<div>
	<label><?php echo elgg_echo("messages:title"); ?>: <br /></label>
	<?php echo elgg_view('input/text', array(
		'name' => 'subject',
		'value' => $reply_title,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo("messages:message"); ?>:</label>
	<?php echo elgg_view("input/longtext", array(
		'name' => 'body',
		'value' => '',
	));
	?>
</div>
<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('send'))); ?>
</div>
