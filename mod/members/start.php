<?php
/**
 * Elgg members plugin
 * 
 * @package Elggmembers
 */
	
function members_init() {
	global $CONFIG;
	elgg_extend_view('css','members/css');
}

// @todo - use page handler for members index
register_elgg_event_handler('pagesetup','system','members_pagesetup');
register_elgg_event_handler('init','system','members_init');