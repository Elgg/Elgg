<?php
/**
 * Elgg members plugin
 * 
 * @package Elggmembers
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */
	
function members_init() {
	global $CONFIG;
	elgg_extend_view('css','members/css');
}

// @todo - use page handler for members index
register_elgg_event_handler('pagesetup','system','members_pagesetup');
register_elgg_event_handler('init','system','members_init');