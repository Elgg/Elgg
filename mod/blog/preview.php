<?php

	/**
	 * Elgg blog: preview page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

		require_once(dirname(dirname(dirname(__FILE__))).'/engine/start.php');

	// Make sure we're logged in (send us to the front page if not)
		gatekeeper();

	// Get input data
	
		$blogpost = new stdClass;
		$blogpost->title = get_input('blogtitle');
		$blogpost->description = get_input('blogbody');
		$blogpost->tags = get_input('blogtags');
		$blogpost->access = get_input('access_id');
		$blogpost->comments_on = get_input('comments_select');
		$blogpost->time_created = time();
		
	// Convert string of tags into a preformatted array
		$blogpost->tags = string_to_tag_array($blogpost->tags);
		
	// Display it
		$area2 = elgg_view("blog/previewpane") .
				 elgg_view("object/blog",array(
											'entity' => $blogpost,
											'entity_owner' => $_SESSION['user']->guid,
											'comments' => false,
											'full' => true
											));
											
	// Set the title appropriately
		$title = sprintf(elgg_echo("blog:posttitle"),$_SESSION['user']->name,$blogpost->title);

	// Display through the correct canvas area
		$body = elgg_view_layout("two_column_left_sidebar", '', $area1 . $area2);			
		page_draw($blogpost->title,$body);

?>
