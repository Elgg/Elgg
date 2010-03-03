<?php

    /**
	 * Elgg Message board history page
	 * 
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 
	 // Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the user who is the owner of the message board
	    $current_user = $_SESSION['user']->getGUID(); //history is only available for your own wall
	    $history_user = get_input('user'); // this is the user how has posted on your messageboard that you want to display your history with
	    
	    
    // Get any annotations for their message board
	//	$contents = $entity->getAnnotations('messageboard', 50, 0, 'desc');
	
	    $users_array = array($current_user, $history_user);
		
		$contents = get_annotations($users_array, "user", "", "messageboard", $value = "", $users_array, $limit = 10, $offset = 0, $order_by = "desc");
	
    // Get the content to display	
		$area2 = elgg_view_title(elgg_echo('messageboard:history:title'));
		$area2 .= elgg_view("messageboard/messageboard", array('annotation' => $contents));
	
	//$area1 = "<h2>Profile owner: " . $current_user . "</h2>";
	//$area1 .= "<h2>User guid: " . $history_user . "</h2>";
	    
		
    //select the correct canvas area
	    $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Display page
		page_draw(elgg_echo('messageboard:history:title'),$body);
	 
?>