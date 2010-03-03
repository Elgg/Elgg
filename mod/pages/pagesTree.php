<?php
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	if (is_callable('group_gatekeeper')) group_gatekeeper();
	
	$page = (int) get_input('root',get_input('source'));
	
	if (!$page = get_entity($page)) {
		exit;
	}
	
	// View tree
		echo pages_get_entity_sidebar($page, $fulltree);
?>