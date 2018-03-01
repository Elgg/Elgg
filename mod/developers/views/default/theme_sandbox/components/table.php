<?php

$attrs['class'] = elgg_extract_class($vars);

$table = "<thead>";
$table .= "<tr><th>column 1</th><th>column 2</th></tr>";
$table .= "<tr><td>head cell 1</td><td>head cell 2</td></tr>";
$table .= "</thead>";
	
for ($i = 1; $i < 5; $i++) {
	$table .= '<tr>';
	for ($j = 1; $j < 3; $j++) {
		$table .= "<td>value $j</td>";
	}
	$table .= '</tr>';
}
	
$table .= "<tfoot>";
$table .= "<tr><td>foot cell 1</td><td>foot cell 2</td></tr>";
$table .= "<tr><th>foot 1</th><th>foot 2</th></tr>";
$table .= "</tfoot>";

echo elgg_format_element('table', $attrs, $table);
