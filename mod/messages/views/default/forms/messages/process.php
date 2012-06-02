<?php
/**
 * Messages folder view (inbox, sent)
 *
 * Provides form body for mass deleting messages
 *
 * @uses $vars['list'] List of messages
 * 
 */

$messages = $vars['list'];
if (!$messages) {
	echo elgg_echo('messages:nomessages');
	return true;
}

echo '<div class="messages-container">';
echo $messages;
echo '</div>';

echo '<div class="elgg-foot messages-buttonbank">';

//submit button assigned to a variable
$submit_button = elgg_view('input/submit', array(
	'value' => elgg_echo('delete'),
	'name' => 'delete',
	'class' => 'elgg-button-delete',
));
//confirmation link after which messages are deleted
echo elgg_view("output/confirmlink", array(
	'href' => "#",
	'text' => $submit_button,
	'confirm' => elgg_echo('messages:deleteconfirm:selected'),
	'encode_text' => false,
));


if ($vars['folder'] == "inbox") {
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('messages:markread'),
		'name' => 'read',
	));
}

echo elgg_view('input/button', array(
	'value' => elgg_echo('messages:toggle'),
	'class' => 'elgg-button elgg-button-cancel',
	'id' => 'messages-toggle',
));

echo '</div>';
