<?php
/**
 * Show a page after registration that account validation is pending
 */

use Elgg\ValidationException;

$session = elgg_get_session();
if (!$session->get('admin_validation')) {
	throw new ValidationException();
}

$session->remove('admin_validation');

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

// build page elements
$title = elgg_echo('account:validation:pending:title');

$content = elgg_view('output/longtext', [
	'value' => elgg_echo('account:validation:pending:content')
]);

// build page
$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'sidebar' => false,
]);

// draw page
echo elgg_view_page($title, $body, $shell);
