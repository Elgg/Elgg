<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:administer_utilities:logbrowser' => 'Elgglog-Browser',
	'logbrowser:search' => 'Angezeigte Logeinträge einschränken',
	'logbrowser:user' => 'Logeinträge für den folgenden Benutzernamen anzeigen',
	'logbrowser:starttime' => 'Logeinträge ab (Eingabe nur in Englisch möglich, z.B. "last monday", "1 hour ago")',
	'logbrowser:endtime' => 'Logeinträge bis',

	'logbrowser:explore' => 'Elgglog durchsuchen',

	'logbrowser:date' => 'Datum und Zeit',
	'logbrowser:ip_address' => 'IP-Adresse',
	'logbrowser:user:name' => 'Benutzer',
	'logbrowser:user:guid' => 'Benutzer-GUID',
	'logbrowser:object' => 'Objekt-Typ',
	'logbrowser:action' => 'Aktion',

	'logrotate:period' => 'Wie oft sollen die Einträge im Elgglog archiviert werden?',
	'logrotate:retention' => 'Archivierte Elgglogs älter als X Tage löschen',
	'logrotate:retention:help' => 'Anzahl der Tage, für die archivierte Elgglogs in der Datenbank verbleiben sollen bevor sie gelöscht werden. Lasse das Eingabefeld leer, damit die archivierten Elgglogs gar nicht automatisch gelöscht werden.',

	'logrotate:logrotated' => "Alte Elgglog-Einträge wurden archiviert.",
	'logrotate:lognotrotated' => "Beim Archivieren alter Elgglog-Einträge ist ein Fehler aufgetreten.",

	'logrotate:logdeleted' => "Alte archivierte Elgglogs wurden gelöscht.",
	'logrotate:lognotdeleted' => "Beim Löschen alter archivierter Elgglogs ist ein Fehler aufgetreten.",

	// not used any more since Elgg 4.1, can be cleaned in Elgg 5.0
	'logrotate:delete' => 'Löschen von archivierten Elgglogs älter als',
	'logrotate:week' => 'eine Woche',
	'logrotate:month' => 'einen Monat',
	'logrotate:year' => 'ein Jahr',
);
