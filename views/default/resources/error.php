<?php
/**
 * The generic error page
 *
 * @uses $vars['current_url']     The current page url
 * @uses $vars['forward_url']     The HTTP Referer url
 * @uses $vars['type']            The type of error (400, 403, 404, etc)
 * @uses $vars['exception']       The exception which cause this page (instance of Elgg\HttpException)
 * @uses $vars['params']['error'] The error text for the page
 *
 * @see \Elgg\Http\ResponseFactory::respondWithError()
 */

$type = elgg_extract('type', $vars);
$exception = elgg_extract('exception', $vars);

$params = elgg_extract('params', $vars, []);
$params['exception'] = elgg_extract('exception', $params, $exception);

$title = elgg_echo('error:default:title');

if (elgg_view_exists("errors/{$type}")) {
	if (elgg_language_key_exists("error:{$type}:title")) {
		// use custom error title is available
		$title = elgg_echo("error:{$type}:title");
	}
	
	$content = elgg_view("errors/{$type}", $params);
} else {
	$content = elgg_view('errors/default', $params);
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
	elgg_set_http_header("HTTP/1.1 {$type} {$httpCodes[$type]}");
}

$layout = elgg_in_context('admin') && elgg_is_admin_logged_in() ? 'admin' : 'error';

$body = elgg_view_layout($layout, [
	'title' => $title,
	'content' => $content,
	'filter' => false,
]);

$shell = $layout;
if (!elgg_is_logged_in() && elgg_get_config('walled_garden')) {
	$shell = 'walled_garden';
}

echo elgg_view_page($title, $body, $shell);
