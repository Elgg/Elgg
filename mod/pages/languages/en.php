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
	'pages:add' => "Add page",

	'pages:group' => "Group pages",
	'groups:enablepages' => 'Enable group pages',

	'pages:edit' => "Edit this page",
	'pages:delete' => "Delete this page",
	'pages:history' => "History",
	'pages:view' => "View page",
	'pages:revision' => "Revision",

	'pages:navigation' => "Navigation",
	'pages:via' => "via pages",
	'item:object:page_top' => 'Top-level pages',
	'item:object:page' => 'Pages',
	'pages:nogroup' => 'This group does not have any pages yet',
	'pages:more' => 'More pages',
	'pages:none' => 'No pages created yet',

	/**
	* River
	**/

	'river:create:object:page' => '%s created a page %s',
	'river:create:object:page_top' => '%s created a page %s',
	'river:update:object:page' => '%s updated a page %s',
	'river:update:object:page_top' => '%s updated a page %s',
	'river:comment:object:page' => '%s commented on a page titled %s',
	'river:comment:object:page_top' => '%s commented on a page titled %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Page title',
	'pages:description' => 'Page text',
	'pages:tags' => 'Tags',
	'pages:access_id' => 'Read access',
	'pages:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'No access to page',
	'pages:cantedit' => 'You cannot edit this page',
	'pages:saved' => 'Page saved',
	'pages:notsaved' => 'Page could not be saved',
	'pages:error:no_title' => 'You must specify a title for this page.',
	'pages:delete:success' => 'The page was successfully deleted.',
	'pages:delete:failure' => 'The page could not be deleted.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Last updated %s by %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revision created %s by %s',

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