<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Entwickler-Werkzeuge',
	
	// menu
	'admin:develop_tools:sandbox' => 'Theme-Sandbox',
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
	'developers:label:show_strings' => "Sprach-Strings im Rohformat anzeigen",
	'developers:help:show_strings' => "Diese Einstellung legt fest, ob die von elgg_echo() verwendeten Sprach-Strings angezeigt werden.",
	'developers:label:show_modules' => "Geladene AMD-Module in der JavaScript-Konsole anzeigen",
	'developers:help:show_modules' => "Zeigt Informationen zu den auf der aktuellen Seite geladenen AMD-Modulen in der JavaScript-Konsole des Browsers an.",
	'developers:label:wrap_views' => "Views einkapseln",
	'developers:help:wrap_views' => "Diese Einstellung aktiviert die Einkapselung fast aller Views in HTML-Kommentare. Dies kann hilfreich sein, um den erzeugten HTML-Code einer View zuzuordnen.
Diese Option kann die Ausgabe von nicht-HTML-Views mit Standard-Viewtype stören. Siehe developers_wrap_views() für weitere Informationen.",
	'developers:label:log_events' => "Events und Plugin Hooks protokollieren",
	'developers:help:log_events' => "Einträge für Events und Plugin Hooks ins Log schreiben. Warnung: es gibt sehr viele davon bei jedem Seitenaufruf.",
	'developers:label:show_gear' => "Verwende %s außerhalb des Admin-Backends",
	'developers:help:show_gear' => "Ein (nur für Admins sichtbares) Icon in der unteren rechten Ecke des Viewports, mit dessen Hilfe Zugriff auf Entwicklungseinstellungen und -links möglich ist.",
	'developers:label:block_email' => "Alle ausgehenden Emails blockieren",
	'developers:help:block_email' => "Du kannst alle Emails oder an normale Benutzer gehenden Emails blockieren.",
	'developers:label:forward_email' => "Alle ausgehenden Emails an eine Email-Adresse umleiten",
	'developers:help:forward_email' => "Alle ausgehenden Emails werden an die angegebene Email-Adresse umgeleitet.",
	'developers:label:enable_error_log' => "Error-Log aktivieren",
	'developers:help:enable_error_log' => "Verwende ein separates Error-Log für Fehler und Meldungen, die von error_log() entsprechend dem eingestellten Log-Level erstellt werden. Du kannst dieses Log im Admin-Bereich einsehen.",

	'developers:label:submit' => "Speichern und Caches zurücksetzen",

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
	'developers:entity_explorer:info' => 'Detailinformationen',
	'developers:entity_explorer:info:attributes' => 'Attribute',
	'developers:entity_explorer:info:metadata' => 'Metadaten',
	'developers:entity_explorer:info:relationships' => 'Beziehungen',
	'developers:entity_explorer:info:private_settings' => 'Entitäts-spezifische Konfigurations-Daten',
	'developers:entity_explorer:info:owned_acls' => 'Im Besitz der Zugriffslevel-Collections',
	'developers:entity_explorer:info:acl_memberships' => 'Mitglied der Zugriffslevel-Collections',
	'developers:entity_explorer:delete_entity' => 'Entität löschen',
	'developers:entity_explorer:inspect_entity' => 'Prüfe diese Entität',
	
	// inspection
	'developers:inspect:help' => 'Konfiguration des Elgg-Frameworks prüfen.',
	'developers:inspect:actions' => 'Actions',
	'developers:inspect:events' => 'Events',
	'developers:inspect:menus' => 'Menüs',
	'developers:inspect:pluginhooks' => 'Plugin-Hooks',
	'developers:inspect:priority' => 'Priorität',
	'developers:inspect:simplecache' => 'Simple-Cache',
	'developers:inspect:routes' => 'Routen',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Beachte!</b> Alle Eingaben/Ausgaben von Views werden durch folgende Plugin-Hooks gefiltert:",
	'developers:inspect:views:input_filtered' => "(Eingaben gefiltert durch Plugin-Hook: %s)",
	'developers:inspect:views:filtered' => "(gefiltert durch Plugin-Hook: %s)",
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

	// theme sandbox
	'theme_sandbox:intro' => 'Einführung',
	'theme_sandbox:breakout' => 'Theme-Preview in ganzen Browserfenster anzeigen',
	'theme_sandbox:buttons' => 'Knöpfe',
	'theme_sandbox:components' => 'Komponenten',
	'theme_sandbox:email' => 'Email',
	'theme_sandbox:forms' => 'Forms',
	'theme_sandbox:grid' => 'Grid',
	'theme_sandbox:icons' => 'Icons',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Layouts',
	'theme_sandbox:modules' => 'Module',
	'theme_sandbox:navigation' => 'Navigation',
	'theme_sandbox:typography' => 'Typographie',

	'theme_sandbox:icons:blurb' => 'Verwende <em>elgg_view_icon($name)</em> zur Ausgabe von Icons.',
	
	'theme_sandbox:test_email:button' => "Sende Test-Email",
	'theme_sandbox:test_email:success' => "Test-Email wurde gesendet an: %s",

	// status messages
	'developers:settings:success' => 'Einstellungen gespeichert und Caches zurückgesetzt.',

	'developers:amd' => 'AMD',

	'admin:develop_tools:error_log' => 'Error-Log',
	'developers:logs:empty' => 'Das Error-Log hat noch keine Einträge.',
);
