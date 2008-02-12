<?php

	/**
	 * Elgg library
	 * Contains important functionality core to Elgg
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Loading libraries **************************************************************************
	 */

	/**
	 * Recursive function designed to load library files on start
	 *
	 * @param string $directory Full path to the directory to start with
	 * @param string $file_exceptions A list of filenames (with no paths) you don't ever want to include
	 * @param string $file_list A list of files that you know already you want to include
	 * @return array Array of full filenames
	 */
		function get_library_files($directory, $file_exceptions = array(), $file_list = array()) {
			
			if (is_file($directory)) {
				$file_list[] = $directory;
			} else if ($handle = opendir($directory)) {
				while ($file = readdir($handle)) {
					if (!in_array($file,$file_exceptions)) {
						$file_list = get_library_files($directory . "/" . $file, $file_exceptions, $file_list);
					}
				}
			}
			
			return $file_list;
			
		}

?>