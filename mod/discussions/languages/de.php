<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Diskussionen",
	
	'add:object:discussion' => 'Diskussion hinzufügen',
	'edit:object:discussion' => 'Diskussion bearbeiten',
	'collection:object:discussion' => 'Diskussionen',
	'collection:object:discussion:group' => 'Gruppen-Diskussionen',
	'collection:object:discussion:my_groups' => 'Diskussionen in meinen Gruppen',
	
	'discussion:settings:enable_global_discussions' => 'Diskussionen allgemein aktivieren',
	'discussion:settings:enable_global_discussions:help' => 'Erlaube Diskussionen ausserhalb von Gruppen.',

	'discussion:latest' => 'Neueste Diskussionen',
	'discussion:none' => 'Es gibt noch keine Diskussionen.',
	'discussion:updated' => "Letzte Antwort von %s %s",

	'discussion:topic:created' => 'Die Diskussion wurde hinzugefügt.',
	'discussion:topic:updated' => 'Die Diskussion wurde aktualisiert.',
	'entity:delete:object:discussion:success' => 'Die Diskussion wurde gelöscht.',

	'discussion:topic:notfound' => 'Die gewünschte Diskussion wurde leider nicht gefunden.',
	'discussion:error:notsaved' => 'Die Diskussion konnte nicht gespeichert werden.',
	'discussion:error:missing' => 'Es müssen sowohl der Titel als auch der Textinhalt der Diskussion ausgefüllt werden.',
	'discussion:error:permissions' => 'Du hast keine Berechtigung für diese Aktion.',
	'discussion:error:no_groups' => "Du bist noch bei keiner Gruppe Mitglied.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s hat die neue Diskussion %s hinzugefügt',
	'river:object:discussion:comment' => '%s schrieb eine Antwort in der Diskussion %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Neue Diskussion namens %s',
	'discussion:topic:notify:subject' => 'Neue Diskussion: %s',
	'discussion:topic:notify:body' =>
'%s hat die neue Diskussion "%s" gestartet:

%s

Schau Dir die neue Diskussion an und antworte darauf:
%s
',

	'discussion:comment:notify:summary' => 'Neue Antwort in Diskussion %s',
	'discussion:comment:notify:subject' => 'Neue Antwort in Diskussion: %s',
	'discussion:comment:notify:body' =>
'%s hat in der Diskussion "%s" geantwortet:

%s

Schau Dir die Diskussion an und antworte selbst darauf:
%s
',

	'groups:tool:forum' => 'Gruppen-Diskussionen aktivieren',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status',
	'discussion:topic:closed:title' => 'Diskussion geschlossen.',
	'discussion:topic:closed:desc' => 'Diese Diskussion ist geschlossen und es können keine neuen Antworten mehr hinzugefügt werden.',

	'discussion:topic:description' => 'Textinhalt der Diskussion',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Wandle den Subtyp von existierenden Diskussions-Antwort-Entitäten in Kommentar-Subtyp um",
	'discussions:upgrade:2017112800:description' => "Diskussions-Antworten hatten bisher einen eigenen Entitäts-Subtyp. Aus Gründen der Vereinfachung wird dieser separate Subtyp nicht mehr verwendet und stattdessen für die Diskussions-Antworten der gleiche Subtyp wie für Kommentar-Entitäten verwendet.",
	'discussions:upgrade:2017112801:title' => "Aktualisiere River-Einträge von Diskussions-Antworten",
	'discussions:upgrade:2017112801:description' => "Diskussions-Antworten hatten bisher einen eigenen Entitäts-Subtyp. Aus Gründen der Vereinfachung wird dieser separate Subtyp nicht mehr verwendet und stattdessen für die Diskussions-Antworten der gleiche Subtyp wie für Kommentar-Entitäten verwendet. Als Folge dessen müssen auch die zugehörigen River-Einträge aktualisiert werden.",
);
