<?php
/**
 * Provide a way of setting your email
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('email:settings');
$content = elgg_view_field(array(
	'#type' => 'email',
	'name' => 'email',
	'value' => $user->email,
	'#label' => elgg_echo('email:address:label'),
));

echo elgg_view_module('info', $title, $content);
