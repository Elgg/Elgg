<?php
/**
 * Gallery view
 *
 * @uses $vars['items']
 *
 * @todo not complete - number of columns
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

$num_columns = 4;

// @todo standardize on full_view - will require backward compatible code
if (isset($vars['full_view'])) {
	$vars['full'] = $vars['full_view'];
}

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
<table class="elgg-gallery">
<?php

$col = 0;
foreach ($items as $item) {
	if ($col == 0) {
		echo '<tr>';
	}
	$col++;

	echo '<td>';
	echo elgg_view_list_item($item, $vars);
	echo "</td>";

	if ($col == $num_columns) {
		echo '</tr>';
		$col = 0;
	}
}

if ($col > 0) {
	echo '</tr>';
}

?>

</table>

<?php
if ($position == 'after' || $position == 'both') {
	echo $nav;
}

elgg_pop_context();
