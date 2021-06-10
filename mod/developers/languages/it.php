<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Strumenti',
	
	// menu
	'admin:develop_tools:sandbox' => 'Sandbox per i temi',
	'admin:develop_tools:inspect' => 'Ispeziona',
	'admin:inspect' => 'Ispeziona',
	'admin:develop_tools:unit_tests' => 'Test unità',
	'admin:developers' => 'Sviluppatori',
	'admin:developers:settings' => 'Impostazioni',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Verificare le impostazioni di sviluppo e debug qui sotto. Alcune di queste impostazioni sono anche disponibili in altre pagine amministrative.',
	'developers:label:simple_cache' => 'Usa la cache semplice',
	'developers:help:simple_cache' => 'Disabilita questa cache in fase di sviluppo. Altrimenti le modifiche nel CSS e JavaScript verranno ignorate ',
	'developers:label:system_cache' => 'Usa la cache di sistema',
	'developers:help:system_cache' => 'Disabilitalo in fase di sviluppo. Altrimenti le modifiche nei tuoi plugins non verranno registrate.',
	'developers:label:debug_level' => "Livello di trace",
	'developers:help:debug_level' => "Questo controlla la quantità di informazioni registrate. Per maggiori informazioni vedi elgg_log()",
	'developers:label:display_errors' => 'Mostra gli errori fatali di PHP',
	'developers:help:display_errors' => "Di norma, il file .htaccess di Elgg disabilita la visualizzazione degli errori irreversibili.",
	'developers:label:screen_log' => "Accedi allo schermo",
	'developers:help:screen_log' => "Visualizza l'output di elgg_log() ed elgg_dump() e un contatore di query al DB.",
	'developers:label:show_strings' => "Mostra le stringhe delle chiavi di traduzione",
	'developers:help:show_strings' => "Visualizza le stringhe delle chiavi di traduzione usate da elgg_echo().",
	'developers:label:show_modules' => "Visualizza i moduli AMD caricati in console",
	'developers:help:show_modules' => "Fa scorrere i moduli caricati e i valori nella console JavaScript.",
	'developers:label:wrap_views' => "Commenta viste",
	'developers:label:log_events' => "Traccia gli agganci di eventi e plugin",
	'developers:help:log_events' => "Scrive gli agganci di eventi e plugin nel log. Attenzione: ce ne sono tanti di questi per pagina.",
	'developers:label:show_gear' => "Usa %s al di fuori dell'area amministrativa",
	'developers:help:show_gear' => "Un'icona nell'angolo in basso a destra della vista che dà accessi amministrativi per sviluppare impostazioni e collegamenti.",

	'developers:label:submit' => "Salva e rinfresca la cache",
	
	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Errore',
	'developers:debug:warning' => 'Warning',
	'developers:debug:notice' => 'Notifica',
	'developers:debug:info' => 'Info',
	
	// entity explorer
	'developers:entity_explorer:info:metadata' => 'Metadato',
	'developers:entity_explorer:info:relationships' => 'Relazioni',
	
	// inspection
	'developers:inspect:help' => 'Indaga la configurazione del framework di Elgg',
	'developers:inspect:actions' => 'Azioni',
	'developers:inspect:events' => 'Eventi',
	'developers:inspect:menus' => 'Menu',
	'developers:inspect:pluginhooks' => 'Agganci dei plugin',
	'developers:inspect:priority' => 'Priorità',
	'developers:inspect:simplecache' => 'Cache semplice',
	'developers:inspect:views' => 'Viste',
	'developers:inspect:views:all_filtered' => "<b>Nota!</b> L'output di tutte le viste è filtrato attraverso questi agganci di plugin:",
	'developers:inspect:views:filtered' => "(filtrato per aggancio di plugin: %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:widgets:context' => 'Contesto',
	'developers:inspect:functions' => 'Funzioni',
	'developers:inspect:file' => 'File',
	'developers:inspect:middleware' => 'File',
	'developers:inspect:service:name' => 'Nome',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "%s query di DB (non comprende l'evento di shutdown)",

	// theme sandbox
	'theme_sandbox:intro' => 'Introduzione',
	'theme_sandbox:breakout' => 'Riquadro di iframe',
	'theme_sandbox:buttons' => 'Pulsanti',
	'theme_sandbox:components' => 'Componenti',
	'theme_sandbox:forms' => 'Moduli',
	'theme_sandbox:grid' => 'Griglia',
	'theme_sandbox:icons' => 'Icone',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Layout',
	'theme_sandbox:modules' => 'Moduli',
	'theme_sandbox:navigation' => 'Navigazione',
	'theme_sandbox:typography' => 'Tipografia',

	'theme_sandbox:icons:blurb' => 'Usare <em>elgg_view_icon($name)</em> per visualizzare le icone.',

	// status messages
	'developers:settings:success' => 'Impostazioni salvate e cache rinfrescata',

	'developers:amd' => 'AMD',
);
