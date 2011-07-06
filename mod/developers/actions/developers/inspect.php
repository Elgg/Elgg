<?php
/**
 * Ajax endpoint for inspection
 *
 */

$inspect_type = get_input('inspect_type');
$method = 'get' . str_replace(' ', '', $inspect_type);

$inspector = new ElggInspector();
if ($inspector && method_exists($inspector, $method)) {
	$tree = $inspector->$method();
	echo elgg_view('developers/tree', array('tree' => $tree));
} else {
	echo 'error';
}
