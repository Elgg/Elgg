<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'blog' => "Blog",
			'blog:user' => "%s's blog",
			'blog:posttitle' => "%s's blog: %s",
			'blog:everyone' => "All blog posts",
	
			'blog:read' => "Read blog",
	
			'blog:addpost' => "Write an entry",
			'blog:editpost' => "Edit entry (%s)",
	
			'blog:text' => "Blog text",
	
			'blog:strapline' => "%s",
	
			'blog:comment:add' => "Add a comment",
			'blog:comment:text' => "Comment text",
	
			'comments' => "Comments",
	
		/**
		 * Status messages
		 */
	
			'blog:posted' => "Your blog post was successfully posted.",
			'comment:success' => "Your comment was successfully added.",
			'blog:deleted' => "Your blog post was successfully deleted.",
			'comment:deleted' => "The comment was successfully deleted.",
	
		/**
		 * Error messages
		 */
	
			'blog:blank' => "Sorry; you need to fill in both the title and body before you can make a post.",
			'blog:notfound' => "Sorry; we could not find the specified blog post.",
			'blog:notdeleted' => "Sorry; we could not delete this blog post.",
	
			'comment:failure' => "An unexpected error occurred when adding your comment. Please try again.",
			'comment:notdeleted' => "The comment could not be deleted.",
	
	);
					
	add_translation("en",$english);

?>