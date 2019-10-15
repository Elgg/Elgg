<?php
/**
 * Security settings subview - site secret
 *
 * @since 3.2
 */

$strength = _elgg_get_site_secret_strength();
$current_strength = elgg_echo('site_secret:current_strength');
$strength_text = elgg_echo("site_secret:strength:$strength");
$strength_msg = elgg_echo("site_secret:strength_msg:$strength");

$site_secret = elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:settings:site_secret:intro'),
]);
$message_type = ($strength != 'strong') ? 'error' : 'success';
$site_secret .= elgg_view_message($message_type, $strength_msg, [
	'title' => "$current_strength: $strength_text",
]);

$site_secret_link = elgg_view('output/url', [
	'text' => elgg_echo('admin:security:settings:site_secret:regenerate'),
	'href' => 'action/admin/security/regenerate_site_secret',
	'confirm' => true,
	'class' => 'elgg-button elgg-button-action',
]);

$site_secret_link .= elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:settings:site_secret:regenerate:help'),
	'class' => 'elgg-subtext',
]);

$site_secret .= elgg_format_element('div', ['class' => 'mtm'], $site_secret_link);

echo elgg_view_module('info', elgg_echo('admin:security:settings:label:site_secret'), $site_secret, [
	'id' => 'admin-security-site-secret',
]);
