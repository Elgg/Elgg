<?php
/**
 * Pages languages
 *
 * @package ElggPages
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Pages",
	'pages:owner' => "%s's pages",
	'pages:friends' => "Friends' pages",
	'pages:all' => "All site pages",
	'pages:add' => "New page",

	'pages:group' => "Group pages",
	'groups:enablepages' => 'Enable group pages',

	'pages:edit' => "Edit this page",
	'pages:delete' => "Delete this page",
	'pages:history' => "Page history",
	'pages:view' => "View page",

	'pages:navigation' => "Page navigation",
	'pages:via' => "via pages",
	'item:object:page_top' => 'Top-level pages',
	'item:object:page' => 'Pages',
	'pages:nogroup' => 'This group does not have any pages yet',
	'pages:more' => 'More pages',
	'pages:none' => 'No pages created yet',

	/**
	* River
	**/

	'pages:river:create' => 'created the page',
	'pages:river:created' => "%s wrote",
	'pages:river:updated' => "%s updated",
	'pages:river:posted' => "%s posted",
	'pages:river:update' => "a page titled",
	'river:commented:object:page' => 'the page',
	'river:commented:object:page_top' => 'the page',

	/**
	 * Form fields
	 */

	'pages:title' => 'Pages Title',
	'pages:description' => 'Your page entry',
	'pages:tags' => 'Tags',
	'pages:access_id' => 'Access',
	'pages:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'No access to page',
	'pages:cantedit' => 'You cannot edit this page',
	'pages:saved' => 'Page saved',
	'pages:notsaved' => 'Page could not be saved',
	'pages:notitle' => 'You must specify a title for your page.',
	'pages:delete:success' => 'The page was successfully deleted.',
	'pages:delete:failure' => 'The page could not be deleted.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Last updated %s by %s',

	/**
	 * History
	 */
	'pages:revision' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Number of pages to display',
	'pages:widget:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "View page",
	'pages:label:edit' => "Edit page",
	'pages:label:history' => "Page history",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "This page",
	'pages:sidebar:children' => "Sub-pages",
	'pages:sidebar:parent' => "Parent",

	'pages:newchild' => "Create a sub-page",
	'pages:backtoparent' => "Back to '%s'",
);

add_translation("en", $english);