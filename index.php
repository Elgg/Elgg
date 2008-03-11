<?php

	/**
	 * Elgg index page for web-based applications
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Start the Elgg engine
	 */
		require_once(dirname(__FILE__) . "/engine/start.php");

	// Display page
        if ($_SESSION['id'] == -1) {
           //Load the front page
           echo page_draw(null, elgg_view("login"));
        } else {
            global $CONFIG;
            header("Location: {$CONFIG->url}mod/dashboard/");
            exit;
        }

?>