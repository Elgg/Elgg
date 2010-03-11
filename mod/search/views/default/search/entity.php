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

// display the entity's owner by default if available.
// @todo allow an option to switch to displaying the entity's icon instead.
$type = $entity->getType();
if ($type == 'user' || $type == 'group') {
	$icon = elgg_view('profile/icon', array('entity' => $entity, 'size' => 'tiny'));
} elseif ($owner = $entity->getOwnerEntity()) {
	$icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));
} else {
	// display a generic icon if no owner, though there will probably be
	// other problems if the owner can't be found.
	$icon = elgg_view(
		'graphics/icon', array(
		'entity' => $entity,
		'size' => 'tiny',
	));
}

$title = $entity->getVolatileData('search_matched_title');
$description = $entity->getVolatileData('search_matched_description');
$extra_info = $entity->getVolatileData('search_matched_extra');
$url = $entity->getURL();

if (!$title) {
	$title =  elgg_echo('untitled');
}

$title = "<a href=\"$url\">$title</a>";
$tc = $entity->time_created;
$tu = $entity->time_updated;
$time = friendly_time(($tu > $tc) ? $tu : $tc);

echo <<<___END
	<div class="search_listing clearfloat">
		<div class="search_listing_icon">$icon</div>
		<div class="search_listing_info">
			<p class="entity_title">$title</p>$description
			<p class="entity_subtext">$time $extra_info</p>
		</div>
	</div>
___END;

// php bug. must have close tag after heredocs
?>