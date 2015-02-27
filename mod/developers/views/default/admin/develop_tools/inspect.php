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
		$viewtypes = $inspector->getViewtypes();
		$viewtype = get_input('type', 'default');

		if (!in_array($viewtype, $viewtypes)) {
			forward('admin', '404');
		}

		$data = $inspector->getViews($viewtype);
		$page = elgg_view($view_name, array(
			"data" => $data,
			"viewtypes" => $viewtypes,
			"viewtype" => $viewtype,
		));
		break;
	default:
		$data = $inspector->$method();
		$page = elgg_view($view_name, array(
			"data" => $data,
		));
		break;
}

echo '<p>' . elgg_echo('developers:inspect:help') . '</p>';

echo $page;
