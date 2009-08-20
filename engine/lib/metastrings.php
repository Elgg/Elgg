<?php
	/**
	 * Elgg metastrngs
	 * Functions to manage object metastrings.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd <info@elgg.com>
	 * @link http://elgg.org/
	 */

	/** Cache metastrings for a page */
	$METASTRINGS_CACHE = array();
	
	/** Keep a record of strings we know don't exist */
	$METASTRINGS_DEADNAME_CACHE = array();

	/**
	 * Return the meta string id for a given tag, or false.
	 * 
	 * @param string $string The value (whatever that is) to be stored
	 * @param bool $case_sensitive Do we want to make the query case sensitive?
	 * @return mixed Integer tag or false.
	 */
	function get_metastring_id($string, $case_sensitive = true)
	{
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		
		$string = sanitise_string($string);
		$result = array_search($string, $METASTRINGS_CACHE);
		if ($result!==false) {
			
			if (isset($CONFIG->debug) && $CONFIG->debug)
				error_log("** Returning id for string:$string from cache.");
			
			return $result;
		}
			
		// See if we have previously looked for this and found nothing
		if (in_array($string, $METASTRINGS_DEADNAME_CACHE))
			return false;
		
		// Experimental memcache
		$msfc = null;
		static $metastrings_memcache;
		if ((!$metastrings_memcache) && (is_memcache_available()))
			$metastrings_memcache = new ElggMemcache('metastrings_memcache');
		if ($metastrings_memcache) $msfc = $metastrings_memcache->load($string);
		if ($msfc) return $msfc;
			
		// Case sensitive
		$cs = "";
		if ($case_sensitive) $cs = " BINARY ";
		
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where string=$cs'$string' limit 1");
		if ($row) { 
			$METASTRINGS_CACHE[$row->id] = $row->string; // Cache it
			
			// Attempt to memcache it if memcache is available
			if ($metastrings_memcache) $metastrings_memcache->save($row->string, $row->id);
			
			if (isset($CONFIG->debug) && $CONFIG->debug)
				error_log("** Cacheing string '{$row->string}'");
				
			return $row->id;
		}
		else
			$METASTRINGS_DEADNAME_CACHE[$string] = $string;
			
		return false;
	}
	
	/**
	 * When given an ID, returns the corresponding metastring
	 *
	 * @param int $id Metastring ID
	 * @return string Metastring
	 */
	function get_metastring($id) {
		
		global $CONFIG, $METASTRINGS_CACHE;
		
		$id = (int) $id;
		
		if (isset($METASTRINGS_CACHE[$id])) {
			
			if ($CONFIG->debug)
				error_log("** Returning string for id:$id from cache.");
			
			return $METASTRINGS_CACHE[$id];
		}
		
		$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where id='$id' limit 1");
		if ($row) {
			$METASTRINGS_CACHE[$id] = $row->string; // Cache it
			
			if ($CONFIG->debug)
				error_log("** Cacheing string '{$row->string}'");
			
			return $row->string;
		}
			
		return false;
		
	}

	/**
	 * Add a metastring.
	 * It returns the id of the tag, whether by creating it or updating it.
	 * 
	 * @param string $string The value (whatever that is) to be stored
	 * @param bool $case_sensitive Do we want to make the query case sensitive?
	 * @return mixed Integer tag or false.
	 */
	function add_metastring($string, $case_sensitive = true)
	{
		global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;
		
		$sanstring = sanitise_string($string);
		
		$id = get_metastring_id($string, $case_sensitive);
		if ($id) return $id;
		
		$result = insert_data("INSERT into {$CONFIG->dbprefix}metastrings (string) values ('$sanstring')");
		if ($result) {
			$METASTRINGS_CACHE[$result] = $string;
			if (isset($METASTRINGS_DEADNAME_CACHE[$string])) unset($METASTRINGS_DEADNAME_CACHE[$string]);
		}
			
		return $result;
	}
	
	/**
	 * Delete any orphaned entries in metastrings. This is run by the garbage collector.
	 * 
	 */
	function delete_orphaned_metastrings()
	{
		global $CONFIG;
		
		// If memcache is enabled then we need to flush it of deleted values
		if (is_memcache_available())
		{
			$select_query = "
			SELECT * 
			from {$CONFIG->dbprefix}metastrings where 
			( 
				(id not in (select name_id from {$CONFIG->dbprefix}metadata)) AND 
				(id not in (select value_id from {$CONFIG->dbprefix}metadata)) AND 
				(id not in (select name_id from {$CONFIG->dbprefix}annotations)) AND 
				(id not in (select value_id from {$CONFIG->dbprefix}annotations))   
			)";
			
			$dead = get_data($select_query);
			if ($dead)
			{
				static $metastrings_memcache;
				if (!$metastrings_memcache)
					$metastrings_memcache = new ElggMemcache('metastrings_memcache');
				foreach ($dead as $d)
					$metastrings_memcache->delete($d->string);
			}
		}
		
		$query = "
			DELETE 
			from {$CONFIG->dbprefix}metastrings where 
			( 
				(id not in (select name_id from {$CONFIG->dbprefix}metadata)) AND 
				(id not in (select value_id from {$CONFIG->dbprefix}metadata)) AND 
				(id not in (select name_id from {$CONFIG->dbprefix}annotations)) AND 
				(id not in (select value_id from {$CONFIG->dbprefix}annotations))   
			)";
			
		return delete_data($query);
	}
	
?>