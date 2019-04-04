<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Sider',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Slet denne side",
	'pages:history' => "Sidehistorik",
	'pages:view' => "Se side",
	'pages:revision' => "Revision",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Ny side kaldt %s',
	'pages:notify:subject' => "En ny side: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Flere sider',
	'pages:none' => 'Der er ikke oprettet sider endnu',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Side titel',
	'pages:description' => 'Side indhold',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Parent page',
	'pages:access_id' => 'Læseadgang',
	'pages:write_access_id' => 'Skriveadgang',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Du kan ikke redigere denne side',
	'pages:saved' => 'Siden gemt',
	'pages:notsaved' => 'Siden kunne ikke gemmes',
	'pages:error:no_title' => 'Din side skal have en titel.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => 'Denne side revision blev hermed slettet.',
	'pages:revision:delete:failure' => 'Denne side revision kunne ikke slettes.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Antal sider, der skal vises',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Se side",
	'pages:label:edit' => "Rediger side",
	'pages:label:history' => "Sidehistorik",

	'pages:newchild' => "Opret en underside",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
