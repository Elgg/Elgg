<?php
/**
 * Configure the contents of the security.txt endpoint
 *
 * @see https://securitytxt.org/
 */

$params = $vars;
$params['selected'] = 'security_txt';
echo elgg_view('admin/security/tabs', $params);

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:security_txt:description', [
		elgg_view_url('https://securitytxt.org/'),
		elgg_view_url(elgg_generate_url('security.txt')),
	]),
]);

$expires = elgg_get_config('security_txt_expires');
if (!empty($expires) && $expires < time()) {
	echo elgg_view_message('warning', elgg_echo('admin:security:security_txt:expired'));
}

echo elgg_view_form('admin/security/security_txt', [
	'enable_sticky' => true,
]);
