<?php
/**
 * Elgg search listing
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */


$entities = $vars['entities'];
$count = $vars['count'] - count($vars['entities']);

if (!is_array($vars['entities']) || !count($vars['entities'])) {
	return FALSE;
}

// figure out what we're deal with.
if (array_key_exists('type', $vars['params']) && array_key_exists('subtype', $vars['params'])) {
	$type_str = elgg_echo("item:{$vars['params']['type']}:{$vars['params']['subtype']}");
} elseif (array_key_exists('type', $vars['params'])) {
	$type_str = elgg_echo("item:{$vars['params']['type']}");
} else {
	$type_str = elgg_echo('search:unknown_entity');
}

$query = htmlspecialchars(http_build_query(
	array(
		'q' => $vars['params']['query'],
		'entity_type' => $vars['params']['type'],
		'entity_subtype' => $vars['params']['subtype'],
		'search_type' => 'entities',
	)
));

$url = "{$vars['url']}pg/search?$query";

// get pagination
if (array_key_exists('pagination', $vars['params']) && $vars['params']['pagination']) {
	$nav .= elgg_view('navigation/pagination',array(
		'baseurl' => $url,
		'offset' => $vars['params']['offset'],
		'count' => $vars['count'],
		'limit' => $vars['params']['limit'],
	));
} else {
	$nav = '';
}

// get any more links.
$more_check = $vars['count'] - ($vars['params']['offset'] + $vars['params']['limit']);
$more = ($more_check > 0) ? $more_check : 0;

if ($more) {
	$title_key = ($more == 1) ? 'comment' : 'comments';
	$more_str = sprintf(elgg_echo('search:more'), $count, $type_str);
	$more_link = "<a href=\"$url\">$more_str</a>";
} else {
	$more_link = '';
}

echo $nav;
$body = elgg_view_title($type_str);

foreach ($entities as $entity) {
	if ($owner = $entity->getOwnerEntity()) {
		$owner_icon = $owner->getIcon('tiny');
		$icon = "<img src=\"$owner_icon\" />";
	} else {
		$icon = '';
	}
	$title = $entity->getVolatileData('search_matched_title');
	$description = $entity->getVolatileData('search_matched_description');
	$url = $entity->getURL();
	$title = "<a href=\"$url\">$title</a>";
	$tc = $entity->time_created;
	$tu = $entity->time_updated;
	$time = friendly_time(($tu > $tc) ? $tu : $tc);

	$body .= <<<___END
<span class="searchListing">
	<h3 class="searchTitle">$title</h3>
	<span class="searchDescription">$description</span><br />
	<span class="searchInfo">$icon $time - $more_link</span>
</span>
___END;
}

echo elgg_view('page_elements/contentwrapper', array('body' => $body));
echo $nav;
