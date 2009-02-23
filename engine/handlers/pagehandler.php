<?php

	/**
	 * Elgg page handler
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		// define('externalpage',true);
		require_once("../start.php");

	// Get input
		$handler = get_input('handler');
		$page = get_input('page');
		
	// Call the page handler functions
		if (!page_handler($handler, $page)) {
			forward();
		}

?>