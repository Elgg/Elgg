<?php
/**
 * Elgg Message board add form
 *
 * @package ElggMessageBoard
 */

$textarea = elgg_view('input/plaintext', array(
	'name' => 'message_content'
));

$owner_input = elgg_view('input/hidden', array(
	'name' => 'owner_guid',
	'value' => elgg_get_page_owner_guid()
));

$submit = elgg_view('input/submit', array(
	'value' => elgg_echo('post')
));

echo $textarea . $owner_input . $submit;