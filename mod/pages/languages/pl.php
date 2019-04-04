<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Strony',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Usuń tą stronę",
	'pages:history' => "Historia strony",
	'pages:view' => "Wyświetl stronę",
	'pages:revision' => "Wersja",

	'pages:navigation' => "Nawigacja strony",

	'pages:notify:summary' => 'Nowa strona o nazwie %s',
	'pages:notify:subject' => "Nowa strona: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Więcej stron',
	'pages:none' => 'Nie utworzono jeszcze stron',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Tytuł strony',
	'pages:description' => 'Treść strony',
	'pages:tags' => 'Tagi',
	'pages:parent_guid' => 'Strona nadrzędna',
	'pages:access_id' => 'Uprawnienia odczytu',
	'pages:write_access_id' => 'Uprawnienia zapisu',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Nie można edytować tej strony',
	'pages:saved' => 'Strona zapisana',
	'pages:notsaved' => 'Strona nie mogła zostać zapisana',
	'pages:error:no_title' => 'Musisz podać tytuł dla tej strony.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => 'Pomyślnie usunięto wersję strony.',
	'pages:revision:delete:failure' => 'Usunięcie wersji strony nie powiodło się.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Wersja utworzona %s przez %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Ilość stron do wyświetlenia',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Zobacz stronę",
	'pages:label:edit' => "Edytuj stronę",
	'pages:label:history' => "Historia strony",

	'pages:newchild' => "Utwórz podstronę",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
