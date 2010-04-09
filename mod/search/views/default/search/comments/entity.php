<?php
/**
 * Elgg search entity
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
$entity = $vars['entity'];

$owner = get_entity($entity->getVolatileData('search_matched_comment_owner_guid'));

if ($owner instanceof ElggUser) {
	$icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'small'));
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

echo <<<___END
	<div class="search_listing">
		<div class="search_listing_icon">$icon</div>
		<div class="search_listing_info">
			<p class="item_title">$title</p>$description
			<p class="item_timestamp">$time</p>
		</div>
	</div>
___END;

?>
