<?php
return array(
	// menu
	'admin:develop_tools' => 'Eines',
	'admin:develop_tools:sandbox' => 'Proves del tema',
	'admin:develop_tools:inspect' => 'Inspeccions',
	'admin:inspect' => 'Inspeccions',
	'admin:develop_tools:unit_tests' => 'Tests d\'unitat',
	'admin:developers' => 'Desenvolupadors',
	'admin:developers:settings' => 'Configuració de Desenvolupadors',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Controla la configuració de desenvolupament i depuració. Algunes d\'aquestes opcions també estan disponibles a altres seccions de l\'administració.',
	'developers:label:simple_cache' => 'Utilitzar mem&ograve;ria cau simple',
	'developers:help:simple_cache' => 'Deshabilitar la mem&ograve;ria cau durant el temps que es desenvolupa. D&acute;una altra manera, les modificacions a les vistes (inclosos els css) seran ignorades.',
	'developers:label:system_cache' => 'Utilitza la memòria cau del sistema',
	'developers:help:system_cache' => 'Desconnecta això mentre desenvolupes. Sinò, els canvis a les teves extensions no es registraran.',
	'developers:label:debug_level' => "Nivell de monitoratge",
	'developers:help:debug_level' => "Aix&ograve; controla la quantitat d&acute;informació que es registra. Mira elgg_log() per ampliar la informació.",
	'developers:label:display_errors' => 'Mostrar errors fatals de PHP',
	'developers:help:display_errors' => "Per defecte, l&acute;arxiu .htaccess d&acute;Elgg deshabilita la visualització d&acute;errors fatals.",
	'developers:label:screen_log' => "Registrar a la pantalla",
	'developers:help:screen_log' => "Això mostra la sortida de elgg_log () i elgg_dump () i un recompte de consulta de la Base de dades.",
	'developers:label:show_strings' => "Mostra les cadenes de text sense traduïr",
	'developers:help:show_strings' => "Aix&ograve; mostra les traduccions utilitzades per elgg_echo().",
	'developers:label:wrap_views' => "Wrap de vistes",
	'developers:help:wrap_views' => "Això embolcalla gairebé cada vista amb comentaris HTML. Útil per trobar el punt de vista particular, la creació HTML.
									Això pot trencar vistes no HTML en el tipus de vista per defecte . Veure developers_wrap_views ( ) per a més detalls .",
	'developers:label:log_events' => "Esdeveniments de Logs i Hooks de plugins",
	'developers:help:log_events' => "Escriure esdeveniments i hooks de plugins en el log. Alerta: n&acute;hi ha més d&acute;un a cada p&aacute;gina.",

	'developers:debug:off' => 'Apagat',
	'developers:debug:error' => 'Error',
	'developers:debug:warning' => 'Alerta',
	'developers:debug:notice' => 'Informació',
	'developers:debug:info' => 'Informació',
	
	// inspection
	'developers:inspect:help' => 'Inspecció de configuració del framework Elgg.',
	'developers:inspect:actions' => 'Accions',
	'developers:inspect:events' => 'Esdeveniments',
	'developers:inspect:menus' => 'Menús',
	'developers:inspect:pluginhooks' => 'Ganxos de complement',
	'developers:inspect:priority' => 'Per prioritat',
	'developers:inspect:simplecache' => 'Memòria cau simple',
	'developers:inspect:views' => 'Vistes',
	'developers:inspect:views:all_filtered' => "<b>Note!</b> All view output is filtered through these Plugin Hooks:",
	'developers:inspect:views:filtered' => "(filtrat per ganxo de complement: %s)",
	'developers:inspect:widgets' => 'Ginys',
	'developers:inspect:webservices' => 'Serveis web',
	'developers:inspect:widgets:context' => 'Context',
	'developers:inspect:functions' => 'Funcions',
	'developers:inspect:file_location' => 'Directori principal d\'Elgg',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' a %s",
	'developers:log_queries' => "%s sol·licituds a la Base de dades (no s'inclouen els esdeveniments de tancament)",

	// theme sandbox
	'theme_sandbox:intro' => 'Introducció',
	'theme_sandbox:breakout' => 'Fora d&acute;iframe',
	'theme_sandbox:buttons' => 'Botons',
	'theme_sandbox:components' => 'Components',
	'theme_sandbox:forms' => 'Formularis',
	'theme_sandbox:grid' => 'Graella',
	'theme_sandbox:icons' => 'Icones',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Disposicions',
	'theme_sandbox:modules' => 'M&ograve;duls',
	'theme_sandbox:navigation' => 'Pàgina de navegació',
	'theme_sandbox:typography' => 'Tipografíes',

	'theme_sandbox:icons:blurb' => 'Utilitza <em>elgg_view_icon($name)</em> o la classe elgg-icon-$name per a mostrar icones.',

	// unit tests
	'developers:unit_tests:description' => 'L\'Elgg té tests d\'integració i unitat per detectar errors en les classes i funcions del nucli',
	'developers:unit_tests:warning' => 'Advertència: No utilitzis aquests tests en un lloc de producció. Poden corrompre la teva base de dades',
	'developers:unit_tests:run' => 'Testejar',

	// status messages
	'developers:settings:success' => 'Configuracions desades',
);
