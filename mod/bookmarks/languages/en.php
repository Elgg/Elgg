<?php
return array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Bookmarks",
	'bookmarks:add' => "Add a bookmark",
	'bookmarks:edit' => "Edit bookmark",
	'bookmarks:owner' => "%s's bookmarks",
	'bookmarks:friends' => "Friends' bookmarks",
	'bookmarks:everyone' => "All site bookmarks",
	'bookmarks:this' => "Bookmark this page",
	'bookmarks:this:group' => "Bookmark in %s",
	'bookmarks:bookmarklet' => "Get bookmarklet",
	'bookmarks:bookmarklet:group' => "Get group bookmarklet",
	'bookmarks:inbox' => "Bookmarks inbox",
	'bookmarks:with' => "Share with",
	'bookmarks:new' => "A new bookmark",
	'bookmarks:address' => "Address of the bookmark",
	'bookmarks:none' => 'No bookmarks',

	'bookmarks:notify:summary' => 'New bookmark called %s',
	'bookmarks:notify:subject' => 'New bookmark: %s',
	'bookmarks:notify:body' =>
'%s added a new bookmark: %s

Address: %s

%s

View and comment on the bookmark:
%s
',

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
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Display your latest bookmarks.",

	'bookmarks:bookmarklet:description' =>
			"A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks, and optionally share it with your friends. To set it up, drag the button below to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"If you are using Internet Explorer, you will need to right click on the bookmarklet icon, select 'add to favorites', and then the Links bar.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"You can then bookmark any page you visit by clicking the button in your browser at any time.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Your item was successfully bookmarked.",
	'bookmarks:delete:success' => "Your bookmark was deleted.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Your bookmark could not be saved. Make sure you've entered a title and address and then try again.",
	'bookmarks:save:invalid' => "The address of the bookmark is invalid and could not be saved.",
	'bookmarks:delete:failed' => "Your bookmark could not be deleted. Please try again.",
	'bookmarks:unknown_bookmark' => 'Cannot find specified bookmark',
);
