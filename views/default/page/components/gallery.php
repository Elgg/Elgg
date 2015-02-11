<?php
/**
 * Gallery view
 *
 * Implemented as an unorder list
 *
 * @uses $vars['items']         Array of ElggEntity, ElggAnnotation or ElggRiverItem objects
 * @uses $vars['offset']        Index of the first list item in complete list
 * @uses $vars['limit']         Number of items per page
 * @uses $vars['count']         Number of items in the complete list
 * @uses $vars['pagination']    Show pagination? (default: true)
 * @uses $vars['position']      Position of the pagination: before, after, or both
 * @uses $vars['full_view']     Show the full view of the items (default: false)
 * @uses $vars['gallery_class'] Additional CSS class for the <ul> element
 * @uses $vars['item_class']    Additional CSS class for the <li> elements
 * @uses $vars['item_view']     Alternative view to render list items
 * @uses $vars['no_results']    Message to display if no results (string|Closure)
 */

$items = $vars['items'];
$count = $vars['count'];
$pagination = elgg_extract('pagination', $vars, true);
$position = elgg_extract('position', $vars, 'after');
$no_results = elgg_extract('no_results', $vars, '');

if (!$items && $no_results) {
	if ($no_results instanceof Closure) {
		echo $no_results();
		return;
	}
	echo "<p class='elgg-no-results'>$no_results</p>";
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

$nav = ($pagination) ? elgg_view('navigation/pagination', $vars) : '';

if ($position == 'before' || $position == 'both') {
	echo $nav;
}

?>
<ul class="<?php echo $gallery_class; ?>">
	<?php
		foreach ($items as $item) {
			if (elgg_instanceof($item)) {
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
