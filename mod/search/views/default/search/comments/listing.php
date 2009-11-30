<?php
/**
 * Elgg comments search listing
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

if (!is_array($vars['entities']) || !count($vars['entities'])) {
	return FALSE;
}

$title_str = elgg_echo('comments');

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

// get more links
$more_check = $vars['count'] - ($vars['params']['offset'] + $vars['params']['limit']);
$more = ($more_check > 0) ? $more_check : 0;

if ($more) {
	$title_key = ($more == 1) ? 'comment' : 'comments';
	$more_str = sprintf(elgg_echo('search:more'), $vars['count'], elgg_echo($title_key));
	$more_link = "<div class='search_listing'><a href=\"$url\">$more_str</a></div>";
} else {
	$more_link = '';
}

echo $nav;
$body = elgg_view_title($title_str);

foreach ($vars['entities'] as $entity) {
	if ($owner = $entity->getOwnerEntity()) {
		$icon = elgg_view('profile/icon', array('entity' => $owner));
	} else {
		$icon = '';
	}

	// @todo Sometimes we find comments on entities we can't display...
	if ($entity->getVolatileData('search_unavailable_entity')) {
		$title = sprintf(elgg_echo('search:comment_on'), elgg_echo('search:unavailable_entity'));
		// keep anchor for formatting.
		$title = "<a>$title</a>";
	} else {
		if ($entity->getType() == 'object') {
			$title = $entity->title;
		} else {
			$title = $entity->name;
		}

		if (!$title) {
			$title = elgg_echo('item:' . $entity->getType() . ':' . $entity->getSubtype());
		}

		if (!$title) {
			$title = elgg_echo('item:' . $entity->getType());
		}

		$title = sprintf(elgg_echo('search:comment_on'), $title);
		$url = $entity->getURL() . '#annotation-' . $entity->getVolatileData('search_match_annotation_id');
		$title = "<a href=\"$url\">$title</a>";
	}

	$description = $entity->getVolatileData('search_matched_comment');
	$tc = $entity->getVolatileData('search_matched_comment_time_created');;
	$time = friendly_time($tc);

	$body .= <<<___END
	<div class="search_listing">
		<div class="search_listing_icon">$icon</div>
		<div class="search_listing_info">
			<p class="ItemTitle">$title</p>$description
			<p class="ItemTimestamp">$time</p>
		</div>
	</div>
___END;
}

echo $body;
echo $more_link;
echo $nav;
