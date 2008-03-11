<?php
	/**
	 * Elgg cache
	 * The API REST endpoint.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * @class ElggCache The elgg cache superclass.
	 * This defines the interface for a cache (wherever that cache is stored).
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	abstract class ElggCache
	{
		/**
		 * Variables for the cache object.
		 *
		 * @var array
		 */
		private $variables;
		
		/**
		 * Set the constructor.
		 */
		function __construct() { $this->variables = array(); }
		
		/**
		 * Set a cache variable.
		 *
		 * @param string $variable
		 * @param string $value
		 */
		public function set_variable($variable, $value) { $this->variables[$variable] = $value;	}
		
		/**
		 * Get variables for this cache.
		 *
		 * @param string $variable
		 * @return mixed The variable or null;
		 */
		public function get_variable($variable) 
		{
			if (isset($this->variables[$variable]))
				return $this->variables[$variable];
				
			return null; 
		}
		
		/**
		 * Save data in a cache.
		 *
		 * @param string $key
		 * @param string $data
		 * @return bool
		 */
		abstract public function save($key, $data);
		
		/**
		 * Load data from the cache using a given key.
		 *
		 * @param string $key
		 * @return mixed The stored data or false.
		 */
		abstract public function load($key);
	}
?>