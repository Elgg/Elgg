<?php

$widget = elgg_extract('entity', $vars);

$feed_url = 'https://elgg.org/blog?view=rss';
$limit = 5;

echo elgg_format_element('div', [
	'id' => 'elgg-news-' . $widget->guid,
	'data-feed-url' => $feed_url,
	'data-limit' => $limit,
]);
echo elgg_format_element('script', [], 'require(["widgets/elgg_news/rss"], function (rss) { rss("#elgg-news-' . $widget->guid . '"); });');