<?php

	require_once("../../engine/start.php");
	
	global $CONFIG;
	
	//var_export($CONFIG);
	$body = elgg_view("testplugin/pageshell");
	page_draw("Test plugin",$body);

	$object = new ElggObject();
	$object->type = "forum";
	$object->title = "Howdy!";
	$object->description = "I am the very model of a modern major general";
	$object->access = 2;
	$object->save();
	$object->setMetadata('parent',0,2);
	
?>