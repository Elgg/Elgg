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

if (!is_array($vars['entities']) || !count($vars['entities'])) {
	return FALSE;
}

$query = htmlspecialchars(http_build_query(
	array(
		'q' => $vars['params']['query'],
		'entity_type' => $vars['params']['type'],
		'entity_subtype' => $vars['params']['subtype'],
		'limit' => get_input('limit', 10),
		'offset' => get_input('offset', 0),
		'search_type' => 'tags',
	)
));

$url = "{$vars['url']}pg/search?$query";

// get any more links.
$more_check = $vars['count'] - ($vars['params']['offset'] + $vars['params']['limit']);
$more = ($more_check > 0) ? $more_check : 0;

if ($more) {
	$title_key = ($more == 1) ? 'tag' : 'tags';
	$more_str = sprintf(elgg_echo('search:more'), $vars['count'], elgg_echo($title_key));
	$more_link = "<div class='search_listing'><a href=\"$url\">$more_str</a></div>";
} else {
	$more_link = '';
}

$title_str = elgg_echo("item:{$vars['params']['type']}:{$vars['params']['subtype']}");
$body = elgg_view_title(elgg_echo('tags'));

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
echo $more_link;
?>
