<?php
/**
 * Blog English langauge file.
 *
 */

$english = array(
	'item:object:blog' => 'Blog',
	'blog:blogs' => 'Blogs',
	'blog:owned_blogs' => '%s blogs',
	'blog:revisions' => 'Revisions',
	'blog:archives' => 'Archives',

	'blog:blog' => 'Blog',
	'blog:yours' => 'Your blog',
	'blog:all' => 'All blogs',
	'blog:friends' => 'Friends\' blogs',

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
	'blog:status:unsaved_draft' => 'Recovered Draft',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Auto Saved Revision',
	'blog:owner_title' => '%s\'s blogs',

	// messages
	'blog:message:saved' => 'Blog post saved.',
	'blog:error:cannot_save' => 'Cannot save blog post.',
	'blog:error:cannot_write_to_container' => 'Insufficient access to save blog to group.',
	'blog:error:post_not_found' => 'This post has been removed or is invalid.',
	'blog:messages:warning:draft' => 'There is an unsaved draft of this post!',
	'blog:edit_revision_notice' => '(Old version)',



);

add_translation('en', $english);