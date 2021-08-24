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

	'item:object:page' => 'Coop-Seiten',
	'collection:object:page' => 'Coop-Seiten',
	'collection:object:page:all' => "Alle Coop-Seiten",
	'collection:object:page:owner' => "Coop-Seiten von %s",
	'collection:object:page:friends' => "Coop-Seiten von Freunden",
	'collection:object:page:group' => "Gruppen-Coop-Seiten",
	'add:object:page' => "Coop-Seite hinzufügen",
	'edit:object:page' => "Coop-Seite bearbeiten",
	'notification:object:page:create' => "Sende eine Benachrichtigung bei Hinzufügen einer Coop-Seite",
	'notifications:mute:object:page' => "über die Coop-Seite '%s'",

	'groups:tool:pages' => 'Gruppen-Coop-Seiten aktivieren',
	
	'annotation:delete:page:success' => 'Die Revision der Coop-Seite wurde gelöscht.',
	'annotation:delete:page:fail' => 'Das Löschen der Revision der Coop-Seite ist fehlgeschlagen.',

	'pages:history' => "Bearbeitungsverlauf",
	'pages:revision' => "Revision",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Eine neue Coop-Seite namens %s wurde erstellt.',
	'pages:notify:subject' => "Neue Coop-Seite: %s",
	'pages:notify:body' => '%s hat eine neue Coop-Seite erstellt: %s

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

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Du kannst diese Coop-Seite nicht bearbeiten.',
	'pages:saved' => 'Die Coop-Seite wurde gespeichert.',
	'pages:notsaved' => 'Die Coop-Seite konnte nicht gespeichert werden.',
	'pages:error:no_title' => 'Du mußt einen Titel für diese Coop-Seite eingeben.',
	'entity:delete:object:page:success' => 'Die Coop-Seite wurde gelöscht.',

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

	'pages:newchild' => "Unter-Coop-Seite hinzufügen",
);
