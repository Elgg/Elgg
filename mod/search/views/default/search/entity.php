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

if ($owner = $entity->getOwnerEntity()) {
	$icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'small'));
} elseif ($entity instanceof ElggUser) {
	$icon = elgg_view('profile/icon', array('entity' => $entity, 'size' => 'small'));
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

echo <<<___END
	<div class="search_listing">
		<div class="search_listing_icon">$icon</div>
		<div class="search_listing_info">
			<p class="ItemTitle">$title</p>$description
			<p class="ItemTimestamp">$time $extra_info</p>
		</div>
	</div>
___END;

// php bug. must have close tag after heredocs
?>