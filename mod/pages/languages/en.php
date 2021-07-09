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

	'item:object:page' => 'Page',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",
	'notification:object:page:create' => "Send a notification when a page is created",
	'notifications:mute:object:page' => "about the page '%s'",

	'groups:tool:pages' => 'Enable group pages',
	
	'annotation:delete:page:success' => 'The page revision was successfully deleted',
	'annotation:delete:page:fail' => 'The page revision could not be deleted',

	'pages:history' => "History",
	'pages:revision' => "Revision",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'New page called %s',
	'pages:notify:subject' => "A new page: %s",
	'pages:notify:body' => '%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'More pages',
	'pages:none' => 'No pages created yet',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Page title',
	'pages:description' => 'Page text',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Parent page',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'You cannot edit this page',
	'pages:saved' => 'Page saved',
	'pages:notsaved' => 'Page could not be saved',
	'pages:error:no_title' => 'You must specify a title for this page.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Number of pages to display',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	'pages:newchild' => "Create a sub-page",
);
