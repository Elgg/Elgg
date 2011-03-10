<?php
/**
 * Content stats widget
 */

$max = $vars['entity']->num_display;

$entity_stats = get_entity_statistics();
$object_stats = $entity_stats['object'];
arsort($object_stats);
$object_stats = array_slice($object_stats, 0, $max);

echo '<table class="elgg-table-alt">';
echo '<tr><th>' . elgg_echo('widget:content_stats:type') . '</th>';
echo '<th>' . elgg_echo('widget:content_stats:number') . '</th></tr>';
foreach ($object_stats as $subtype => $num) {
	$name = elgg_echo("item:object:$subtype");
	echo "<tr><td>$name</td><td>$num</td></tr>";
}
echo '</table>';

echo elgg_view('output/url', array(
	'href' => 'admin/statistics/overview',
	'text' => 'more',
));
