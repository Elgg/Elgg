<?php
/**
 * Elgg search entity
 *
 * Display largely controlled by a set of overrideable volatile data:
 *   - search_icon (defaults to entity icon)
 *   - search_matched_title 
 *   - search_matched_description
 *   - search_matched_extra
 *   - search_url (defaults to entity->getURL())
 *   - search_time (defaults to entity->time_updated or entity->time_created)
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$entity = $vars['entity'];

$icon = $entity->getVolatileData('search_icon');
if (!$icon) {
	// display the entity's owner by default if available.
	// @todo allow an option to switch to displaying the entity's icon instead.
	$type = $entity->getType();
	if ($type == 'user' || $type == 'group') {
		$icon = elgg_view('profile/icon', array('entity' => $entity, 'size' => 'small'));
	} elseif ($owner = $entity->getOwnerEntity()) {
		$icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'small'));
	} else {
		// display a generic icon if no owner, though there will probably be
		// other problems if the owner can't be found.
		$icon = elgg_view(
			'graphics/icon', array(
				'entity' => $entity,
				'size' => 'small',
				));
	}
}

$title = $entity->getVolatileData('search_matched_title');
$description = $entity->getVolatileData('search_matched_description');
$extra_info = $entity->getVolatileData('search_matched_extra');
$url = $entity->getVolatileData('search_url');

if (!$url) {
	$url = $entity->getURL();
}

$title = "<a href=\"$url\">$title</a>";
$time = $entity->getVolatileData('search_time');
if (!$time) {
	$tc = $entity->time_created;
	$tu = $entity->time_updated;
	$time = friendly_time(($tu > $tc) ? $tu : $tc);
}
?>
	<div class="search_listing">
	<div class="search_listing_icon"><?php echo $icon; ?></div>
		<div class="search_listing_info">
			<p class="item_title"><?php echo $title; ?></p>
			<p class="item_description"><?php echo $description; ?></p>
<?php 
if ($extra_info) {
?>
			<p class="item_extra"><?php echo $extra_info; ?></p>
<?php
}
?>
			<p class="item_timestamp"><?php echo $time; ?></p>
		</div>
	</div>
