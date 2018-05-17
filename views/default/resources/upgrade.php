<?php

$site_url = _elgg_config()->url;
$site_host = parse_url($site_url, PHP_URL_HOST) . '/';

// turn any full in-site URLs into absolute paths
$forward_url = get_input('forward', '/admin', false);
$forward_url = str_replace([$site_url, $site_host], '/', $forward_url);

if (strpos($forward_url, '/') !== 0) {
	$forward_url = '/' . $forward_url;
}

$refresh_url = elgg_generate_url('upgrade:run', [
	'upgrade' => 'upgrade',
	'forward' => $forward_url,
]);

// sign the url in order to get past the protection
$refresh_url = elgg_http_get_signed_url($refresh_url);

// render content before head so that JavaScript and CSS can be loaded. See #4032
$body = "<div style='margin-top:200px'>" . elgg_view('graphics/ajax_loader', ['hidden' => false]) . "</div>";

$head = elgg_view('page/elements/head', [
	'title' => elgg_echo('upgrading'),
]);

$head .= elgg_format_element([
	'#tag_name' => 'meta',
	'#options' => ['is_xml' => true],
	'http-equiv' => 'refresh',
	'content' => '1;url=' . $refresh_url,
]);

echo elgg_view("page/elements/html", ["head" => $head, "body" => $body]);