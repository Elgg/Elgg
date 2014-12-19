<?php
/**
* Inspect View
*
* Inspect global variables of Elgg
*/

$inspect_type = get_input('inspect_type');
$method = 'get' . str_replace(' ', '', $inspect_type);
$view_name = "admin/develop_tools/inspect/" . strtolower(str_replace(' ', '', $inspect_type));
$inspector = new \Elgg\Debug\Inspector();

if (!elgg_view_exists($view_name) || !method_exists($inspector, $method)) {
	forward('admin', '404');
}

switch ($inspect_type) {
	case 'Views':
		// TODO find way to present list of available viewtypes
		$viewtype = get_input('type', 'default');
		if (_elgg_is_valid_viewtype($viewtype)) {
			$data = $inspector->getViews($viewtype);
		} else {
			forward('admin', '404');
		}
		break;
	default:
		$data = $inspector->$method();
		break;
}

echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

echo elgg_view($view_name, array("data" => $data));