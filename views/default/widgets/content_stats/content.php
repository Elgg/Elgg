<?php
/**
 * Content stats widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = sanitize_int($widget->num_display, false);
// set default value for display number
if (!$num_display) {
	$num_display = 8;
}

$entity_stats = get_entity_statistics();
$object_stats = elgg_extract('object', $entity_stats);
arsort($object_stats);
$object_stats = array_slice($object_stats, 0, $num_display);

echo '<table class="elgg-table-alt table table-striped">';
echo '<tr><th>' . elgg_echo('widget:content_stats:type') . '</th>';
echo '<th>' . elgg_echo('widget:content_stats:number') . '</th></tr>';
foreach ($object_stats as $subtype => $num) {
	$name = elgg_echo("item:object:$subtype");
	echo "<tr><td>$name</td><td>$num</td></tr>";
}
echo '</table>';

echo '<div class="mtm elgg-widget-more">';
echo elgg_view('output/url', [
	'href' => 'admin/statistics/overview',
	'text' => elgg_echo('more'),
	'is_trusted' => true,
]);
echo '</div>';
