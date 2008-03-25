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
		 * @param int $offset 
		 * @param int $limit
		 * @return mixed The stored data or false.
		 */
		abstract public function load($key, $offset = 0, $limit = null);
	}
	
	/**
	 * @class ElggFileCache
	 * Store cached data in a file store.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ElggFileCache extends ElggCache
	{
		/**
		 * Set the Elgg cache.
		 *
		 * @param string $cache_path The cache path.
		 * @param int $max_age Maximum age in seconds, 0 if no limit.
		 * @param int $max_size Maximum size of cache in seconds, 0 if no limit.
		 */
		function __construct($cache_path, $max_age = 0, $max_size = 0)
		{
			$this->set_variable("cache_path", $cache_path);
			$this->set_variable("max_age", $max_age);
			$this->set_variable("max_size", $max_size);	

			if ($cache_path=="") throw new ConfigurationException("Cache path set to nothing!");
		}
		
		/**
		 * Create and return a handle to a file.
		 *
		 * @param string $filename
		 * @param string $rw
		 */
		protected function create_file($filename, $rw = "rb")
		{
			// Create a filename matrix
			$matrix = "";
			$depth = strlen($filename);
			if ($depth > 5) $depth = 5;
			 
		//	for ($n = 0; $n < $depth; $n++)
		//		$matrix .= $filename[$n] . "/";	
				
			// Create full path
			$path = $this->get_variable("cache_path") . $matrix;
			
	//		if (!mkdir($path, 0700, true)) throw new IOException("Could not make $path");
			
			// Open the file
			if ((!file_exists($path . $filename)) && ($rw=="rb")) return false;
			
			return fopen($path . $filename, $rw);
		}
		
		/**
		 * Create a sanitised filename for the file.
		 *
		 * @param string $filename
		 */
		protected function sanitise_filename($filename)
		{
			// TODO : Writeme

			return $filename;
		}
		
		public function save($key, $data)
		{
			$f = $this->create_file($this->sanitise_filename($key), "wb");
			if ($f)
			{
				$result = fwrite($f, $data);
				fclose($f);
				
				return $result;
			}
			
			return false;
		}
		
		public function load($key, $offset = 0, $limit = null)
		{
			$f = $this->create_file($this->sanitise_filename($key));
			if ($f) 
			{
				fseek($f, $offset);
				$data = stream_get_contents($f, $limit, $offset);
				fclose($f);
				
				return $data;
			}
			
			return false;
		}
		
		public function __destruct()
		{
			// TODO: Check size and age, clean up accordingly
			$size = 0;
			$dir = $this->get_variable("cache_path");
			
			// Short circuit if both size and age are unlimited
			if (($this->get_variable("max_age")==0) && ($this->get_variable("max_size")==0))
				return;
			
			$exclude = array(".","..");
			
			$files = scandir($dir);
			if (!$files) throw new IOException("$dir is not a directory.");
			
			// Perform cleanup
			foreach ($files as $f)
			{
				if (!in_array($f, $exclude))
				{
					$stat = stat($dir.$f);
					
					// Add size
					$size .= $stat['size'];
					
					// Is this older than my maximum date?
					if (($this->get_variable("max_age")>0) && (time() - $stat['mtime'] > $this->get_variable("max_age")))
						unlink($dir.$f);
					
					
					
					// TODO: Size
					
				}
			}
		}
	}
?>