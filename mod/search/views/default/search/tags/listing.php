<?php
/**
 * Elgg tag search listing
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>


<?php
$entities = $vars['entities'];
$count = $vars['count'];

if (!is_array($vars['entities']) || !count($vars['entities'])) {
	return FALSE;
}

$title_str = elgg_echo("item:{$vars['params']['type']}:{$vars['params']['subtype']}");
$body = elgg_view_title(elgg_echo('tags'));

//echo elgg_view('page_elements/contentwrapper', array('body' => $body));

foreach ($entities as $entity) {
	if ($owner = $entity->getOwnerEntity()) {
		$owner_icon = $owner->getIcon('tiny');
		$icon = "<img src=\"$owner_icon\" />";
	} else {
		$icon = '';
	}
	$tags = $entity->getVolatileData('search_matched_tags');

	$entity_html = elgg_view_entity($entity);
	$url = $entity->getURL();
	$title = "<a href=\"$url\">$title</a>";
	$tc = $entity->time_created;
	$tu = $entity->time_updated;
	$time = friendly_time(($tu > $tc) ? $tu : $tc);

	$body .= $entity_html;
}
echo $body;
?>
