<?php
/**
* Inspect View
*
* Inspect global variables of Elgg
*/

$inspect_type = get_input('inspect_type');
$method = 'get' . str_replace(' ', '', $inspect_type);

$inspector = new ElggInspector();
$inspect_result = '';
if ($inspector && method_exists($inspector, $method)) {
	$data = $inspector->$method();
}

echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

$view_name = strtolower(str_replace(' ', '', $inspect_type));
echo elgg_view("admin/develop_tools/inspect/$view_name", array("data" => $data));
