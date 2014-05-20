<?php
return array(
	// menu
	'admin:develop_tools' => 'Værktøjer',
	'admin:develop_tools:sandbox' => 'Tema Sandkasse',
	'admin:develop_tools:inspect' => 'Undersøg',
	'admin:develop_tools:unit_tests' => 'Enheds Tests',
	'admin:developers' => 'Udviklere',
	'admin:developers:settings' => 'Udvikler indstillinger',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Kontrollér dine indstillinger for udvikling og fejlfinding nedenfor. Nogle af disse indstillinger er også tilgængelige på andre admin sider.',
	'developers:label:simple_cache' => 'Brug simpel cache',
	'developers:help:simple_cache' => 'Fravælg fil cache, når du udvikler. Ellers vil dine ændringer (herunder CSS) blive ignoreret.',
	'developers:label:system_cache' => 'Use system cache',
	'developers:help:system_cache' => 'Turn this off when developing. Otherwise, changes in your plugins will not be registered.',
	'developers:label:debug_level' => "Sporings niveau",
	'developers:help:debug_level' => "Dette kontrollerer mængden af ​​loggede oplysninger. Se elgg_log() for flere oplysninger.",
	'developers:label:display_errors' => 'Vis fatale PHP fejl',
	'developers:help:display_errors' => "Som standard, udelader Elgg's .htaccess fil visning af fatale fejl.",
	'developers:label:screen_log' => "Log til skærmen",
	'developers:help:screen_log' => "Dette viser elgg_log() og elgg_dump() output på websiden.",
	'developers:label:show_strings' => "Vis rå oversættelses strenge",
	'developers:help:show_strings' => "Dette viser oversættelses strenge brugt af elgg_echo().",
	'developers:label:wrap_views' => "Wrap views",
	'developers:help:wrap_views' => "Dette wrapper næsten enhver view med HTML kommentarer. Brugbar for at finde viewet der især laver HTML.
⇥⇥⇥⇥⇥⇥⇥⇥⇥Dette kan knække ikke-HTML views i en standard viewtype. Se developers_wrap_views() for detaljer",
	'developers:label:log_events' => "Log events og plugin hooks",
	'developers:help:log_events' => "Skriver events og plugin hooks til loggen. Advarsel: Der er mange af disse per side.",

	'developers:debug:off' => 'Fra',
	'developers:debug:error' => 'Fejl',
	'developers:debug:warning' => 'Advarsel',
	'developers:debug:notice' => 'Bemærk',
	'developers:debug:info' => 'Info',
	
	// inspection
	'developers:inspect:help' => 'Inspicér konfiguration af Elgg\'s  framework.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' i %s",

	// theme sandbox
	'theme_sandbox:intro' => 'Introduktion',
	'theme_sandbox:breakout' => 'Hop ud af iframe',
	'theme_sandbox:buttons' => 'Knapper',
	'theme_sandbox:components' => 'Komponenter',
	'theme_sandbox:forms' => 'Formularer',
	'theme_sandbox:grid' => 'Grid',
	'theme_sandbox:icons' => 'Ikoner',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Layouts',
	'theme_sandbox:modules' => 'Moduler',
	'theme_sandbox:navigation' => 'Navigation',
	'theme_sandbox:typography' => 'Typografi',

	'theme_sandbox:icons:blurb' => 'Brug <em>elgg_view_icon($name)</em> eller class elgg-icon-$name for at vise ikoner.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg has unit and integration tests for detecting bugs in its core classes and functions.',
	'developers:unit_tests:warning' => 'Warning: Do Not Run These Tests on a Production Site. They can corrupt your database.',
	'developers:unit_tests:run' => 'Run',

	// status messages
	'developers:settings:success' => 'Indstillinger gemt',
);
