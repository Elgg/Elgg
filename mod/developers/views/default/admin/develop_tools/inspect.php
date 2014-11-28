<?php
/**
* Inspect View
*
* Inspect global variables of Elgg
*/

elgg_load_js('jquery.jstree');
elgg_load_css('jquery.jstree');

$inspect_type = get_input('inspect_type');
$method = 'get' . str_replace(' ', '', $inspect_type);

$inspector = new ElggInspector();
$inspect_result = '';
if ($inspector && method_exists($inspector, $method)) {
	$tree = $inspector->$method();
	$inspect_result = elgg_view('developers/tree', array('tree' => $tree));
}

echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

echo "<div id=\"developers-inspect-results\" class=\"hidden\">{$inspect_result}</div>";
