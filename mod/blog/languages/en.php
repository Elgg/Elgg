<?php
/**
 * Blog English language file.
 *
 */

$english = array(
	'blog' => 'Blogs',
	'blog:blogs' => 'Blogs',
	'blog:revisions' => 'Revisions',
	'blog:archives' => 'Archives',
	'blog:blog' => 'Blog',
	'item:object:blog' => 'Blogs',

	'blog:title:user_blogs' => '%s\'s Blogs',
	'blog:title:all_blogs' => 'All Site Blogs',
	'blog:title:friends' => 'Friends\' Blogs',

	'blog:group' => 'Group blog',
	'blog:enableblog' => 'Enable group blog',
	'blog:write' => 'Write a blog post',

	// Editing
	'blog:add' => 'Add blog post',
	'blog:edit' => 'Edit blog post',
	'blog:excerpt' => 'Excerpt',
	'blog:body' => 'Body',
	'blog:save_status' => 'Last saved: ',
	'blog:never' => 'Never',

	// Statuses
	'blog:status' => 'Status',
	'blog:status:draft' => 'Draft',
	'blog:status:published' => 'Published',
	'blog:status:unsaved_draft' => 'Unsaved Draft',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Auto Saved Revision',

	// messages
	'blog:message:saved' => 'Blog post saved.',
	'blog:error:cannot_save' => 'Cannot save blog post.',
	'blog:error:cannot_write_to_container' => 'Insufficient access to save blog to group.',
	'blog:error:post_not_found' => 'This post has been removed, is invalid, or you do not have permission to view it.',
	'blog:messages:warning:draft' => 'There is an unsaved draft of this post!',
	'blog:edit_revision_notice' => '(Old version)',
	'blog:message:deleted_post' => 'Blog post deleted.',
	'blog:error:cannot_delete_post' => 'Cannot delete blog post.',
	'blog:none' => 'No blog posts',
	'blog:error:missing:title' => 'Please enter a blog title!',
	'blog:error:missing:description' => 'Please enter the body of your blog!',
	'blog:error:cannot_edit_post' => 'This post may not exist or you may not have permissions to edit it.',
	'blog:error:revision_not_found' => 'Cannot find this revision.',

	// river
	
	'river:create:object:blog' => '%s published a blog post %s',
	'river:comment:object:blog' => '%s commented on the blog %s',

	// widget
	'blog:widget:description' => 'Display your latest blog posts',
	'blog:moreblogs' => 'More blog posts',
	'blog:numbertodisplay' => 'Number of blog posts to display',
	'blog:noblogs' => 'No blog posts'
);

add_translation('en', $english);
