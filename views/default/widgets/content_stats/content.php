<?php
/**
 * Content stats widget
 */

$max = 5;

$entity_stats = get_entity_statistics();
$object_stats = $entity_stats['object'];
arsort($object_stats);
$object_stats = array_slice($object_stats, 0, $max);

echo '<table class="elgg-table">';
foreach ($object_stats as $subtype => $num) {
	$name = elgg_echo("item:object:$subtype");
	echo "<tr><td>$name</td><td>$num</td></tr>";
}
echo '</table>';
