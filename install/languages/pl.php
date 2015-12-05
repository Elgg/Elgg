<?php
return array(
	'install:title' => 'Instalacja Elgg',
	'install:welcome' => 'Witaj',
	'install:requirements' => 'Kontrola wymagań',
	'install:database' => 'Instalacja bazy danych',
	'install:settings' => 'Konfiguracja strony',
	'install:admin' => 'Utwórz konto administracyjne',
	'install:complete' => 'Gotowe',

	'install:next' => 'Dalej',
	'install:refresh' => 'Odśwież',

	'install:welcome:instructions' => "Installing Elgg has 6 simple steps and reading this welcome is the first one!

If you haven't already, read through the installation instructions included with Elgg (or click the instructions link at the bottom of the page).

If you are ready to proceed, click the Next button.",
	'install:requirements:instructions:success' => "Twój serwer przeszedł pomyślnie kontrolę wymagań.",
	'install:requirements:instructions:failure' => "Your server failed the requirements check. After you have fixed the below issues, refresh this page. Check the troubleshooting links at the bottom of this page if you need further assistance.",
	'install:requirements:instructions:warning' => "Your server passed the requirements check, but there is at least one warning. We recommend that you check the install troubleshooting page for more details.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Serwer www',
	'install:require:settings' => 'Plik ustawień',
	'install:require:database' => 'Baza danych',

	'install:check:root' => 'Your web server does not have permission to create an .htaccess file in the root directory of Elgg. You have two choices:

		1. Change the permissions on the root directory

		2. Copy the file install/config/htaccess.dist to .htaccess',

	'install:check:php:version' => 'Elgg wymaga PHP w wersji %s lub nowszej. Serwer używa wersji %s.',
	'install:check:php:extension' => 'Elgg wymaga następującego rozszerzenia PHP: %s.',
	'install:check:php:extension:recommend' => 'Zalecane jest zainstalowanie następującego rozszerzenia PHP: %s.',
	'install:check:php:open_basedir' => 'Dyrektywa PHP open_basedir może przeszkodzić Elgg w zapisie plików do katalogu danych.',
	'install:check:php:safe_mode' => 'Używanie PHP w trybie safe mode jest niezalecane i może powodować problemy z Elgg.',
	'install:check:php:arg_separator' => 'arg_separator.output musi mieć wartość & aby Elgg działał poprawnie. Wartość dla twojego serwera to %s',
	'install:check:php:register_globals' => 'Register globals musi być wyłączone.',
	'install:check:php:session.auto_start' => "session.auto_start musi być wyłączone aby Elgg poprawnie działał. Zmień konfigurację serwera lub dodaj wpis do pliku .htaccess .",

	'install:check:enginedir' => 'Twój serwer nie ma uprawnień do utworzenia pliku settings.php w katalogu engine. Masz dwie możliwości:

		1. Zmień uprawnienia dostępu do katalogu engine

		2. Skopiuj plik settings.example.php do settings.php i podążaj za instrukcjami zawartymi w pliku w celu ustawienia parametrów bazy danych.',
	'install:check:readsettings' => 'A settings file exists in the engine directory, but the web server cannot read it. You can delete the file or change the read permissions on it.',

	'install:check:php:success' => "Twoja instalacja PHP spełnia wszystkie wymagania Elgg.",
	'install:check:rewrite:success' => 'Test reguł przepisywania powiódł się.',
	'install:check:database' => 'Wymagania bazy danych są sprawdzane, kiedy Elgg załaduje bazę danych.',

	'install:database:instructions' => "Jeśli nie utworzyłeś jeszcze bazy danych dla Elgg - zrób to teraz. Potem wypełnij poniższe pola, aby zainicjować bazę danych Elgg.",
	'install:database:error' => 'There was an error creating the Elgg database and installation cannot continue. Review the message above and correct any problems. If you need more help, visit the Install troubleshooting link below or post to the Elgg community forums.',

	'install:database:label:dbuser' =>  'Nazwa użytkownika bazy danych',
	'install:database:label:dbpassword' => 'Hasło bazy danych',
	'install:database:label:dbname' => 'Nazwa bazy danych',
	'install:database:label:dbhost' => 'Host bazy danych',
	'install:database:label:dbprefix' => 'Prefiks tabeli bazy danych',

	'install:database:help:dbuser' => 'Użytkownik, który ma pełne uprawnienia dla bazy danych MySQL, którą utworzyłeś dla Elgg',
	'install:database:help:dbpassword' => 'Hasło dla powyższego konta użytkownika bazy danych',
	'install:database:help:dbname' => 'Nazwa bazy danych Elgg',
	'install:database:help:dbhost' => 'Nazwa hosta serwera MySQL (zazwyczaj localhost)',
	'install:database:help:dbprefix' => "Prefiks nadawany wszystkim tabelom Elgg (zazwyczaj elgg_)",

	'install:settings:instructions' => 'We need some information about the site as we configure Elgg. If you haven\'t <a href="http://learn.elgg.org/en/1.x/intro/install.html#create-a-data-folder" target="_blank">created a data directory</a> for Elgg, you need to do so now.',

	'install:settings:label:sitename' => 'Nazwa strony',
	'install:settings:label:siteemail' => 'Adres e-mail strony',
	'install:settings:label:wwwroot' => 'Adres URL strony',
	'install:settings:label:path' => 'Katalog, w którym instalujesz Elgg',
	'install:settings:label:dataroot' => 'Katalog danych',
	'install:settings:label:language' => 'Język strony',
	'install:settings:label:siteaccess' => 'Domyślny poziom dostępu na stronie',
	'install:label:combo:dataroot' => 'Elgg tworzy katalog danych',

	'install:settings:help:sitename' => 'Nazwa Twojej nowej strony opartej na Elgg',
	'install:settings:help:siteemail' => 'Adres e-mail używany przez Elgg do komunikacji z użytkownikami',
	'install:settings:help:wwwroot' => 'Adres strony (Elgg zazwyczaj poprawnie zgaduje)',
	'install:settings:help:path' => 'Ścieżka gdzie umieściłeś kod Elgg (Elgg zazwyczaj zgaduje ją poprawnie)',
	'install:settings:help:dataroot' => 'The directory that you created for Elgg to save files (the permissions on this directory are checked when you click Next). It must be an absolute path.',
	'install:settings:help:dataroot:apache' => 'You have the option of Elgg creating the data directory or entering the directory that you already created for storing user files (the permissions on this directory are checked when you click Next)',
	'install:settings:help:language' => 'Domyślny język dla strony',
	'install:settings:help:siteaccess' => 'Domyślny poziom dostępu dla nowo utworzonych treści użytkownika',

	'install:admin:instructions' => "Nastał właściwy czas, by utworzyć konto administratora.",

	'install:admin:label:displayname' => 'Wyświetlana nazwa',
	'install:admin:label:email' => 'Adres e-mail',
	'install:admin:label:username' => 'Nazwa użytkownika',
	'install:admin:label:password1' => 'Hasło',
	'install:admin:label:password2' => 'Ponownie hasło',

	'install:admin:help:displayname' => 'Nazwa, która jest wyświetlana na stronie dla tego konta',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Nazwa użytkownika konta używana do logowania',
	'install:admin:help:password1' => "Hasło konta musi mieć przynajmniej %u znaków",
	'install:admin:help:password2' => 'Wprowadź ponownie hasło dla potwierdzenia',

	'install:admin:password:mismatch' => 'Hasła muszą być jednakowe.',
	'install:admin:password:empty' => 'Hasło nie może być puste.',
	'install:admin:password:tooshort' => 'Twoje hasło jest za krótkie',
	'install:admin:cannot_create' => 'Nie udało się utworzyć konta administratora.',

	'install:complete:instructions' => 'Twoja strona Elgg jest gotowa do użytku. Kliknij przycisk poniżej, aby ją zobaczyć.',
	'install:complete:gotosite' => 'Przejdź do strony',

	'InstallationException:UnknownStep' => '%s jest nieznanym krokiem instalacji.',
	'InstallationException:MissingLibrary' => 'Nie można załadować %s',
	'InstallationException:CannotLoadSettings' => 'Elgg nie mógł wczytać pliku ustawień. Może on nie istnieć lub plik ma złe ustawienia uprawnienień.',

	'install:success:database' => 'Baza danych została zainstalowana.',
	'install:success:settings' => 'Ustawienia strony zostały zapisane.',
	'install:success:admin' => 'Konto administratora zostało utworzone.',

	'install:error:htaccess' => 'Nie udało się utworzyć pliku .htaccess',
	'install:error:settings' => 'Nie udało się utworzyć pliku ustawień',
	'install:error:databasesettings' => 'Nie można połączyć się z bazą danych z tymi ustawieniami.',
	'install:error:database_prefix' => 'Niepoprawne znaki w prefiksie bazy danych',
	'install:error:oldmysql' => 'MySQL musi być w wersji 5.0 lub wyższej. Twój serwer używa %s.',
	'install:error:nodatabase' => 'Nie można było użyć bazy danych %s. Możliwe, że nie istnieje.',
	'install:error:cannotloadtables' => 'Nie można wczytać tabeli bazy danych',
	'install:error:tables_exist' => 'There are already Elgg tables in the database. You need to either drop those tables or restart the installer and we will attempt to use them. To restart the installer, remove \'?step=database\' from the URL in your browser\'s address bar and press Enter.',
	'install:error:readsettingsphp' => 'Nie można odczytać engine/settings.example.php',
	'install:error:writesettingphp' => 'Nie można zapisać engine/settings.php',
	'install:error:requiredfield' => '%s jest wymagany',
	'install:error:relative_path' => 'Nie myślimy, że "%s" jest absolutną ścieżką do Twojego katalogu danych',
	'install:error:datadirectoryexists' => 'Twój katalog danych %s nie istnieje.',
	'install:error:writedatadirectory' => 'Twój katalog danych %s nie jest zapisywalny przez serwer.',
	'install:error:locationdatadirectory' => 'Twój katalog danych %s musi być poza ścieżką Twojej instalacji dla bezpieczeństwa.',
	'install:error:emailaddress' => '%s nie jest poprawnym adresem e-mail',
	'install:error:createsite' => 'Nie można stworzyć strony.',
	'install:error:savesitesettings' => 'Nie można zapisać ustawień strony.',
	'install:error:loadadmin' => 'Nie można wczytać użytkownika administracyjnego.',
	'install:error:adminaccess' => 'Nie można dać uprawnień nowemu kontu administratora.',
	'install:error:adminlogin' => 'Nie można zalogować automatycznie nowego administratora.',
	'install:error:rewrite:apache' => 'We think your server is running the Apache web server.',
	'install:error:rewrite:nginx' => 'Myślimy, że Twój serwer używa serwera Nginx.',
	'install:error:rewrite:lighttpd' => 'Myślimy, że Twój serwer używa serwera Lighttpd.',
	'install:error:rewrite:iis' => 'Myślimy, że Twój serwer używa serwera IIS.',
	'install:error:rewrite:allowoverride' => "The rewrite test failed and the most likely cause is that AllowOverride is not set to All for Elgg's directory. This prevents Apache from processing the .htaccess file which contains the rewrite rules.
				\n\nA less likely cause is Apache is configured with an alias for your Elgg directory and you need to set the RewriteBase in your .htaccess. There are further instructions in the .htaccess file in your Elgg directory.",
	'install:error:rewrite:htaccess:write_permission' => 'Your web server does not have permission to create the .htaccess file in Elgg\'s directory. You need to manually copy install/config/htaccess.dist to .htaccess or change the permissions on the directory.',
	'install:error:rewrite:htaccess:read_permission' => 'Plik .htaccess jest w katalogu Elgg, ale Twój serwer nie ma uprawnień do jego odczytywania.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Plik .htaccess w katalogu Elgg nie został utworzony przez Elgg. Proszę usuń go.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'There appears to be an old Elgg .htaccess file in Elgg\'s directory. It does not contain the rewrite rule for testing the web server.',
	'install:error:rewrite:htaccess:cannot_copy' => 'A unknown error occurred while creating the .htaccess file. You need to manually copy install/config/htaccess.dist to .htaccess in Elgg\'s directory.',
	'install:error:rewrite:altserver' => 'The rewrite rules test failed. You need to configure your web server with Elgg\'s rewrite rules and try again.',
	'install:error:rewrite:unknown' => 'Oof. We couldn\'t figure out what kind of web server is running on your server and it failed the rewrite rules. We cannot offer any specific advice. Please check the troubleshooting link.',
	'install:warning:rewrite:unknown' => 'Your server does not support automatic testing of the rewrite rules and your browser does not support checking via JavaScript. You can continue the installation, but you may experience problems with your site. You can manually test the rewrite rules by clicking this link: <a href="%s" target="_blank">test</a>. You will see the word success if the rules are working.',
    
	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Wystąpił nieodwracalny błąd i został zalogowany. Jeśli jesteś administratorem, sprawdź swój plik ustawień, w przeciwnym wypadku skontaktuj się z administratorem, podając następującą informację:',
	'DatabaseException:WrongCredentials' => "Elgg nie mógł się połączyć z bazą danych przy użyciu zadanych ustawień. Sprawdź plik ustawień.",
);
