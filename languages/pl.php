<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Strony',

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

	'loggedinrequired' => "Strona dostępna tylko dla zalogowanych.",
	'adminrequired' => "Strona dostępna tylko dla administratorów.",
	'membershiprequired' => "Strona dostępna tylko dla członków grupy.",
	'limited_access' => "Nie masz uprawnień do wyświetlania wskazanej strony.",


/**
 * Errors
 */

	'exception:title' => "Błąd krytyczny.",
	'exception:contact_admin' => 'Wystąpił nieodwracalny błąd. Skontaktuj się z administratorem, podając następującą informację:',

	'actionundefined' => "Żądana akcja (%s) nie została zdefiniowana w systemie.",
	'actionnotfound' => "Nie odnaleziono pliku akcji %s.",
	'actionloggedout' => "Przepraszamy, nie możesz wykonać tej akcji będąc wylogowany.",
	'actionunauthorized' => 'Nie masz uprawnień do wykonania tej akcji.',

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
	'ElggPlugin:Exception:CannotIncludeFile' => 'Nie można wczytać %s dla rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Nie można otworzyć katalogu widoków rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Nie można zarejestrować tłumaczeń dla rozszerzenia %s (guid: %s) w %s.',
	'ElggPlugin:Exception:NoID' => 'Brak identyfikatora rozszerzenia guid %s!',
	'PluginException:NoPluginName' => "Nie można znaleźć nazwy rozszerzenia.",
	'PluginException:ParserError' => 'Błąd w trakcie parsowania manifestu dla wersji API %s w rozszerzeniu %s.',
	'PluginException:NoAvailableParser' => 'Nie znaleziono parsera dla wersji API manifestu %s w rozszerzeniu %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Brak wymaganego atrybutu '%s' w manifeście rozszerzenia %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s jest niepoprawnym rozszerzeniem i został wyłączony.',

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


	'RegistrationException:EmptyPassword' => 'Hasło nie może być puste',
	'RegistrationException:PasswordMismatch' => 'Hasła muszą być jednakowe',
	'LoginException:BannedUser' => 'Zostałeś zbanowany na tej stronie i nie możesz się logować',
	'LoginException:UsernameFailure' => 'Nie można się zalogować. Sprawdź nazwę użytkownika/e-mail oraz hasło.',
	'LoginException:PasswordFailure' => 'Nie można się zalogować. Sprawdź nazwę użytkownika/e-mail oraz hasło.',
	'LoginException:AccountLocked' => 'Twoje konto zostało zablokowane z powodu zbyt wielu nieudanych prób logowania.',
	'LoginException:ChangePasswordFailure' => 'Nie powiodła się weryfikacja obecnego hasła.',
	'LoginException:Unknown' => 'Nie można się zalogować z powodu nieznanego błędu.',

	'deprecatedfunction' => 'Uwaga: Ten kod używa zdeprecjonowanej funkcji \'%s\' i nie jest kompatybilny z tą wersją Elgg.',

	'pageownerunavailable' => 'Uwaga: Właściciel strony %d jest niedostępny!',
	'viewfailure' => 'Wystąpił wewnętrzny błąd w widoku %s',
	'view:missing_param' => "Nie znaleziono wymaganego parametru '%s' w widoku %s",
	'changebookmark' => 'Proszę zaktualizować swoją zakładkę dla tej strony',
	'noaccess' => 'Treść, którą usiłujesz wyświetlić, została usunięta lub nie masz uprawnień do jej przeglądania.',
	'error:missing_data' => 'Zabrakło pewnych danych w twoim zapytaniu',
	'save:fail' => 'Zapis danych nie powiódł się',
	'save:success' => 'Twoje dane zostały zapisane',

	'error:default:title' => 'Ojoj...',
	'error:default:content' => 'Kurza twarz... coś poszło nie tak',
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

	'PRIVATE' => "Prywatny",
	'LOGGED_IN' => "Zalogowani użytkownicy",
	'PUBLIC' => "Publiczny",
	'LOGGED_OUT' => "Wylogowani użytkownicy",
	'access:friends:label' => "Znajomi",
	'access' => "Dostęp",
	'access:overridenotice' => "Uwaga: Ze względu na politykę grup, ten element będzie dostępny wyłącznie dla członków grupy.",
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
	'widgets:position:fixed' => '(Stała pozycja na stronie)',
	'widget:unavailable' => 'Już dodałeś ten gadżet.',
	'widget:numbertodisplay' => 'Ilość elementów do wyświetlenia',

	'widget:delete' => 'Usuń %s',
	'widget:edit' => 'Personalizuj ten gadżet',

	'widgets' => "Gadżety",
	'widget' => "Gadżet",
	'item:object:widget' => "Gadżety",
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

/**
 * Users
 */

	'user' => "Użytkownik",
	'item:user' => "Użytkownicy",

/**
 * Friends
 */

	'friends' => "Znajomi",
	'friends:yours' => "Twoi znajomi",
	'friends:owned' => "Znajomi użytkownika %s",
	'friend:add' => "Dodaj znajomego",
	'friend:remove' => "Usuń ze znajomych",

	'friends:add:successful' => "Pomyślnie dodano %s jako znajomego.",
	'friends:add:failure' => "Nie można dodać %s do listy znajomych. Spróbuj ponowanie.",

	'friends:remove:successful' => "Pomyślnie usunięto %s z listy twoich znajomych.",
	'friends:remove:failure' => "Nie można usunąć %s z listy znajomych.",

	'friends:none' => "Ten użytkownik nie ma jeszcze znajomych.",
	'friends:none:you' => "Nie masz jeszcze znajomych.",

	'friends:none:found' => "Nie znaleziono znajomych.",

	'friends:of:none' => "Nikt jeszcze nie dodał tego użytkownika jako znajomego.",
	'friends:of:none:you' => "Nikt jeszcze nie dodał Ciebie jako znajomego. Zacznij dodawać treść oraz wypełnij swój profil aby można było cię odnaleźć.",

	'friends:of:owned' => "Ludzie którzy mają %s jako znajomego.",

	'friends:of' => "Znajomi ",
	'friends:collections' => "Kręgi znajomych",
	'collections:add' => "Nowy krąg",
	'friends:collections:add' => "Nowy krąg znajomych",
	'friends:addfriends' => "Wybierz znajomych",
	'friends:collectionname' => "Nazwa kręgu",
	'friends:collectionfriends' => "Znajomi w kręgu",
	'friends:collectionedit' => "Edytuj ten krąg",
	'friends:nocollections' => "Nie masz jeszcze żadnych kręgów.",
	'friends:collectiondeleted' => "Twój krąg został usunięty.",
	'friends:collectiondeletefailed' => "Usuwanie kręgu się nie powiodło. Nie masz uprawnień lub wystąpił inny problem.",
	'friends:collectionadded' => "Twój krąg został utworzony pomyślnie.",
	'friends:nocollectionname' => "Musisz podać nazwę kręgu zanim zostanie on utworzony.",
	'friends:collections:members' => "Członkowie kręgu",
	'friends:collections:edit' => "Edytuj krąg",
	'friends:collections:edited' => "Zapisano krąg",
	'friends:collection:edit_failed' => 'Nie powiódł się zapis zmian w kręgu.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Awatar',
	'avatar:noaccess' => "Nie masz uprawnień aby edytować awatar tego użytkownika",
	'avatar:create' => 'Utwórz swój awatar',
	'avatar:edit' => 'Edytuj awatar',
	'avatar:preview' => 'Podgląd',
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

	'profile:edit' => 'Edytuj profil',
	'profile:aboutme' => "O mnie",
	'profile:description' => "O mnie",
	'profile:briefdescription' => "Krótki opis",
	'profile:location' => "Miejscowość",
	'profile:skills' => "Umiejętności",
	'profile:interests' => "Zainteresowania",
	'profile:contactemail' => "E-mail kontaktowy",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Telefon komórkowy",
	'profile:website' => "Strona www",
	'profile:twitter' => "Login na Twitterze",
	'profile:saved' => "Twój profil został zapisany pomyślnie.",

	'profile:field:text' => 'Krótki tekst',
	'profile:field:longtext' => 'Buży obszar tekstowy',
	'profile:field:tags' => 'Tagi',
	'profile:field:url' => 'Adres sieciowy',
	'profile:field:email' => 'Adres e-mail',
	'profile:field:location' => 'Miejscowość',
	'profile:field:date' => 'Data',

	'admin:appearance:profile_fields' => 'Zastąp pola profilu',
	'profile:edit:default' => 'Zastąp pola profilu',
	'profile:label' => "Etykieta profilu",
	'profile:type' => "Typ profilu",
	'profile:editdefault:delete:fail' => 'Błąd podczas usunięcia elementu domyślnego profilu ',
	'profile:editdefault:delete:success' => 'Usunięto pole profilu',
	'profile:defaultprofile:reset' => 'Reset profilu domyślnego',
	'profile:resetdefault' => 'Reset profilu domyślnego',
	'profile:resetdefault:confirm' => 'Czy na pewno chcesz usunąć twoje pola profilowe?',
	'profile:explainchangefields' => "Możesz zastąpić istniejące pola profilowe, używając formularza poniżej. \n\n Nadaj nowemu polu etykietę, na przykład, 'Ulubiona drużyna', następnie wybierz typ pola (np. text, url, tags) i kliknij przycisk 'Dodaj'. Aby zmienić kolejność pól, przeciągnij za uchwyt obok etykiety pola. Aby edytować etykietę - kliknij na etykiecie aby stała się edytowalna. \n\n W każdej chwili możesz wycofać zmiany i powrócić do domyślnych ustawień profilu, jednak utracisz wtedy wszystkie informacje, które zostały wprowadzone do dodatkowych pól na stronach profilowych.",
	'profile:editdefault:success' => 'Element został dodany do profilu domyślnego',
	'profile:editdefault:fail' => 'Domyślny profil nie może zostać zapisany',
	'profile:field_too_long' => 'Nie można zapisać twoich informacji profilowych. Sekcja "%s" jest za długa.',
	'profile:noaccess' => "Nie masz uprawnień do edycji tego profilu.",
	'profile:invalid_email' => '%s musi być poprawnym adresem e-mail.',


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
	'river:friend:user:default' => "%s jest teraz znajomym z %s",
	'river:update:user:avatar' => '%s ma nowy awatar',
	'river:update:user:profile' => '%s zaktualizował swój profil',
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

	'river:widget:title' => "Aktywny",
	'river:widget:description' => "Pokaż twoją ostanią aktywność.",
	'river:widget:type' => "Rodzaj aktywności",
	'river:widgets:friends' => 'Aktywność znajomych',
	'river:widgets:all' => 'Cała aktywność',

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

	'walled_garden:welcome' => 'Witaj w',

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

	'admin:unknown_section' => 'Niepoprawna sekcja panelu administracyjnego.',

	'admin' => "Administracja",
	'admin:description' => "Panel administratora umożliwia kontrolę wszystkich aspektów tego systemu, od zarządzania użytkownikiem, do konfiguracji rozszerzeń. Wybierz opcję poniżej, aby rozpocząć.",

	'admin:statistics' => "Statystyki",
	'admin:statistics:overview' => 'Przegląd',
	'admin:statistics:server' => 'Informacje o serwerze',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Najnowsze zadania cron',
	'admin:cron:period' => 'Okres cron\'a',
	'admin:cron:friendly' => 'Ostatnio ukończone',
	'admin:cron:date' => 'Data i czas',

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

	'admin:administer_utilities:maintenance' => 'Tryb konserwacji',
	'admin:upgrades' => 'Aktualizacje',

	'admin:settings' => 'Ustawienia',
	'admin:settings:basic' => 'Ustawienia podstawowe',
	'admin:settings:advanced' => 'Ustawienia zaawansowane',
	'admin:site:description' => "Ten panel umożliwia globalne ustawienia witryny. Wybierz opcję poniżej, aby rozpocząć.",
	'admin:site:opt:linktext' => "Konfiguracja strony...",
	'admin:settings:in_settings_file' => 'Opcja jest skonfigurowana w pliku settings.php',

	'admin:legend:security' => 'Bezpieczeństwo',
	'admin:site:secret:intro' => 'Elgg używa sekretnego klucza strony do tworzenia tokenów bezpieczeństwa do różnych celów.',
	'admin:site:secret_regenerated' => "Twój sekretny klucz strony został utworzony ponownie.",
	'admin:site:secret:regenerate' => "Utwórz ponownie sekretny klucz strony.",
	'admin:site:secret:regenerate:help' => "Zauważ: Ponowne utworzenie sekretnego klucza strony, może spowodować niedogodności dla niektórych użytkowników, poprzez unieważnienie ciasteczek trwałego logowania, e-maili z prośbą o walidację, kodów zaproszeń, itd.",
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
	'widget:content_stats:type' => 'Rodzaj treści',
	'widget:content_stats:number' => 'Ilość',

	'admin:widget:admin_welcome' => 'Witaj',
	'admin:widget:admin_welcome:help' => "Krótkie wprowadzenie to panelu administracyjnego Elgg",
	'admin:widget:admin_welcome:intro' =>
'Witaj w Elgg! Właśnie patrzysz na tablicę administracyjną. Jest ona przydatna, gdy chcesz śledzić co się dzieje na stronie.',

	'admin:widget:admin_welcome:admin_overview' =>
"Nawigacja w panelu administracyjnym jest dostępna poprzez menu po prawej stronie. Jest ona podzielona na trzy sekcje:
<dl>
		<dt>Administracja</dt><dd>Codzienne zadania, takie jak przeglądanie zgłoszonej treści, kontrola użytkowników on-line lub przeglądanie statystyk.</dd>
		<dt>Konfiguracja</dt><dd>Doraźne zadania, takie jak ustawianie nazwy strony lub aktywacja rozszerzenia.</dd>
		<dt>Develop</dt><dd>Dla programistów budujących rozszerzenia lub projektujących motywy graficzne. (Wymaga rozszerzenia Developers)</dd>
	</dl>",

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

/**
 * Plugins
 */

	'plugins:disabled' => 'Rozszerzenia nie są wczytywane, ponieważ plik o nazwie "disabled" jest obecny w katalogu mod.',
	'plugins:settings:save:ok' => "Ustawienia dla rozszerzenia %s zostały pomyśłnie zapisane.",
	'plugins:settings:save:fail' => "Wystąpił problem podczas zapisu ustawień dla rozszerzenia %s.",
	'plugins:usersettings:save:ok' => "Ustawienia użytkownika dla rozszerzenia %s zostały pomyślnie zapisane.",
	'plugins:usersettings:save:fail' => "Wystąpił problem podczas zapisywania ustawień użytkownika dla rozszerzenia %s.",
	'item:object:plugin' => 'Rozszerzenie',

	'admin:plugins' => "Rozszerzenia",
	'admin:plugins:activate_all' => 'Aktywuj wszystkie',
	'admin:plugins:deactivate_all' => 'Dezaktywuj wszystkie',
	'admin:plugins:activate' => 'Aktywuj',
	'admin:plugins:deactivate' => 'Dezaktywuj',
	'admin:plugins:description' => "Ten panel pozwala kontrolować i konfigurować narzędzia zainstalowane w twoim serwisie.",
	'admin:plugins:opt:linktext' => "Konfiguracja narzędzi...",
	'admin:plugins:opt:description' => "Konfigurowanie narzędzi zainstalowanych w serwisie. ",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorie',
	'admin:plugins:label:licence' => "Licencja",
	'admin:plugins:label:website' => "Adres",
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

	'admin:plugins:warning:elgg_version_unknown' => 'To rozszerzenie używa starszego manifestu i nie specyfikuje kompatybilnej wersji Elgg. Prawdopodobnie nie będzie działać!',
	'admin:plugins:warning:unmet_dependencies' => 'To rozszerzenie ma brakujące zależności i nie może być włączone. Sprawdź wymagane zależności w \'więcej informacji\'.',
	'admin:plugins:warning:invalid' => 'To rozszerzenie jest niepoprawne: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Sprawdź <a href="http://docs.elgg.org/Invalid_Plugin">dokumentację Elgg</a> w poszukiwaniu wskazówek.',
	'admin:plugins:cannot_activate' => 'nie można włączyć',

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
	'admin:statistics:label:basic' => "Podstawowe statystyk strony",
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

	'admin:appearance:menu_items' => 'Elementy menu',
	'admin:menu_items:configure' => 'Konfiguruj główne elementy menu',
	'admin:menu_items:description' => 'Wybierz, które elementy menu chcesz uwydatnić. Nieużywane elementy będą dodane do sekcji "Więcej", na końcu listy.',
	'admin:menu_items:hide_toolbar_entries' => 'Usunąć linki z menu paska narzędzi?',
	'admin:menu_items:saved' => 'Elementy menu zapisane.',
	'admin:add_menu_item' => 'Dodaj własny element menu',
	'admin:add_menu_item:description' => 'Wypełnij wyświetlaną nazwę oraz URL aby dodać własne elementy to twojego menu nawigacji.',

	'admin:appearance:default_widgets' => 'Domyślne gadżety',
	'admin:default_widgets:unknown_type' => 'Nieznany typ gadżetu',
	'admin:default_widgets:instructions' => 'Dodaj, usuń, ustaw i konfiguruj domyślne gadżety dla wybranej strony gadżetów.',

	'admin:robots.txt:instructions' => "Edytuj poniżej plik robots.txt dla tej strony",
	'admin:robots.txt:plugins' => "Rozszerzenia dodają poniższą treść do pliku robots.txt",
	'admin:robots.txt:subdir' => "Plik robots.txt nie zadziała, ponieważ Elgg jest zainstalowany w podkatalogu",

	'admin:maintenance_mode:default_message' => 'Ta strona jest w trakcie prac konserwacyjnych',
	'admin:maintenance_mode:instructions' => 'Tryb konserwacyjny powinien być używany przy aktualizacjach i innych dużych zmianach na stronie.⏎
⇥⇥Gdy jest włączony, tylko administratorzy mogą się logować i przeglądać stronę.',
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
	'river:generic_comment' => 'skomentował %s %s',

	'friends:widget:description' => "Wyświetla niektórych twoich znajomych.",
	'friends:num_display' => "Liczba znajomych do wyświetlenia",
	'friends:icon_size' => "Rozmiar ikony",
	'friends:tiny' => "bardzo mały",
	'friends:small' => "mały",

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
	'spotlight' => "Wyróżnienie",
	'all' => "Wszyscy",
	'mine' => "Moje",

	'by' => 'przez',
	'none' => 'żadne',

	'annotations' => "Adnotacje",
	'relationships' => "Relacje",
	'metadata' => "Metadane",
	'tagcloud' => "Chmura tagów",
	'tagcloud:allsitetags' => "Wszystkie tagi",

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
	'fileexists' => "Plik został już przesłany. Aby zastąpić go, zaznacz go poniżej:",

/**
 * User add
 */

	'useradd:subject' => 'Utworzono konto użytkownika',
	'useradd:body' => '
%s,

Konto użytkownika zostało dla ciebie utworzone na %s. Aby się zalogować, wejdź na:

%s

Do logowania użyj poniższych danych:

Nazwa użytkownika: %s
Hasło: %s

Jak już się zalogujesz, gorąco polecamy zmianę hasła.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "kliknij aby odrzucić",


/**
 * Import / export
 */
		
	'importsuccess' => "Dane zostały pomyślnie zaimportowane",
	'importfail' => "OpenDD import danych nieudany.",

/**
 * Time
 */

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

	'date:weekday:0' => 'Niedziela',
	'date:weekday:1' => 'Poniedziałek',
	'date:weekday:2' => 'Wtorek',
	'date:weekday:3' => 'Środa',
	'date:weekday:4' => 'Czwartek',
	'date:weekday:5' => 'Piątek',
	'date:weekday:6' => 'Sobota',
	
	'interval:minute' => 'Co minutę',
	'interval:fiveminute' => 'Co pięć minut',
	'interval:fifteenmin' => 'Co kwadrans',
	'interval:halfhour' => 'Co pół godziny',
	'interval:hourly' => 'Co godzinę',
	'interval:daily' => 'Codziennie',
	'interval:weekly' => 'Co tydzień',
	'interval:monthly' => 'Co miesiąc',
	'interval:yearly' => 'Co rok',
	'interval:reboot' => 'Co restart',

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

	'installation:httpslogin' => "Włącz aby użytkownicy logowali się przy użyciu HTTPS. Musisz mieć włączone https na serwerze, aby ta opcja zadziałała.",
	'installation:httpslogin:label' => "Włącz logowanie przez HTTPS",
	'installation:view' => "Wprowadź widok, który będzie domyślnie używany przez twoją stronę lub pozostaw wartość domyślną (w razie wątpliwości, pozostaw wartość domyślną):",

	'installation:siteemail' => "Adres e-mail strony (używany do wysyłania systemowych e-mail)",

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

	'admin:legend:caching' => 'Pamieć podręczna',
	'admin:legend:content_access' => 'Dostęp do treści',
	'admin:legend:site_access' => 'Poziom dostępu na stronie',
	'admin:legend:debug' => 'Odpluskwianie i logowanie',

	'upgrading' => 'Aktualizacja',
	'upgrade:db' => 'Twoja baza danych została zaktualizowana.',
	'upgrade:core' => 'Twoja instalacja elgg została zaktualizowana',
	'upgrade:unlock' => 'Odblokuj aktualizację',
	'upgrade:unlock:confirm' => "Baza danych jest zablokowana z powodu przebiegającej aktualizacji. Wykonywanie aktualizacji równolegle jest niebezpieczne. Powinieneś kontynuować tylko jeśli jesteś pewien, że nie jest uruchomiona żadna aktualizacja. Odblokować?",
	'upgrade:locked' => "Nie można wykonać aktualizacji. Inna aktualizacja wciąż przebiega. Aby wyłączyć blokadę aktualizacji, zobacz sekcję Administracja.",
	'upgrade:unlock:success' => "Blokada aktualizacji zdjęta pomyślnie.",
	'upgrade:unable_to_upgrade' => 'Aktualizacja nie powiodła się.',
	'upgrade:unable_to_upgrade_info' =>
		'Instalacja nie może być przeprowadzona ze względu na wykrycie starych widoków w głównym katalogu widoków. Te widoki są przestarzałe i muszą zostać usunięte aby Elgg pracował poprawnie. Jeśli nie wykonywałeś zmian w jądrze Elgg, możesz po prostu usunąć katalog views i zastąpić go wersją z najnowszego pakietu dostępnego na <a href="http://elgg.org">elgg.org</a>.<br /><br /> Jeśli potrzebujesz bardziej szczegółowych instrukcji, zajrzyj do <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">Aktualizacja Elgg</a>. Jeśli potrzebujesz pomocy, napisz na stronie <a href="http://community.elgg.org/pg/groups/discussion/">Społecznościowego Forum Wsparcia</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (dawniej: Usługi Twittera) został wyłączony w trakcie aktualizacji. W razie potrzeby, proszę aktywować ręcznie.',
	'update:oauth_api:deactivated' => 'OAuth API (dawniej: OAuth Lib) został wyłączony w trakcie aktualizacji. W razie potrzeby, proszę aktywować ręcznie.',
	'upgrade:site_secret_warning:moderate' => "Zalecamy ponowne utworzenie sekretnego klucza strony dla poprawy bezpieczeństwa strony. Zobacz Konfiguracja &gt; Ustawienia &gt; Zaawansowane",
	'upgrade:site_secret_warning:weak' => "Zdecydowanie zalecamy ponowne utworzenie sekretnego klucza strony dla poprawy bezpieczeństwa strony. Zobacz Konfiguracja &gt; Ustawienia &gt; Zaawansowane",

	'ElggUpgrade:error:url_invalid' => 'Niepoprawna wartość jako URL.',
	'ElggUpgrade:error:url_not_unique' => 'URLe aktualizacji muszą być unikatowe.',
	'ElggUpgrade:error:title_required' => 'Obiekty ElggUpgrade muszą posiadać tytuł.',
	'ElggUpgrade:error:description_required' => 'Obiekty ElggUpgrade muszą posiadać opis.',
	'ElggUpgrade:error:upgrade_url_required' => 'Obiekty ElggUpgrade muszą posiadać adres URL aktualizacji.',

	'deprecated:function' => '%s() została zdeprecjonowana na rzecz %s()',

	'admin:pending_upgrades' => 'Ta strona ma oczekujące aktualizacje, które wymagają Twojej interwencji.',
	'admin:view_upgrades' => 'Przeglądaj oczekujące aktualizacje.',
 	'admin:upgrades' => 'Aktualizacje',
	'item:object:elgg_upgrade' => 'Aktualizacje strony',
	'admin:upgrades:none' => 'Twoja instalacja Elgg jest aktualna!',

	'upgrade:item_count' => 'Jest <b>%s</b> elementów, które należy zaktualizować.',
	'upgrade:warning' => '<b>Uwaga:</b> na dużych stronach, aktualizacja może zająć istotnie dużo czasu!',
	'upgrade:success_count' => 'Zaktualizowano:',
	'upgrade:error_count' => 'Błędów:',
	'upgrade:river_update_failed' => 'Nie powiodła się aktualizacja wpisu aktualności dla elementu o id %s',
	'upgrade:timestamp_update_failed' => 'Nie udało się zaktualizować znaczników czasu dla elementu o id %s',
	'upgrade:finished' => 'Aktualizacja zakończona',
	'upgrade:finished_with_errors' => '<p>Aktualizacja zakończyła się błędami. Odśwież stronę aby spróbować ponownie.</p></p><br />Jeśli błąd się powtarza, sprawdź możliwe przyczyny w dzienniku błędów. Możesz szukać pomocy w rozwiązaniu problemów na stronie <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupy wsparcia technicznego</a> w społeczności Elgg.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Aktualizacja komentarzy',
	'upgrade:comment:create_failed' => 'Nie udało się skonwertować komentarza o id %s do entity.',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Aktualizacja katalogu danych',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Aktualizacja odpowiedzi na forach',
	'discussion:upgrade:replies:create_failed' => 'Nie udało się skonwertować komentarza o id %s do entity.',

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

	'email:save:success' => "Nowy adres e-mail zapisano.",
	'email:save:fail' => "Nie zapisano nowego adresu.",

	'friend:newfriend:subject' => "%s dodał cię do listy znajomych!",
	'friend:newfriend:body' => "%s dodał cię do listy znajomych!

Aby zobaczyć jego profil, kliknij tutaj:

%s

Nie możesz odpowiedzieć na ten e-mail.",

	'email:changepassword:subject' => "Hasło zmienione!",
	'email:changepassword:body' => "Witaj %s,

Twoje hasło zostało zmienione.",

	'email:resetpassword:subject' => "Reset hasła!",
	'email:resetpassword:body' => "Cześć %s,
			
Twoje nowe hasło to: %s",

	'email:changereq:subject' => "Prośba o zmianę hasła.",
	'email:changereq:body' => "Witaj %s,

Ktoś (z adresu IP %s) poprosił o zmianę hasła do Twojego konta.

Jeśli to byłeś Ty, kliknij odnośnik poniżej. W przeciwnym wypadku zignoruj tą wiadomość.

%s
",

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

	'river:comment:object:default' => '%s skomentował %s',

	'generic_comments:add' => "Dodaj komentarz",
	'generic_comments:edit' => "Edytuj komentarz",
	'generic_comments:post' => "Dodaj komentarz",
	'generic_comments:text' => "Komentarz",
	'generic_comments:latest' => "Najnowsze komentarze",
	'generic_comment:posted' => "Twój komentarz został dodany.",
	'generic_comment:updated' => "Twój komentarz został pomyślnie zaktualizowany.",
	'generic_comment:deleted' => "Twój komentarz został skasowany.",
	'generic_comment:blank' => "Przepraszamy: musisz coś wpisać przed zapisaniem.",
	'generic_comment:notfound' => "Przepraszamy: nie można znaleźć określonej pozycji.",
	'generic_comment:notdeleted' => "Sorry: nie można skasować tego komentarza.",
	'generic_comment:failure' => "Wystąpił nieoczekiwany błąd podczas dodawania komentarza.",
	'generic_comment:none' => 'Brak komentarzy',
	'generic_comment:title' => 'Komentarz użytkownika %s',
	'generic_comment:on' => '%s dotyczący %s',
	'generic_comments:latest:posted' => 'napisał',

	'generic_comment:email:subject' => 'Masz nowy komentarz!',
	'generic_comment:email:body' => "Masz nowy komentarz w \"%s\" od %s. Treść:

			
%s

	
Aby odpowiedzieć lub wyświetlić komentarz, kliknij tutaj:

	%s

Zobacz %s's profil, kliknij tutaj:

	%s

Nie możesz odpowiedzieć na ten e-mail.",

/**
 * Entities
 */
	
	'byline' => 'Przez %s',
	'entity:default:strapline' => 'Stworzone %s przez %s',
	'entity:default:missingsupport:popup' => 'Przedmiot ten nie może zostać wyświetlony poprawnie. Może to być spowodowane brakiem uprzednio zainstalowanego rozszerzenia.',

	'entity:delete:success' => 'Element %s został skasowany',
	'entity:delete:fail' => 'Element %s nie został skasowany',

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
	'tags:site_cloud' => 'Chmura tagów strony',

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
 * Languages according to ISO 639-1
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
);
