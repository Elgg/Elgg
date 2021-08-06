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

	'item:object:page' => 'Sida',
	'collection:object:page' => 'Sidor',
	'collection:object:page:all' => "Alla sidor på webbplatsen",
	'collection:object:page:owner' => "%ss sidor",
	'collection:object:page:friends' => "Vänners sidor",
	'collection:object:page:group' => "Gruppsidor",
	'add:object:page' => "Lägg till en sida",
	'edit:object:page' => "Redigera den här sidan",

	'groups:tool:pages' => 'Aktivera gruppsidor',
	
	'annotation:delete:page:success' => 'Sidans utgåva togs bort',
	'annotation:delete:page:fail' => 'Sidans utgåva kunde inte tas bort',

	'pages:history' => "Historik",
	'pages:revision' => "Utgåva",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Ny sida med namnet %s',
	'pages:notify:subject' => "En ny sida: %s",

	'pages:more' => 'Fler sidor',
	'pages:none' => 'Inga sidor skapade än',

	/**
	* River
	**/

	'river:object:page:create' => '%s skapade en sida %s',
	'river:object:page:update' => '%s uppdaterade en sida %s',
	'river:object:page:comment' => '%s kommenterade en sida med titel %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Sidans titel',
	'pages:description' => 'Sidans text',
	'pages:tags' => 'Taggar',
	'pages:parent_guid' => 'Föräldrasida',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Du kan inte redigera den här sidan',
	'pages:saved' => 'Sidan sparad',
	'pages:notsaved' => 'Sida kunde inte sparas',
	'pages:error:no_title' => 'Du måste ange en titel för den här sidan.',
	'entity:delete:object:page:success' => 'Sidan togs bort.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Utgåva skapad %s av %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Anta sidor att visa',
	'widgets:pages:name' => 'Sidor',
	'widgets:pages:description' => "Det här är en lista med dina sidor.",

	'pages:newchild' => "Skapa en undersida",
);
