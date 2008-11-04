<?php
	/**
	 * Elgg memcache support.
	 * 
	 * Requires php5-memcache to work.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */


	class ElggMemcache extends ElggCache
	{
		/**
		 * Namespace variable used for key
		 *
		 * @var string
		 */
		private $namespace;
		
		/**
		 * Memcache object
		 */
		private $memcache;
		
		/**
		 * Expiry of saved items (defaults forever)
		 */
		private $expires = 0;
		
		/**
		 * The version of memcache running
		 */
		private $version = 0;
		
		/**
		 * Keys so far.
		 * This variable holds a list of keys we have seen so that when we call ->clear() we invalidate only those keys.
		 * TODO: Could this be done better?
		 */
		private $keys_so_far = array();
		
		/**
		 * Connect to memcache.
		 * 
		 * @param string $cache_id The namespace for this cache to write to - note, namespaces of the same name are shared!
		 */
		function __construct($namespace = 'default')
		{	
			global $CONFIG;
			
			$this->namespace = $namespace;
			
			// Do we have memcache?
			if (!class_exists('Memcache'))
				throw new ConfigurationException(elgg_echo('memcache:notinstalled'));

			// Create memcache object
			$this->memcache	= new Memcache;
			
			// Now add servers
			if (!$CONFIG->memcache_servers)
				throw new ConfigurationException(elgg_echo('memcache:noservers'));
				
			foreach ($CONFIG->memcache_servers as $server)
			{
				if (is_array($server))
				{
					$this->memcache->addServer(
						$server[0], 
						isset($server[1]) ? $server[1] : 11211,
						isset($server[2]) ? $server[2] : true,
						isset($server[3]) ? $server[3] : null,
						isset($server[4]) ? $server[4] : 1,
						isset($server[5]) ? $server[5] : 15,
						isset($server[6]) ? $server[6] : true
					);
					
				}
				else
					$this->memcache->addServer($server, 11211);
			}
			
			// Get version
			$this->version = $this->memcache->getversion();
			if (version_compare($this->version, '2.0.0', '<'))
				throw new ConfigurationException(sprintf(elgg_echo('memcache:versiontoolow'), $this->version));
			
			// Set some defaults
			if (isset($CONFIG->memcache_expires))
				$this->expires = $CONFIG->memcache_expires;
			
		}
		
		/**
		 * Combine a key with the namespace.
		 * Memcache can only accept <250 char key. If the given key is too long it is shortened.
		 * 
		 * @param string $key The key
		 * @return string The new key. 
		 */
		private function make_memcache_key($key)
		{
			$prefix = $this->namespace . ":";
			
			if (strlen($prefix.$key)> 250)
				$key = md5($key);
				
			return $prefix.$key;
		}
		
		public function save($key, $data) 
		{
			$key = $this->make_memcache_key($key);
			
			return $this->memcache->add($key, $data, null, $this->expires);	
		}
		
		public function load($key, $offset = 0, $limit = null)
		{
			$key = $this->make_memcache_key($key);
			
			return $this->memcache->get($key);
		}
		
		public function delete($key) 
		{
			$key = $this->make_memcache_key($key);
			
			return $this->memcache->delete($key, 0);
		}
		
		public function clear()
		{
			foreach ($this->keys_so_far as $key)
				$this->memcache->delete($key, 0);
				
			$this->keys_so_far = array();
			
			return true;
		}
	}
?>