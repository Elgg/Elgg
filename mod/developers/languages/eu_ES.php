<?php
return array(
	// menu
	'admin:develop_tools' => 'Tresnak',
	'admin:develop_tools:sandbox' => 'Sandbox gaia',
	'admin:develop_tools:inspect' => 'Ikuskatu',
	'admin:inspect' => 'Ikuskatu',
	'admin:develop_tools:unit_tests' => 'Unitate frogak',
	'admin:developers' => 'Garatzaileak',
	'admin:developers:settings' => 'Ezarpenak',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Kontrolatu zure garapen eta arazte ezarpenak hemen. Ezarpen hauetako batzuk beste administrazio orrialdeetan ere eskuragarri daude.',
	'developers:label:simple_cache' => 'Katxe sinplea erabili',
	'developers:help:simple_cache' => 'Desgaitu katxe hau garatzen dabilenean. Bestela, zure CSS eta JavaScript aldaketak alde batera utziko dira.',
	'developers:label:system_cache' => 'Sistemako katxea erabili',
	'developers:help:system_cache' => 'Desgaitu katxe hau garatzen dabilenean. Bestela, zure plugineko aldaketak alde batera utziko dira.',
	'developers:label:debug_level' => "Aztarna maila",
	'developers:help:debug_level' => "Honek logeatzen den informazio kopurua kontrolatzen du. Ikusi elgg_log() informazio gehiagorako.",
	'developers:label:display_errors' => 'PHPko errore larriak erakutsi',
	'developers:help:display_errors' => "Defektuz, Elgg-en .htaccess fitxategiaren ezabatzean errore larria ekarriko du.",
	'developers:label:screen_log' => "Logak pantailan",
	'developers:help:screen_log' => "Honek elgg_log(), elgg_dump() eta DB kontsulta kontaketak erakusten ditu.",
	'developers:label:show_strings' => "Itzulpen kate gordinak erakutsi",
	'developers:help:show_strings' => "elgg_echo() erabilitako Itzulpen kateak erakutsi.",
	'developers:label:wrap_views' => "Egokitutako bistak",
	'developers:help:wrap_views' => "Honek HTML iruzkinekin ia bista guztiak biltzen ditu. Erabilgarria bistak bilatzen sortutako HTML partikularrekin.
									Honek ez-HTML bistak apurtu ditzake bista motean. Ikusi developers_wrap_views() xehetasun gehiagorako.",
	'developers:label:log_events' => "Log gertakariak eta plugin kakoak",
	'developers:help:log_events' => "Gertakariak eta plugin kakoak logean idatzi. Abisua: orriko hauetariko asko egon daitezke.",

	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Errorea',
	'developers:debug:warning' => 'Abisua',
	'developers:debug:notice' => 'Oharra',
	'developers:debug:info' => 'Informazioa',
	
	// inspection
	'developers:inspect:help' => 'Elgg esparruko ikuskatze konfigurazioa.',
	'developers:inspect:actions' => 'Akzioak',
	'developers:inspect:events' => 'Gertaerak',
	'developers:inspect:menus' => 'Menuak',
	'developers:inspect:pluginhooks' => 'Plugin kakoak',
	'developers:inspect:priority' => 'Prioritatea',
	'developers:inspect:simplecache' => 'Katxe sinplea',
	'developers:inspect:views' => 'Bistak',
	'developers:inspect:views:all_filtered' => "<b>Oharra!</b> Bista irteera guziak Plugin Kako hauen bitartez iragazten dira:",
	'developers:inspect:views:filtered' => "(plugin kako hauen bitartez iragazita: %s)",
	'developers:inspect:widgets' => 'Widgetak',
	'developers:inspect:webservices' => 'Web zerbitzuak',
	'developers:inspect:widgets:context' => 'Testuingurua',
	'developers:inspect:functions' => 'Funtzioak',
	'developers:inspect:file_location' => 'Elgg errotik fitxategi bidea',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s hemen %s",
	'developers:log_queries' => "%s DB kontsultak (ez daude ixte gertakariak barne)",

	// theme sandbox
	'theme_sandbox:intro' => 'Sarrera',
	'theme_sandbox:breakout' => 'Iframearen etena',
	'theme_sandbox:buttons' => 'Botoiak',
	'theme_sandbox:components' => 'Osagaiak',
	'theme_sandbox:forms' => 'Formularioak',
	'theme_sandbox:grid' => 'Sareta',
	'theme_sandbox:icons' => 'Ikonoak',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Diseinua',
	'theme_sandbox:modules' => 'Moduluak',
	'theme_sandbox:navigation' => 'Nabigazioa',
	'theme_sandbox:typography' => 'Tipografia',

	'theme_sandbox:icons:blurb' => '<em>elgg_view_icon($name)</em> edo elgg-icon-$name klasea erabili ikonoak erakusteko.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg-ek unitate eta integrazio frogak ditu muineko klase eta funtzioetan arazoak detektatzeko.',
	'developers:unit_tests:warning' => 'Abisua: Ez jarri martxan froga hauek produkzio gune baten. Datu-basea hondatu dezakete.',
	'developers:unit_tests:run' => 'Exekutatu',

	// status messages
	'developers:settings:success' => 'Ezarpenak gordeta',
);
