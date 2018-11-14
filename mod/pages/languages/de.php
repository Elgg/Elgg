<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Coop-Seiten',
	'collection:object:page' => 'Coop-Seiten',
	'collection:object:page:all' => "Alle Coop-Seiten",
	'collection:object:page:owner' => "Coop-Seiten von %s",
	'collection:object:page:friends' => "Coop-Seiten von Freunden",
	'collection:object:page:group' => "Gruppen-Coop-Seiten",
	'add:object:page' => "Coop-Seite hinzufügen",
	'edit:object:page' => "Coop-Seite bearbeiten",

	'groups:tool:pages' => 'Gruppen-Coop-Seiten aktivieren',

	'pages:delete' => "Coop-Seite löschen",
	'pages:history' => "Bearbeitungsverlauf",
	'pages:view' => "Coop-Seite anzeigen",
	'pages:revision' => "Revision",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Eine neue Coop-Seite namens %s wurde erstellt.',
	'pages:notify:subject' => "Neue Coop-Seite: %s",
	'pages:notify:body' =>
'%s hat eine neue Coop-Seite erstellt: %s

%s

Schau Dir die neue Coop-Seite an und schreibe einen Kommentar:
%s',

	'pages:more' => 'Weitere Coop-Seiten',
	'pages:none' => 'Es wurden noch keine Coop-Seiten erstellt.',

	/**
	* River
	**/

	'river:object:page:create' => '%s hat die Coop-Seite %s hinzugefügt',
	'river:object:page:update' => '%s aktualisierte die Coop-Seite %s',
	'river:object:page:comment' => '%s schrieb einen Kommentar zur Coop-Seite %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Titel der Coop-Seite',
	'pages:description' => 'Seitentext',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Übergeordnete Coop-Seite',
	'pages:access_id' => 'Zugangslevel',
	'pages:write_access_id' => 'Schreibberechtigung',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Du kannst diese Coop-Seite nicht bearbeiten.',
	'pages:saved' => 'Die Coop-Seite wurde gespeichert.',
	'pages:notsaved' => 'Die Coop-Seite konnte nicht gespeichert werden.',
	'pages:error:no_title' => 'Du mußt einen Titel für diese Coop-Seite eingeben.',
	'entity:delete:object:page:success' => 'Die Coop-Seite wurde gelöscht.',
	'pages:revision:delete:success' => 'Die Revision der Coop-Seite wurde gelöscht.',
	'pages:revision:delete:failure' => 'Die Revision der Coop-Seite konnte nicht gelöscht werden.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revision erzeugt am %s von %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Anzahl der anzuzeigenden Coop-Seiten',
	'widgets:pages:name' => 'Coop-Seiten',
	'widgets:pages:description' => "Dies ist eine Auflistung Deiner neuesten Coop-Seiten.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Coop-Seite anzeigen",
	'pages:label:edit' => "Coop-Seite bearbeiten",
	'pages:label:history' => "Bearbeitungsverlauf der Coop-Seite",

	'pages:newchild' => "Unter-Coop-Seite hinzufügen",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Umwandeln von \"page_top\"- in \"page\"-Entitäten",
	'pages:upgrade:2017110700:description' => "Ändert den Subtyp von Haupt-Coop-Seiten von \"page_top\" zu \"page\" und fügt einen Metadata-Eintrag hinzu, damit die Auflistung der Seiten weiterhin korrekt erfolgt.",
	
	'pages:upgrade:2017110701:title' => "Aktualisieren von River-Einträgen der \"page_top\"-Entitäten",
	'pages:upgrade:2017110701:description' => "Aktualisiert den Subtyp bei allen River-Einträgen vom bisherigen \"page_top\" zu \"page\".",
);
