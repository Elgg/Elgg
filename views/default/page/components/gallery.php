<?php
/**
 * Gallery view
 *
 * Implemented as an unorder list
 *
 * @uses $vars['items']         Array of ElggEntity or ElggAnnotation objects
 * @uses $vars['offset']        Index of the first list item in complete list
 * @uses $vars['limit']         Number of items per page
 * @uses $vars['count']         Number of items in the complete list
 * @uses $vars['pagination']    Show pagination? (default: true)
 * @uses $vars['position']      Position of the pagination: before, after, or both
 * @uses $vars['full_view']     Show the full view of the items (default: false)
 * @uses $vars['gallery_class'] Additional CSS class for the <ul> element
 * @uses $vars['item_class']    Additional CSS class for the <li> elements
 * @uses $vars['no_results']    Message to display if no results (string|Closure)
 */

$items = $vars['items'];
$offset = $vars['offset'];
$limit = $vars['limit'];
$count = $vars['count'];
$base_url = elgg_extract('base_url', $vars, '');
$pagination = elgg_extract('pagination', $vars, true);
$offset_key = elgg_extract('offset_key', $vars, 'offset');
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

if (!$items && $no_results) {
	if ($no_results instanceof Closure) {
		echo $no_results();
		return;
	}
	echo "<p>$no_results</p>";
	return;
}

if (!is_array($items) || count($items) == 0) {
	return;
}

elgg_push_context('gallery');

$gallery_class = 'elgg-gallery';
if (isset($vars['gallery_class'])) {
	$gallery_class = "$gallery_class {$vars['gallery_class']}";
}

$item_class = 'elgg-item';
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
}

$nav = '';

if ($pagination && $limit) {
	if ($count) {
		$nav .= elgg_view('navigation/pagination/count', array(
			'base_url' => $base_url,
			'offset' => $offset,
			'count' => $count,
			'limit' => $limit,
			'offset_key' => $offset_key,
		));
	} else {
		$nav .= elgg_view('navigation/pagination/no_count', array(
			'base_url' => $base_url,
			'offset' => $offset,
			'has_next' => count($items) > $limit,
			'limit' => $limit,
			'offset_key' => $offset_key,
		));
	}
}

if ($limit) {
	// if using prev_next pagination, we have one too many
	$items = array_slice($items, 0, $limit);
}

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

?>
<ul class="<?php echo $gallery_class; ?>">
	<?php
		foreach ($items as $item) {
			if ($item instanceof ElggEntity) {
				$id = "elgg-{$item->getType()}-{$item->getGUID()}";
			} else {
				$id = "item-{$item->getType()}-{$item->id}";
			}
			echo "<li id=\"$id\" class=\"$item_class\">";
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
