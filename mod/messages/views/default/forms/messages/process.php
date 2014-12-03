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

echo elgg_view('input/submit', array(
	'value' => elgg_echo('delete'),
	'name' => 'delete',
	'class' => 'elgg-button-delete',
	'title' => elgg_echo('deleteconfirm:plural'),
	'data-confirm' => elgg_echo('deleteconfirm:plural')
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
