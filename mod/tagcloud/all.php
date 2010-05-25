<?php
/**
 * Display site tagcloud
 **/
 
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
$tags = display_tagcloud(0, 100, 'tags');

//select the correct canvas area
$body = elgg_view_layout("one_column_with_sidebar", $tags);
		
// Display page
page_draw(sprintf(elgg_echo('tagcloud:site:title'),$page_owner->name),$body);