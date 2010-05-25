<?php
/**
 * Display sidebar tagcloud
 **/
 
// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
$title = elgg_view_title(elgg_echo('tagcloud:site:title'));
$tags = display_tagcloud(0, 100, 'tags');

//select the correct canvas area
$body = elgg_view_layout("one_column_with_sidebar", $title . $tags, $sidebar);
		
// Display page
page_draw(sprintf(elgg_echo('tagcloud:site:title'),$page_owner->name),$body);