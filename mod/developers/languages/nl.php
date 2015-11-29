<?php
return array(
	// menu
	'admin:develop_tools' => 'Tools',
	'admin:develop_tools:sandbox' => 'Theme Sandbox',
	'admin:develop_tools:inspect' => 'Inspecteer',
	'admin:inspect' => 'Inspecteer',
	'admin:develop_tools:unit_tests' => 'Unittesten',
	'admin:developers' => 'Ontwikkelaars',
	'admin:developers:settings' => 'Instellingen',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Beheer je ontwikkel- en debuginstellingen hieronder. Sommige van de instellingen zijn ook beschikbaar op andere beheerpagina\'s.',
	'developers:label:simple_cache' => 'Gebruik simple cache',
	'developers:help:simple_cache' => 'Zet file cache uit tijdens het ontwikkelen. Anders zullen wijzigingen in je views (inclusief CSS) worden genegeerd.',
	'developers:label:system_cache' => 'Gebruik systeem cache',
	'developers:help:system_cache' => 'Zet dit uit tijdens ontwikkeling. Zo niet, dan zullen de wijzigingen niet geregistreerd worden.',
	'developers:label:debug_level' => "Trace-niveau",
	'developers:help:debug_level' => "Hiermee beheer je de hoeveel informatie er gelogd wordt. Zie elgg_log() voor meer informatie.",
	'developers:label:display_errors' => 'Toon PHP fatale fouten',
	'developers:help:display_errors' => "Standaard verbergt de .htaccess van Elgg het weergeven van fatale fouten.",
	'developers:label:screen_log' => "Log naar het scherm",
	'developers:help:screen_log' => "Dit toont het resultaat van elgg_log() en elgg_dump() op de webpagina alsmede het aantal database queries.",
	'developers:label:show_strings' => "Toon vertaalsleutels",
	'developers:help:show_strings' => "Dit toont de vertaalsleutels die gebruikt worden door elgg_echo().",
	'developers:label:show_modules' => "Toon de geladen AMD modules in de console",
	'developers:help:show_modules' => "Streamt geladen modules en waarden naar jouw JavaScript console.",
	'developers:label:wrap_views' => "Omcirkel views",
	'developers:help:wrap_views' => "Dit omcirkelt bijna alle views met HTML-commentaar. Handig om erachter te komen uit welke view een stuk HTML komt.",
	'developers:label:log_events' => "Log-events en plugin-hooks",
	'developers:help:log_events' => "Schrijf events- en plugin-hook naar de log. Waarschuwing: dit zijn er veel per pagina.",
	'developers:label:show_gear' => "Gebruik %s buiten de admin sectie",
	'developers:help:show_gear' => "Een icoon onderaan rechts van het venster dat administrators toegang geeft tot ontwikkelaars instellingen en links.",
	'developers:label:submit' => "Opslaan en cache wissen",

	'developers:debug:off' => 'Uit',
	'developers:debug:error' => 'Fout',
	'developers:debug:warning' => 'Waarschuwing',
	'developers:debug:notice' => 'Bericht',
	'developers:debug:info' => 'Informatie',
	
	// inspection
	'developers:inspect:help' => 'Inspecteer de configuratie van het Elgg framework.',
	'developers:inspect:actions' => 'Acties',
	'developers:inspect:events' => 'Events',
	'developers:inspect:menus' => 'Menu\'s',
	'developers:inspect:pluginhooks' => 'Plugin Hooks',
	'developers:inspect:priority' => 'Prioriteit',
	'developers:inspect:simplecache' => 'Simple Cache',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Opmerking!</b>Alle view output word gefilterd door deze Plugin Hooks:",
	'developers:inspect:views:filtered' => "(gefilterd op plugin hook: %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:webservices' => 'Webservices',
	'developers:inspect:widgets:context' => 'Context',
	'developers:inspect:functions' => 'Functies',
	'developers:inspect:file_location' => 'Pad van het bestand vanaf de Elgg root map',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "%s database queries (zonder het shutdown event)",

	// theme sandbox
	'theme_sandbox:intro' => 'Introductie',
	'theme_sandbox:breakout' => 'Verlaat het iframe',
	'theme_sandbox:buttons' => 'Knoppen',
	'theme_sandbox:components' => 'Componenten',
	'theme_sandbox:forms' => 'Formulieren',
	'theme_sandbox:grid' => 'Raster',
	'theme_sandbox:icons' => 'Iconen',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Lay-outs',
	'theme_sandbox:modules' => 'Modules',
	'theme_sandbox:navigation' => 'Paginanavigatie',
	'theme_sandbox:typography' => 'Typografie',

	'theme_sandbox:icons:blurb' => 'Gebruik <em>elgg_view_icon($name)</em> of de klasse <em>elgg-icon-$name</em> om iconen weer te geven.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg bevat unit- en integratietesten voor het detecteren van bugs in de core classes en functies.',
	'developers:unit_tests:warning' => 'Waarschuwing: doe deze testen niet op een productiesite! Deze kunnen de database beschadigen.',
	'developers:unit_tests:run' => 'Uitvoeren',

	// status messages
	'developers:settings:success' => 'Instellingen opgeslagen en cache gewist',

	'developers:amd' => 'AMD',
);
