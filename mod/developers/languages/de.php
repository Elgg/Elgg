<?php
return array(
	// menu
	'admin:develop_tools' => 'Entwickler-Werkzeuge',
	'admin:develop_tools:sandbox' => 'Theme-Sandbox',
	'admin:develop_tools:inspect' => 'Prüfen',
	'admin:inspect' => 'Prüfen',
	'admin:develop_tools:unit_tests' => 'Modultests',
	'admin:developers' => 'Entwickler',
	'admin:developers:settings' => 'Einstellungen',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Prüfe die untenstehenden Entwicklungs- und Debug-Einstellungen. Einige dieser Einstellungen sind auch auf anderen Admin-Seiten verfügbar.',
	'developers:label:simple_cache' => 'Simple-Cache aktivieren',
	'developers:help:simple_cache' => 'Deaktiviere den Simple-Cache während Entwicklungstests. Andernfalls werden Code-Änderungen an Views (inklusive CSS und Javascript) nicht unmittelbar sichtbar sein.',
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
	'developers:label:wrap_views' => "Views einkapseln",
	'developers:help:wrap_views' => "Diese Einstellung aktiviert die Einkapselung fast aller Views in HTML-Kommentare. Dies kann hilfreich sein, um den erzeugten HTML-Code einer View zuzuordnen.
Diese Option kann die Ausgabe von nicht-HTML-Views mit Standard-Viewtype stören. Siehe developers_wrap_views() für weitere Informationen.",
	'developers:label:log_events' => "Events und Plugin Hooks protokollieren",
	'developers:help:log_events' => "Einträge für Events und Plugin Hooks ins Log schreiben. Warnung: es gibt sehr viele davon bei jedem Seitenaufruf.",

	'developers:debug:off' => 'Aus',
	'developers:debug:error' => 'Fehler',
	'developers:debug:warning' => 'Warnung',
	'developers:debug:notice' => 'Hinweis',
	'developers:debug:info' => 'Info',
	
	// inspection
	'developers:inspect:help' => 'Konfiguration des Elgg-Frameworks prüfen.',
	'developers:inspect:actions' => 'Actions',
	'developers:inspect:events' => 'Events',
	'developers:inspect:menus' => 'Menüs',
	'developers:inspect:pluginhooks' => 'Plugin-Hooks',
	'developers:inspect:priority' => 'Priorität',
	'developers:inspect:simplecache' => 'Simple-Cache',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Zu beachten!</b> Die gesamte View-Ausgabe wird durch diese Plugin-Hooks gefiltert:",
	'developers:inspect:views:filtered' => "(gefiltert durch Plugin-Hook: %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:webservices' => 'Webservices',
	'developers:inspect:widgets:context' => 'Kontext',
	'developers:inspect:functions' => 'Funktionen',
	'developers:inspect:file_location' => 'Dateipfad relativ zum Elgg-Root-Verzeichnis',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "%s Datenbankabfragen (Shutdown-Event nicht berücksichtigt)",

	// theme sandbox
	'theme_sandbox:intro' => 'Einführung',
	'theme_sandbox:breakout' => 'Theme-Preview in ganzen Browserfenster anzeigen',
	'theme_sandbox:buttons' => 'Knöpfe',
	'theme_sandbox:components' => 'Komponenten',
	'theme_sandbox:forms' => 'Forms',
	'theme_sandbox:grid' => 'Grid',
	'theme_sandbox:icons' => 'Icons',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Layouts',
	'theme_sandbox:modules' => 'Module',
	'theme_sandbox:navigation' => 'Navigation',
	'theme_sandbox:typography' => 'Typographie',

	'theme_sandbox:icons:blurb' => 'Verwende <em>elgg_view_icon($name)</em> oder die Klasse elgg-icon-$name zur Ausgabe von Icons.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg enthält Modultests und Integrationstests, um mögliche Fehler in seinen Klassen und Funktionen zu finden.',
	'developers:unit_tests:warning' => 'Warnung: Führe diese Tests niemals auf Deiner Hauptinstallation aus. Sie können Deine Datenbank beschädigen!',
	'developers:unit_tests:run' => 'Ausführen',

	// status messages
	'developers:settings:success' => 'Einstellungen gespeichert',
);
