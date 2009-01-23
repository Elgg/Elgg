<?php

	/**
	 * Elgg profile links
	 * We need to make sure that the correct links display depending on whether you are looking at your own 
	 * profile or someone else's
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

?>

<?php

	$banned = false;
	$owner = page_owner_entity();
	if ($owner) $banned = $owner->isBanned();

	// Allow menus if not banned or admin logged in
	if ((!$banned) || (isadminloggedin()))
	{
	    //check to see if the user is looking at their own profile
	    if ($_SESSION['user']->guid == page_owner()){
	
	        echo "<div id=\"profile_menu_wrapper\">"; //start the wrapper div
		    echo elgg_view("profile/menu/actions",$vars);//grab action links such as make friend
		    echo elgg_view("profile/menu/linksownpage",$vars); // an different view for user's own profile
		    echo "</div>"; //close wrapper div 
		    
	    } else {
	        
	        echo "<div id=\"profile_menu_wrapper\">"; //start the wrapper div
	        echo elgg_view("profile/menu/actions",$vars); //grab action links such as make friend
		    echo elgg_view("profile/menu/links",$vars); //passive links to items such as user blog etc
		    echo "</div>"; //close wrapper div 
		    
	    }
	}
	else
	{ 	// Some nice spacing
		echo "<div id=\"profile_menu_wrapper\">"; //start the wrapper div
	    echo "</div>"; //close wrapper div 
	}
?>