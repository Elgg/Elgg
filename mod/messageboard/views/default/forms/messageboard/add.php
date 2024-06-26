<?php
/**
 * Elgg Message board add form body
 */


echo elgg_view_field([
	'#type' => 'plaintext',
	'required' => true,
	'name' => 'message_content',
	'class' => 'messageboard-input',
	'rows' => 4,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'owner_guid',
	'value' => elgg_get_page_owner_guid(),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('post'),
]);

elgg_set_form_footer($footer);

elgg_import_esm('elgg/messageboard');
