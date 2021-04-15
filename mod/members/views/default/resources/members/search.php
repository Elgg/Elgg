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

$display_query = _elgg_get_display_query($query);
$title = elgg_echo('members:title:search', [$display_query]);

$content = elgg_list_entities([
	'query' => $query,
	'type' => 'user',
	'no_results' => true,
], 'elgg_search');

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => elgg_view('members/sidebar'),
	'filter_id' => 'members',
	'filter_value' => 'search',
]);
