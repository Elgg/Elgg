<?php
	/**
	 * Group links
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

    //check to see if the user is looking at their own profile
    if($_SESSION['user']->guid == $vars['entity']->owner_guid){

        echo "<div id=\"profile_menu_wrapper\">"; //start the wrapper div
	    echo elgg_view("groups/menu/actions",$vars);
	    echo elgg_view("groups/menu/ownerlinks",$vars); 
	    echo "</div>"; //close wrapper div 
	    
    } else {
        
        echo "<div id=\"profile_menu_wrapper\">"; //start the wrapper div
        echo elgg_view("groups/menu/actions",$vars); //grab action links such as make friend
	    echo elgg_view("groups/menu/links",$vars); //passive links to items such as user blog etc
	    echo "</div>"; //close wrapper div 
	    
    }

?>