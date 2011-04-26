<?php
/**
 * Gallery view
 *
 * @uses $vars['items']
 */

$items = $vars['items'];
if (!is_array($items) && sizeof($items) == 0) {
	return true;
}

elgg_push_context('gallery');

$offset = $vars['offset'];
$limit = $vars['limit'];
$count = $vars['count'];
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');

if ($pagination && $count) {
	$nav .= elgg_view('navigation/pagination', array(
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
		'offset_key' => $offset_key,
	));
}

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

?>
<ul class="elgg-gallery">
	<?php
		foreach ($items as $item) {
			echo '<li>';
			echo elgg_view_list_item($item, $vars);
			echo "</li>";
		}
	?>
</ul>

<?php
if ($position == 'after' || $position == 'both') {
	echo $nav;
}

elgg_pop_context();
