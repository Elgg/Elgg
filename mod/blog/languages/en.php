<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'blog' => "Blog", 
			'blogs' => "Blogs",
			'blog:user' => "%s's blog",
			'blog:user:friends' => "%s's friends' blog",
			'blog:yours' => "My blog",
			'blog:posttitle' => "%s's blog: %s",
			'blog:friends' => "Friends' blogs",
			'blog:workgroup' => "Work Group blogs",
			'blog:yourfriends' => "Your friends' latest blogs",
			'blog:all' => "All site blogs",
			'blog:new' => "New blog post",
			'blog:posts' => "Latest blog posts",
			'blog:title' => "Blog title",
			'blog:via' => "via blog",
			'blog:read' => "Read blog",
			'blog:backto' => "Back to blogs",
			'blog:addpost' => "New blog post",
			'blog:editpost' => "Edit blog post",
			'blog:defaultaccess' => "Your site wide access level is:",
			'blog:text' => "Blog text",
			'blog:access' => "This blog's access is:",
			'blog:strapline' => "%s",
			'blog:none' => "There are no blog posts to display",
			'item:object:blog' => 'Blog posts',
			'blog:latestcomments' => 'Latest comments',
			'blog:never' => 'never',
			'blog:preview' => 'Preview',
			'blog:archive' => 'Archive',
			'blog:excerpt' => 'Excerpt (Optional)',
			'blog:excerptdesc' => 'An optional short summary, displayed on blog and search listings<br />(instead of the first 200 characters).',
			'blog:draft:save' => 'Save draft',
			'blog:readmore' => 'Read more',
			'blog:draft:saved' => 'Draft last saved',
			'blog:comments:allow' => 'Allow comments',
			'blog:widget:description' => 'This widget will display your latest blog post titles on your profile.',
	
			'blog:preview:description' => 'This is an unsaved preview of your blog post.',
			'blog:preview:description:link' => 'To continue editing or save your post, click here.',
	
			'blog:enableblog' => 'Enable community blog',
	
			'blog:group' => 'Group blog',
			
         /**
	     * Blog river
	     **/
	        
	        //generic terms to use
	        'blog:river:created' => "%s wrote",
	        'blog:river:updated' => "%s updated",
	        'blog:river:posted' => "%s posted",
	        
	        //these get inserted into the river links to take the user to the entity
	        'blog:river:create' => "a blog post",
	        'blog:river:update' => "a blog post",
	        'blog:river:annotate' => "a comment on the blog post",
			
	
		/**
		 * Status messages
		 */
	
			'blog:posted' => "Your blog post was successfully posted.",
			'blog:deleted' => "Your blog post was successfully deleted.",
	
		/**
		 * Error messages
		 */
	
			'blog:error' => 'Something went wrong. Please try again.',
			'blog:save:failure' => "Your blog post could not be saved. Please try again.",
			'blog:blank' => "Sorry; you need to fill in both the title and body before you can make a post.",
			'blog:notfound' => "Sorry; we could not find the specified blog post.",
			'blog:notdeleted' => "Sorry; we could not delete this blog post.",
	
	);
					
	add_translation("en",$english);

?>