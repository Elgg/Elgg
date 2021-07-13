<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'item:object:bookmarks' => 'Bookmark',
	'collection:object:bookmarks' => 'Bookmarks',
	'collection:object:bookmarks:group' => 'Group bookmarks',
	'collection:object:bookmarks:all' => "All site bookmarks",
	'collection:object:bookmarks:owner' => "%s's bookmarks",
	'collection:object:bookmarks:friends' => "Friends' bookmarks",
	'add:object:bookmarks' => "Add a bookmark",
	'edit:object:bookmarks' => "Edit bookmark",
	'notification:object:bookmarks:create' => "Send a notification when a bookmark is created",
	'notifications:mute:object:bookmarks' => "about the bookmark '%s'",

	'bookmarks:this' => "Bookmark this page",
	'bookmarks:this:group' => "Bookmark in %s",
	'bookmarks:bookmarklet' => "Get bookmarklet",
	'bookmarks:bookmarklet:group' => "Get group bookmarklet",
	'bookmarks:address' => "Address of the bookmark",
	'bookmarks:none' => 'No bookmarks',

	'bookmarks:notify:summary' => 'New bookmark called %s',
	'bookmarks:notify:subject' => 'New bookmark: %s',
	'bookmarks:notify:body' => '%s added a new bookmark: %s

Address: %s

%s

View and comment on the bookmark:
%s',

	'bookmarks:numbertodisplay' => 'Number of bookmarks to display',

	'river:object:bookmarks:create' => '%s bookmarked %s',
	'river:object:bookmarks:comment' => '%s commented on a bookmark %s',

	'groups:tool:bookmarks' => 'Enable group bookmarks',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Bookmarks',
	'widgets:bookmarks:description' => "Display your latest bookmarks.",

	'bookmarks:bookmarklet:description' => "A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks. To set it up, drag the button below to your browser's links bar:",
	'bookmarks:bookmarklet:descriptionie' => "If you are using Internet Explorer, you will need to right click on the bookmarklet icon, select 'add to favorites', and then the Links bar.",
	'bookmarks:bookmarklet:description:conclusion' => "You can then bookmark any page you visit by clicking the button in your browser at any time.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Your item was successfully bookmarked.",
	'entity:delete:object:bookmarks:success' => "The bookmark was deleted.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Your bookmark could not be saved. Make sure you've entered a title and address and then try again.",
);
