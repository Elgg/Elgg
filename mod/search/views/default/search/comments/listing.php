<?php
/**
 * Elgg comments search listing
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>

<div class="search_listing">
<?php
if (!is_array($vars['entities']) || !count($vars['entities'])) {
	return FALSE;
}

$title_str = elgg_echo('comments');
$body = elgg_view_title($title_str);

$query = htmlspecialchars(http_build_query(
	array(
		'q' => $vars['params']['query'],
		'entity_type' => $vars['params']['type'],
		'entity_subtype' => $vars['params']['subtype'],
		'limit' => get_input('limit', 10),
		'offset' => get_input('offset', 0),
		'search_type' => 'comments',
	)
));

$url = "{$vars['url']}pg/search?$query";
$more = "<a href=\"$url\">+$count more $title_str</a>";

echo elgg_view('page_elements/contentwrapper', array('body' => $body));

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
?>
</div>
