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

	'pages:delete' => "Ta bort den här sidan",
	'pages:history' => "Historik",
	'pages:view' => "Visa sida",
	'pages:revision' => "Utgåva",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Ny sida med namnet %s',
	'pages:notify:subject' => "En ny sida: %s",
	'pages:notify:body' =>
'%s lade till en ny sida: %s

%s

Visa och kommentera sidan:
%s',

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
	'pages:access_id' => 'Läsrättighet',
	'pages:write_access_id' => 'Skrivrättighet',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Du kan inte redigera den här sidan',
	'pages:saved' => 'Sidan sparad',
	'pages:notsaved' => 'Sida kunde inte sparas',
	'pages:error:no_title' => 'Du måste ange en titel för den här sidan.',
	'entity:delete:object:page:success' => 'Sidan togs bort.',
	'pages:revision:delete:success' => 'Sidans utgåva togs bort.',
	'pages:revision:delete:failure' => 'Sidans utgåva kunde inte tas bort.',

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

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Visa sida",
	'pages:label:edit' => "Redigera sida",
	'pages:label:history' => "Sidhistorik",

	'pages:newchild' => "Skapa en undersida",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrera page_top till sidenheter",
	'pages:upgrade:2017110700:description' => "Ändrar undertypen av alla topp-sidor till \"sida\" och ställer in metadata för att vara säker på korrekt listning.",
	
	'pages:upgrade:2017110701:title' => "Migrera page_top river enhet",
	'pages:upgrade:2017110701:description' => "Ändrar undertypen för alla river objekt för topp-sidor till \"sida\".",
);
