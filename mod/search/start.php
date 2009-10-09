<?php

	/**
	 * Elgg core search.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd <info@elgg.com>
	 * @link http://elgg.org/
	 */

	/**
	 * Initialise search helper functions.
	 *
	 */
	function search_init() {
		global $CONFIG;

		// page handler for search actions and results
		register_page_handler('search','search_page_handler');

		// hook into the search callback to use the metadata system (this is the part that will go away!)
		register_plugin_hook('search:entities', 'all', 'search_original_hook');

		// list of available search types should include our base parts
		register_plugin_hook('searchtypes', 'all', 'search_base_search_types_hook');

		// add in CSS for search elements
		extend_view('css', 'search/css');
	}
	
	/**
	 * Page handler for search
	 *
	 * @param array $page Page elements from pain page handler
	 */
	function search_page_handler($page) {
		global $CONFIG;
		
		if(!get_input('tag')) {
			set_input('tag', $page[0]);	
		}

		include_once($CONFIG->path . "mod/search/index.php");
	}

	/**
	 * Core search hook.
	 * Returns an object with two parts:
	 *    ->entities: an array of instantiated entities that have been decorated with 
	 *                volatile "search" data indicating what they matched. These are
	 *                the entities to be displayed to the user on this page.
	 *    ->total:    total number of entities overall. This function can update this
	 *                limit to ask for more pages in the pagination.
	 */
	function search_original_hook($hook, $type, $returnvalue, $params) {
	    	$tag = $params['tag'];
		$offset = $params['offset']; // starting page
		$limit = $params['limit']; // number per page
		$searchtype = $params['searchtype']; // the search type we're looking for
		$object_type = $params['object_type'];
		$subtype = $params['subtype'];
		$owner_guid = $params['owner_guid'];
		$tagtype = $params['tagtype'];
		
		$count = get_entities_from_metadata($tagtype, elgg_strtolower($tag), $object_type, $subtype, $owner_guid, $limit, $offset, "", 0, true); 
		$ents =  get_entities_from_metadata($tagtype, elgg_strtolower($tag), $object_type, $subtype, $owner_guid, $limit, $offset, "", 0, false);	


		/*
		 * Foreach entity
		 *	get the metadata keys
		 *	If the value matches, hang onto the key
		 *	add all the matched keys to VolatileData
		 *   This tells us *why* each entity matched
		 */
		foreach ($ents as $ent) {
		    	$metadata = get_metadata_for_entity($ent->getGUID());
			$matched = array();
			if ($metadata) {
				foreach ($metadata as $tuple) {
					if ($tag === $tuple->value) {
						// This is one of the matching elements
						$matched[] = $tuple->name;
					}
				}
				$ent->setVolatileData("search", $matched);
			}
		}

		// merge in our entities with any coming in from elsewhere
		$returnvalue->entities = array_merge($returnvalue->entities, $ents);

		// expand the total entity count if necessary
		if ($count > $returnvalue->total) {
		    	$returnvalue->total = $count;
		}

		return $returnvalue;
	}

        /**
	 * return our base search types
	 */
        function search_base_search_types_hook($hook, $type, $returnvalue, $params) {
	    if (!is_array($returnvalue)) {
		$returnvalue = array();
	    }

	    return $returnvalue;
	}

	/** Register init system event **/

	register_elgg_event_handler('init','system','search_init');

?>
