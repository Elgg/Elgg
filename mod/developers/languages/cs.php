<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Nástroje',
	
	// menu
	'admin:develop_tools:inspect' => 'Prohlížet',
	'admin:inspect' => 'Prohlížet',
	'admin:develop_tools:unit_tests' => 'Unit testy',
	'admin:develop_tools:entity_explorer' => 'Průzkumník entit',
	'admin:developers' => 'Vývojáři',
	'admin:developers:settings' => 'Nastavení',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Na této stránce můžete měnit vývojářská a ladící nastavení. Některá z těchto nastavení jsou také dostupná na dalších správcovských stránkách.',
	'developers:label:simple_cache' => 'Používat simple cache',
	'developers:help:simple_cache' => 'Během vývoje vypněte tuto mezipaměť, jinak budou ignorovány změny v CSS a JavaScriptu.',
	'developers:label:system_cache' => 'Používat systémovou mezipaněť',
	'developers:help:system_cache' => 'Během vývoje vypněte tuto mezipaměť, jinak budou ignorovány změny v doplňcích.',
	'developers:label:debug_level' => "Úroveň stopování",
	'developers:help:debug_level' => "Řídí množství zapisovaných informací. Pro více informací nahlédněte do elgg_log().",
	'developers:label:display_errors' => 'Zobrazovat smrtelné chyby PHP',
	'developers:label:screen_log' => "Vypisovat na obrazovku",
	'developers:label:show_strings' => "Ukazovat zdrojové řetězce pro překlad",
	'developers:help:show_strings' => "Zobrazuje překladové řetězce použité funkcí elgg_echo().",
	'developers:label:wrap_views' => "Zabalit pohledy",
	
	'developers:debug:off' => 'Vypnuto',
	'developers:debug:error' => 'Chyba',
	'developers:debug:warning' => 'Varování',
	'developers:debug:notice' => 'Upozornění',
	'developers:debug:info' => 'Všechno',
	
	// entity explorer
	'developers:entity_explorer:help' => 'Zobrazí informace o entitách a provede na nich několik základních operací.',
	'developers:entity_explorer:guid:label' => 'Zadejte guid entity, kterou chcete prozkoumat',
	'developers:entity_explorer:info:attributes' => 'Vlastnosti',
	'developers:entity_explorer:info:metadata' => 'Metadata',
	'developers:entity_explorer:info:relationships' => 'Relace',
	'developers:entity_explorer:delete_entity' => 'Odebrat tuto entitu',
	
	// inspection
	'developers:inspect:actions' => 'Akce',
	'developers:inspect:events' => 'Události',
	'developers:inspect:menus' => 'Nabídky',
	'developers:inspect:notifications' => 'Upozornění',
	'developers:inspect:notifications:type' => 'Typ',
	'developers:inspect:notifications:subtype' => 'Podtyp',
	'developers:inspect:priority' => 'Priorita',
	'developers:inspect:simplecache' => 'Simple cache',
	'developers:inspect:views' => 'Pohledy',
	'developers:inspect:views:all_filtered' => "<b>Poznámka!</b> Veškerý vstup a výstup pohledu je filtrován přes tato zapojení doplňků:",
	'developers:inspect:widgets' => 'Udělátka',
	'developers:inspect:widgets:context' => 'K dispozici',
	'developers:inspect:functions' => 'Funkce',
	'developers:inspect:file' => 'Soubory',
	'developers:inspect:service:name' => 'Jméno',

	'developers:request_stats' => "Požadovat statistiky (neobsahuje událost vypnutí)",
	'developers:event_log_msg' => "%s: '%s, %s' v %s",
	'developers:boot_cache_rebuilt' => "Zaváděcí mezipaměť byla pro tento požadavek obnovena",
	'developers:elapsed_time' => "Doba běhu (s)",
);
