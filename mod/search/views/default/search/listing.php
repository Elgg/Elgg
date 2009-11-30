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

$query = htmlspecialchars(http_build_query(
	array(
		'q' => $vars['params']['query'],
		'entity_type' => $vars['params']['type'],
		'entity_subtype' => $vars['params']['subtype'],
		'limit' => get_input('limit', 10),
		'offset' => get_input('offset', 0),
		'search_type' => 'comments',
	)
));

$url = "{$vars['url']}pg/search?$query";

// get pagination
if (array_key_exists('pagination', $vars) && $vars['pagination']) {
	$nav .= elgg_view('navigation/pagination',array(
		'baseurl' => $url,
		'offset' => $vars['params']['offset'],
		'count' => $vars['count'],
		'limit' => $vars['params']['limit'],
	));
} else {
	$nav = '';
}

// figure out what we're deal with.
if (array_key_exists('type', $vars['params']) && array_key_exists('subtype', $vars['params'])) {
	$type_str = elgg_echo("item:{$vars['params']['type']}:{$vars['params']['subtype']}");
} elseif (array_key_exists('type', $vars['params'])) {
	$type_str = elgg_echo("item:{$vars['params']['type']}");
} else {
	$type_str = elgg_echo('search:unknown_entity');
}

// allow overrides for titles
$search_type_str = elgg_echo("search_types:{$vars['params']['search_type']}");
if (array_key_exists('search_type', $vars['params'])
	&& $search_type_str != "search_types:{$vars['params']['search_type']}") {

	$type_str = $search_type_str;
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
	$more_link = "<div class='search_listing'><a href=\"$url\">$more_str</a></div>";
} else {
	$more_link = '';
}

echo $nav;
$body = elgg_view_title($type_str);

foreach ($entities as $entity) {
	if ($owner = $entity->getOwnerEntity()) {
		$icon = elgg_view('profile/icon', array('entity' => $owner));
	} elseif ($entity instanceof ElggUser) {
		$icon = elgg_view('profile/icon', array('entity' => $entity));
	} else {
		$icon = '';
	}
	$title = $entity->getVolatileData('search_matched_title');
	$description = $entity->getVolatileData('search_matched_description');
	$extra_info = $entity->getVolatileData('search_matched_extra');
	$url = $entity->getURL();
	$title = "<a href=\"$url\">$title</a>";
	$tc = $entity->time_created;
	$tu = $entity->time_updated;
	$time = friendly_time(($tu > $tc) ? $tu : $tc);

	$body .= <<<___END
	<div class="search_listing">
		<div class="search_listing_icon">$icon</div>
		<div class="search_listing_info">
			<p class="ItemTitle">$title</p>$description
			<p class="ItemTimestamp">$time $extra_info</p>
		</div>
	</div>
___END;
}
echo $body;
echo $more_link;
echo $nav;
