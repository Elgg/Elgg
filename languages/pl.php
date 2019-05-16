<?php

return array(
/**
 * Sites
 */

	'item:site:site' => 'Site',
	'collection:site:site' => 'Sites',
	'index:content' => '<p>Welcome to your Elgg site.</p><p><strong>Tip:</strong> Many sites use the <code>activity</code> plugin to place a site activity stream on this page.</p>',

/**
 * Sessions
 */

	'login' => "Zaloguj",
	'loginok' => "Zostałeś zalogowany.",
	'loginerror' => "Nie można się zalogować. Upewnij się, że wprowadzone dane są prawidłowe i spróbuj ponownie ",
	'login:empty' => "Wymagana jest nazwa użytkownika lub adres e-mail.",
	'login:baduser' => "Nie można było wczytać konta użytkownika.",
	'auth:nopams' => "Błąd wewnętrzny. Brak zainstalowanej metody uwierzytelniania użytkowników.",

	'logout' => "Wyloguj",
	'logoutok' => "Zostałeś wylogowany.",
	'logouterror' => "Nie można się wylogować, spróbuj ponownie.",
	'session_expired' => "Twoja sesja się przedawniła. Odśwież stroną aby zalogować się ponownie.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "Strona dostępna tylko dla zalogowanych.",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "Strona dostępna tylko dla administratorów.",
	'membershiprequired' => "Strona dostępna tylko dla członków grupy.",
	'limited_access' => "Nie masz uprawnień do wyświetlania wskazanej strony.",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "Błąd krytyczny.",
	'exception:contact_admin' => 'Wystąpił nieodwracalny błąd. Skontaktuj się z administratorem, podając następującą informację:',

	'actionundefined' => "Żądana akcja (%s) nie została zdefiniowana w systemie.",
	'actionnotfound' => "Nie odnaleziono pliku akcji %s.",
	'actionloggedout' => "Przepraszamy, nie możesz wykonać tej akcji będąc wylogowany.",
	'actionunauthorized' => 'Nie masz uprawnień do wykonania tej akcji.',

	'ajax:error' => 'Unexpected error while performing an AJAX call. Maybe the connection to the server is lost.',
	'ajax:not_is_xhr' => 'You cannot access AJAX views directly',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) to źle skonfigurowany plugin i został wyłączony. Proszę sprawdzić możliwe przyczyny na wiki (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (guid: %s) nie mógł wystartować i został wyłączony. Przyczyna: %s',
	'PluginException:InvalidID' => "%s jest niepoprawnym identyfikatorem rozszerzenia.",
	'PluginException:InvalidPath' => "%s jest niepoprawną ścieżką rozszerzenia.",
	'PluginException:InvalidManifest' => 'Niepoprawny plik manifestu w rozszerzeniu %s',
	'PluginException:InvalidPlugin' => '%s nie jest poprawnym rozszerzeniem.',
	'PluginException:InvalidPlugin:Details' => '%s nie jest poprawnym rozszerzeniem: %s',
	'PluginException:NullInstantiated' => 'Nie można było utworzyć instancji ElggPlugin. Musisz przekazać GUID, identyfikator rozszerzenia lub pełną ścieżkę.',
	'ElggPlugin:MissingID' => 'Brak identyfikatora rozszerzenia (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Brak ElggPluginPackage dla identyfikatora %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Nie znaleziono wymaganego pliku "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Katalog tego rozszerzenia musi mieć zmienioną nazwę na "%s" aby być zgodny z ID ustawionym w manifeście.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Ten manifest zawiera niepoprawny rodzaj zależności "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Ten manifest zawiera niepoprawny typ zależności "provides" "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Wykryto niepoprawną %s zależność "%s" w rozszerzeniu %s. Rozszerzenie nie może konfliktować ze sobą lub wymagać zależności, którą sam zapewnia.',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Error:ID' => 'Error in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error in plugin path "%s"',
	'ElggPlugin:Error:Unknown' => 'Undefined plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Nie można wczytać %s dla rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Nie można otworzyć katalogu widoków rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:Exception:NoID' => 'Brak identyfikatora rozszerzenia guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Package cannot be loaded',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest is missing or invalid',
	'PluginException:NoPluginName' => "Nie można znaleźć nazwy rozszerzenia.",
	'PluginException:ParserError' => 'Błąd w trakcie parsowania manifestu dla wersji API %s w rozszerzeniu %s.',
	'PluginException:NoAvailableParser' => 'Nie znaleziono parsera dla wersji API manifestu %s w rozszerzeniu %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Brak wymaganego atrybutu '%s' w manifeście rozszerzenia %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s jest niepoprawnym rozszerzeniem i został wyłączony.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => 'Wymaga',
	'ElggPlugin:Dependencies:Suggests' => 'Sugeruje',
	'ElggPlugin:Dependencies:Conflicts' => 'Konfliktuje',
	'ElggPlugin:Dependencies:Conflicted' => 'Skonfliktowany',
	'ElggPlugin:Dependencies:Provides' => 'Zapewnia',
	'ElggPlugin:Dependencies:Priority' => 'Priorytet',

	'ElggPlugin:Dependencies:Elgg' => 'Wersja Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Wersja PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Rozszerzenie PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Ustawienie INI dla PHP: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Rozszerzenie: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Poniżej %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Powyżej %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s jest niezainstalowany',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Brak',

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

	'PageNotFoundException' => 'The page you are trying to view does not exist or you do not have permissions to view it',
	'EntityNotFoundException' => 'The content you were trying to access has been removed or you do not have permissions to access it.',
	'EntityPermissionsException' => 'You do not have sufficient permissions for this action.',
	'GatekeeperException' => 'You do not have permissions to view the page you are trying to access',
	'BadRequestException' => 'Bad request',
	'ValidationException' => 'Submitted data did not meet the requirements, please check your input.',
	'LogicException:InterfaceNotImplemented' => '%s must implement %s',

	'deprecatedfunction' => 'Uwaga: Ten kod używa zdeprecjonowanej funkcji \'%s\' i nie jest kompatybilny z tą wersją Elgg.',

	'pageownerunavailable' => 'Uwaga: Właściciel strony %d jest niedostępny!',
	'viewfailure' => 'Wystąpił wewnętrzny błąd w widoku %s',
	'view:missing_param' => "Nie znaleziono wymaganego parametru '%s' w widoku %s",
	'changebookmark' => 'Proszę zaktualizować swoją zakładkę dla tej strony',
	'noaccess' => 'Treść, którą usiłujesz wyświetlić, została usunięta lub nie masz uprawnień do jej przeglądania.',
	'error:missing_data' => 'Zabrakło pewnych danych w twoim zapytaniu',
	'save:fail' => 'Zapis danych nie powiódł się',
	'save:success' => 'Twoje dane zostały zapisane',

	'forward:error' => 'Sorry. An error occurred while redirecting to you to another site.',

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
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Banned',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Description',
	'table_columns:fromView:link' => 'Name/Title',
	'table_columns:fromView:icon' => 'Icon',
	'table_columns:fromView:item' => 'Item',
	'table_columns:fromView:language' => 'Language',
	'table_columns:fromView:owner' => 'Owner',
	'table_columns:fromView:time_created' => 'Time Created',
	'table_columns:fromView:time_updated' => 'Time Updated',
	'table_columns:fromView:user' => 'User',

	'table_columns:fromProperty:description' => 'Description',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Name',
	'table_columns:fromProperty:type' => 'Type',
	'table_columns:fromProperty:username' => 'Username',

	'table_columns:fromMethod:getSubtype' => 'Subtype',
	'table_columns:fromMethod:getDisplayName' => 'Name/Title',
	'table_columns:fromMethod:getMimeType' => 'MIME Type',
	'table_columns:fromMethod:getSimpleType' => 'Type',

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
	'autogen_password_option' => "Automatically generate a secure password?",

/**
 * Access
 */

	'access:label:private' => "Private",
	'access:label:logged_in' => "Logged in users",
	'access:label:public' => "Public",
	'access:label:logged_out' => "Logged out users",
	'access:label:friends' => "Friends",
	'access' => "Dostęp",
	'access:overridenotice' => "Uwaga: Ze względu na politykę grup, ten element będzie dostępny wyłącznie dla członków grupy.",
	'access:limited:label' => "Ograniczony",
	'access:help' => "Poziom dostępu",
	'access:read' => "Uprawnienia odczytu",
	'access:write' => "Uprawnienia zapisu",
	'access:admin_only' => "Tylko administratorzy",
	'access:missing_name' => "Missing access level name",
	'access:comments:change' => "This discussion is currently visible to a limited audience. Be thoughtful about who you share it with.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Tablica",
	'dashboard:nowidgets' => "Twoja tablica pozwala ci śledzić tą aktywność i treści na stronie, które są istotne dla ciebie.",

	'widgets:add' => 'Dodaj gadżety',
	'widgets:add:description' => "Kliknij na dowolnym przycisku poniżej, aby dodać gadżet do tej strony.",
	'widgets:position:fixed' => '(Stała pozycja na stronie)',
	'widget:unavailable' => 'Już dodałeś ten gadżet.',
	'widget:numbertodisplay' => 'Ilość elementów do wyświetlenia',

	'widget:delete' => 'Usuń %s',
	'widget:edit' => 'Personalizuj ten gadżet',

	'widgets' => "Gadżety",
	'widget' => "Gadżet",
	'item:object:widget' => "Gadżety",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "Gadżet został zapisany pomyślnie.",
	'widgets:save:failure' => "Nie można było zapisać gadżetu.",
	'widgets:add:success' => "Gadżet dodany pomyślnie.",
	'widgets:add:failure' => "Nie mogliśmy dodać tego gadżetu.",
	'widgets:move:failure' => "Nie mogliśmy zapisać nowej pozycji gadżetu.",
	'widgets:remove:failure' => "Nie powiodło się usunięcie gadżetu",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "Grupa",
	'item:group' => "Grupy",
	'collection:group' => 'Groups',
	'item:group:group' => "Group",
	'collection:group:group' => 'Groups',
	'groups:tool_gatekeeper' => "The requested functionality is currently not enabled in this group",

/**
 * Users
 */

	'user' => "Użytkownik",
	'item:user' => "Użytkownicy",
	'collection:user' => 'Users',
	'item:user:user' => 'User',
	'collection:user:user' => 'Users',

	'friends' => "Znajomi",
	'collection:friends' => 'Friends\' %s',

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
	
	'action:user:validate:already' => "%s was already validated",
	'action:user:validate:success' => "%s has been validated",
	'action:user:validate:error' => "An error occurred while validating %s",

/**
 * Feeds
 */
	'feed:rss' => 'Subskrybuj',
	'feed:rss:title' => 'RSS feed for this page',
/**
 * Links
 */
	'link:view' => 'pokaż link',
	'link:view:all' => 'Wyświetl wszystkie',


/**
 * River
 */
	'river' => "Aktywność",
	'river:user:friend' => "%s is now a friend with %s",
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
	'river:delete:lack_permission' => 'You lack permission to delete this activity item',
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => 'Niepoprawny użytkownik',
	'activity:owner' => 'Przeglądaj aktywność',

	

/**
 * Notifications
 */
	'notifications:usersettings' => "Ustawienia powiadomień",
	'notification:method:email' => 'E-mail',

	'notifications:usersettings:save:ok' => "Twoje ustawienia zostały pomyślnie zapisane.",
	'notifications:usersettings:save:fail' => "Wystąpił problem podczas zapisywania twoich ustawień.",

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
	'next' => "Dalej",
	'previous' => "Wstecz",

	'viewtype:change' => "Wybierz typ wyświetlania wyników",
	'viewtype:list' => "Wyświetl listę",
	'viewtype:gallery' => "Galeria",

	'tag:search:startblurb' => "Pozycje pasujące do tagów '%s':",

	'user:search:startblurb' => "Użytkownicy '%s':",
	'user:search:finishblurb' => "Zobacz więcej, kliknij tutaj.",

	'group:search:startblurb' => "Grupy pasujące do '%s':",
	'group:search:finishblurb' => "Zobacz więcej, kliknij tutaj.",
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

	'registration:noname' => 'Display name is required.',
	'registration:notemail' => 'Podany adres e-mail wydaje się nieprawidłowy.',
	'registration:userexists' => 'Ta nazwa użytkownika już istnieje',
	'registration:usernametooshort' => 'Nazwa użytkownika musi mieć co najmniej 4 znaki.',
	'registration:usernametoolong' => 'Nazwa użytkownika jest zbyt długa. Możesz użyć maksymalnie %u znaków.',
	'registration:passwordtooshort' => 'Hasło musi mieć co najmniej 6 znaków.',
	'registration:dupeemail' => 'Ten adres e-mail jest już w naszej bazie.',
	'registration:invalidchars' => 'Twoja nazwa zawiera niedozwolone znaki.',
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
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Could not change username on the system.",

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
	'user:username:help' => 'Please be aware that changing a username will change all dynamic user related links',

	'user:password:lost' => 'Zapomniane hasło',
	'user:password:hash_missing' => 'Regretfully, we must ask you to reset your password. We have improved the security of passwords on the site, but were unable to migrate all accounts in the process.',
	'user:password:changereq:success' => 'Nowe hasło wygenerowano pomyślnie, wysłano e-mail.',
	'user:password:changereq:fail' => 'Nie można wygenerowaćnowego hasła.',

	'user:password:text' => 'Aby wygenerować nowe hasło wpisz poniżej swoją nazwę użytkownika. Otrzymasz e-mail z linkiem weryfikacyjnym, po kliknięciu nowe hasło zostanie wysłane.',

	'user:persistent' => 'Zapamiętaj mnie',

	'walled_garden:home' => 'Home',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administracja',
	'menu:page:header:configure' => 'Konfiguracja',
	'menu:page:header:develop' => 'Develop',
	'menu:page:header:information' => 'Information',
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
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Najnowsze zadania cron',
	'admin:cron:period' => 'Okres cron\'a',
	'admin:cron:friendly' => 'Ostatnio ukończone',
	'admin:cron:date' => 'Data i czas',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
	'admin:cron:complete' => 'Cron jobs for "%s" completed at %s',

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
	'admin:users:unvalidated' => 'Unvalidated',
	'admin:users:unvalidated:no_results' => 'No unvalidated users.',
	'admin:users:unvalidated:registered' => 'Registered: %s',
	
	'admin:configure_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'Aktualizacje',
	'admin:upgrades:finished' => 'Completed',
	'admin:upgrades:db' => 'Database upgrades',
	'admin:upgrades:db:name' => 'Upgrade name',
	'admin:upgrades:db:start_time' => 'Start time',
	'admin:upgrades:db:end_time' => 'End time',
	'admin:upgrades:db:duration' => 'Duration',
	'admin:upgrades:menu:pending' => 'Pending upgrades',
	'admin:upgrades:menu:completed' => 'Completed upgrades',
	'admin:upgrades:menu:db' => 'Database upgrades',
	'admin:upgrades:menu:run_single' => 'Run this upgrade',
	'admin:upgrades:run' => 'Run upgrades now',
	'admin:upgrades:error:invalid_upgrade' => 'Entity %s does not exist or not a valid instance of ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Batch runner for the upgrade %s (%s) could not be instantiated',
	'admin:upgrades:completed' => 'Upgrade "%s" completed at %s',
	'admin:upgrades:completed:errors' => 'Upgrade "%s" completed at %s but encountered %s errors',
	'admin:upgrades:failed' => 'Upgrade "%s" failed',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" was reset',

	'admin:settings' => 'Ustawienia',
	'admin:settings:basic' => 'Ustawienia podstawowe',
	'admin:settings:advanced' => 'Ustawienia zaawansowane',
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
	'admin:statistics:numentities' => 'Content Statistics',
	'admin:statistics:numentities:type' => 'Content type',
	'admin:statistics:numentities:number' => 'Number',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Witaj',
	'admin:widget:admin_welcome:help' => "Krótkie wprowadzenie to panelu administracyjnego Elgg",
	'admin:widget:admin_welcome:intro' =>
'Witaj w Elgg! Właśnie patrzysz na tablicę administracyjną. Jest ona przydatna, gdy chcesz śledzić co się dzieje na stronie.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into
three sections:
	<dl>
		<dt>Administer</dt><dd>Basic tasks like managing users, monitoring reported content and activating plugins.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or configuring settings of a plugin.</dd>
		<dt>Information</dt><dd>Information about your site like statistics.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>
",

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

	'admin:notices:delete_all' => 'Dismiss all %s notices',
	'admin:notices:could_not_delete' => 'Nie można usunąć notatki.',
	'item:object:admin_notice' => 'Notatka administracyjna',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => 'Opcje administracyjne',

	'admin:security' => 'Security',
	'admin:security:settings' => 'Settings',
	'admin:security:settings:description' => 'On this page you can configure some security features. Please read the settings carefully.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:notifications' => 'Notifications',
	'admin:security:settings:label:site_secret' => 'Site secret',
	
	'admin:security:settings:notify_admins' => 'Notify all site administrators when an admin is added or removed',
	'admin:security:settings:notify_admins:help' => 'This will send out a notification to all site administrators that one of the admins added/removed a site administrator.',
	
	'admin:security:settings:notify_user_admin' => 'Notify the user when the admin role is added or removed',
	'admin:security:settings:notify_user_admin:help' => 'This will send a notification to the user that the admin role was added to/removed from their account.',
	
	'admin:security:settings:notify_user_ban' => 'Notify the user when their account gets (un)banned',
	'admin:security:settings:notify_user_ban:help' => 'This will send a notification to the user that their account was (un)banned.',
	
	'admin:security:settings:protect_upgrade' => 'Protect upgrade.php',
	'admin:security:settings:protect_upgrade:help' => 'This will protect upgrade.php so you require a valid token or you\'ll have to be an administrator.',
	'admin:security:settings:protect_upgrade:token' => 'In order to be able to use the upgrade.php when logged out or as a non admin, the following URL needs to be used:',
	
	'admin:security:settings:protect_cron' => 'Protect the /cron URLs',
	'admin:security:settings:protect_cron:help' => 'This will protect the /cron URLs with a token, only if a valid token is provided will the cron execute.',
	'admin:security:settings:protect_cron:token' => 'In order to be able to use the /cron URLs the following tokens needs to be used. Please note that each interval has its own token.',
	'admin:security:settings:protect_cron:toggle' => 'Show/hide cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => 'Disable autocomplete on password fields',
	'admin:security:settings:disable_password_autocomplete:help' => 'Data entered in these fields will be cached by the browser. An attacker who can access the victim\'s browser could steal this information. This is especially important if the application is commonly used in shared computers such as cyber cafes or airport terminals. If you disable this, password management tools can no longer autofill these fields. The support for the autocomplete attribute can be browser specific.',
	
	'admin:security:settings:email_require_password' => 'Require password to change email address',
	'admin:security:settings:email_require_password:help' => 'When the user wishes to change their email address, require that they provide their current password.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:security:settings:site_secret:regenerate' => "Regenerate site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	
	'admin:site:secret:regenerated' => "Your site secret has been regenerated",
	'admin:site:secret:prevented' => "The regeneration of the site secret was prevented",
	
	'admin:notification:make_admin:admin:subject' => 'A new site administrator was added to %s',
	'admin:notification:make_admin:admin:body' => 'Hi %s,

%s made %s a site administrator of %s.

To view the profile of the new administrator, click here:
%s

To go to the site, click here:
%s',
	
	'admin:notification:make_admin:user:subject' => 'You were added as a site administator of %s',
	'admin:notification:make_admin:user:body' => 'Hi %s,

%s made you a site administrator of %s.

To go to the site, click here:
%s',
	'admin:notification:remove_admin:admin:subject' => 'A site administrator was removed from %s',
	'admin:notification:remove_admin:admin:body' => 'Hi %s,

%s removed %s as a site administrator of %s.

To view the profile of the old administrator, click here:
%s

To go to the site, click here:
%s',
	
	'admin:notification:remove_admin:user:subject' => 'You were removed as a site administator from %s',
	'admin:notification:remove_admin:user:body' => 'Hi %s,

%s removed you as site administrator of %s.

To go to the site, click here:
%s',
	'user:notification:ban:subject' => 'Your account on %s was banned',
	'user:notification:ban:body' => 'Hi %s,

Your account on %s was banned.

To go to the site, click here:
%s',
	
	'user:notification:unban:subject' => 'Your account on %s is no longer banned',
	'user:notification:unban:body' => 'Hi %s,

Your account on %s is no longer banned. You can use the site again.

To go to the site, click here:
%s',
	
/**
 * Plugins
 */

	'plugins:disabled' => 'Rozszerzenia nie są wczytywane, ponieważ plik o nazwie "disabled" jest obecny w katalogu mod.',
	'plugins:settings:save:ok' => "Ustawienia dla rozszerzenia %s zostały pomyśłnie zapisane.",
	'plugins:settings:save:fail' => "Wystąpił problem podczas zapisu ustawień dla rozszerzenia %s.",
	'plugins:usersettings:save:ok' => "Ustawienia użytkownika dla rozszerzenia %s zostały pomyślnie zapisane.",
	'plugins:usersettings:save:fail' => "Wystąpił problem podczas zapisywania ustawień użytkownika dla rozszerzenia %s.",
	'item:object:plugin' => 'Rozszerzenie',
	'collection:object:plugin' => 'Plugins',

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
	'admin:plugins:label:author' => "Autor",
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
	'admin:plugins:label:contributors' => 'Współpracownicy',
	'admin:plugins:label:contributors:name' => 'Nazwa',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Strona www',
	'admin:plugins:label:contributors:username' => 'Nazwa użytkownika na forum społeczności Elgg',
	'admin:plugins:label:contributors:description' => 'Opis',
	'admin:plugins:label:dependencies' => 'Zależności',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'To rozszerzenie ma brakujące zależności i nie może być włączone. Sprawdź wymagane zależności w \'więcej informacji\'.',
	'admin:plugins:warning:invalid' => 'To rozszerzenie jest niepoprawne: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Sprawdź <a href="http://docs.elgg.org/Invalid_Plugin">dokumentację Elgg</a> w poszukiwaniu wskazówek.',
	'admin:plugins:cannot_activate' => 'nie można włączyć',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => 'The selected plugin(s) are already active.',
	'admin:plugins:already:inactive' => 'The selected plugin(s) are already inactive.',

	'admin:plugins:set_priority:yes' => "Zmieniono kolejność %s.",
	'admin:plugins:set_priority:no' => "Nie można było zmienić kolejności %s.",
	'admin:plugins:set_priority:no_with_msg' => "Nie można było zmienić kolejności %s. Błąd: %s",
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

	'admin:plugins:dependencies:type' => 'Typ',
	'admin:plugins:dependencies:name' => 'Nazwa',
	'admin:plugins:dependencies:expected_value' => 'Oczekiwana wartość',
	'admin:plugins:dependencies:local_value' => 'Aktualna wartość',
	'admin:plugins:dependencies:comment' => 'Komentarz',

	'admin:statistics:description' => "Jest to przegląd statystyk dotyczących witryny.",
	'admin:statistics:opt:description' => "Zobacz informacje statystyczne na temat użytkowników i obiektów na stronie.",
	'admin:statistics:opt:linktext' => "Zobacz statystyki...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "Przedmioty na stronie",
	'admin:statistics:label:numusers' => "Liczba użytkowników",
	'admin:statistics:label:numonline' => "Liczba użytkowników online",
	'admin:statistics:label:onlineusers' => "Użytkownicy online",
	'admin:statistics:label:admins'=>"Administratorzy",
	'admin:statistics:label:version' => "Wersja Elgg",
	'admin:statistics:label:version:release' => "Wydanie",
	'admin:statistics:label:version:version' => "Wersja",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Show PHPInfo',
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
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure memcache (or redis).
',

	'admin:server:label:redis' => 'Redis',
	'admin:server:redis:inactive' => '
		Redis is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure redis (or memcache).
',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => '
		OPcache is not available on this server or it has not yet been enabled.
		For improved performance, it is recommended that you enable and configure OPcache.
',
	
	'admin:user:label:search' => "Znajdź użytkownika:",
	'admin:user:label:searchbutton' => "Szukaj",

	'admin:user:ban:no' => "Nie można zbanować użytkownika.",
	'admin:user:ban:yes' => "Użytkownik zbanowany.",
	'admin:user:self:ban:no' => "Nie możesz zbanować samego siebie",
	'admin:user:unban:no' => "Użytkownik nie został od banowany.",
	'admin:user:unban:yes' => "Użytkownik został pomyślnie od banowany.",
	'admin:user:delete:no' => "Nie można skasować użytkownika.",
	'admin:user:delete:yes' => "Użytkownik skasowany !",
	'admin:user:self:delete:no' => "Nie możesz skasować samego siebie",

	'admin:user:resetpassword:yes' => "Reset hasła, użytkownik powiadomiony.",
	'admin:user:resetpassword:no' => "Hasło nie zostało zresetowane.",

	'admin:user:makeadmin:yes' => "Użytkownik jest obecnie adminem.",
	'admin:user:makeadmin:no' => "Nie można utworzyć administratorem tego użytkownika.",

	'admin:user:removeadmin:yes' => "Użytkownik już nie jest administratorem",
	'admin:user:removeadmin:no' => "Nie mogliśmy usunąć uprawnień administracyjnych temu użytkownikowi.",
	'admin:user:self:removeadmin:no' => "Nie możesz usunąć uprawnień administracyjnych samemu sobie.",

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Konfiguruj główne elementy menu',
	'admin:menu_items:description' => 'Wybierz, które elementy menu chcesz uwydatnić. Nieużywane elementy będą dodane do sekcji "Więcej", na końcu listy.',
	'admin:menu_items:hide_toolbar_entries' => 'Usunąć linki z menu paska narzędzi?',
	'admin:menu_items:saved' => 'Elementy menu zapisane.',
	'admin:add_menu_item' => 'Dodaj własny element menu',
	'admin:add_menu_item:description' => 'Wypełnij wyświetlaną nazwę oraz URL aby dodać własne elementy to twojego menu nawigacji.',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Nieznany typ gadżetu',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Edytuj poniżej plik robots.txt dla tej strony",
	'admin:robots.txt:plugins' => "Rozszerzenia dodają poniższą treść do pliku robots.txt",
	'admin:robots.txt:subdir' => "Plik robots.txt nie zadziała, ponieważ Elgg jest zainstalowany w podkatalogu",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'Ta strona jest w trakcie prac konserwacyjnych',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
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

	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "Twoje ustawienia",
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
	
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Zapisz",
	'save_go' => "Save, and go to %s",
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
	'validate' => 'Validate',
	'read_more' => 'Read more',

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
	'content:latest:blurb' => 'Alternatywnie, kliknij tutaj aby wyświetlić ostatnią treść z całej witryny.',

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
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'Utworzono konto użytkownika',
	'useradd:body' => '%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

Username: %s
Password: %s

Once you have logged in, we highly recommend that you change your password.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "kliknij aby odrzucić",


/**
 * Messages
 */
	'messages:title:success' => 'Success',
	'messages:title:error' => 'Error',
	'messages:title:warning' => 'Warning',
	'messages:title:help' => 'Help',
	'messages:title:notice' => 'Notice',

/**
 * Import / export
 */

	'importsuccess' => "Dane zostały pomyślnie zaimportowane",
	'importfail' => "OpenDD import danych nieudany.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "przed chwilą",
	'friendlytime:minutes' => "%s minut temu",
	'friendlytime:minutes:singular' => "minutę temu",
	'friendlytime:hours' => "%s godzin temu",
	'friendlytime:hours:singular' => "godzinę temu",
	'friendlytime:days' => "%s dni temu",
	'friendlytime:days:singular' => "wczoraj",
	'friendlytime:date_format' => 'Y-m-d H:i:s',
	'friendlytime:date_format:short' => 'j M Y',

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
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
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
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Default number of items per page",

	'admin:site:access:warning' => "Modyfikacja ustawienia poziomu dostępu, wpływa wyłącznie na treści tworzone w przyszłości.",
	'installation:allow_user_default_access:description' => "Jeśli zaznaczone, indywidualni użytkownicy będą mogli ustawić własny, domyślny poziom dostępu nadpisujący systemowy domyślny poziom dostępu.",
	'installation:allow_user_default_access:label' => "Ustawienie użytkownika domyślnego poziomu dostępu",

	'installation:simplecache:description' => "Prosta pamięć podręczna zwiększa wydajność przez zapamiętywanie treści statycznych, wliczając część plików CSS i JavaScript. Zazwyczaj chcesz włączyć tą opcję.",
	'installation:simplecache:label' => "Użyj prostej pamięci podręcznej (zalecane)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "Prosta pamięć podręczna może również poprawić wydajność przez kompresję kodu JavaScript i CSS. (Wymaga włączonej pamięci podręcznej)",
	'installation:minify_js:label' => "Kompresuj JavaScript (zalecane)",
	'installation:minify_css:label' => "Kompresuj CSS (zalecane)",

	'installation:htaccess:needs_upgrade' => "Musisz zaktualizować swój plik .htaccess aby ścieżka byłą przekazywana jako parametr GET o nazwie __elgg_uri (możesz użyć htaccess_dist jako prykładu).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg nie mógł się połączyć z serwerem w celu przetestowania reguł przepisywania. Sprawdź czy curl działa poprawnie oraz czy nie ma ograniczeń na twój adres IP, które wzbraniały by połączeń do localhost.",

	'installation:systemcache:description' => "Systemowa pamięć podręczna zmniejsza czas ładowania rdzenia Elgg, poprzez zapisywanie danych do plików.",
	'installation:systemcache:label' => "Użyj systemowej pamięci podręcznej (zalecane)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Pamieć podręczna',
	'admin:legend:content_access' => 'Dostęp do treści',
	'admin:legend:site_access' => 'Poziom dostępu na stronie',
	'admin:legend:debug' => 'Odpluskwianie i logowanie',
	
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	
	'upgrading' => 'Aktualizacja',
	'upgrade:core' => 'Twoja instalacja elgg została zaktualizowana',
	'upgrade:unlock' => 'Odblokuj aktualizację',
	'upgrade:unlock:confirm' => "Baza danych jest zablokowana z powodu przebiegającej aktualizacji. Wykonywanie aktualizacji równolegle jest niebezpieczne. Powinieneś kontynuować tylko jeśli jesteś pewien, że nie jest uruchomiona żadna aktualizacja. Odblokować?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Nie można wykonać aktualizacji. Inna aktualizacja wciąż przebiega. Aby wyłączyć blokadę aktualizacji, zobacz sekcję Administracja.",
	'upgrade:unlock:success' => "Blokada aktualizacji zdjęta pomyślnie.",
	'upgrade:unable_to_upgrade' => 'Aktualizacja nie powiodła się.',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (dawniej: OAuth Lib) został wyłączony w trakcie aktualizacji. W razie potrzeby, proszę aktywować ręcznie.',
	'upgrade:site_secret_warning:moderate' => "Zalecamy ponowne utworzenie sekretnego klucza strony dla poprawy bezpieczeństwa strony. Zobacz Konfiguracja &gt; Ustawienia &gt; Zaawansowane",
	'upgrade:site_secret_warning:weak' => "Zdecydowanie zalecamy ponowne utworzenie sekretnego klucza strony dla poprawy bezpieczeństwa strony. Zobacz Konfiguracja &gt; Ustawienia &gt; Zaawansowane",

	'deprecated:function' => '%s() została zdeprecjonowana na rzecz %s()',

	'admin:pending_upgrades' => 'Ta strona ma oczekujące aktualizacje, które wymagają Twojej interwencji.',
	'admin:view_upgrades' => 'Przeglądaj oczekujące aktualizacje.',
	'item:object:elgg_upgrade' => 'Aktualizacje strony',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Twoja instalacja Elgg jest aktualna!',

	'upgrade:item_count' => 'Jest <b>%s</b> elementów, które należy zaktualizować.',
	'upgrade:warning' => '<b>Uwaga:</b> na dużych stronach, aktualizacja może zająć istotnie dużo czasu!',
	'upgrade:success_count' => 'Zaktualizowano:',
	'upgrade:error_count' => 'Błędów:',
	'upgrade:finished' => 'Aktualizacja zakończona',
	'upgrade:finished_with_errors' => '<p>Aktualizacja zakończyła się błędami. Odśwież stronę aby spróbować ponownie.</p></p><br />Jeśli błąd się powtarza, sprawdź możliwe przyczyny w dzienniku błędów. Możesz szukać pomocy w rozwiązaniu problemów na stronie <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupy wsparcia technicznego</a> w społeczności Elgg.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
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
	'email:address:password' => "Password",
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "Nowy adres e-mail zapisano.",
	'email:save:fail' => "Nie zapisano nowego adresu.",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s dodał cię do listy znajomych!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "Hasło zmienione!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Reset hasła!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "Prośba o zmianę hasła.",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

%s",

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
	'collection:object:comment' => 'Comments',

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "Dodaj komentarz",
	'generic_comments:edit' => "Edytuj komentarz",
	'generic_comments:post' => "Dodaj komentarz",
	'generic_comments:text' => "Komentarz",
	'generic_comments:latest' => "Najnowsze komentarze",
	'generic_comment:posted' => "Twój komentarz został dodany.",
	'generic_comment:updated' => "Twój komentarz został pomyślnie zaktualizowany.",
	'entity:delete:object:comment:success' => "The comment was successfully deleted.",
	'generic_comment:blank' => "Przepraszamy: musisz coś wpisać przed zapisaniem.",
	'generic_comment:notfound' => "Przepraszamy: nie można znaleźć określonej pozycji.",
	'generic_comment:notfound_fallback' => "Sorry, we could not find the specified comment, but we've forwarded you to the page where it was left.",
	'generic_comment:failure' => "Wystąpił nieoczekiwany błąd podczas dodawania komentarza.",
	'generic_comment:none' => 'Brak komentarzy',
	'generic_comment:title' => 'Komentarz użytkownika %s',
	'generic_comment:on' => '%s dotyczący %s',
	'generic_comments:latest:posted' => 'napisał',

	'generic_comment:notification:owner:subject' => 'You have a new comment!',
	'generic_comment:notification:owner:summary' => 'You have a new comment!',
	'generic_comment:notification:owner:body' => "You have a new comment on your item \"%s\" from %s. It reads:

%s

To reply or view the original item, click here:
%s

To view %s's profile, click here:
%s",
	
	'generic_comment:notification:user:subject' => 'A new comment on: %s',
	'generic_comment:notification:user:summary' => 'A new comment on: %s',
	'generic_comment:notification:user:body' => "A new comment was made on \"%s\" by %s. It reads:

%s

To reply or view the original item, click here:
%s

To view %s's profile, click here:
%s",

/**
 * Entities
 */

	'byline' => 'Przez %s',
	'byline:ingroup' => 'in the group %s',
	'entity:default:missingsupport:popup' => 'Przedmiot ten nie może zostać wyświetlony poprawnie. Może to być spowodowane brakiem uprzednio zainstalowanego rozszerzenia.',

	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item not found.',
	'entity:delete:permission_denied' => 'You do not have permissions to delete this item.',
	'entity:delete:success' => 'Element %s został skasowany',
	'entity:delete:fail' => 'Element %s nie został skasowany',

	'entity:can_delete:invaliduser' => 'Cannot check canDelete() for user_guid [%s] as the user does not exist.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'W formularzu brakuje pól _token lub _ts',
	'actiongatekeeper:tokeninvalid' => "Formularz wygasł, proszę spróbować ponownie.",
	'actiongatekeeper:timeerror' => 'Formularz wygasł, prosimy odświeżyć i spróbować ponownie.',
	'actiongatekeeper:pluginprevents' => 'Rozszerzenie zablokowało wysłanie formularza.',
	'actiongatekeeper:uploadexceeded' => 'Rozmiar wgranego pliku (lub plików) przekroczył limit ustawiony przez administratora',
	'actiongatekeeper:crosssitelogin' => "Przykro nam, ale logowanie z innej domeny jest niedozwolone. Spróbuj ponownie.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tagi',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Nie można połączyć się z %s. Możesz mieć problem z zapisaniem zmian. Proszę odświeżyć stronę.',
	'js:security:token_refreshed' => 'Połączenie z %s nawiązane na nowo!',
	'js:lightbox:current' => "obraz %s z %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Dostarczone przez Elgg",

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
	"eu_es" => "Basque (Spain)",
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
	"ro_ro" => "Romanian (Romania)",
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
	"sr_latin" => "Serbian (Latin)",
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
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => 'Required',

	"core:upgrade:2017080900:title" => "Alter database encoding for multi-byte support",
	"core:upgrade:2017080900:description" => "Alters database and table encoding to utf8mb4, in order to support multi-byte characters such as emoji",

	"core:upgrade:2017080950:title" => "Update default security parameters",
	"core:upgrade:2017080950:description" => "Installed Elgg version introduces additional security parameters. It is recommended that your run this upgrade to configure the defaults. You can later update these parameters in your site settings.",

	"core:upgrade:2017121200:title" => "Create friends access collections",
	"core:upgrade:2017121200:description" => "Migrates the friends access collection to an actual access collection",

	"core:upgrade:2018041800:title" => "Activate new plugins",
	"core:upgrade:2018041800:description" => "Certain core features have been extracted into plugins. This upgrade activates these plugins to maintain compatibility with third-party plugins that maybe dependant on these features",

	"core:upgrade:2018041801:title" => "Delete old plugin entities",
	"core:upgrade:2018041801:description" => "Deletes entities associated with plugins removed in Elgg 3.0",
	
	"core:upgrade:2018061401:title" => "Migrate cron log entries",
	"core:upgrade:2018061401:description" => "Migrate the cron log entries in the database to the new location.",
);
