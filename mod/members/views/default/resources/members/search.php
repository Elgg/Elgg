<?php
/**
 * Members search page
 */

use Elgg\Exceptions\Http\BadRequestException;

$query = get_input('member_query');
if (empty($query)) {
	$e = new BadRequestException(elgg_echo('error:missing_data'));
	$e->setRedirectUrl(elgg_generate_url('collection:user:user'));
	throw $e;
}

echo elgg_view_page(elgg_echo('members:title:search', [$query]), [
	'content' => elgg_list_entities([
		'query' => $query,
		'type' => 'user',
		'no_results' => true,
	], 'elgg_search'),
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'search',
]);
