<?php
	/**
	 * Elgg search helper functions.
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
	function search_init()
	{
		register_page_handler('search','search_page_handler');
	}
	
	/**
	 * Page handler for search
	 *
	 * @param array $page Page elements from pain page handler
	 */
	function search_page_handler($page) 
	{
		global $CONFIG;
		
		if(!get_input('tag')) {
			set_input('tag', $page[0]);	
		}

		if (isset($page[0])) {
			switch ($page[0]) {
				case 'user' :
				case 'users' : include_once($CONFIG->path . "search/users.php"); break;
				
				case 'group' :
				case 'groups' : include_once($CONFIG->path . "search/groups.php"); break;
				
				default: include_once($CONFIG->path . "search/index.php");
			}
		}
		else
			include_once($CONFIG->path . "search/index.php");
	}

	/** Register init system event **/
	register_elgg_event_handler('init','system','search_init');
?>
