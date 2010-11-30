<?php
/**
 * Elgg members plugin
 * 
 * @package Elggmembers
 */
	
function members_init() {
	elgg_extend_view('css','members/css');
}

// @todo - use page handler for members index
elgg_register_event_handler('pagesetup','system','members_pagesetup');
elgg_register_event_handler('init','system','members_init');