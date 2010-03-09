<?php

	/**
	 * Elgg blog archive page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		
	// Get timestamp upper and lower bounds
		$timelower = (int) get_input('param2');
		$timeupper = (int) get_input('param3');
		if (empty($timelower)) {
			forward('pg/blog/'.$page_owner->username);
			exit;
		}
		if (empty($timeupper)) {
			$timeupper = $timelower + (86400 * 30);
		}

	// Set blog title
		$area2 = elgg_view_title(sprintf(elgg_echo('date:month:'.date('m',$timelower)),date('Y',$timelower)));
		
	// Get a list of blog posts
		// note: this does not pass offset because list_user_objects gets it from input
		$area2 .= list_user_objects($page_owner->getGUID(),'blog',10,false,false,true,$timelower,$timeupper);

	// Get blog tags

	// Get blog categories
		
	// Display them in the page
        $body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2);
		
	// Display page
		page_draw(sprintf(elgg_echo('blog:user'),$page_owner->name),$body);
		
?>