<?php

	/**
	 * Simple cache viewer
	 * Bypasses the engine to view simple cached CSS views.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Get DB settings, connect
		require_once(dirname(dirname(__FILE__)). '/engine/settings.php');
		
		global $CONFIG, $viewinput;
		
		$contents = '';
		if (!isset($viewinput)) $viewinput = $_GET;
		
		if ($dblink = @mysql_connect($CONFIG->dbhost,$CONFIG->dbuser,$CONFIG->dbpass)) {

			$view = $viewinput['view'];
			
		// Get the dataroot
			if (@mysql_select_db($CONFIG->dbname,$dblink)) {
				if ($result = mysql_query("select value from {$CONFIG->dbprefix}datalists where name = 'dataroot'",$dblink)) {
					$row = mysql_fetch_object($result);
					$dataroot = $row->value;
				}
				$filename = $dataroot . 'views_simplecache/' . md5($view);
				if (file_exists($filename))
					$contents = @file_get_contents($filename);
				 else {
				 	echo ''; exit;
				 }
			}
		}
		
		echo $contents;

?>