<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
/**
 * Sites
 */

	'item:site:site' => 'Strona',
	'collection:site:site' => 'Strony',

/**
 * Sessions
 */

	'login' => "Zaloguj",
	'loginok' => "Zostałeś zalogowany.",
	'login:empty' => "Wymagana jest nazwa użytkownika lub adres e-mail.",
	'login:baduser' => "Nie można było wczytać konta użytkownika.",
	'auth:nopams' => "Błąd wewnętrzny. Brak zainstalowanej metody uwierzytelniania użytkowników.",

	'logout' => "Wyloguj",
	'logoutok' => "Zostałeś wylogowany.",
	'logouterror' => "Nie można się wylogować, spróbuj ponownie.",
	'session_expired' => "Twoja sesja się przedawniła. Odśwież stroną aby zalogować się ponownie.",

	'loggedinrequired' => "Strona dostępna tylko dla zalogowanych.",
	'adminrequired' => "Strona dostępna tylko dla administratorów.",
	'membershiprequired' => "Strona dostępna tylko dla członków grupy.",
	'limited_access' => "Nie masz uprawnień do wyświetlania wskazanej strony.",

/**
 * Errors
 */

	'exception:title' => "Błąd krytyczny.",
	'exception:contact_admin' => 'Wystąpił nieodwracalny błąd. Skontaktuj się z administratorem, podając następującą informację:',

	'actionnotfound' => "Nie odnaleziono pliku akcji %s.",
	'actionunauthorized' => 'Nie masz uprawnień do wykonania tej akcji.',

	'ajax:error' => 'Unexpected error while performing an AJAX call. Maybe the connection to the server is lost.',
	'ajax:not_is_xhr' => 'You cannot access AJAX views directly',

	'PluginException:CannotStart' => '%s (guid: %s) nie mógł wystartować i został wyłączony. Przyczyna: %s',
	'PluginException:InvalidID' => "%s jest niepoprawnym identyfikatorem rozszerzenia.",
	'PluginException:InvalidPath' => "%s jest niepoprawną ścieżką rozszerzenia.",
	'ElggPlugin:MissingID' => 'Brak identyfikatora rozszerzenia (guid %s)',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Nie można wczytać %s dla rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Nie można otworzyć katalogu widoków rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:InvalidAndDeactivated' => '%s jest niepoprawnym rozszerzeniem i został wyłączony.',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Inne rozszerzenia korzystają z %s jako zależności. Aby go wyłączyć, musisz najpierw wyłączyć następujące rozszerzenia: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Menu items found without parents to link them to',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menu item [%s] found with a missing parent[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Duplicate registration found for menu item [%s]',

	'RegistrationException:EmptyPassword' => 'Hasło nie może być puste',
	'RegistrationException:PasswordMismatch' => 'Hasła muszą być jednakowe',
	'LoginException:BannedUser' => 'Zostałeś zbanowany na tej stronie i nie możesz się logować',
	'LoginException:UsernameFailure' => 'Nie można się zalogować. Sprawdź nazwę użytkownika/e-mail oraz hasło.',
	'LoginException:PasswordFailure' => 'Nie można się zalogować. Sprawdź nazwę użytkownika/e-mail oraz hasło.',
	'LoginException:AccountLocked' => 'Twoje konto zostało zablokowane z powodu zbyt wielu nieudanych prób logowania.',
	'LoginException:ChangePasswordFailure' => 'Nie powiodła się weryfikacja obecnego hasła.',
	'LoginException:Unknown' => 'Nie można się zalogować z powodu nieznanego błędu.',

	'UserFetchFailureException' => 'Cannot check permission for user_guid [%s] as the user does not exist.',
	'BadRequestException' => 'Bad request',

	'viewfailure' => 'Wystąpił wewnętrzny błąd w widoku %s',
	'changebookmark' => 'Proszę zaktualizować swoją zakładkę dla tej strony',
	'error:missing_data' => 'Zabrakło pewnych danych w twoim zapytaniu',
	'save:fail' => 'Zapis danych nie powiódł się',
	'save:success' => 'Twoje dane zostały zapisane',

	'error:default:title' => 'Ojoj...',
	'error:default:content' => 'Kurza twarz... coś poszło nie tak',
	'error:400:title' => 'Bad request',
	'error:400:content' => 'Sorry. The request is invalid or incomplete.',
	'error:403:title' => 'Forbidden',
	'error:403:content' => 'Sorry. You are not allowed to access the requested page.',
	'error:404:title' => 'Nie znaleziono strony',
	'error:404:content' => 'Przepraszamy. Nie można znaleźć wskazanej strony.',

	'upload:error:ini_size' => 'Plik, który usiłujesz wgrać, jest za duży.',
	'upload:error:form_size' => 'Plik, który usiłujesz wgrać, jest za duży.',
	'upload:error:partial' => 'Wgrywanie pliku nie zostało ukończone.',
	'upload:error:no_file' => 'Nie wybrano pliku.',
	'upload:error:no_tmp_dir' => 'Nie można było zapisać wgranego pliku.',
	'upload:error:cant_write' => 'Nie można było zapisać wgranego pliku.',
	'upload:error:extension' => 'Nie można było zapisać wgranego pliku.',
	'upload:error:unknown' => 'Wgrywanie pliku nie powiodło się.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Administracja',
	'table_columns:fromView:banned' => 'Zbanowany',
	'table_columns:fromView:excerpt' => 'Opis',
	'table_columns:fromView:language' => 'Twój język',
	'table_columns:fromView:owner' => 'Właściciel',
	'table_columns:fromView:user' => 'Użytkownicy',

	'table_columns:fromProperty:description' => 'Opis',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Nazwa',
	'table_columns:fromProperty:type' => 'Typ',
	'table_columns:fromProperty:username' => 'Użytkownik',
	'table_columns:fromMethod:getSimpleType' => 'Typ',

/**
 * User details
 */

	'name' => "Wyświetlana nazwa",
	'email' => "Adres e-mail",
	'username' => "Użytkownik",
	'loginusername' => "Nazwa użytkownika lub e-mail",
	'password' => "Hasło",
	'passwordagain' => "Hasło (potwierdź)",
	'admin_option' => "Przydzielić temu użytkownikowi funkcje administratora?",

/**
 * Access
 */
	'access:label:friends' => "Znajomi",
	'access' => "Dostęp",
	'access:limited:label' => "Ograniczony",
	'access:help' => "Poziom dostępu",
	'access:read' => "Uprawnienia odczytu",
	'access:write' => "Uprawnienia zapisu",
	'access:admin_only' => "Tylko administratorzy",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Tablica",
	'dashboard:nowidgets' => "Twoja tablica pozwala ci śledzić tą aktywność i treści na stronie, które są istotne dla ciebie.",

	'widgets:add' => 'Dodaj gadżety',
	'widgets:add:description' => "Kliknij na dowolnym przycisku poniżej, aby dodać gadżet do tej strony.",
	'widget:unavailable' => 'Już dodałeś ten gadżet.',
	'widget:numbertodisplay' => 'Ilość elementów do wyświetlenia',

	'widget:delete' => 'Usuń %s',
	'widget:edit' => 'Personalizuj ten gadżet',

	'widgets' => "Gadżety",
	'widget' => "Gadżet",
	'item:object:widget' => "Gadżety",
	'collection:object:widget' => 'Gadżety',
	'widgets:save:success' => "Gadżet został zapisany pomyślnie.",
	'widgets:save:failure' => "Nie można było zapisać gadżetu.",
	'widgets:add:success' => "Gadżet dodany pomyślnie.",
	'widgets:add:failure' => "Nie mogliśmy dodać tego gadżetu.",
	'widgets:move:failure' => "Nie mogliśmy zapisać nowej pozycji gadżetu.",
	'widgets:remove:failure' => "Nie powiodło się usunięcie gadżetu",
	
/**
 * Groups
 */

	'group' => "Grupa",
	'item:group' => "Grupy",
	'collection:group' => 'Grupy',
	'item:group:group' => "Grupy",
	'collection:group:group' => 'Grupy',

/**
 * Users
 */

	'user' => "Użytkownik",
	'item:user' => "Użytkownicy",
	'collection:user' => 'Użytkownicy',
	'item:user:user' => 'Użytkownicy',
	'collection:user:user' => 'Użytkownicy',

	'friends' => "Znajomi",

	'avatar' => 'Awatar',
	'avatar:noaccess' => "Nie masz uprawnień aby edytować awatar tego użytkownika",
	'avatar:create' => 'Utwórz swój awatar',
	'avatar:edit' => 'Edytuj awatar',
	'avatar:upload' => 'Wgraj nowy awatar',
	'avatar:current' => 'Obecny awatar',
	'avatar:remove' => 'Usuń obecny awatar i ustaw domyślną ikonę',
	'avatar:crop:title' => 'Narzędzie do przycinania awatara',
	'avatar:upload:instructions' => "Twój awatar jest wyświetlany wszędzie na stronie. Możesz go zmieniać tak często jak masz na to ochotę. (Akceptowane formaty plików: GIF, JPG lub PNG)",
	'avatar:create:instructions' => 'Kliknij i przeciągnij kwadrat poniżej aby zaznaczyć obszar przycięcia awatara. Podgląd pokaże się po prawej. Gdy będziesz usatysfakcjonowany wyglądem, kliknij \'Utwórz awatar\'. Przycięta wersja będzie używana wszędzie na stronie jako twój awatar.',
	'avatar:upload:success' => 'Pomyślnie wgrano awatar',
	'avatar:upload:fail' => 'Nie powiodło się wgrywanie awatara',
	'avatar:resize:fail' => 'Nie powiodło się przeskalowywanie awatara',
	'avatar:crop:success' => 'Pomyślnie przycięto awatar',
	'avatar:crop:fail' => 'Nie powiodło się przycinanie awatara',
	'avatar:remove:success' => 'Usunięto awatar',
	'avatar:remove:fail' => 'Nie powiodło się usuwanie awatara',

/**
 * Feeds
 */
	'feed:rss' => 'Subskrybuj',
/**
 * Links
 */
	'link:view' => 'pokaż link',
	'link:view:all' => 'Wyświetl wszystkie',


/**
 * River
 */
	'river' => "Aktywność",
	'river:update:user:avatar' => '%s ma nowy awatar',
	'river:noaccess' => 'Nie masz uprawnień do wyświetlania tego elementu.',
	'river:posted:generic' => '%s napisał',
	'riveritem:single:user' => 'jakiś użytkownik',
	'riveritem:plural:user' => 'jacyś użytkownicy',
	'river:ingroup' => 'w grupie %s',
	'river:none' => 'Brak aktywności',
	'river:update' => 'Aktualizuj dla %s',
	'river:delete' => 'Usuń ten wpis aktywności',
	'river:delete:success' => 'Wpis aktywności został skasowany',
	'river:delete:fail' => 'Nie powiodło się usunięcie wpisu aktywności',
	'river:subject:invalid_subject' => 'Niepoprawny użytkownik',
	'activity:owner' => 'Przeglądaj aktywność',

/**
 * Relationships
 */

/**
 * Notifications
 */
	'notification:method:email' => 'E-mail',
	'notification:subject' => 'Powiadomienia o %s',
	'notification:body' => 'Zobacz nowy element tutaj %s',

/**
 * Search
 */

	'search' => "Szukaj",
	'searchtitle' => "Szukaj: %s",
	'users:searchtitle' => "Szukano: %s",
	'groups:searchtitle' => "Wyszukiwanie grup: %s",
	'advancedsearchtitle' => "%s z pasujących wyników %s",
	'notfound' => "Nie znaleziono nic.",

	'viewtype:change' => "Wybierz typ wyświetlania wyników",
	'viewtype:list' => "Wyświetl listę",
	'viewtype:gallery' => "Galeria",
	'search:go' => 'Idź',
	'userpicker:only_friends' => 'Tylko znajomi',

/**
 * Account
 */

	'account' => "Konto",
	'settings' => "Ustawienia",
	'tools' => "Narzędzia",
	'settings:edit' => 'Edytuj ustawienia',

	'register' => "Rejestracja",
	'registerok' => "Rejestracja przebiegła pomyślnie dla %s. Aby aktywować swoje konto, proszę potwierdzić swój adres e-mail poprzez kliknięcie na wysłany link.",
	'registerbad' => "Wystąpił bład w czasie rejestracji. Nazwa użytkownika już istnieje, hasła są za krótkie, lub nazwa użytkownika albo hasło jest za krótki.",
	'registerdisabled' => "Rejestracja została wyłączona przez administratora.",
	'register:fields' => 'Wszystkie pola są wymagane',
	'registration:notemail' => 'Podany adres e-mail wydaje się nieprawidłowy.',
	'registration:userexists' => 'Ta nazwa użytkownika już istnieje',
	'registration:usernametoolong' => 'Nazwa użytkownika jest zbyt długa. Możesz użyć maksymalnie %u znaków.',
	'registration:dupeemail' => 'Ten adres e-mail jest już w naszej bazie.',
	'registration:emailnotvalid' => 'Adres e-mail który podałeś jest niepoprawny dla systemu,',
	'registration:passwordnotvalid' => 'Twoje hasło które podałeś jest niepoprawne dla systemu.',
	'registration:usernamenotvalid' => 'Użytkownik którego nazwę podałeś jest niepoprawny dla systemu.',

	'adduser' => "Dodaj użytkownika",
	'adduser:ok' => "Nowy użytkownik dodany pomyślnie.",
	'adduser:bad' => "Nowy użytkownik nie został utworzony.",

	'user:set:name' => "Ustawienia nazwy konta",
	'user:name:label' => "Twoje imię",
	'user:name:success' => "Twoje imię zmieniono pomyślnie.",
	'user:name:fail' => "Nie można zmienić imienia.",

	'user:set:password' => "Hasło konta",
	'user:current_password:label' => 'Aktualne hasło',
	'user:password:label' => "Twoje nowe hasło",
	'user:password2:label' => "Twoje nowe hasło ponownie",
	'user:password:success' => "Zmiana hasła",
	'user:password:fail' => "Nie można zmienić twojego hasła.",
	'user:password:fail:notsame' => "Hasła nie są takie same!",
	'user:password:fail:tooshort' => "Hasło jest za krótkie!",
	'user:password:fail:incorrect_current_password' => 'Wprowadzono niepoprawne aktualne hasło.',
	'user:changepassword:unknown_user' => 'Niepoprawny użytkownik.',
	'user:changepassword:change_password_confirm' => 'Twoje hasło zostanie zmienione.',

	'user:set:language' => "Ustawienia języka",
	'user:language:label' => "Twój język",
	'user:language:success' => "Ustawienia twojego języka zostały pomyśłnie aktualizowane.",
	'user:language:fail' => "Twój język nie został zapisany.",

	'user:username:notfound' => 'Użytkownik %s nie znaleziony.',

	'user:password:lost' => 'Zapomniane hasło',
	'user:password:changereq:success' => 'Nowe hasło wygenerowano pomyślnie, wysłano e-mail.',
	'user:password:changereq:fail' => 'Nie można wygenerowaćnowego hasła.',

	'user:password:text' => 'Aby wygenerować nowe hasło wpisz poniżej swoją nazwę użytkownika. Otrzymasz e-mail z linkiem weryfikacyjnym, po kliknięciu nowe hasło zostanie wysłane.',

	'user:persistent' => 'Zapamiętaj mnie',

/**
 * Password requirements
 */
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administracja',
	'menu:page:header:configure' => 'Konfiguracja',
	'menu:page:header:develop' => 'Develop',
	'menu:page:header:default' => 'Inne',

	'admin:view_site' => 'Wyświetl stronę',
	'admin:loggedin' => 'Zalogowany jako %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Twoje ustawienia zostały zapisane.",
	'admin:configuration:fail' => "Twoje ustawienie nie zostały zapisane.",
	'admin:configuration:dataroot:relative_path' => 'Nie można ustawić "%s" jako dataroot, ponieważ nie jest to ścieżka bezwzględna.',
	'admin:configuration:default_limit' => 'The number of items per page must be at least 1.',

	'admin:unknown_section' => 'Niepoprawna sekcja panelu administracyjnego.',

	'admin' => "Administracja",
	'admin:description' => "Panel administratora umożliwia kontrolę wszystkich aspektów tego systemu, od zarządzania użytkownikiem, do konfiguracji rozszerzeń. Wybierz opcję poniżej, aby rozpocząć.",
	
	'admin:statistics' => 'Statystyki',
	'admin:server' => 'Serwer',
	'admin:cron:record' => 'Najnowsze zadania cron',
	'admin:cron:period' => 'Okres cron\'a',
	'admin:cron:friendly' => 'Ostatnio ukończone',
	'admin:cron:date' => 'Data i czas',
	'admin:cron:msg' => 'Wiadomość',

	'admin:appearance' => 'Wygląd',
	'admin:administer_utilities' => 'Narzędzia',
	'admin:develop_utilities' => 'Narzędzia',
	'admin:configure_utilities' => 'Narzędzia',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Użytkownicy",
	'admin:users:online' => 'Obecnie on-line',
	'admin:users:newest' => 'Najnowsi',
	'admin:users:admins' => 'Administratorzy',
	'admin:users:add' => 'Dodaj nowego użytkownika',
	'admin:users:description' => "Ten panel umożliwia kontrolę ustawień użytkowników na twojej stronie. Wybierz opcję poniżej, aby rozpocząć.",
	'admin:users:adduser:label' => "Kliknij tutaj aby dodać nowego użytkownika...",
	'admin:users:opt:linktext' => "Konfiguracja użytkowników...",
	'admin:users:opt:description' => "Konfiguracja użytkowników i informacji o koncie.",
	'admin:users:find' => 'Szukaj',
	'admin:users:unvalidated' => 'Niezatwierdzony',
	'admin:users:unvalidated:no_results' => 'Brak niezatwierdzonych użytkowników.',
	
	'admin:configure_utilities:maintenance' => 'Tryb konserwacyjny',
	'admin:upgrades' => 'Aktualizacje',

	'admin:settings' => 'Ustawienia',
	'admin:settings:basic' => 'Ustawienia podstawowe',
	'admin:settings:advanced' => 'Ustawienia zaawansowane',
	'admin:settings:users' => 'Użytkownicy',
	'admin:site:description' => "Ten panel umożliwia globalne ustawienia witryny. Wybierz opcję poniżej, aby rozpocząć.",
	'admin:site:opt:linktext' => "Konfiguracja strony...",
	'admin:settings:in_settings_file' => 'Opcja jest skonfigurowana w pliku settings.php',

	'site_secret:current_strength' => 'Siła klucza',
	'site_secret:strength:weak' => "Słaby",
	'site_secret:strength_msg:weak' => "Zdecydowanie zalecamy ponowne utworzenie sekretnego klucza strony.",
	'site_secret:strength:moderate' => "Umiarkowany",
	'site_secret:strength_msg:moderate' => "Zalecamy ponowne utworzenie sekretnego klucza strony w celu poprawy bezpieczeństwa strony.",
	'site_secret:strength:strong' => "Silny",
	'site_secret:strength_msg:strong' => "Twój sekretny klucz strony jest odpowiednio bezpieczny. Nie ma potrzeby tworzenia nowego.",

	'admin:dashboard' => 'Tablica',
	'admin:widget:online_users' => 'Użytkownicy on-line',
	'admin:widget:online_users:help' => 'Wyświetla listę użytkowników przepywających obecnie na stronie',
	'admin:widget:new_users' => 'Nowi użytkownicy',
	'admin:widget:new_users:help' => 'Wyświetla listę najnowszych użytkowników',
	'admin:widget:banned_users' => 'Zbanowani użytkownicy',
	'admin:widget:banned_users:help' => 'Wyświetla zbanowanych użytkowników',
	'admin:widget:content_stats' => 'Statystyki treści',
	'admin:widget:content_stats:help' => 'Śledź treści tworzone przez twoich użytkowników',
	'admin:widget:cron_status' => 'Cron status',
	'admin:widget:cron_status:help' => 'Shows the status of the last time cron jobs finished',

	'admin:widget:admin_welcome' => 'Witaj',
	'admin:widget:admin_welcome:help' => "Krótkie wprowadzenie to panelu administracyjnego Elgg",
	'admin:widget:admin_welcome:intro' => 'Witaj w Elgg! Właśnie patrzysz na tablicę administracyjną. Jest ona przydatna, gdy chcesz śledzić co się dzieje na stronie.',

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Upewnij się, że sprawdziłeś zasoby dostępne poprzez linki dostępne w stopce. Dziękujemy za wybór Elgg!',

	'admin:widget:control_panel' => 'Panel sterowania',
	'admin:widget:control_panel:help' => "Zapewnia prosty dostęp do często używanych narzędzi",

	'admin:cache:flush' => 'Wyczyść pamięć podręczną',
	'admin:cache:flushed' => "Pamięć podręczna strony została wyczyszczona",

	'admin:footer:faq' => 'Częste pytania administracyjne',
	'admin:footer:manual' => 'Podręcznik administracji',
	'admin:footer:community_forums' => 'Forum społeczności Elgg',
	'admin:footer:blog' => 'Blog Elgg',

	'admin:plugins:category:all' => 'Wszystkie rozszerzenia',
	'admin:plugins:category:active' => 'Aktywne rozszerzenia',
	'admin:plugins:category:inactive' => 'Nieaktywne rozszerzenia',
	'admin:plugins:category:admin' => 'Administracja',
	'admin:plugins:category:bundled' => 'W pakiecie',
	'admin:plugins:category:nonbundled' => 'Spoza pakietu',
	'admin:plugins:category:content' => 'Treść',
	'admin:plugins:category:development' => 'Development',
	'admin:plugins:category:enhancement' => 'Usprawnienia',
	'admin:plugins:category:api' => 'Usługi/API',
	'admin:plugins:category:communication' => 'Komunikacja',
	'admin:plugins:category:security' => 'Bezpieczeństwo i spam',
	'admin:plugins:category:social' => 'Społeczne',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Tematy graficzne',
	'admin:plugins:category:widget' => 'Gadżety',
	'admin:plugins:category:utility' => 'Narzędzia',

	'admin:plugins:markdown:unknown_plugin' => 'Nieznane rozszerzenie',
	'admin:plugins:markdown:unknown_file' => 'Nieznany plik.',
	'admin:notices:could_not_delete' => 'Nie można usunąć notatki.',
	'item:object:admin_notice' => 'Notatka administracyjna',

	'admin:options' => 'Opcje administracyjne',
	
	'admin:security:settings' => 'Ustawienia',
	'admin:security:settings:label:account' => 'Konto',
	'admin:security:settings:label:notifications' => 'Powiadomienia',

/**
 * Plugins
 */

	'plugins:disabled' => 'Rozszerzenia nie są wczytywane, ponieważ plik o nazwie "disabled" jest obecny w katalogu mod.',
	'plugins:settings:save:ok' => "Ustawienia dla rozszerzenia %s zostały pomyśłnie zapisane.",
	'plugins:settings:save:fail' => "Wystąpił problem podczas zapisu ustawień dla rozszerzenia %s.",
	'plugins:usersettings:save:ok' => "Ustawienia użytkownika dla rozszerzenia %s zostały pomyślnie zapisane.",
	'plugins:usersettings:save:fail' => "Wystąpił problem podczas zapisywania ustawień użytkownika dla rozszerzenia %s.",
	
	'item:object:plugin' => 'Rozszerzenie',
	'collection:object:plugin' => 'Rozszerzenia',

	'admin:plugins' => "Rozszerzenia",
	'admin:plugins:activate_all' => 'Aktywuj wszystkie',
	'admin:plugins:deactivate_all' => 'Dezaktywuj wszystkie',
	'admin:plugins:activate' => 'Aktywuj',
	'admin:plugins:deactivate' => 'Dezaktywuj',
	'admin:plugins:description' => "Ten panel pozwala kontrolować i konfigurować narzędzia zainstalowane w twoim serwisie.",
	'admin:plugins:opt:linktext' => "Konfiguracja narzędzi...",
	'admin:plugins:opt:description' => "Konfigurowanie narzędzi zainstalowanych w serwisie. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Name",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorie',
	'admin:plugins:label:licence' => "Licencja",
	'admin:plugins:label:website' => "Adres",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Files",
	'admin:plugins:label:resources' => "Resources",
	'admin:plugins:label:screenshots' => "Screenshots",
	'admin:plugins:label:repository' => "Kod",
	'admin:plugins:label:bugtracker' => "Zgłoś problem",
	'admin:plugins:label:donate' => "Wesprzyj",
	'admin:plugins:label:moreinfo' => 'więcej informacji',
	'admin:plugins:label:version' => 'Wersja',
	'admin:plugins:label:location' => 'Położenie',
	'admin:plugins:label:priority' => 'Priorytet',
	'admin:plugins:label:dependencies' => 'Zależności',

	'admin:plugins:warning:unmet_dependencies' => 'To rozszerzenie ma brakujące zależności i nie może być włączone. Sprawdź wymagane zależności w \'więcej informacji\'.',
	'admin:plugins:warning:invalid' => 'To rozszerzenie jest niepoprawne: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Sprawdź <a href="http://docs.elgg.org/Invalid_Plugin">dokumentację Elgg</a> w poszukiwaniu wskazówek.',
	'admin:plugins:cannot_activate' => 'nie można włączyć',

	'admin:plugins:set_priority:yes' => "Zmieniono kolejność %s.",
	'admin:plugins:set_priority:no' => "Nie można było zmienić kolejności %s.",
	'admin:plugins:deactivate:yes' => "Dezaktywowano %s.",
	'admin:plugins:deactivate:no' => "Nie można było dezaktywować %s.",
	'admin:plugins:deactivate:no_with_msg' => "Nie można było dezaktywować %s. Błąd: %s",
	'admin:plugins:activate:yes' => "Aktywowano %s.",
	'admin:plugins:activate:no' => "Nie można było aktywować %s.",
	'admin:plugins:activate:no_with_msg' => "Nie można było aktywować %s. Błąd: %s",
	'admin:plugins:categories:all' => 'Wszystkie kategorie',
	'admin:plugins:plugin_website' => 'Strona www rozszerzenia',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Wersja %s',
	'admin:plugin_settings' => 'Ustawienia rozszerzenia',
	'admin:plugins:warning:unmet_dependencies_active' => 'To rozszerzenie jest aktywne, ale ma niespełnione zależności. Możesz napotkać problemy. Zobacz \'więcej informacji\' w celu uzuskania szczegółów.',

	'admin:statistics:description' => "Jest to przegląd statystyk dotyczących witryny.",
	'admin:statistics:opt:description' => "Zobacz informacje statystyczne na temat użytkowników i obiektów na stronie.",
	'admin:statistics:opt:linktext' => "Zobacz statystyki...",
	'admin:statistics:label:numentities' => "Przedmioty na stronie",
	'admin:statistics:label:numusers' => "Liczba użytkowników",
	'admin:statistics:label:numonline' => "Liczba użytkowników online",
	'admin:statistics:label:onlineusers' => "Użytkownicy online",
	'admin:statistics:label:admins'=>"Administratorzy",
	'admin:statistics:label:version' => "Wersja Elgg",
	'admin:statistics:label:version:release' => "Wydanie",
	'admin:statistics:label:version:version' => "Wersja",
	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Serwer www',
	'admin:server:label:server' => 'Serwer',
	'admin:server:label:log_location' => 'Lokalizacja logów',
	'admin:server:label:php_version' => 'Wersja PHP',
	'admin:server:label:php_ini' => 'Lokalizacja pliku php.ini',
	'admin:server:label:php_log' => 'Log PHP',
	'admin:server:label:mem_avail' => 'Dostępna pamięć',
	'admin:server:label:mem_used' => 'Użyta pamięć',
	'admin:server:error_log' => "Log błędów serwera www",
	'admin:server:label:post_max_size' => 'Maksymalny rozmiar danych POST',
	'admin:server:label:upload_max_filesize' => 'Maksymalny rozmiar wgrywanych plików',
	'admin:server:warning:post_max_too_small' => '(Uwaga: post_max_size musi być większy niż ta wartość aby móc wgrywać pliki tego rozmiaru)',
	
	'admin:server:requirements:php_extension' => "Rozszerzenie PHP: %s",
	
	'admin:user:label:search' => "Znajdź użytkownika:",
	'admin:user:label:searchbutton' => "Szukaj",

	'admin:user:ban:no' => "Nie można zbanować użytkownika.",
	'admin:user:ban:yes' => "Użytkownik zbanowany.",
	'admin:user:self:ban:no' => "Nie możesz zbanować samego siebie",
	'admin:user:unban:no' => "Użytkownik nie został od banowany.",
	'admin:user:unban:yes' => "Użytkownik został pomyślnie od banowany.",
	'admin:user:delete:no' => "Nie można skasować użytkownika.",
	'admin:user:self:delete:no' => "Nie możesz skasować samego siebie",

	'admin:user:resetpassword:yes' => "Reset hasła, użytkownik powiadomiony.",
	'admin:user:resetpassword:no' => "Hasło nie zostało zresetowane.",

	'admin:user:makeadmin:yes' => "Użytkownik jest obecnie adminem.",
	'admin:user:makeadmin:no' => "Nie można utworzyć administratorem tego użytkownika.",

	'admin:user:removeadmin:yes' => "Użytkownik już nie jest administratorem",
	'admin:user:removeadmin:no' => "Nie mogliśmy usunąć uprawnień administracyjnych temu użytkownikowi.",
	'admin:user:self:removeadmin:no' => "Nie możesz usunąć uprawnień administracyjnych samemu sobie.",
	'admin:menu_items:configure' => 'Konfiguruj główne elementy menu',
	'admin:menu_items:hide_toolbar_entries' => 'Usunąć linki z menu paska narzędzi?',
	'admin:menu_items:saved' => 'Elementy menu zapisane.',
	'admin:add_menu_item' => 'Dodaj własny element menu',
	'admin:add_menu_item:description' => 'Wypełnij wyświetlaną nazwę oraz URL aby dodać własne elementy to twojego menu nawigacji.',
	'admin:default_widgets:unknown_type' => 'Nieznany typ gadżetu',

	'admin:robots.txt:instructions' => "Edytuj poniżej plik robots.txt dla tej strony",
	'admin:robots.txt:plugins' => "Rozszerzenia dodają poniższą treść do pliku robots.txt",
	'admin:robots.txt:subdir' => "Plik robots.txt nie zadziała, ponieważ Elgg jest zainstalowany w podkatalogu",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'Ta strona jest w trakcie prac konserwacyjnych',
	'admin:maintenance_mode:mode_label' => 'Tryb konserwacyjny',
	'admin:maintenance_mode:message_label' => 'Komunikat wyświetlany w trybie konserwacyjnym',
	'admin:maintenance_mode:saved' => 'Ustawienia tryby konserwacyjnego zostały zapisane.',
	'admin:maintenance_mode:indicator_menu_item' => 'Ta strona jest w trybie konserwacyjnym.',
	'admin:login' => 'Logowanie administracyjne',

/**
 * User settings
 */

	'usersettings:description' => "Panel ustawień użytkownika pozwala na kontrolę wszystkich ustawień osobistych oraz zachowania rozszerzeń. Wybierz opcję poniżej, aby rozpocząć.",

	'usersettings:statistics' => "Twoje statystyki",
	'usersettings:statistics:opt:description' => "Zobacz informacje statystyczne na temat użytkowników i obiektów na stronie.",
	'usersettings:statistics:opt:linktext' => "Ustawienia konta",
	'usersettings:user:opt:description' => "To pozwala na kontrolę ustawień użytkownika.",
	'usersettings:user:opt:linktext' => "Zmień swoje ustawienia",

	'usersettings:plugins' => "Narzędzia",
	'usersettings:plugins:opt:description' => "Konfiguracja ustawień dla twoich aktywnych narzędzi.",
	'usersettings:plugins:opt:linktext' => "Konfiguruj swoje narzędzia.",

	'usersettings:plugins:description' => "Panel umożliwia na kontrolę i konfigurację ustawień osobistych, narzędzi zainstalowanych przez administratora systemu.",
	'usersettings:statistics:label:numentities' => "Twoje jednostki",

	'usersettings:statistics:yourdetails' => "Twoje szczegóły",
	'usersettings:statistics:label:name' => "Pełne imię",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Członek od",
	'usersettings:statistics:label:lastlogin' => "Ostatnie logowanie",

/**
 * Activity river
 */

	'river:all' => 'Cała aktywność',
	'river:mine' => 'Moja aktywność',
	'river:owner' => 'Aktywność użytkownika %s',
	'river:friends' => 'Aktywność znajomych',
	'river:select' => 'Pokaż %s',
	'river:comments:more' => '+%u więcej',
	'river:comments:all' => 'View all %u comments',
	'river:generic_comment' => 'skomentował %s %s',

/**
 * Icons
 */

	'icon:size' => "Rozmiar ikony",
	'icon:size:topbar' => "Górna belka",
	'icon:size:tiny' => "Bardzo mały",
	'icon:size:small' => "Mały",
	'icon:size:medium' => "Średni",
	'icon:size:large' => "Duży",
	'icon:size:master' => "Bardzo duży",

/**
 * Generic action words
 */

	'save' => "Zapisz",
	'reset' => 'Zresetuj',
	'publish' => "Opublikuj",
	'cancel' => "Anuluj",
	'saving' => "Zapisywanie ...",
	'update' => "Aktualizacja",
	'preview' => "Podgląd",
	'edit' => "Edycja",
	'delete' => "Usuń",
	'accept' => "Zaakceptuj",
	'reject' => "Odrzuć",
	'decline' => "Odrzuć",
	'approve' => "Zatwierdź",
	'activate' => "Aktywuj",
	'deactivate' => "Dezaktywuj",
	'disapprove' => "Dezaprobuj",
	'revoke' => "Odwołaj",
	'load' => "Załaduj",
	'upload' => "Dodaj",
	'download' => "Pobierz",
	'ban' => "Ban",
	'unban' => "Usuń ban",
	'banned' => "Zbanowany",
	'enable' => "Włącz",
	'disable' => "Wyłącz",
	'request' => "Żądanie",
	'complete' => "Kompletne",
	'open' => 'Otwórz',
	'close' => 'Zamknij',
	'hide' => 'Ukryj',
	'show' => 'Pokaż',
	'reply' => "Odpowiedz",
	'more' => 'Więcej',
	'more_info' => 'Więcej informacji',
	'comments' => 'Komentarze',
	'import' => 'Import',
	'export' => 'Eksport',
	'untitled' => 'Bez tytułu',
	'help' => 'Pomoc',
	'send' => 'Wyślij',
	'post' => 'Wyślij',
	'submit' => 'Wyślij',
	'comment' => 'Skomentuj',
	'upgrade' => 'Aktualizuj',
	'sort' => 'Sortuj',
	'filter' => 'Filtruj',
	'new' => 'Nowy',
	'add' => 'Dodaj',
	'create' => 'Utwórz',
	'remove' => 'Usuń',
	'revert' => 'Przywróć',
	'validate' => 'Zatwierdź',
	'next' => 'Dalej',
	'previous' => 'Wstecz',
	
	'site' => 'Strona',
	'activity' => 'Aktywność',
	'members' => 'Członkowie',
	'menu' => 'Menu',

	'up' => 'Do góry',
	'down' => 'Do dołu',
	'top' => 'Góra',
	'bottom' => 'Dół',
	'right' => 'Prawy',
	'left' => 'Lewy',
	'back' => 'Tył',

	'invite' => "Zaproś",

	'resetpassword' => "Resetuj hasło",
	'changepassword' => "Zmień hasło",
	'makeadmin' => "Zrób adminem",
	'removeadmin' => "Usuń uprawnienia administracyjne",

	'option:yes' => "Tak",
	'option:no' => "Nie",

	'unknown' => 'Nieznany',
	'never' => 'Nigdy',

	'active' => 'Aktywny',
	'total' => 'Razem',
	'unvalidated' => 'Niezatwierdzony',
	
	'ok' => 'OK',
	'any' => 'Dowolny',
	'error' => 'Błąd',

	'other' => 'Inne',
	'options' => 'Opcje',
	'advanced' => 'Zaawansowane',

	'learnmore' => "Kliknij tutaj, aby dowiedzieć się więcej.",
	'unknown_error' => 'Nieznany błąd',

	'content' => "treść",
	'content:latest' => 'Ostatnia aktywność',

	'link:text' => 'pokaż link',

/**
 * Generic questions
 */

	'question:areyousure' => 'Czy jesteś pewien?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Niezapisany szkic',
	'status:draft' => 'Szkic',
	'status:unpublished' => 'Nieopublikowane',
	'status:published' => 'Opublikowane',
	'status:featured' => 'Promowane',
	'status:open' => 'Otwarty',
	'status:closed' => 'Zamknięty',
	'status:active' => 'Aktywny',

/**
 * Generic sorts
 */

	'sort:newest' => 'Najnowsi',
	'sort:popular' => 'Najpopularniejsze',
	'sort:alpha' => 'Alfabetycznie',
	'sort:priority' => 'Priorytet',

/**
 * Generic data words
 */

	'title' => "Tytuł",
	'description' => "Opis",
	'tags' => "Tagi",
	'all' => "Wszyscy",
	'mine' => "Moje",

	'by' => 'przez',
	'none' => 'żadne',

	'annotations' => "Adnotacje",
	'relationships' => "Relacje",
	'metadata' => "Metadane",
	'tagcloud' => "Chmura tagów",

	'on' => 'Włączony',
	'off' => 'Wyłączony',

/**
 * Entity actions
 */

	'edit:this' => 'Edytuj to',
	'delete:this' => 'Usuń to',
	'comment:this' => 'Skomentuj to',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Czy na pewno chcesz usunąć ten element?",
	'deleteconfirm:plural' => "Czy na pewno chcesz usunąć te elementy?",

/**
 * User add
 */

	'useradd:subject' => 'Utworzono konto użytkownika',

/**
 * Messages
 */
	'messages:title:error' => 'Błąd',
	'messages:title:warning' => 'Ostrzeżenie',
	'messages:title:help' => 'Pomoc',
	'messages:title:notice' => 'Uwaga',
	'messages:title:info' => 'Informacje',

/**
 * Time
 */
	'input:date_format:datepicker' => '', // jQuery UI datepicker format

	'friendlytime:justnow' => "przed chwilą",
	'friendlytime:minutes' => "%s minut temu",
	'friendlytime:minutes:singular' => "minutę temu",
	'friendlytime:hours' => "%s godzin temu",
	'friendlytime:hours:singular' => "godzinę temu",
	'friendlytime:days' => "%s dni temu",
	'friendlytime:days:singular' => "wczoraj",
	'friendlytime:date_format' => 'Y-m-d H:i:s',

	'friendlytime:future:minutes' => "za %s minut",
	'friendlytime:future:minutes:singular' => "za minutę",
	'friendlytime:future:hours' => "za %s godzin",
	'friendlytime:future:hours:singular' => "za godzinę",
	'friendlytime:future:days' => "za %s dni",
	'friendlytime:future:days:singular' => "jutro",

	'date:month:01' => 'Styczeń %s',
	'date:month:02' => 'Luty %s',
	'date:month:03' => 'Marzec %s',
	'date:month:04' => 'Kwiecień %s',
	'date:month:05' => 'Maj %s',
	'date:month:06' => 'Czerwiec %s',
	'date:month:07' => 'Lipiec %s',
	'date:month:08' => 'Sierpień %s',
	'date:month:09' => 'Wrzesień %s',
	'date:month:10' => 'Październik %s',
	'date:month:11' => 'Listopad %s',
	'date:month:12' => 'Grudzień %s',

	'date:month:short:01' => 'Jan %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'May %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Aug %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dec %s',

	'date:weekday:0' => 'Niedziela',
	'date:weekday:1' => 'Poniedziałek',
	'date:weekday:2' => 'Wtorek',
	'date:weekday:3' => 'Środa',
	'date:weekday:4' => 'Czwartek',
	'date:weekday:5' => 'Piątek',
	'date:weekday:6' => 'Sobota',

	'date:weekday:short:0' => 'Sun',
	'date:weekday:short:1' => 'Mon',
	'date:weekday:short:2' => 'Tue',
	'date:weekday:short:3' => 'Wed',
	'date:weekday:short:4' => 'Thu',
	'date:weekday:short:5' => 'Fri',
	'date:weekday:short:6' => 'Sat',

	'interval:minute' => 'Co minutę',
	'interval:fiveminute' => 'Co pięć minut',
	'interval:fifteenmin' => 'Co kwadrans',
	'interval:halfhour' => 'Co pół godziny',
	'interval:hourly' => 'Co godzinę',
	'interval:daily' => 'Codziennie',
	'interval:weekly' => 'Co tydzień',
	'interval:monthly' => 'Co miesiąc',
	'interval:yearly' => 'Co rok',

/**
 * System settings
 */

	'installation:sitename' => "Nazwa strony (np. \"Moja strona\")",
	'installation:sitedescription' => "Krótki opis strony (opcjonalny)",
	'installation:wwwroot' => "Pełny adres URL strony:",
	'installation:path' => "Pełna ścieżka do katalogu głównego Elgg.",
	'installation:dataroot' => "Pełna ścieżka do katalogu gdzie zapisywane będą pliki.",
	'installation:dataroot:warning' => "Musisz utworzyć katalog ręcznie. Musi on znajdować się poza katalogiem Elgg.",
	'installation:sitepermissions' => "DOmyślne uprawnienia dostępu:",
	'installation:language' => "Domyślny język dla twojej strony:",
	'installation:debug' => "Tryb debuggera dostarcza dodatkowe informacje użyteczne przy diagnozowaniu błędów, spowalnia jednak to znacznie system, używaj tylko wtedy gdy masz problem.",
	'installation:debug:label' => "Poziom logowania:",
	'installation:debug:none' => 'Wyłącz tryb odpluskwiania (zalecane)',
	'installation:debug:error' => 'Wyświetlaj tylko błędy krytyczne',
	'installation:debug:warning' => 'Wyświetlaj błędy i ostrzeżenia',
	'installation:debug:notice' => 'Loguj wszystkie błędy, ostrzeżenia i adnotacje',
	'installation:debug:info' => 'Loguj wszystko',

	// Walled Garden support
	'installation:registration:description' => 'Rejestracja nowych użytkowników jest domyślnie włączona. Wyłącz tą opcję, jeśli chcesz uniemożłiwić nowym użytkownikom samodzielną rejestrację.',
	'installation:registration:label' => 'Pozwól na rejestrację nowych użytkowników',
	'installation:walled_garden:description' => 'Włącz tryb prywatnej sieci dla swojej strony. To uniemożliwi niezalogowanym użytkownikom, na wyświetlanie jakichkolwiek stron, poza wyraźnie wskazanymi jako publiczne.',
	'installation:walled_garden:label' => 'Pozwól na przeglądanie stron tylko zalogowanym użytkownikom',

	'installation:view' => "Wprowadź widok, który będzie domyślnie używany przez twoją stronę lub pozostaw wartość domyślną (w razie wątpliwości, pozostaw wartość domyślną):",

	'installation:siteemail' => "Adres e-mail strony (używany do wysyłania systemowych e-mail)",
	'installation:default_limit' => "Default number of items per page",

	'admin:site:access:warning' => "Modyfikacja ustawienia poziomu dostępu, wpływa wyłącznie na treści tworzone w przyszłości.",
	'installation:allow_user_default_access:description' => "Jeśli zaznaczone, indywidualni użytkownicy będą mogli ustawić własny, domyślny poziom dostępu nadpisujący systemowy domyślny poziom dostępu.",
	'installation:allow_user_default_access:label' => "Ustawienie użytkownika domyślnego poziomu dostępu",

	'installation:simplecache:description' => "Prosta pamięć podręczna zwiększa wydajność przez zapamiętywanie treści statycznych, wliczając część plików CSS i JavaScript. Zazwyczaj chcesz włączyć tą opcję.",
	'installation:simplecache:label' => "Użyj prostej pamięci podręcznej (zalecane)",

	'installation:minify:description' => "Prosta pamięć podręczna może również poprawić wydajność przez kompresję kodu JavaScript i CSS. (Wymaga włączonej pamięci podręcznej)",
	'installation:minify_js:label' => "Kompresuj JavaScript (zalecane)",
	'installation:minify_css:label' => "Kompresuj CSS (zalecane)",

	'installation:htaccess:needs_upgrade' => "Musisz zaktualizować swój plik .htaccess aby ścieżka byłą przekazywana jako parametr GET o nazwie __elgg_uri (możesz użyć htaccess_dist jako prykładu).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg nie mógł się połączyć z serwerem w celu przetestowania reguł przepisywania. Sprawdź czy curl działa poprawnie oraz czy nie ma ograniczeń na twój adres IP, które wzbraniały by połączeń do localhost.",

	'installation:systemcache:description' => "Systemowa pamięć podręczna zmniejsza czas ładowania rdzenia Elgg, poprzez zapisywanie danych do plików.",
	'installation:systemcache:label' => "Użyj systemowej pamięci podręcznej (zalecane)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Pamieć podręczna',
	'admin:legend:content' => 'Treść',
	'admin:legend:content_access' => 'Dostęp do treści',
	'admin:legend:site_access' => 'Poziom dostępu na stronie',
	'admin:legend:debug' => 'Odpluskwianie i logowanie',

	'upgrading' => 'Aktualizacja',
	'upgrade:core' => 'Twoja instalacja elgg została zaktualizowana',
	'upgrade:unlock' => 'Odblokuj aktualizację',
	'upgrade:unlock:confirm' => "Baza danych jest zablokowana z powodu przebiegającej aktualizacji. Wykonywanie aktualizacji równolegle jest niebezpieczne. Powinieneś kontynuować tylko jeśli jesteś pewien, że nie jest uruchomiona żadna aktualizacja. Odblokować?",
	'upgrade:locked' => "Nie można wykonać aktualizacji. Inna aktualizacja wciąż przebiega. Aby wyłączyć blokadę aktualizacji, zobacz sekcję Administracja.",
	'upgrade:unlock:success' => "Blokada aktualizacji zdjęta pomyślnie.",

	'admin:pending_upgrades' => 'Ta strona ma oczekujące aktualizacje, które wymagają Twojej interwencji.',
	'admin:view_upgrades' => 'Przeglądaj oczekujące aktualizacje.',
	'item:object:elgg_upgrade' => 'Aktualizacje strony',
	'admin:upgrades:none' => 'Twoja instalacja Elgg jest aktualna!',

	'upgrade:success_count' => 'Zaktualizowano:',
	'upgrade:finished' => 'Aktualizacja zakończona',
	'upgrade:finished_with_errors' => '<p>Aktualizacja zakończyła się błędami. Odśwież stronę aby spróbować ponownie.</p></p><br />Jeśli błąd się powtarza, sprawdź możliwe przyczyny w dzienniku błędów. Możesz szukać pomocy w rozwiązaniu problemów na stronie <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupy wsparcia technicznego</a> w społeczności Elgg.</p>',
	
	// Strings specific for the database guid columns reply upgrade
	
/**
 * Welcome
 */

	'welcome' => "Witaj",
	'welcome:user' => 'Witaj %s',

/**
 * Emails
 */

	'email:from' => 'Nadawca',
	'email:to' => 'Adresat',
	'email:subject' => 'Tytuł',
	'email:body' => 'Treść',

	'email:settings' => "Ustawienia e-mail",
	'email:address:label' => "Twój adres e-mail",
	'email:address:password' => "Hasło",

	'email:save:success' => "Nowy adres e-mail zapisano.",
	'email:save:fail' => "Nie zapisano nowego adresu.",

	'friend:newfriend:subject' => "%s dodał cię do listy znajomych!",

	'email:changepassword:subject' => "Hasło zmienione!",

	'email:resetpassword:subject' => "Reset hasła!",

	'email:changereq:subject' => "Prośba o zmianę hasła.",

/**
 * user default access
 */

	'default_access:settings' => "Twój domyślny poziom dostępu",
	'default_access:label' => "Domyślny poziom dostępu",
	'user:default_access:success' => "Twój domyślny poziom dostępu został zapisany.",
	'user:default_access:failure' => "Zapis domyślnego poziomu dostępu nie powiódł się.",

/**
 * Comments
 */

	'comments:count' => "%s komentarzy",
	'item:object:comment' => 'Komentarze',
	'collection:object:comment' => 'Komentarze',

	'generic_comments:add' => "Dodaj komentarz",
	'generic_comments:edit' => "Edytuj komentarz",
	'generic_comments:post' => "Dodaj komentarz",
	'generic_comments:text' => "Komentarz",
	'generic_comments:latest' => "Najnowsze komentarze",
	'generic_comment:posted' => "Twój komentarz został dodany.",
	'generic_comment:updated' => "Twój komentarz został pomyślnie zaktualizowany.",
	'entity:delete:object:comment:success' => "Twój komentarz został skasowany.",
	'generic_comment:blank' => "Przepraszamy: musisz coś wpisać przed zapisaniem.",
	'generic_comment:notfound' => "Przepraszamy: nie można znaleźć określonej pozycji.",
	'generic_comment:failure' => "Wystąpił nieoczekiwany błąd podczas dodawania komentarza.",
	'generic_comment:none' => 'Brak komentarzy',
	'generic_comment:title' => 'Komentarz użytkownika %s',
	'generic_comment:on' => '%s dotyczący %s',
	'generic_comments:latest:posted' => 'napisał',

/**
 * Entities
 */

	'byline' => 'Przez %s',
	'byline:ingroup' => 'in the group %s',
	'entity:delete:success' => 'Element %s został skasowany',
	'entity:delete:fail' => 'Element %s nie został skasowany',

/**
 * Annotations
 */
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'W formularzu brakuje pól _token lub _ts',
	'actiongatekeeper:tokeninvalid' => "Formularz wygasł, proszę spróbować ponownie.",
	'actiongatekeeper:timeerror' => 'Formularz wygasł, prosimy odświeżyć i spróbować ponownie.',
	'actiongatekeeper:pluginprevents' => 'Rozszerzenie zablokowało wysłanie formularza.',
	'actiongatekeeper:uploadexceeded' => 'Rozmiar wgranego pliku (lub plików) przekroczył limit ustawiony przez administratora',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Nie można połączyć się z %s. Możesz mieć problem z zapisaniem zmian. Proszę odświeżyć stronę.',
	'js:lightbox:current' => "obraz %s z %s",

/**
 * Diagnostics
 */
	
/**
 * Miscellaneous
 */
	'elgg:powered' => "Dostarczone przez Elgg",
	
/**
 * Cli commands
 */
	
/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabski",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"cmn" => "Mandarin Chinese", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "Niemiecki",
	"dz" => "Bhutani",
	"el" => "Grecki",
	"en" => "Angielski",
	"eo" => "Esperanto",
	"es" => "Hiszpański",
	"et" => "Estoński",
	"eu" => "Basque",
	"fa" => "Perski",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "Francuski",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armeński",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonezyjski",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kanadyjski",
	"ko" => "Koreański",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polski",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Brazilian Portuguese",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ru" => "Rosyjski",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Jidysz",
	"yi" => "Jidysz",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zu" => "Zulu",

	"field:required" => 'Required',
);
