<?php
/**
* Inspect View
*
* Inspect global variables of Elgg
*/

use Elgg\Exceptions\Http\EntityNotFoundException;

$inspect_type = get_input('inspect_type');
$method = 'get' . str_replace(' ', '', $inspect_type);
$view_name = 'admin/develop_tools/inspect/' . strtolower(str_replace(' ', '', $inspect_type));
$inspector = new \Elgg\Debug\Inspector();

if (!elgg_view_exists($view_name) || !method_exists($inspector, $method)) {
	throw new EntityNotFoundException();
}

switch ($inspect_type) {
	case 'Views':
		$viewtypes = $inspector->getViewtypes();
		$viewtype = get_input('type', 'default');

		if (!in_array($viewtype, $viewtypes)) {
			throw new EntityNotFoundException();
		}

		$data = $inspector->getViews($viewtype);
		$page = elgg_view($view_name, [
			'data' => $data,
			'viewtypes' => $viewtypes,
			'viewtype' => $viewtype,
		]);
		break;

	default:
		$data = $inspector->$method();
		$page = elgg_view($view_name, [
			'data' => $data,
		]);
		break;
}

echo elgg_view('output/longtext', [
	'value' => elgg_echo('developers:inspect:help'),
]);

echo $page;
