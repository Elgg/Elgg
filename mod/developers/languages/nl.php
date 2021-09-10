<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Tools',
	
	// menu
	'admin:develop_tools:sandbox' => 'Theme Sandbox',
	'admin:develop_tools:inspect' => 'Inspecteer',
	'admin:inspect' => 'Inspecteer',
	'admin:develop_tools:unit_tests' => 'Unittesten',
	'admin:develop_tools:entity_explorer' => 'Entiteiten Verkenner',
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
	'developers:help:wrap_views' => "Dit omwikkeld alle views met een HTML comment block. Dit kan je helpen indien je wilt weten welke view de HTML heeft gegenereerd.

Dit kan potentieel non-HTML views niet laten functioneren. Bekijk de developers_wrap_views() functie voor meer informatie.",
	'developers:label:log_events' => "Log-events en plugin-hooks",
	'developers:help:log_events' => "Schrijf events- en plugin-hook naar de log. Waarschuwing: dit zijn er veel per pagina.",
	'developers:label:show_gear' => "Gebruik %s buiten de admin sectie",
	'developers:help:show_gear' => "Een icoon onderaan rechts van het venster dat administrators toegang geeft tot ontwikkelaars instellingen en links.",
	'developers:label:block_email' => "Blokkeer alle uitgaande emails",
	'developers:help:block_email' => "Het is mogelijk om alle uitgaande emails naar reguliere gebruikers of naar alle gebruikers",
	'developers:label:forward_email' => "Stuur alle uitgaande emails naar één adres",
	'developers:help:forward_email' => "Alle uitgaande emails zullen naar het geconfigureerde emailadres worden verzonden",
	'developers:label:enable_error_log' => "Schakel error logging in",
	'developers:help:enable_error_log' => "Maak een eigen logbestand aan met fouten en berichten welke gelogd worden via error_log() gebaseerd op je loglevel instellingen. Deze log is zichtbaar via de beheer pagina.",

	'developers:label:submit' => "Opslaan en cache wissen",

	'developers:block_email:forward' => 'Stuur alle emails door',
	'developers:block_email:users' => 'Enkel voor gewone gebruikers',
	'developers:block_email:all' => 'Beheerders en gewone gebruikers',
	
	'developers:debug:off' => 'Uit',
	'developers:debug:error' => 'Fout',
	'developers:debug:warning' => 'Waarschuwing',
	'developers:debug:notice' => 'Bericht',
	'developers:debug:info' => 'Informatie',
	
	// entity explorer
	'developers:entity_explorer:help' => 'Bekijk informatie van entiteiten en voor enkele simpele acties uit',
	'developers:entity_explorer:guid:label' => 'Voer de GUID in van de entiteit die je wilt inspecteren',
	'developers:entity_explorer:info' => 'Entiteit Informatie',
	'developers:entity_explorer:info:attributes' => 'Attributen',
	'developers:entity_explorer:info:metadata' => 'Metadata',
	'developers:entity_explorer:info:relationships' => 'Relationships',
	'developers:entity_explorer:info:private_settings' => 'Private Settings',
	'developers:entity_explorer:info:owned_acls' => 'Eigen Access Collections',
	'developers:entity_explorer:info:acl_memberships' => 'Access Collections lidmaatschappen',
	'developers:entity_explorer:delete_entity' => 'Verwijder deze entiteit',
	'developers:entity_explorer:inspect_entity' => 'Inspecteer deze entiteit',
	'developers:entity_explorer:view_entity' => 'Bekijk deze entiteit op de site',
	
	// inspection
	'developers:inspect:help' => 'Inspecteer de configuratie van het Elgg framework.',
	'developers:inspect:actions' => 'Acties',
	'developers:inspect:events' => 'Events',
	'developers:inspect:menus' => 'Menu\'s',
	'developers:inspect:pluginhooks' => 'Plugin Hooks',
	'developers:inspect:priority' => 'Prioriteit',
	'developers:inspect:simplecache' => 'Simple Cache',
	'developers:inspect:routes' => 'Routes',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Opmerking!</b>Alle view in/output word gefilterd door deze Plugin Hooks:",
	'developers:inspect:views:input_filtered' => "(input gefilterd op plugin hook: %s)",
	'developers:inspect:views:filtered' => "(gefilterd op plugin hook: %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:widgets:context' => 'Context',
	'developers:inspect:functions' => 'Functies',
	'developers:inspect:file_location' => 'Bestandslocatie of controller',
	'developers:inspect:route' => 'Route Naam',
	'developers:inspect:path' => 'Path Patroon',
	'developers:inspect:resource' => 'Resource View',
	'developers:inspect:handler' => 'Handler',
	'developers:inspect:controller' => 'Controller',
	'developers:inspect:file' => 'Bestand',
	'developers:inspect:middleware' => 'Bestand',
	'developers:inspect:handler_type' => 'Afhandeling door',
	'developers:inspect:services' => 'Services',
	'developers:inspect:service:name' => 'Naam',
	'developers:inspect:service:path' => 'Definitie',
	'developers:inspect:service:class' => 'Class',

	// event logging
	'developers:request_stats' => "Request Statistieken (bevat niet het shutdown event)",
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "DB queries: %s",
	'developers:boot_cache_rebuilt' => "De boot cache is herbouwd voor deze pagina",
	'developers:elapsed_time' => "Tijd verstreken (s)",

	// theme sandbox
	'theme_sandbox:intro' => 'Introductie',
	'theme_sandbox:breakout' => 'Verlaat het iframe',
	'theme_sandbox:buttons' => 'Knoppen',
	'theme_sandbox:components' => 'Componenten',
	'theme_sandbox:email' => 'Email',
	'theme_sandbox:forms' => 'Formulieren',
	'theme_sandbox:grid' => 'Raster',
	'theme_sandbox:icons' => 'Iconen',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Lay-outs',
	'theme_sandbox:modules' => 'Modules',
	'theme_sandbox:navigation' => 'Paginanavigatie',
	'theme_sandbox:typography' => 'Typografie',

	'theme_sandbox:icons:blurb' => 'Gebruik <em>elgg_view_icon($name)</em> of de klasse <em>elgg-icon-$name</em> om iconen weer te geven.',
	
	'theme_sandbox:test_email:button' => "Stuur test email",
	'theme_sandbox:test_email:success' => "Test email verzonden naar: %s",

	// status messages
	'developers:settings:success' => 'Instellingen opgeslagen',

	'developers:amd' => 'AMD',

	'admin:develop_tools:error_log' => 'Fouten logboek',
	'developers:logs:empty' => 'Foutenlogboek is leeg',
);
