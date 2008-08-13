<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'blog' => "Blog",
			'blogs' => "Blogs",
			'blog:user' => "%s's blog",
			'blog:user:friends' => "%s's friends' blog",
			'blog:your' => "Your blog",
			'blog:posttitle' => "%s's blog: %s",
			'blog:friends' => "Friends' blogs",
			'blog:yourfriends' => "Your friends' latest blogs",
			'blog:everyone' => "All site blogs",
	
			'blog:read' => "Read blog",
	
			'blog:addpost' => "Write a blog post",
			'blog:editpost' => "Edit blog post",
	
			'blog:text' => "Blog text",
	
			'blog:strapline' => "%s",
			
			'item:object:blog' => 'Blog posts',
	
			
         /**
	     * Blog river
	     **/
	        
	        //generic terms to use
	        'blog:river:created' => "%s wrote",
	        'blog:river:updated' => "%s updated",
	        'blog:river:posted' => "%s posted",
	        
	        //these get inserted into the river links to take the user to the entity
	        'blog:river:create' => "a new blog post.",
	        'blog:river:update' => "a blog post.",
	        'blog:river:annotate:create' => "a comment on a blog post.",
			
	
		/**
		 * Status messages
		 */
	
			'blog:posted' => "Your blog post was successfully posted.",
			'blog:deleted' => "Your blog post was successfully deleted.",
	
		/**
		 * Error messages
		 */
	
			'blog:save:failure' => "Your blog post could not be saved. Please try again.",
			'blog:blank' => "Sorry; you need to fill in both the title and body before you can make a post.",
			'blog:notfound' => "Sorry; we could not find the specified blog post.",
			'blog:notdeleted' => "Sorry; we could not delete this blog post.",
	
	);
					
	add_translation("en",$english);

?>