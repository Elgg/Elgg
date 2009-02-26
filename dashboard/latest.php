<?php

	/**
	 * Elgg latest content page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * Start the Elgg engine
	 */
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		
		
	// Load the front page
        global $CONFIG;
        $title = elgg_view_title(elgg_echo('content:latest'));
        set_context('search');
        $content = list_registered_entities(0,10,true,false,array('object','group'));
        set_context('latest');
        $content = elgg_view_layout('two_column_left_sidebar', '', $title . $content);
        page_draw(elgg_echo('content:latest'), $content);
		

?>