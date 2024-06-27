<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Entwickler-Werkzeuge',
	
	// menu
	'admin:develop_tools:inspect' => 'Prüfen',
	'admin:inspect' => 'Prüfen',
	'admin:develop_tools:unit_tests' => 'Modultests',
	'admin:develop_tools:entity_explorer' => 'Entitäten-Explorer',
	'admin:developers' => 'Entwickler',
	'admin:developers:settings' => 'Einstellungen',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Prüfe die untenstehenden Entwicklungs- und Debug-Einstellungen. Einige dieser Einstellungen sind auch auf anderen Admin-Seiten verfügbar.',
	'developers:label:simple_cache' => 'Simple-Cache aktivieren',
	'developers:help:simple_cache' => 'Deaktiviere Simple-Cache während Entwicklungstests. Andernfalls werden Code-Änderungen an Views (inklusive CSS und Javascript) nicht unmittelbar sichtbar sein.',
	'developers:label:system_cache' => 'Systemcache aktivieren',
	'developers:help:system_cache' => 'Deaktiviere den Systemcache während Entwicklungstests. Andernfalls werden neue Views in Deinen Plugins nicht unmittelbar registriert werden.',
	'developers:label:debug_level' => "Fehlerprotokoll-Level",
	'developers:help:debug_level' => "Diese Einstellung legt fest, welche Details protokolliert werden. Siehe elgg_log() für mehr Informationen.",
	'developers:label:display_errors' => 'Fatal PHP Errors anzeigen',
	'developers:help:display_errors' => "Standardmäßig unterdrückt die .htaccess-Datei von Elgg die Anzeige von Fatal Errors.",
	'developers:label:screen_log' => "Protokolleinträge auf dem Bildschirm ausgeben",
	'developers:help:screen_log' => "Anzeige der Ausgabe von elgg_log() und elgg_dump() und Anzeige der Anzahl der durchgeführten Datenbankabfragen.",
	'developers:show_strings:default' => "Übersetzungen normal anzeigen",
	'developers:show_strings:key_append' => "Native Sprach-Strings anhängen",
	'developers:show_strings:key_only' => "Nur native Sprach-Strings anzeigen",
	'developers:label:show_strings' => "Native Sprach-Strings anzeigen",
	'developers:help:show_strings' => "Diese Einstellung legt fest, ob die von elgg_echo() verwendeten Sprach-Strings angezeigt werden.",
	'developers:label:wrap_views' => "Views einkapseln",
	'developers:label:log_events' => "Events protokollieren",
	'developers:help:log_events' => "Einträge für Events ins Log schreiben. Warnung: es gibt sehr viele davon bei jedem Seitenaufruf.",
	'developers:label:block_email' => "Alle ausgehenden Emails blockieren",
	'developers:help:block_email' => "Du kannst alle Emails oder an normale Benutzer gehenden Emails blockieren.",
	'developers:label:forward_email' => "Alle ausgehenden Emails an eine Email-Adresse umleiten",
	'developers:help:forward_email' => "Alle ausgehenden Emails werden an die angegebene Email-Adresse umgeleitet.",
	'developers:label:enable_error_log' => "Error-Log aktivieren",
	'developers:help:enable_error_log' => "Verwende ein separates Error-Log für Fehler und Meldungen, die von error_log() entsprechend dem eingestellten Log-Level erstellt werden. Du kannst dieses Log im Admin-Bereich einsehen.",

	'developers:block_email:forward' => 'Alle Emails umleiten',
	'developers:block_email:users' => 'Nur von normalen Benutzern',
	'developers:block_email:all' => 'Von Admins und normalen Benutzern',
	
	'developers:debug:off' => 'Aus',
	'developers:debug:error' => 'Fehler',
	'developers:debug:warning' => 'Warnung',
	'developers:debug:notice' => 'Hinweis',
	'developers:debug:info' => 'Info',
	
	// entity explorer
	'developers:entity_explorer:help' => 'Detailinformationen einer Entität anzeigen und (optional) die Anwendung von Datenbank-Operationen auf den Datenbankeintrag der Entität.',
	'developers:entity_explorer:guid:label' => 'Gebe die GUID der Entität ein, für die Detailinformationen angezeigt werden sollen:',
	'developers:entity_explorer:info:attributes' => 'Attribute',
	'developers:entity_explorer:info:metadata' => 'Metadaten',
	'developers:entity_explorer:info:relationships' => 'Beziehungen',
	'developers:entity_explorer:info:owned_acls' => 'Im Besitz der Zugriffslevel-Collections',
	'developers:entity_explorer:info:acl_memberships' => 'Mitglied der Zugriffslevel-Collections',
	'developers:entity_explorer:delete_entity' => 'Entität löschen',
	'developers:entity_explorer:inspect_entity' => 'Prüfe diese Entität',
	'developers:entity_explorer:view_entity' => 'Diese Entität auf der Seite anzeigen',
	
	// inspection
	'developers:inspect:actions' => 'Actions',
	'developers:inspect:events' => 'Events',
	'developers:inspect:menus' => 'Menüs',
	'developers:inspect:priority' => 'Priorität',
	'developers:inspect:simplecache' => 'Simple-Cache',
	'developers:inspect:routes' => 'Routen',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Beachte!</b> Alle Eingaben/Ausgaben von Views werden durch folgende Plugin-Hooks gefiltert:",
	'developers:inspect:views:input_filtered' => "(Eingaben gefiltert durch Event: %s)",
	'developers:inspect:views:filtered' => "(gefiltert durch Event: %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:widgets:context' => 'Kontext',
	'developers:inspect:functions' => 'Funktionen',
	'developers:inspect:file_location' => 'Dateipfad relativ zum Elgg-Root-Verzeichnis oder Controller',
	'developers:inspect:route' => 'Route-Name',
	'developers:inspect:path' => 'Pfad-Muster',
	'developers:inspect:resource' => 'Resource-View',
	'developers:inspect:handler' => 'Handler',
	'developers:inspect:controller' => 'Controller',
	'developers:inspect:file' => 'Datei',
	'developers:inspect:middleware' => 'Datei',
	'developers:inspect:handler_type' => 'Verarbeitet von',
	'developers:inspect:services' => 'Services',
	'developers:inspect:service:name' => 'Name',
	'developers:inspect:service:path' => 'Definition',
	'developers:inspect:service:class' => 'Klasse',

	// event logging
	'developers:request_stats' => "Abfragen-Statistik (Shutdown-Event nicht berücksichtigt)",
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "Datenbankabfragen: %s",
	'developers:boot_cache_rebuilt' => "Der Boot-Cache wurde für diese Abfrage neu erzeugt.",
	'developers:elapsed_time' => "Benötigte Zeit (s)",

	'admin:develop_tools:error_log' => 'Error-Log',
	'developers:logs:empty' => 'Das Error-Log hat noch keine Einträge.',
);
