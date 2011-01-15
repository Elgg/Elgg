<?php
/**
 * Compse message form
 *
 * @package ElggMessages
 * @uses $vars['friends']
 */

$recipient_guid = elgg_get_array_value('recipient_guid', $vars, 0);
$subject = elgg_get_array_value('subject', $vars, '');
$body = elgg_get_array_value('body', $vars, '');

$recipients_options = array();
foreach ($vars['friends'] as $friend) {
	$recipients_options[$friend->guid] = $friend->name;
}

$recipient_drop_down = elgg_view('input/pulldown', array(
	'internalname' => 'recipient_guid',
	'value' => $recipient_guid,
	'options_values' => $recipients_options,
));

?>
<p>
	<label><?php echo elgg_echo("messages:to"); ?>: </label>
	<?php echo $recipient_drop_down; ?>
</p>
<p>
	<label><?php echo elgg_echo("messages:title"); ?>: <br /></label>
	<?php echo elgg_view('input/text', array(
		'internalname' => 'subject',
		'value' => $subject,
	));
	?>
</p>
<p>
	<label><?php echo elgg_echo("messages:message"); ?>:</label>
	<?php echo elgg_view("input/longtext", array(
		'internalname' => 'body',
		'value' => $body,
	));
	?>
</p>
<p>
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('messages:send'))); ?>
</p>
