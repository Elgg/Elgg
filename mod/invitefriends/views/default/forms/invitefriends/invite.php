<?php

/**
 * Elgg invite form contents
 */

$default_message = elgg_echo('invitefriends:message:default', [elgg_get_site_entity()->getDisplayName()]);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('invitefriends:introduction'),
]);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('invitefriends:emails'),
	'id' => 'invitefriends-emails',
	'name' => 'emails',
	'value' => elgg_get_sticky_value('invitefriends', 'emails'),
	'required' => true,
	'rows' => 4,
]);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('invitefriends:message'),
	'id' => 'invitefriends-emailmessage',
	'name' => 'emailmessage',
	'value' => elgg_get_sticky_value('invitefriends', 'emailmessage', $default_message),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('send'),
]);

elgg_set_form_footer($footer);
