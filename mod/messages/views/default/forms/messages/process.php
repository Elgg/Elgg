<?php
/**
 * Messages folder view (inbox, sent)
 *
 * Provides form body for mass deleting messages
 *
 * @uses $vars['list'] List of messages
 * 
 */

echo $vars['list'];

echo '<div class="messages-buttonbank">';
echo elgg_view('input/submit', array(
	'value' => elgg_echo('delete'),
	'name' => 'delete',
	'class' => 'elgg-button-delete',
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
