<?php
/**
 * Elgg News fetched from the community
 */

$blogs = [];
try {
	$client = elgg_get_http_client(['verify' => false]);
	
	$response = $client->get('https://elgg.org/blog/all?view=json');
	$data = $response->getBody()->getContents();
	$blogs = @json_decode($data);
} catch (\Exception $e) {
	// catch any issue and ignore it until later
}

if (empty($blogs) || !is_array($blogs)) {
	echo elgg_view('page/components/no_results', ['no_results' => elgg_echo('admin:widget:elgg_blog:no_results')]);
	return;
}

$blogs = array_slice($blogs, 0, 5);

$list_items = '';
foreach ($blogs as $blog) {
	$title = elgg_format_element('div', ['class' => 'elgg-listing-summary-title'], elgg_format_element('h3', [], elgg_view_url($blog->url, $blog->title, ['target' => '_blank'])));
	
	$time = elgg_view('object/elements/imprint/element', [
		'icon_name' => 'history',
		'content' => elgg_view_friendly_time($blog->time_created),
		'class' => 'elgg-listing-time',
	]);
	
	$imprint = elgg_format_element('div', ['class' => 'elgg-listing-imprint'], $time);
	
	$subtitle = elgg_format_element('div', ['class' => ['elgg-listing-summary-subtitle', 'elgg-subtext']], $imprint);
	
	$image = elgg_view('output/img', ['src' => elgg_get_simplecache_url('widgets/elgg_blog/elgg-32.png'), 'alt' => 'Elgg logo']);
	
	$item = elgg_view_image_block($image, $title . $subtitle);
	
	$list_items .= elgg_format_element('li', ['class' => 'elgg-item'], $item);
}

$list = elgg_format_element('ul', ['class' => 'elgg-list'], $list_items);

echo elgg_format_element('div', ['class' => 'elgg-list-container'], $list);
