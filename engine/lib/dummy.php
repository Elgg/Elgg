<?php
	/**
	 * Dummy class.
	 * This function contains dummy functions and classes which return safe dummy values. Essentially this is 
	 * to make sure that things like calling echo page_owner_entity()->name don't cause a WSoD.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey 
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	
	/**
	 * Dummy class.
	 * For every function call, get and set this function returns false.
	 */
	class ElggDummy {
		
		 public function __call($method, $args) {
		 	return false;
		 }
		 
		function __get($name) { return false; }
		
		function __set($name, $value) { return false; }
	}
?>