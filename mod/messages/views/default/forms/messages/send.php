<?php
/**
 * Compose message form
 *
 * @package ElggMessages
 * @uses $vars['friends']
 */

$recipient_guid = elgg_extract('recipient_guid', $vars, 0);
$subject = elgg_extract('subject', $vars, '');
$body = elgg_extract('body', $vars, '');

$recipient = get_entity($recipient_guid);
if (elgg_instanceof($recipient, 'user')) {
	$recipient_username = $recipient->username;
}

$recipient_autocomplete = elgg_view('input/autocomplete', array(
	'name' => 'recipient_username',
	'value' => $recipient_username,
	'match_on' => array('users'),
));

?>
<div>
	<label><?php echo elgg_echo("messages:to"); ?>: </label>
	<?php echo $recipient_autocomplete; ?>
	<span class="elgg-text-help"><?php echo elgg_echo("messages:to:help"); ?></span>
	
</div>
<div>
	<label><?php echo elgg_echo("messages:title"); ?>: <br /></label>
	<?php echo elgg_view('input/text', array(
		'name' => 'subject',
		'value' => $subject,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo("messages:message"); ?>:</label>
	<?php echo elgg_view("input/longtext", array(
		'name' => 'body',
		'value' => $body,
	));
	?>
</div>
<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('messages:send'))); ?>
</div>
