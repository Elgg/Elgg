<?php
/**
 * Show a page after registration that account validation is pending
 */

use Elgg\Exceptions\Http\ValidationException;

$session = elgg_get_session();
if (!$session->get('admin_validation')) {
	throw new ValidationException();
}

$session->remove('admin_validation');

echo elgg_view_page(elgg_echo('account:validation:pending:title'), [
	'content' => elgg_view('output/longtext', [
		'value' => elgg_echo('account:validation:pending:content')
	]),
	'sidebar' => false,
	'filter' => false,
], 'walled_garden');
