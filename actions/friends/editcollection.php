<?php

	/**
	 * Elgg collection add page
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	 
	 $collection_id = get_input('collection_id');
	 $friends = get_input('friend');
	 
	 //chech the collection exists and the current user owners it
	 update_access_collection($collection_id, $friends);
	 
?>