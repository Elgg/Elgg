<?php
/**
 * Elgg river image
 *
 * Displayed next to the body of each river item
 *
 * @uses $vars['item']
 */

$image = elgg_extract('image', $vars);
if (isset($image)) {
	if ($image) {
		echo $image;
	}
	return;
}

$item = elgg_extract('item', $vars);
if (!$item instanceof ElggRiverItem) {
	return;
}

$subject = $item->getSubjectEntity();

echo elgg_view_entity_icon($subject, 'small');
