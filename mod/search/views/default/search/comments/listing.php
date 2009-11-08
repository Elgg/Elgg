<?php
/**
 * Elgg comments search listing
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

foreach ($vars['entities'] as $entity) {
	if ($owner = $entity->getOwnerEntity()) {
		$owner_icon = $owner->getIcon('tiny');
		$icon = "<img src=\"$owner_icon\" />";
	} else {
		$icon = '';
	}
	$title = "Comment on " . elgg_echo('item:' . $entity->getType() . ':' . $entity->getSubtype());
	$description = $entity->getVolatileData('search_matched_comment');
	$url = $entity->getURL();
	$title = "<a href=\"$url\">$title</a>";
	$tc = $entity->getVolatileData('search_matched_comment_time_created');;
	$time = friendly_time($tc);

	echo <<<___END
<span class="searchListing">
	<h3 class="searchTitle">$title</h3>
	<span class="searchDetails">
		<span class="searchDescription">$description</span><br />
		$icon $time - $more</a>
	</span>
</span>
___END;
}