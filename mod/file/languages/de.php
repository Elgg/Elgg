<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Dateien',
	'collection:object:file' => 'Dateien',
	'collection:object:file:all' => "Alle Dateien der Community",
	'collection:object:file:owner' => "Dateien von %s",
	'collection:object:file:friends' => "Dateien Deiner Freunde",
	'collection:object:file:group' => "Gruppen-Dateien",
	'add:object:file' => "Datei hochladen",
	'edit:object:file' => "Datei-Eintrag bearbeiten",
	'notification:object:file:create' => "Sende eine Benachrichtigung beim Hochladen einer Datei",
	'notifications:mute:object:file' => "über die Datei '%s'",

	'file:more' => "Weitere Dateien",
	'file:list' => "Listen-Ansicht",

	'file:num_files' => "Anzahl der anzuzeigenden Dateien",
	'file:replace' => 'Ersetzen der Datei (leer lassen, um Datei nicht zu ändern)',
	'file:list:title' => "%s's %s %s",

	'file:file' => "Datei",

	'file:list:list' => 'Zur Listen-Ansicht wechseln',
	'file:list:gallery' => 'Zur Gallerie-Ansicht wechseln',

	'file:type:' => 'Dateien',
	'file:type:all' => "Alle Dateien",
	'file:type:video' => "Videos",
	'file:type:document' => "Dokumente",
	'file:type:audio' => "Audio-Dateien",
	'file:type:image' => "Bilder",
	'file:type:general' => "Unbestimmte Dateien",

	'file:user:type:video' => "Videos von %s",
	'file:user:type:document' => "Dokumente von %s",
	'file:user:type:audio' => "Audio-Dateien von %s",
	'file:user:type:image' => "Bilder von %s",
	'file:user:type:general' => "Unbestimmte Dateien von %s",

	'file:friends:type:video' => "Videos Deiner Freunde",
	'file:friends:type:document' => "Dokumente Deiner Freunde",
	'file:friends:type:audio' => "Audio-Dateien Deiner Freunde",
	'file:friends:type:image' => "Bilder Deiner Freunde",
	'file:friends:type:general' => "Unbestimmte Dateien Deiner Freunde",

	'widgets:filerepo:name' => "Datei-Widget",
	'widgets:filerepo:description' => "Auflistung Deiner neuesten Dateien",

	'groups:tool:file' => 'Gruppen-Dateien aktivieren',

	'river:object:file:create' => '%s hat die Datei %s hochgeladen',
	'river:object:file:comment' => '%s schrieb einen Kommentar zur Datei %s',

	'file:notify:summary' => 'Eine neue Datei namens %s wurde hochgeladen',
	'file:notify:subject' => 'Neue Datei: %s',
	'file:notify:body' => '%s hat eine neue Datei hochgeladen: %s

%s

Schau Dir die Datei an und schreibe einen Kommentar:
%s',

	/**
	 * Status messages
	 */

	'file:saved' => "Die Datei wurde gespeichert.",
	'entity:delete:object:file:success' => "Die Datei wurde gelöscht.",

	/**
	 * Error messages
	 */

	'file:none' => "Noch keine Dateien vorhanden.",
	'file:uploadfailed' => "Entschuldigung, wir konnten die Datei nicht speichern.",
	'file:noaccess' => "Du hast keine Berechtigung, um diesen Datei-Eintrag zu ändern.",
	'file:cannotload' => "Beim Hochladen dieser Datei ist ein Fehler aufgetreten.",
);
