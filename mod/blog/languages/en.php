<?php
/**
 * Blog English language file.
 *
 */

$english = array(
	'blog' => 'Blogs',
	'blog:blogs' => 'Blogs',
	'blog:owned_blogs' => '%s',
	'blog:revisions' => 'Revisions',
	'blog:archives' => 'Archives',
	'blog:blog' => 'Blog',
	'blog:author_by_line' => 'By %s',
	'item:object:blog' => 'Blogs',

	'blog:title:user_blogs' => '%s\'s Blogs',
	'blog:title:all_blogs' => 'All Site Blogs',
	'blog:title:friends' => 'All Friends\' Blogs',

	// Editing
	'blog:new' => 'New blog post',
	'blog:edit' => 'Edit blog post',
	'blog:excerpt' => 'Excerpt',
	'blog:body' => 'Body',
	'blog:save_status' => 'Last saved: ',
	'blog:never' => 'Never',
	'blog:publish_date' => 'Publish Date',

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
	'blog:error:post_not_found' => 'This post has been removed or is invalid.',
	'blog:messages:warning:draft' => 'There is an unsaved draft of this post!',
	'blog:edit_revision_notice' => '(Old version)',
	'blog:message:deleted_post' => 'Blog post deleted.',
	'blog:error:cannot_delete_post' => 'Cannot delete blog post.',
	'blog:none' => 'No blogs found',
	'blog:error:missing:title' => 'Please enter a blog title!',
	'blog:error:missing:description' => 'Please enter the body of your blog!',
	'blog:error:cannot_edit_post' => 'This post may not exist or you may not have permissions to edit it.',
	'blog:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'blog:river:create' => 'published a blog post',
	'river:commented:object:blog' => 'the blog',
);

add_translation('en', $english);
