<?php

	/**
	 * Elgg friends collection
	 * Lists one of a user's friends collections
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @see collections.php
	 * 
	 * @uses $vars['collection'] The individual friends collection
	 */

			$coll = $vars['collection'];

			if (is_array($vars['collection']->entities)) {
				$count = sizeof($vars['collection']->entities);
			} else {
				$count = 0;
			}
			
			echo "<li><h2>";
        	
        	//as collections are private, check that the logged in user is the owner
        	if($coll->owner_guid == $_SESSION['user']->getGUID())
        	    echo "<div class=\"friends_collections_controls\"> (<a href=\"" . $vars['url'] . "mod/friends/edit.php?collection={$coll->id}\">" . elgg_echo('edit') . "</a>) (<a href=\"" . $vars['url'] . "action/friends/deletecollection?collection={$coll->id}\">" . elgg_echo('delete') . "</a>)";
        	    
			echo "</div>";
			echo $coll->name;
			echo " ({$count}) </h2>";
        	
        	// Ben - this is where the friends picker view needs to go
        	if($members = $vars['collection']->entities){
				echo elgg_view('friends/picker',array('entities' => $members));
    	    }
    	    
    	    // close friends_picker div and the accordian list item
    	    echo "</li>";

?>