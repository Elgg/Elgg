<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:bin' => 'bin',
	'collection:object:bin' => 'bin',
	'collection:object:bin:all' => 'All site bin',
	'collection:object:bin:owner' => '%s\'s bin',

	'bin:revisions' => 'Revisions',
	'bin:archives' => 'Archives',

	// messages
	'bin:error:cannot_write_to_container' => 'Insufficient access to save bin to group.',
	'bin:edit_revision_notice' => '(Old version)',
	'bin:none' => 'No bin posts',
	'bin:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'river:object:bin:create' => '%s published a bin post %s',
	'river:object:bin:comment' => '%s commented on the bin %s',

	// notifications
	'bin:notify:summary' => 'New bin post called %s',
	'bin:notify:subject' => 'New bin post: %s',
	'bin:notify:body' => '%s published a new bin post: %s

%s

View and comment on the bin post:
%s',
	
	'notification:mentions:object:bin:subject' => '%s mentioned you in a bin post',

	// widget
	'widgets:bin:name' => 'bin posts',
	'widgets:bin:description' => 'Display your latest bin posts',
	'bin:morebin' => 'More bin posts',
	'bin:numbertodisplay' => 'Number of bin posts to display',
);
