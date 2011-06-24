<?php
/**
 * Bookmarks English language file
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Bookmarks",
	'bookmarks:add' => "Add bookmark",
	'bookmarks:edit' => "Edit bookmark",
	'bookmarks:owner' => "%s's bookmarks",
	'bookmarks:friends' => "Friends' bookmarks",
	'bookmarks:everyone' => "All site bookmarks",
	'bookmarks:this' => "Bookmark this page",
	'bookmarks:this:group' => "Bookmark in %s",
	'bookmarks:bookmarklet' => "Get bookmarklet",
	'bookmarks:bookmarklet:group' => "Get group bookmarklet",
	'bookmarks:inbox' => "Bookmarks inbox",
	'bookmarks:morebookmarks' => "More bookmarks",
	'bookmarks:more' => "More",
	'bookmarks:with' => "Share with",
	'bookmarks:new' => "A new bookmark",
	'bookmarks:via' => "via bookmarks",
	'bookmarks:address' => "Address of the resource to bookmark",
	'bookmarks:none' => 'No bookmarks',

	'bookmarks:delete:confirm' => "Are you sure you want to delete this resource?",

	'bookmarks:numbertodisplay' => 'Number of bookmarks to display',

	'bookmarks:shared' => "Bookmarked",
	'bookmarks:visit' => "Visit resource",
	'bookmarks:recent' => "Recent bookmarks",

	'river:create:object:bookmarks' => '%s bookmarked %s',
	'river:comment:object:bookmarks' => '%s commented on a bookmark %s',
	'bookmarks:river:annotate' => 'a comment on this bookmark',
	'bookmarks:river:item' => 'an item',

	'item:object:bookmarks' => 'Bookmarks',

	'bookmarks:group' => 'Group bookmarks',
	'bookmarks:enablebookmarks' => 'Enable group bookmarks',
	'bookmarks:nogroup' => 'This group does not have any bookmarks yet',
	'bookmarks:more' => 'More bookmarks',

	'bookmarks:no_title' => 'No title',

	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Display your latest bookmarks.",

	'bookmarks:bookmarklet:description' =>
			"The bookmarks bookmarklet allows you to share any resource you find on the web with your friends, or just bookmark it for yourself. To use it, simply drag the following button to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"If you are using Internet Explorer, you will need to right click on the bookmarklet icon, select 'add to favorites', and then the Links bar.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"You can then save any page you visit by clicking it at any time.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Your item was successfully bookmarked.",
	'bookmarks:delete:success' => "Your bookmarked item was successfully deleted.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Your bookmark could not be saved. Make sure you've entered a title and address and then try again.",
	'bookmarks:delete:failed' => "Your bookmark could not be deleted. Please try again.",
);

add_translation('en', $english);