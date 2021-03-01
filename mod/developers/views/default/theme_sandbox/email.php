<?php
/**
 * Email CSS
 */

elgg_register_menu_item('title', [
	'name' => 'mail',
	'href' => elgg_generate_action_url('developers/test_email'),
	'text' => elgg_echo('theme_sandbox:test_email:button'),
	'icon' => 'mail',
	'link_class' => 'elgg-button elgg-button-action',
]);

echo elgg_view('output/iframe', [
	'src' => elgg_generate_url('default:developers:email'),
	'style' => 'width: 100%; height: 800px',
]);
