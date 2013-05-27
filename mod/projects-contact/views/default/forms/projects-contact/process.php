<?php

$messages = $vars['list'];
if (!$messages) {
	echo elgg_echo('projects_contact:nomessages');
	return true;
}

echo '<div class="projects-contact-container">';
	echo $messages;
echo '</div>';

echo '<div class="elgg-foot projects-contact-buttonbank">';

	echo elgg_view('input/submit', array(
		'value' => elgg_echo('delete'),
		'name' => 'delete',
		'class' => 'elgg-button-delete elgg-requires-confirmation',
		'title' => elgg_echo('deleteconfirm:plural'),
	));

	if ($vars['folder'] == "inbox") {
		echo elgg_view('input/submit', array(
		'value' => elgg_echo('projects_contact:markread'),
		'name' => 'read',
		));
	}

	echo elgg_view('input/button', array(
		'value' => elgg_echo('projects_contact:toggle'),
		'class' => 'elgg-button elgg-button-cancel',
		'id' => 'projects-contact-toggle',
	));

echo '</div>';
