<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Työkalut',
	
	// menu
	'admin:develop_tools:sandbox' => 'Teemojen kehittäminen',
	'admin:develop_tools:inspect' => 'Tekninen rakenne',
	'admin:inspect' => 'Tekninen rakenne',
	'admin:develop_tools:unit_tests' => 'Yksikkötestit',
	'admin:developers' => 'Kehittäjät',
	'admin:developers:settings' => 'Asetukset',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Määrittele kehitys- ja testausasetuksesesi tästä. Jotkin asetuksista löytyvät myös muilta hallintasivuilta.',
	'developers:label:simple_cache' => 'Käytä yksinkertaista välimuistia',
	'developers:help:simple_cache' => 'Ota tämä välimuisti pois käytöstä kehitysympäristössä. Muutoin css- ja javascript-tiedostoihin tekemäsi muutokset eivät tule voimaan.',
	'developers:label:system_cache' => 'Käytä järjestelmän välimuistia',
	'developers:help:system_cache' => 'Ota tämä pois päältä kehitysympäristössä. Muutoin plugineihin tekemiäsi muutoksia ei rekisteröidä.',
	'developers:label:debug_level' => "Käytettävä taso",
	'developers:help:debug_level' => "Tämä määrittää lokiin tallennettavan datan määrän. Tutustu elgg_log()-funktioon saadaksesi lisätietoa.",
	'developers:label:display_errors' => 'Näytä kriittiset virheet',
	'developers:help:display_errors' => "Oletuksena Elggin .htaccess-tiedosto piilottaa kriittiset virheet.",
	'developers:label:screen_log' => "Näytä loki näytöllä",
	'developers:help:screen_log' => "Tämä tulostaa näkyviin elgg_log() ja elgg_dump()-funktioiden ulosannin sekä tietokantakyselyiden määrän.",
	'developers:label:show_strings' => "Näytä käännösten alkuperäiset merkkijonot",
	'developers:help:show_strings' => "Tämä näyttää merkkijonot, jotka syötetään elgg_echo()-käännösfunktiolle.",
	'developers:label:show_modules' => "Näytä ladatut AMD-moduulit konsolissa",
	'developers:help:show_modules' => "Näyttää konsolissa listan yksittäisen sivulatauksen aikana ladatuista AMD-moduuleista.",
	'developers:label:wrap_views' => "Lisää html-kommentit näkymiin",
	'developers:label:log_events' => "Merkitse event ja plugin hook -toiminnot lokiin.",
	'developers:help:log_events' => " Merkitse event ja plugin hook -toiminnot lokiin. Varoitus: näitä voi olla hyvin useita yksittäistä sivua kohden.",
	'developers:label:show_gear' => "Näytä %s hallintapaneelin ulkopuolella",
	'developers:help:show_gear' => "Näyttää sivun oikeassa alakulmassa ikonin, josta pääset helposti käsiksi hallintapaneeliin liittyviin toimintoihin ja asetuksiin.",

	'developers:label:submit' => "Tallenna ja tyhjennä välimuistit",
	
	'developers:debug:off' => 'Pois päältä',
	'developers:debug:error' => 'Virheet',
	'developers:debug:warning' => 'Varoitukset',
	'developers:debug:notice' => 'Huomautukset',
	'developers:debug:info' => 'Info',
	
	// entity explorer
	'developers:entity_explorer:info:metadata' => 'Metadata',
	'developers:entity_explorer:info:relationships' => 'Suhteet',
	
	// inspection
	'developers:inspect:help' => 'Tarkastele sivuston teknistä rakennetta.',
	'developers:inspect:actions' => 'Actions',
	'developers:inspect:events' => 'Events',
	'developers:inspect:menus' => 'Menus',
	'developers:inspect:pluginhooks' => 'Plugin hooks',
	'developers:inspect:priority' => 'Tärkeysjärjestys',
	'developers:inspect:simplecache' => 'Simple cache',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Huom!</b> Kaikki näkymät ajetaan seuraavien hookkien läpi:",
	'developers:inspect:views:filtered' => "(ajettu läpi plugin-hookista: %s)",
	'developers:inspect:widgets' => 'Vimpaimet',
	'developers:inspect:widgets:context' => 'Context',
	'developers:inspect:functions' => 'Functions',
	'developers:inspect:file' => 'Tiedostot',
	'developers:inspect:middleware' => 'Tiedostot',
	'developers:inspect:service:name' => 'Nimi',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "%s tietokantakyselyä (ei sisällä shutdown-eventissä tapahtuneita)",

	// theme sandbox
	'theme_sandbox:intro' => 'Ohjeet',
	'theme_sandbox:breakout' => 'Käytä koko näytön tilaa',
	'theme_sandbox:buttons' => 'Painikkeet',
	'theme_sandbox:components' => 'Komponentit',
	'theme_sandbox:forms' => 'Lomakkeet',
	'theme_sandbox:grid' => 'Palstat',
	'theme_sandbox:icons' => 'Ikonit',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Asettelu',
	'theme_sandbox:modules' => 'Moduulit',
	'theme_sandbox:navigation' => 'Navigaatio',
	'theme_sandbox:typography' => 'Typografia',

	'theme_sandbox:icons:blurb' => 'Käytä funktiota <code>elgg_view_icon($name)</code>  tai luokkaa <code>elgg-icon-$name</code> näyttääksesi ikoneita.',

	// status messages
	'developers:settings:success' => 'Asetukset tallennettu',

	'developers:amd' => 'AMD',
);
