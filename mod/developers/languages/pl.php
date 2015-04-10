<?php
return array(
	// menu
	'admin:develop_tools' => 'Narzędzia',
	'admin:develop_tools:sandbox' => 'Piaskownica szablonu',
	'admin:develop_tools:inspect' => 'Inspekcja',
	'admin:inspect' => 'Inspekcja',
	'admin:develop_tools:unit_tests' => 'Testy jednostkowe',
	'admin:developers' => 'Deweloperzy',
	'admin:developers:settings' => 'Ustawienia',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Kontroluj ustawienia programistyczne i debugowania poniżej. Niektóre z tych ustawień dostępne są również na innych stronach administratora.',
	'developers:label:simple_cache' => 'Użyj prostej pamięci podręcznej',
	'developers:help:simple_cache' => 'Wyłącz tą pamięć podręczną w chwili programowania. Inaczej zmiany w Twoim CSS i JavaScript zostaną zignorowane.',
	'developers:label:system_cache' => 'Użyj pamięci podręcznej systemu',
	'developers:help:system_cache' => 'Wyłącz w chwili programowania. Inaczej zmiany w Twoich pluginach nie będą zarejestrowane.',
	'developers:label:debug_level' => "Poziom tropienia",
	'developers:help:debug_level' => "Kontroluje ilość logowanych informacji. Zobacz elgg_log() po więcej informacji.",
	'developers:label:display_errors' => 'Wyświetlaj błędy PHP',
	'developers:help:display_errors' => "Domyślnie, plik .htaccess Elgg tłumi wyświetlanie krytycznych błędów.",
	'developers:label:screen_log' => "Logi na ekranie",
	'developers:help:screen_log' => "Wyświetla na stronie rezultaty elgg_log() i elgg_dump() oraz ilość zapytań do bazy danych.",
	'developers:label:show_strings' => "Wyświetl surowe teksty tłumaczenia",
	'developers:help:show_strings' => "Wyświetla łańcuchy tłumaczeń używane przez elgg_echo().",
	'developers:label:wrap_views' => "Widoki opakowania",
	'developers:help:wrap_views' => "To rozszerza prawie każdy widok o komentarze HTML. Jest to wygodne  szczególnie przy szukaniu widoku tworzącego konkretny HTML.
									To może wpłynąć negatywnie na widoki innego typu niż HTML dla typu widoku default. Zobacz szczegóły w developers_wrap_views().",
	'developers:label:log_events' => "Loguj wydarzenia i haki pluginów",
	'developers:help:log_events' => "Write events and plugin hooks to the log. Warning: there are many of these per page.",

	'developers:debug:off' => 'Wyłączone',
	'developers:debug:error' => 'Błąd',
	'developers:debug:warning' => 'Ostrzeżenie',
	'developers:debug:notice' => 'Uwaga',
	'developers:debug:info' => 'Informacje',
	
	// inspection
	'developers:inspect:help' => 'Sprawdź konfigurację szkieletu Elgg.',
	'developers:inspect:actions' => 'Akcje',
	'developers:inspect:events' => 'Wydarzenia',
	'developers:inspect:menus' => 'Menu',
	'developers:inspect:pluginhooks' => 'Haki rozszerzenia',
	'developers:inspect:priority' => 'Priorytet',
	'developers:inspect:simplecache' => 'Prosta pamięć podręczna',
	'developers:inspect:views' => 'Widoki',
	'developers:inspect:views:all_filtered' => "<b>Ostrożnie!</b> Wszystkie rezultaty widoków są filtrowane przez następujące wpięcia rozszerzeń:",
	'developers:inspect:views:filtered' => "(filtrowane przez filtr rozszerzenia: %s)",
	'developers:inspect:widgets' => 'Gadżety',
	'developers:inspect:webservices' => 'Usługi internetowe',
	'developers:inspect:widgets:context' => 'Kontekst',
	'developers:inspect:functions' => 'Funkcje',
	'developers:inspect:file_location' => 'Ścieżka systemu plików do korzenia Elgg',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' w %s",
	'developers:log_queries' => "%s zapytań do bazy danych (nie wliczając zapytań w zdarzeniu shutdown)",

	// theme sandbox
	'theme_sandbox:intro' => 'Wprowadzenie',
	'theme_sandbox:breakout' => 'Break out of iframe',
	'theme_sandbox:buttons' => 'Przyciski',
	'theme_sandbox:components' => 'Komponenty',
	'theme_sandbox:forms' => 'Formularze',
	'theme_sandbox:grid' => 'Układ',
	'theme_sandbox:icons' => 'Ikony',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Szablony',
	'theme_sandbox:modules' => 'Moduły',
	'theme_sandbox:navigation' => 'Nawigacja strony',
	'theme_sandbox:typography' => 'Typografia',

	'theme_sandbox:icons:blurb' => 'Użyj <em>elgg_view_icon($name)</em> lub klasy elgg-icon-$name aby wyświetlać ikony.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg has unit and integration tests for detecting bugs in its core classes and functions.',
	'developers:unit_tests:warning' => 'Ostrzeżenie: Nie uruchamiaj tych testów na stronie produkcyjnej. Mogą popsuć Twoją bazę danych.',
	'developers:unit_tests:run' => 'Uruchom',

	// status messages
	'developers:settings:success' => 'Ustawienia zostały zapisane',
);
