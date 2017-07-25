<?php

$type = elgg_extract('type', $vars);
$params = elgg_extract('params', $vars, []);

if (elgg_view_exists("errors/$type")) {
	$title = elgg_echo("error:$type:title");
	if ($title == "error:$type:title") {
		// use default if there is no title for this error type
		$title = elgg_echo("error:default:title");
	}
	
	$content = elgg_view("errors/$type", $params);
} else {
	$title = elgg_echo("error:default:title");
	$content = elgg_view("errors/default", $params);
}

$httpCodes = [
	'400' => 'Bad Request',
	'401' => 'Unauthorized',
	'403' => 'Forbidden',
	'404' => 'Not Found',
	'407' => 'Proxy Authentication Required',
	'500' => 'Internal Server Error',
	'503' => 'Service Unavailable',
];

if (isset($httpCodes[$type])) {
	elgg_set_http_header("HTTP/1.1 $type {$httpCodes[$type]}");
}

$layout = elgg_in_context('admin') && elgg_is_admin_logged_in() ? 'admin' : 'error';

$body = elgg_view_layout($layout, [
	'title' => $title,
	'content' => $content,
]);

$shell = $layout;
if (!elgg_is_logged_in() && elgg_get_config('walled_garden')) {
	$shell = 'walled_garden';
}

echo elgg_view_page($title, $body, $shell);
