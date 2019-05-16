<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Články',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Smazat tento článek",
	'pages:history' => "Historie",
	'pages:view' => "Zobrazit článek",
	'pages:revision' => "Revize",

	'pages:navigation' => "Navigace",

	'pages:notify:summary' => 'Nový článek se jménem %s',
	'pages:notify:subject' => "Nový článek: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Více článků',
	'pages:none' => 'Zatím nebyly vytvořeny žádné články',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Název článku',
	'pages:description' => 'Text článku',
	'pages:tags' => 'Štítky',
	'pages:parent_guid' => 'Nadřazená stránka',
	'pages:access_id' => 'Ke čtení',
	'pages:write_access_id' => 'Pro zápis',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Nemůžete upravit tento článek',
	'pages:saved' => 'Článek byl uložen',
	'pages:notsaved' => 'Článek není možné uložit',
	'pages:error:no_title' => 'Musíte zadat název článku.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => 'Revize článku byla úspěšně smazána.',
	'pages:revision:delete:failure' => 'Revizi článku není možné smazat.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revize vytvořena %s uživatelem %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Počet zobrazených článků',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Zobrazit článek",
	'pages:label:edit' => "Upravit článek",
	'pages:label:history' => "Historie článku",

	'pages:newchild' => "Vytvořit pod-článek",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
