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
 */

$entity = $vars['entity'];

$icon = $entity->getVolatileData('search_icon');
if (!$icon) {
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
	$time = elgg_view_friendly_time(($tu > $tc) ? $tu : $tc);
}
?>
	<div class="search_listing clearfix">
	<div class="search_listing_icon"><?php echo $icon; ?></div>
		<div class="search_listing_info">
			<p class="entity-title"><?php echo $title; ?></p>
			<?php echo $description; ?>
<?php 
if ($extra_info) {
?>
			<p class="entity-subtext"><?php echo $extra_info; ?></p>
<?php
}
?>
			<p class="entity-subtext"><?php echo $time; ?></p>
		</div>
	</div>
