<?php
/**
 * Elgg search entity
 *
 * @package Elgg
 * @subpackage Core
 */
$entity = $vars['entity'];

$owner = get_entity($entity->getVolatileData('search_matched_comment_owner_guid'));

if ($owner instanceof ElggUser) {
	$icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
} else {
	$icon = '';
}

// @todo Sometimes we find comments on entities we can't display...
if ($entity->getVolatileData('search_unavailable_entity')) {
	$title = elgg_echo('search:comment_on', array(elgg_echo('search:unavailable_entity')));
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

	$title = elgg_echo('search:comment_on', array($title));
	$url = $entity->getURL() . '#comment_' . $entity->getVolatileData('search_match_annotation_id');
	$title = "<a href=\"$url\">$title</a>";
}

$description = $entity->getVolatileData('search_matched_comment');
$tc = $entity->getVolatileData('search_matched_comment_time_created');;
$time = elgg_view_friendly_time($tc);

echo <<<___END
	<div class="search_listing clearfix">
		<div class="search_listing_icon">$icon</div>
		<div class="search_listing_info">
			<p class="entity-title">$title</p>$description
			<p class="entity-subtext">$time</p>
		</div>
	</div>
___END;

?>
