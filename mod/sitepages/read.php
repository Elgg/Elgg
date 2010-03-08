<?php
/**
 * Elgg read site page
 */

// Load Elgg engine
define('externalpage',true);
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// set some variables
$type = get_input('sitepages');
											
// Set the title appropriately
$area1 = elgg_view_title(elgg_echo("sitepages:". strtolower($type)));
		
//get contents
$contents = elgg_get_entities(array('type' => 'object', 'subtype' => $type, 'limit' => 1));
		
if($contents){
	foreach($contents as $c){
		$area1 .= elgg_view('page_elements/elgg_content',array('body' => $c->description));
	}
}else{
	$area1 .= elgg_view('page_elements/elgg_content',array('body' => elgg_echo("sitepages:notset")));
}

// Display through the correct canvas area
$body = elgg_view_layout("one_column_with_sidebar", "", $area1);
		
// Display page
page_draw($title,$body);