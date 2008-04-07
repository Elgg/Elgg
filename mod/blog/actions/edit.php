<?php

	/**
	 * Elgg blog: edit post action
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();

	// Get input data
		$guid = (int) get_input('blogpost');
		$title = get_input('blogtitle');
		$body = get_input('blogbody');
		$tags = get_input('blogtags');
		
	// Make sure we actually have permission to edit
		$blog = get_entity($guid);
		if ($blog->getSubtype() == "blog" && $blog->canEdit()) {
	
		// Cache to the session
			$_SESSION['blogtitle'] = $title;
			$_SESSION['blogbody'] = $body;
			$_SESSION['blogtags'] = $tags;
			
		// Convert string of tags into a preformatted array
			$tagarray = string_to_tag_array($tags);
			
		// Make sure the title / description aren't blank
			if (empty($title) || empty($body)) {
				register_error(elgg_echo("blog:blank"));
				forward("mod/blog/add.php");
				
		// Otherwise, save the blog post 
			} else {
				
		// Get owning user
				$owner = get_entity($blog->getOwner());
		// For now, set its access to public (we'll add an access dropdown shortly)
				$blog->access_id = 2;
		// Set its title and description appropriately
				$blog->title = $title;
				$blog->description = $body;
		// Before we can set metadata, we need to save the blog post
				if (!$blog->save()) {
					register_error(elgg_echo("blog:error"));
					forward("mod/blog/add.php");
				}
		// Now let's add tags. We can pass an array directly to the object property! Easy.
				$blog->clearMetadata('tags');
				if (is_array($tagarray)) {
					$blog->tags = $tagarray;
				}
		// Success message
				system_message(elgg_echo("blog:posted"));
		// Remove the blog post cache
				unset($_SESSION['blogtitle']); unset($_SESSION['blogbody']); unset($_SESSION['blogtags']);
		// Forward to the main blog page
				forward("mod/blog/?username=" . $owner->username);
					
			}
		
		}
		
?>