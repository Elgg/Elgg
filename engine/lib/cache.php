<?php
	/**
	 * Elgg cache
	 * Cache file interface for caching data.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * ElggCache The elgg cache superclass.
	 * This defines the interface for a cache (wherever that cache is stored).
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage API
	 */
	abstract class ElggCache implements
		ArrayAccess // Override for array access
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
		public function set_variable($variable, $value) 
		{
			if (!is_array($this->variables))
				$this->variables = array();
			
			$this->variables[$variable] = $value;	
		}
		
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
		 * Class member get overloading, returning key using $this->load defaults.
		 *
		 * @param string $key
		 * @return mixed
		 */
		function __get($key) { return $this->load($key); }
		
		/**
		 * Class member set overloading, setting a key using $this->save defaults.
		 *
		 * @param string $key
		 * @param mixed $value
		 * @return mixed
		 */
		function __set($key, $value) { return $this->save($key, $value); }
		
		/**
		 * Supporting isset, using $this->load() with default values.
		 *
		 * @param string $key The name of the attribute or metadata.
		 * @return bool
		 */
		function __isset($key) { if ($this->load($key)!="") return true; else return false; }
		
		/**
		 * Supporting unsetting of magic attributes.
		 *
		 * @param string $key The name of the attribute or metadata.
		 */
		function __unset($key) { return $this->delete($key); }
		
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
		
		/**
		 * Invalidate a key
		 *
		 * @param string $key
		 * @return bool
		 */
		abstract public function delete($key);
		
		/**
		 * Clear out all the contents of the cache.
		 *
		 */
		abstract public function clear();
		
		// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
		function offsetSet($key, $value)
		{
     		$this->save($key, $value);
 		} 
 		
 		function offsetGet($key) 
 		{
   			return $this->load($key);
 		} 
 		
 		function offsetUnset($key) 
 		{
   			if ( isset($this->key) ) {
     			unset($this->key);
   			}
 		} 
 		
 		function offsetExists($offset) 
 		{
   			return isset($this->$offset);
 		} 
	}
	
	/**
	 * ElggStaticVariableCache
	 * Dummy cache which stores values in a static array. Using this makes future replacements to other caching back 
	 * ends (eg memcache) much easier.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage API
	 */
	class ElggStaticVariableCache extends ElggCache
	{
		/**
		 * The cache.
		 *
		 * @var unknown_type
		 */
		public static $__cache;
		
		/**
		 * ID of a cache to use.
		 *
		 * @var unknown_type
		 */
		private $cache_id;
		
		/**
		 * Create the variable cache.
		 * 
		 * This function creates a variable cache in a static variable in memory, optionally with a given namespace (to avoid overlap).
		 *
		 * @param string $cache_id The namespace for this cache to write to - note, namespaces of the same name are shared!
		 */
		function __construct($cache_id = 'default')
		{	
			$this->cache_id = $cache_id;
			$this->clear();
		}
		
		public function save($key, $data) 
		{
			ElggStaticVariableCache::$__cache[$this->cache_id][$key] = $data;
			
			return true;
		}
		
		public function load($key, $offset = 0, $limit = null)
		{
			return ElggStaticVariableCache::$__cache[$this->cache_id][$key];
		}
		
		public function delete($key) 
		{
			unset(ElggStaticVariableCache::$__cache[$this->cache_id][$key]);
			
			return true;
		}
		
		public function clear()
		{
			if (!ElggStaticVariableCache::$__cache)
				ElggStaticVariableCache::$__cache = array();
				
			if (!ElggStaticVariableCache::$__cache[$this->cache_id])
				ElggStaticVariableCache::$__cache[$this->cache_id] = array();
		}
	}
	
	/**
	 * ElggFileCache
	 * Store cached data in a file store.
	 * 
	 * @author Curverider Ltd <info@elgg.com>
	 * @package Elgg
	 * @subpackage API
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

			if ($cache_path=="") throw new ConfigurationException(elgg_echo('ConfigurationException:NoCachePath'));
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
		
		/**
		 * Save a key
		 *
		 * @param string $key
		 * @param string $data
		 * @return boolean
		 */
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
		
		/**
		 * Load a key
		 *
		 * @param string $key
		 * @param int $offset
		 * @param int $limit
		 * @return string
		 */
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
		
		/**
		 * Invalidate a given key.
		 *
		 * @param string $key
		 * @return bool
		 */
		public function delete($key)
		{
			$dir = $this->get_variable("cache_path");
			
			return unlink($dir.$f);
		}
		
		public function clear()
		{
			// TODO : writeme
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
			if (!$files) throw new IOException(sprintf(elgg_echo('IOException:NotDirectory'), $dir));
			
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