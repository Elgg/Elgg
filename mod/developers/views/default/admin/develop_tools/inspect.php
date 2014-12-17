<?php
/**
* Inspect View
*
* Inspect global variables of Elgg
*/

$inspect_type = get_input('inspect_type');
$method = 'get' . str_replace(' ', '', $inspect_type);
$view_name = "admin/develop_tools/inspect/" . strtolower(str_replace(' ', '', $inspect_type));
$inspector = new ElggInspector();

if (!elgg_view_exists($view_name) || !method_exists($inspector, $method)) {
	forward('admin', '404');
}

// why the switch? to keep BC with the original ElggInspector API
switch ($inspect_type) {
	case 'Actions': // fallthrough intentional
	case 'Views':
		$data = $inspector->$method(true);
		break;
	default:
		$data = $inspector->$method();
		break;
}

echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

echo elgg_view($view_name, array("data" => $data));
