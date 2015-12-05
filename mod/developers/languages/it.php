<?php
return array(
	// menu
	'admin:develop_tools' => 'Strumenti',
	'admin:develop_tools:sandbox' => 'Sandbox per i temi',
	'admin:develop_tools:inspect' => 'Ispeziona',
	'admin:inspect' => 'Ispeziona',
	'admin:develop_tools:unit_tests' => 'Test unità',
	'admin:developers' => 'Sviluppatori',
	'admin:developers:settings' => 'Impostazioni',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Verificare le impostazioni di sviluppo e debug qui sotto. Alcune di queste impostazioni sono anche disponibili in altre pagine amministrative.',
	'developers:label:simple_cache' => 'Usa la cache semplice',
	'developers:help:simple_cache' => 'Disabilitare questa cache in fase di sviluppo. Altrimenti le modifiche nei CSS e nei JavaScript verranno ignorate .',
	'developers:label:system_cache' => 'Usa la cache di sistema',
	'developers:help:system_cache' => 'Disabilitarla in fase di sviluppo. Altrimenti le modifiche fatte ai plugins non verranno registrate.',
	'developers:label:debug_level' => "Livello di trace",
	'developers:help:debug_level' => "Controlla la quantità di informazioni registrate. Vedere elgg_log() per maggiori informazioni.",
	'developers:label:display_errors' => 'Visualizza gli errori PHP irreversibili',
	'developers:help:display_errors' => "Di norma il file .htaccess di Elgg disabilita la visualizzazione degli errori irreversibili.",
	'developers:label:screen_log' => "Log sullo schermo",
	'developers:help:screen_log' => "Visualizza l'output di elgg_log() ed elgg_dump() e il contatore delle query al DB.",
	'developers:label:show_strings' => "Mostra le stringhe delle chiavi di traduzione",
	'developers:help:show_strings' => "Visualizza le stringhe delle chiavi di traduzione usate da elgg_echo().",
	'developers:label:show_modules' => "Visualizza i moduli AMD caricati in console",
	'developers:help:show_modules' => "Fa scorrere i moduli caricati e i valori nella console JavaScript.",
	'developers:label:wrap_views' => "Commenta viste",
	'developers:help:wrap_views' => "Avvolge praticamente ogni vista con commenti HTML. Può tornare utile per trovare la vista che crea uno specifico HTML.
									Potrebbe interrompere le viste non HTML nel tipo di vista predefinito. Consultare developers_wrap_views() per dettagli.",
	'developers:label:log_events' => "Traccia gli agganci di eventi e plugin",
	'developers:help:log_events' => "Scrive gli agganci di eventi e plugin nel log. Attenzione: ce ne sono tanti di questi per pagina.",
	'developers:label:show_gear' => "Usa %s al di fuori dell'area amministrativa",
	'developers:help:show_gear' => "Un'icona nell'angolo in basso a destra della vista che garantisce accessi amministrativi per sviluppare impostazioni e collegamenti.",
	'developers:label:submit' => "Salva e rinfresca la cache",

	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Errori',
	'developers:debug:warning' => 'Warning',
	'developers:debug:notice' => 'Notifiche',
	'developers:debug:info' => 'Info',
	
	// inspection
	'developers:inspect:help' => 'Indaga la configurazione del framework di Elgg.',
	'developers:inspect:actions' => 'Azioni',
	'developers:inspect:events' => 'Eventi',
	'developers:inspect:menus' => 'Menu',
	'developers:inspect:pluginhooks' => 'Agganci dei plugin',
	'developers:inspect:priority' => 'Priorità',
	'developers:inspect:simplecache' => 'Cache semplice',
	'developers:inspect:views' => 'Viste',
	'developers:inspect:views:all_filtered' => "<b>Nota!</b> L'output di tutte le viste è filtrato attraverso questi agganci di plugin:",
	'developers:inspect:views:filtered' => "(filtrato per aggancio di plugin: %s)",
	'developers:inspect:widgets' => 'Widget',
	'developers:inspect:webservices' => 'Servizi web',
	'developers:inspect:widgets:context' => 'Contesto',
	'developers:inspect:functions' => 'Funzioni',
	'developers:inspect:file_location' => 'Percorso del file dalla radice di Elgg',

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

	'theme_sandbox:icons:blurb' => 'Usa <em>elgg_view_icon($name)</em> o la classe elgg-icon-$name per visualizzare le icone.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg possiede un\'unità e dei test integrati per rilevare i bug nelle sue classi e funzioni del core.',
	'developers:unit_tests:warning' => 'Attenzione: Non eseguire questi test su un sito di produzione. Potrebbero danneggiare il database.',
	'developers:unit_tests:run' => 'Esegui',

	// status messages
	'developers:settings:success' => 'Impostazioni salvate e cache rinfrescata',

	'developers:amd' => 'AMD',
);
