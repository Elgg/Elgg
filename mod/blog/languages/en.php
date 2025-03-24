<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blog',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'All site blogs',
	'collection:object:blog:owner' => '%s\'s blogs',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Add blog post',
	'edit:object:blog' => 'Edit blog post',
	'notification:object:blog:publish' => "Send a notification when a blog is published",
	'notifications:mute:object:blog' => "about the blog '%s'",
	'menu:blog_archive:header' => "Blog archive",
	
	'entity:edit:object:blog:success' => 'The blog was saved successfully',

	'blog:revisions' => 'Revisions',
	'blog:archives' => 'Archives',

	'groups:tool:blog' => 'Enable group blog',
	'groups:tool:blog:description' => 'Allow group members to write blogs in this group.',

	// Editing
	'blog:excerpt' => 'Excerpt',
	'blog:body' => 'Body',
	'blog:save_status' => 'Last saved: ',

	'blog:revision' => 'Revision',
	
	// messages
	'blog:message:saved' => 'Blog post saved.',
	'blog:error:cannot_save' => 'Cannot save blog post.',
	'blog:error:cannot_write_to_container' => 'Insufficient access to save blog to group.',
	'blog:edit_revision_notice' => '(Old version)',
	'blog:none' => 'No blog posts',
	'blog:error:missing:title' => 'Please enter a blog title!',
	'blog:error:missing:description' => 'Please enter the body of your blog!',
	'blog:error:post_not_found' => 'Cannot find specified blog post.',
	'blog:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'New blog post called %s',
	'blog:notify:subject' => 'New blog post: %s',
	'blog:notify:body' => '%s published a new blog post: %s

%s

View and comment on the blog post:
%s',
	
	'notification:mentions:object:blog:subject' => '%s mentioned you in a blog post',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Display your latest blog posts',
	'blog:moreblogs' => 'More blog posts',
	'blog:numbertodisplay' => 'Number of blog posts to display',
);
