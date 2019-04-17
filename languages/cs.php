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

	'login' => "Přihlásit se",
	'loginok' => "Byl/a jste přihlášen/a.",
	'loginerror' => "Nemůžeme vás přihlásit. Zkontrolujete přihlašovací údaje a zkuste to znovu.",
	'login:empty' => "Je vyžadováno uživatelské jméno/e-mail a heslo.",
	'login:baduser' => "Váš profil není možné načíst.",
	'auth:nopams' => "Vnitřní chyba. Není nainstalovaná ověřovací metoda uživatele.",

	'logout' => "Odhlásit se",
	'logoutok' => "Byl/a jste odhlášen/a.",
	'logouterror' => "Nemůžeme vás odhlásit. Zkuste to znovu.",
	'session_expired' => "Vaše sezení vypršelo. <a href='javascript:location.reload(true)'>Obnovte</a> prosím stránku k novém přihlášení.",
	'session_changed_user' => "Byl/a jste přihlášen/a jako jiný uživatel. Měl/a byste <a href='javascript:location.reload(true)'>obnovit</a> stránku.",

	'loggedinrequired' => "Musíte být přihlášen/a aby jste mohl/a vidět požadovanou stránku.",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "Musíte být správcem aby jste viděl/a požadovanou stránku.",
	'membershiprequired' => "Musíte být členem této skupiny aby jste mohl/a vidět požadovanou stránku.",
	'limited_access' => "Nemáte oprávnění vidět požadovanou stránku.",
	'invalid_request_signature' => "Adresa stránky kterou se snažíte zobrazit je buď neplatná nebo jí skončila platnost",

/**
 * Errors
 */

	'exception:title' => "Fatální chyba.",
	'exception:contact_admin' => 'Došlo k neodstranitelné chybě, která byla zaznamenána. Obraťte se na správce těchto stránek s následujícími údaji:',

	'actionundefined' => "Požadovaná akce (%s) nebyla v systému definována.",
	'actionnotfound' => "Soubor s akcí pro %s nebyl nalezen.",
	'actionloggedout' => "Tuto akci bohužel nelze provést dokud nejste jste přihlášen/a.",
	'actionunauthorized' => 'Nemáte oprávnění provést tuto akci.',

	'ajax:error' => 'Neočekávaná chyba během volání AJAX požadavku. Pravděpodobně došlo ke ztrátě spojení se serverem.',
	'ajax:not_is_xhr' => 'Nemůžete přistupovat přímo k AJAX pohledům',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) je špatně nakonfigurovaný doplněk a byl zakázán. Prohledejte prosím wiki projektu Elgg pro nalezení možných příčin (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) se nespustil a byl deaktivován. Důvod: %s',
	'PluginException:InvalidID' => "%s je neplatné ID doplňku.",
	'PluginException:InvalidPath' => "%s je neplatná cesta doplňku.",
	'PluginException:InvalidManifest' => 'Neplatný soubor manifestu pro doplněk %s',
	'PluginException:InvalidPlugin' => '%s není platný doplněk.',
	'PluginException:InvalidPlugin:Details' => '%s není platný doplněk: %s',
	'PluginException:NullInstantiated' => 'Doplněk nemůže být odstraněn. Musíte dodat GUID, ID doplňku nebo kompletní cestu.',
	'ElggPlugin:MissingID' => 'Postrádám ID doplňku (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Postrádám balíček doplňku pro ID doplňku %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Požadovaný soubor "%s" chybí.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Složka doplňku musí být přejmenována na "%s" aby odpovídala ID uvedenému v manifestu.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Jeho manifest obsahuje neplatný typ závislosti "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Jeho manifest obsahuje neplatný typ poskytování "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Existuje vadná %s závislost "%s" v doplňku %s. Doplněk nemůže být v konfliktu s nebo požadovat něco co poskytuje!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Je v konfliktu s doplňkem: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Soubor doplňku "elgg-plugin.php" file je přítomen ale nedá se číst.',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Error:ID' => 'Error in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error in plugin path "%s"',
	'ElggPlugin:Error:Unknown' => 'Undefined plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Nemohu vložit %s pro doplněk %s (guid: %s) na %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Vyhozena výjimka obsahující %s pro doplněk %s (guid: %s) na %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Nemohu otevřít složku pohledů pro doplněk %s (guid: %s) na %s.',
	'ElggPlugin:Exception:NoID' => 'Žádné ID pro doplněk guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Package cannot be loaded',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest is missing or invalid',
	'PluginException:NoPluginName' => "Jméno doplňku nebylo nalezeno",
	'PluginException:ParserError' => 'Při čtení manifestu s verzí API %s v doplňku %s nastala chyba.',
	'PluginException:NoAvailableParser' => 'Nemohu najít čtečku pro manifest s verzí API %s v doplňku %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Postrádám vyžadovanou vlastnost '%s' v manifestu pro doplněk %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s je neplatný doplněk a byl deaktivován.',
	'ElggPlugin:activate:BadConfigFormat' => 'Soubor doplňku "elgg-plugin.php" nevrátil seriazovatelné pole.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Soubor doplňku "elgg-plugin.php" poslal výstup.',

	'ElggPlugin:Dependencies:Requires' => 'Požadováno',
	'ElggPlugin:Dependencies:Suggests' => 'Doporučeno',
	'ElggPlugin:Dependencies:Conflicts' => 'Konflikty',
	'ElggPlugin:Dependencies:Conflicted' => 'V konfliktu',
	'ElggPlugin:Dependencies:Provides' => 'Poskytuje',
	'ElggPlugin:Dependencies:Priority' => 'Priorita',

	'ElggPlugin:Dependencies:Elgg' => 'Verze Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Verze PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Rozšíření PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Nastavení v ini PHP: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Doplněk: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Po %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Před %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s není nainstalováno',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Chybí',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Jsou zde jiné doplňky, které závisí na %s. Než zakážete tento, musíte zakázat následující doplňky: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Nalezeny položky nabídky které neodkazují na rodiče',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Nalezena položka nabídky [%s], které chybí rodič[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Nalezena duplicitní registrace pro položku nabídky [%s]',

	'RegistrationException:EmptyPassword' => 'Pole s heslem nemůže být prázdné',
	'RegistrationException:PasswordMismatch' => 'Hesla se musí shodovat',
	'LoginException:BannedUser' => 'Na tyto stánky vám byl zakázán přístup a nemůžete se přihlásit',
	'LoginException:UsernameFailure' => 'Nemůžeme vás přihlásit. Zkontrolujte prosím vaše uživatelské jméno/e-mail a heslo.',
	'LoginException:PasswordFailure' => 'Nemůžeme vás přihlásit. Zkontrolujte prosím vaše uživatelské jméno/e-mail a heslo.',
	'LoginException:AccountLocked' => 'Příliš mnoho neúspěšných pokusů. Váš účet byl uzamčen.',
	'LoginException:ChangePasswordFailure' => 'Chybně zadané současné heslo.',
	'LoginException:Unknown' => 'Díky neznámé chybě vás nemůžeme přihlásit.',

	'UserFetchFailureException' => 'Nemohu zkontrolovat práva pro user_guid [%s] neboť tento uživatel neexistuje.',

	'PageNotFoundException' => 'The page you are trying to view does not exist or you do not have permissions to view it',
	'EntityNotFoundException' => 'The content you were trying to access has been removed or you do not have permissions to access it.',
	'EntityPermissionsException' => 'You do not have sufficient permissions for this action.',
	'GatekeeperException' => 'You do not have permissions to view the page you are trying to access',
	'BadRequestException' => 'Bad request',
	'ValidationException' => 'Submitted data did not meet the requirements, please check your input.',
	'LogicException:InterfaceNotImplemented' => '%s must implement %s',

	'deprecatedfunction' => 'Varování: Tento kód používá zastaralou funkci \'%s\' a není kompatibilní s touto verzí projektu Elgg',

	'pageownerunavailable' => 'Varování: Vlastník stránky %d není dostupný!',
	'viewfailure' => 'V pohledu %s došlo k vnitřnímu selhání',
	'view:missing_param' => "Požadovaný parametr '%s' chybí v pohledu %s",
	'changebookmark' => 'Změňte prosím vaši záložku pro tuto stránku.',
	'noaccess' => 'Obsah který se snažíte zobrazit byl odstraněn nebo nemáte povolení prohlížet si ho.',
	'error:missing_data' => 'V požadavku chybí nějaká data.',
	'save:fail' => 'Při ukládání vašich dat se vyskytla chyba',
	'save:success' => 'Data byla uložena',

	'forward:error' => 'Sorry. An error occurred while redirecting to you to another site.',

	'error:default:title' => 'Jejda...',
	'error:default:content' => 'Jejda... něco se pokazilo.',
	'error:400:title' => 'Špatný požadavek',
	'error:400:content' => 'Požadavek byl bohužel špatný nebo neúplný.',
	'error:403:title' => 'Nepřístupná stránka',
	'error:403:content' => 'Bohužel nemáte přístup k požadované stránce.',
	'error:404:title' => 'Stránka nebyla nalezena',
	'error:404:content' => 'Bohužel jsme nemohli najít stránku kterou požadujete.',

	'upload:error:ini_size' => 'Soubor, který se snažíte nahrát, je příliš veliký.',
	'upload:error:form_size' => 'Soubor, který se snažíte nahrát, je příliš veliký.',
	'upload:error:partial' => 'Nahrání souboru není dokončeno.',
	'upload:error:no_file' => 'Nebyl vybrán soubor.',
	'upload:error:no_tmp_dir' => 'Nemohu uložit nahraný soubor.',
	'upload:error:cant_write' => 'Nemohu uložit nahraný soubor.',
	'upload:error:extension' => 'Nemohu uložit nahraný soubor.',
	'upload:error:unknown' => 'Nahrání souboru selhalo.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Správce',
	'table_columns:fromView:banned' => 'Zakázán přístup',
	'table_columns:fromView:container' => 'Kontejner',
	'table_columns:fromView:excerpt' => 'Popis',
	'table_columns:fromView:link' => 'Jméno/Název',
	'table_columns:fromView:icon' => 'Ikona',
	'table_columns:fromView:item' => 'Položka',
	'table_columns:fromView:language' => 'Jazyk',
	'table_columns:fromView:owner' => 'Vlastník',
	'table_columns:fromView:time_created' => 'Čas vytvoření',
	'table_columns:fromView:time_updated' => 'Čas aktualizace',
	'table_columns:fromView:user' => 'Uživatel',

	'table_columns:fromProperty:description' => 'Popis',
	'table_columns:fromProperty:email' => 'E-mail',
	'table_columns:fromProperty:name' => 'Jméno',
	'table_columns:fromProperty:type' => 'Typ',
	'table_columns:fromProperty:username' => 'Uživatelské jméno',

	'table_columns:fromMethod:getSubtype' => 'Podtyp',
	'table_columns:fromMethod:getDisplayName' => 'Jméno/Název',
	'table_columns:fromMethod:getMimeType' => 'MIME typ',
	'table_columns:fromMethod:getSimpleType' => 'Typ',

/**
 * User details
 */

	'name' => "Zobrazené jméno",
	'email' => "E-mailová adresa",
	'username' => "Uživatelské jméno",
	'loginusername' => "Uživatelské jméno nebo e-mail",
	'password' => "Heslo",
	'passwordagain' => "Heslo (znovu pro ověření)",
	'admin_option' => "Má být tento uživatel správcem?",
	'autogen_password_option' => "Automagicky vyrobit bezpečné heslo?",

/**
 * Access
 */

	'access:label:private' => "Private",
	'access:label:logged_in' => "Logged in users",
	'access:label:public' => "Public",
	'access:label:logged_out' => "Logged out users",
	'access:label:friends' => "Friends",
	'access' => "Přístup",
	'access:overridenotice' => "Poznámka: Díky pravidlům skupiny bude tento obsah přístupný pouze pro členy skupiny.",
	'access:limited:label' => "Omezený",
	'access:help' => "Úroveň přístupu",
	'access:read' => "Ke čtení",
	'access:write' => "Pro zápis",
	'access:admin_only' => "Pouze pro správce",
	'access:missing_name' => "Postrádám jméno úrovně přístupu",
	'access:comments:change' => "Tato diskuse je momentálně viditelná omezenému publiku. Buďte pozorný s kým jí sdílíte.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Nástěnka",
	'dashboard:nowidgets' => "Vaše nástěnka vám umožní sledovat aktivitu a obsah, který se vás týká.",

	'widgets:add' => 'Přidat udělátka',
	'widgets:add:description' => "Udělátka na stránku přidáte kliknutím na následující tlačítka.",
	'widgets:position:fixed' => '(Pevná pozice na stránce)',
	'widget:unavailable' => 'Toto udělátko již máte přidané',
	'widget:numbertodisplay' => 'Počet zobrazených položek',

	'widget:delete' => 'Odebrat %s',
	'widget:edit' => 'Upravit udělátko',

	'widgets' => "Udělátka",
	'widget' => "Udělátko",
	'item:object:widget' => "Udělátka",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "Udělátko bylo úspěšně uloženo.",
	'widgets:save:failure' => "Nemohu uložit udělátko.",
	'widgets:add:success' => "Udělátko bylo úspěšně přidáno.",
	'widgets:add:failure' => "Nemohu přidat udělátko.",
	'widgets:move:failure' => "Nemohu uložit novou pozici udělátka.",
	'widgets:remove:failure' => "Toto udělátko není možné odebrat ",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "Skupina",
	'item:group' => "Skupiny",
	'collection:group' => 'Groups',
	'item:group:group' => "Group",
	'collection:group:group' => 'Groups',
	'groups:tool_gatekeeper' => "The requested functionality is currently not enabled in this group",

/**
 * Users
 */

	'user' => "Uživatel",
	'item:user' => "Uživatelé",
	'collection:user' => 'Users',
	'item:user:user' => 'User',
	'collection:user:user' => 'Users',

	'friends' => "Přátelé",
	'collection:friends' => 'Friends\' %s',

	'avatar' => 'Portrét',
	'avatar:noaccess' => "Nemáte povoleno upravovat portrét tohoto uživatele",
	'avatar:create' => 'Vytvořit portrét',
	'avatar:edit' => 'Upravit portrét',
	'avatar:upload' => 'Nahrát nový portrét',
	'avatar:current' => 'Současný portrét',
	'avatar:remove' => 'Odebrat portrét a nastavit výchozí obrázek',
	'avatar:crop:title' => 'Nástroj pro ořez portrétu',
	'avatar:upload:instructions' => "Portrét je zobrazen u vašich aktivit na těchto stránkách. Můžete jej měnit tak často jak chcete. (Jsou přijímány formáty GIF, JPG a PNG.)",
	'avatar:create:instructions' => 'Myší natáhněte a přemisťte čtverec vespod tak aby odpovídal požadovanému ořezu. V pravé části se zobrazí náhled. Až budete s náhledem spokojen/a, klikněte na \'Vytvořit portrét\'. Tato oříznutá verze bude zobrazena u všech vašich aktivit.',
	'avatar:upload:success' => 'Portrét úspěšně nahrán',
	'avatar:upload:fail' => 'Nahrání portrétu se nezdařilo',
	'avatar:resize:fail' => 'Změna velikosti portrétu se nezdařila',
	'avatar:crop:success' => 'Portrét úspěšně oříznut',
	'avatar:crop:fail' => 'Oříznutí portrétu se nezdařilo',
	'avatar:remove:success' => 'Portrét úspěšně odebrán',
	'avatar:remove:fail' => 'Odebrání portrétu se nezdařilo',
	
	'action:user:validate:already' => "%s was already validated",
	'action:user:validate:success' => "%s has been validated",
	'action:user:validate:error' => "An error occurred while validating %s",

/**
 * Feeds
 */
	'feed:rss' => 'RSS kanál pro tuto stránku',
	'feed:rss:title' => 'RSS feed for this page',
/**
 * Links
 */
	'link:view' => 'zobrazit odkaz',
	'link:view:all' => 'Zobrazit vše',


/**
 * River
 */
	'river' => "Aktivita",
	'river:user:friend' => "%s is now a friend with %s",
	'river:update:user:avatar' => '%s má nový portrét',
	'river:noaccess' => 'Nemáte oprávnění vidět tuto položku.',
	'river:posted:generic' => '%s odesláno',
	'riveritem:single:user' => 'uživatel',
	'riveritem:plural:user' => 'někteří uživatelé',
	'river:ingroup' => 've skupině %s',
	'river:none' => 'Žádná aktivita',
	'river:update' => 'Aktualizace pro %s',
	'river:delete' => 'Odebrat tuto položku aktivity',
	'river:delete:success' => 'Položka aktivity byla odstraněna',
	'river:delete:fail' => 'Položka aktivity nemůže být odstraněna',
	'river:delete:lack_permission' => 'Nemáte dostatečná práva pro odstranění této položky aktivity',
	'river:can_delete:invaliduser' => 'Nemohu zkontrolovat canDelete pro user_guid [%s] neboť tento uživatel neexistuje.',
	'river:subject:invalid_subject' => 'Neplatný uživatel',
	'activity:owner' => 'Zobrazit aktivitu',

	

/**
 * Notifications
 */
	'notifications:usersettings' => "Nastavení oznámení",
	'notification:method:email' => 'E-mail',

	'notifications:usersettings:save:ok' => "Nastavení oznámení bylo úspěšně uloženo.",
	'notifications:usersettings:save:fail' => "Vyskytl se problém při ukládání nastavení oznámení.",

	'notification:subject' => 'Oznámení o %s',
	'notification:body' => 'Zobrazit novou aktivitu v %s',

/**
 * Search
 */

	'search' => "Hledat",
	'searchtitle' => "Hledat: %s",
	'users:searchtitle' => "Hledat uživatele: %s",
	'groups:searchtitle' => "Hledat skupiny: %s",
	'advancedsearchtitle' => "%s s výsledky shodující se s %s",
	'notfound' => "Nic nenalezeno.",
	'next' => "Následující",
	'previous' => "Předešlé",

	'viewtype:change' => "Změnit typ výpisu",
	'viewtype:list' => "Výpis",
	'viewtype:gallery' => "Galerie",

	'tag:search:startblurb' => "Položky odpovídající '%s':",

	'user:search:startblurb' => "Uživatelé odpovídající '%s':",
	'user:search:finishblurb' => "Klikněte sem pro podrobnější výpis.",

	'group:search:startblurb' => "Skupiny odpovídající '%s':",
	'group:search:finishblurb' => "Klikněte sem pro podrobnější výpis.",
	'search:go' => 'Start',
	'userpicker:only_friends' => 'Pouze přátelé',

/**
 * Account
 */

	'account' => "Účet",
	'settings' => "Nastavení",
	'tools' => "Nástroje",
	'settings:edit' => 'Upravit nastavení',

	'register' => "Registrovat",
	'registerok' => "Byl/a jste úspěšně zaregistrován/a do %s.",
	'registerbad' => "Vaše registrace nebyla úspěšná díky neznámé chybě.",
	'registerdisabled' => "Registrace byla zakázána správcem systému",
	'register:fields' => 'Všechny položky jsou vyžadované',

	'registration:noname' => 'Display name is required.',
	'registration:notemail' => 'E-mailová adresa kterou jste zadal/a se nezdá být platnou e-mailovou adresou.',
	'registration:userexists' => 'Toto uživatelské jméno již existuje',
	'registration:usernametooshort' => 'Uživatelské jméno musí mít alespoň %u znaků.',
	'registration:usernametoolong' => 'Vaše uživatelské jméno je příliš dlouhé. Může mít maximálně %u znaků.',
	'registration:passwordtooshort' => 'Heslo musí mít alespoň %u znaků.',
	'registration:dupeemail' => 'Tento e-mail je již zaregistrován.',
	'registration:invalidchars' => 'Vaše uživatelské jméno bohužel obsahuje znak %s který je neplatný. Následující znaky jsou neplatné: %s',
	'registration:emailnotvalid' => 'E-mailová adresa kterou jste zadal/a je bohužel neplatná',
	'registration:passwordnotvalid' => 'Heslo které jste zadal/a je bohužel neplatné',
	'registration:usernamenotvalid' => 'Uživatelské jméno které jste zadal/a je bohužel neplatné',

	'adduser' => "Přidat uživatele",
	'adduser:ok' => "Úspěšně jste přidal/a nového uživatele.",
	'adduser:bad' => "Nový uživatel nemůže být vytvořen.",

	'user:set:name' => "Nastavení jména účtu",
	'user:name:label' => "Zobrazené jméno",
	'user:name:success' => "Zobrazované jméno bylo úspěšně změněno.",
	'user:name:fail' => "Nemohu změnit zobrazované jméno.",
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Could not change username on the system.",

	'user:set:password' => "Heslo účtu",
	'user:current_password:label' => 'Současné heslo',
	'user:password:label' => "Nové heslo",
	'user:password2:label' => "Nové heslo znovu",
	'user:password:success' => "Heslo bylo změněno",
	'user:password:fail' => "Nemohu změnit vaše heslo.",
	'user:password:fail:notsame' => "Obě zadaná hesla nejsou stejná!",
	'user:password:fail:tooshort' => "Heslo je příliš krátké!",
	'user:password:fail:incorrect_current_password' => 'Současné heslo není zadáno správně.',
	'user:changepassword:unknown_user' => 'Neplatný úživatel.',
	'user:changepassword:change_password_confirm' => 'Tímto změníte vaše heslo.',

	'user:set:language' => "Nastavení jazyka",
	'user:language:label' => "Jazyk",
	'user:language:success' => "Jazykové nastavení bylo aktualizováno.",
	'user:language:fail' => "Jazykové nastavení není možné uložit.",

	'user:username:notfound' => 'Uživatelské jméno %s nebylo nalezeno.',
	'user:username:help' => 'Please be aware that changing a username will change all dynamic user related links',

	'user:password:lost' => 'Ztracené heslo',
	'user:password:hash_missing' => 'Regretfully, we must ask you to reset your password. We have improved the security of passwords on the site, but were unable to migrate all accounts in the process.',
	'user:password:changereq:success' => 'Požadavek na nové heslo byl úspěšný, e-mail byl odeslán',
	'user:password:changereq:fail' => 'Nelze požádat o nové heslo.',

	'user:password:text' => 'O nové heslo požádáte zadáním uživatelského jména nebo e-mailové adresy do následujícího formuláře a následným stiskem tlačítka Požádat.',

	'user:persistent' => 'Pamatovat si mě',

	'walled_garden:home' => 'Home',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Spravovat',
	'menu:page:header:configure' => 'Nastavovat',
	'menu:page:header:develop' => 'Vyvíjet',
	'menu:page:header:information' => 'Information',
	'menu:page:header:default' => 'Ostatní',

	'admin:view_site' => 'Zobrazit stránky',
	'admin:loggedin' => 'Přihlášen jako %s',
	'admin:menu' => 'Nabídka',

	'admin:configuration:success' => "Nastavení bylo uloženo.",
	'admin:configuration:fail' => "Nastavení není možné uložit.",
	'admin:configuration:dataroot:relative_path' => 'Nemohu "%s" nastavit jako dataroot, protože to není absolutní cesta.',
	'admin:configuration:default_limit' => 'Počet položek na stránku musí být alespoň 1.',

	'admin:unknown_section' => 'Neplatná sekce správce.',

	'admin' => "Správa",
	'admin:description' => "Správcovský panel umožňuje řídit všechny součásti systému, od správy uživatelů k chování doplňků. Následují dostupné možnosti.",

	'admin:statistics' => 'Statistiky',
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Nejnovější úlohy cronu',
	'admin:cron:period' => 'Lhůta cronu',
	'admin:cron:friendly' => 'Naposledy spuštěno',
	'admin:cron:date' => 'Datum a čas',
	'admin:cron:msg' => 'Zpráva',
	'admin:cron:started' => 'Úlohy cronu pro "%s" spuštěny v %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
	'admin:cron:complete' => 'Úlohy cronu pro "%s" dokončeny v %s',

	'admin:appearance' => 'Vzhled',
	'admin:administer_utilities' => 'Nástroje',
	'admin:develop_utilities' => 'Nástroje',
	'admin:configure_utilities' => 'Nástroje',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Uživatelé",
	'admin:users:online' => 'Právě přihlášeni',
	'admin:users:newest' => 'Nejnovější',
	'admin:users:admins' => 'Správci',
	'admin:users:add' => 'Přidat nového uživatele',
	'admin:users:description' => "Tento správcovský panel umožňuje měnit nastavení členů vašich stránek. Následují dostupné možnosti.",
	'admin:users:adduser:label' => "Pro přidání nového uživatele klikněte sem...",
	'admin:users:opt:linktext' => "Nastavit uživatele...",
	'admin:users:opt:description' => "Umožňuje nastavit uživatele a informace o účtech. ",
	'admin:users:find' => 'Najít',
	'admin:users:unvalidated' => 'Unvalidated',
	'admin:users:unvalidated:no_results' => 'No unvalidated users.',
	'admin:users:unvalidated:registered' => 'Registered: %s',
	
	'admin:configure_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'Aktualizace',
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

	'admin:settings' => 'Nastavení',
	'admin:settings:basic' => 'Základní nastavení',
	'admin:settings:advanced' => 'Rozšířená nastavení',
	'admin:site:description' => "Tento správcovský panel umožňuje měnit celkové nastavení vašich stránek. Následují dostupné možnosti.",
	'admin:site:opt:linktext' => "Nastavit stránky...",
	'admin:settings:in_settings_file' => 'Toto nastavení je definováno v settings.php',

	'site_secret:current_strength' => 'Síla klíče',
	'site_secret:strength:weak' => "Slabý",
	'site_secret:strength_msg:weak' => "Důrazně vám doporučujeme přegenerování šifrovacího klíče stránek.",
	'site_secret:strength:moderate' => "Průměrný",
	'site_secret:strength_msg:moderate' => "Pro větší bezpečnosti stránek vám doporučujeme přegenerování šifrovacího klíče.",
	'site_secret:strength:strong' => "Silný",
	'site_secret:strength_msg:strong' => "Váš šifrovací klíč je dostatečně silný. Není důvod ho přegenerovat.",

	'admin:dashboard' => 'Nástěnka',
	'admin:widget:online_users' => 'Přihlášení uživatelé',
	'admin:widget:online_users:help' => 'Zobrazuje aktuálně připojené uživatele',
	'admin:widget:new_users' => 'Noví uživatelé',
	'admin:widget:new_users:help' => 'Zobrazuje nejnovější uživatele',
	'admin:widget:banned_users' => 'Uživatelé se zákazem přístupu',
	'admin:widget:banned_users:help' => 'Zobrazuje uživatele se zákazem přístupu',
	'admin:widget:content_stats' => 'Statistiky obsahu',
	'admin:widget:content_stats:help' => 'Zobrazuje statistiky obsahu vytvořeného vašimi uživateli',
	'admin:widget:cron_status' => 'Stav cronu',
	'admin:widget:cron_status:help' => 'Zobrazuje stav kdy byla úloha cronu naposledy skončena',
	'admin:statistics:numentities' => 'Content Statistics',
	'admin:statistics:numentities:type' => 'Content type',
	'admin:statistics:numentities:number' => 'Number',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Vítejte',
	'admin:widget:admin_welcome:help' => "Krátké uvedení do administrace projektu Elgg",
	'admin:widget:admin_welcome:intro' =>
'Vítejte v projektu Elgg! Právě se díváte na správcovskou nástěnku. Hodí se na sledování dějů na stránkách.',

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
	'admin:widget:admin_welcome:outro' => '<br />Nezapomeňte zkontrolovat zdroje dostupné přes odkazy v patičce. Děkujeme že používáte Elgg!',

	'admin:widget:control_panel' => 'Řídící panel',
	'admin:widget:control_panel:help' => "Poskytuje snadný přístup k často používaným ovládacím prvkům",

	'admin:cache:flush' => 'Pročistit vyrovnávací paměť',
	'admin:cache:flushed' => "Vyrovnávací paměť stránek byla pročištěna",

	'admin:footer:faq' => 'Správcovské FAQ',
	'admin:footer:manual' => 'Příručka správce',
	'admin:footer:community_forums' => 'Komunitní fórum projektu Elgg',
	'admin:footer:blog' => 'Blog projektu Elgg',

	'admin:plugins:category:all' => 'Všechny doplňky',
	'admin:plugins:category:active' => 'Aktivní doplňky',
	'admin:plugins:category:inactive' => 'Neaktivní doplňky',
	'admin:plugins:category:admin' => 'Správa',
	'admin:plugins:category:bundled' => 'Dodané',
	'admin:plugins:category:nonbundled' => 'Nedodané',
	'admin:plugins:category:content' => 'Obsah',
	'admin:plugins:category:development' => 'Vývoj',
	'admin:plugins:category:enhancement' => 'Rozšíření',
	'admin:plugins:category:api' => 'Služby/API',
	'admin:plugins:category:communication' => 'Komunikace',
	'admin:plugins:category:security' => 'Bezpečnost a nevyžádaný obsah',
	'admin:plugins:category:social' => 'Komunita',
	'admin:plugins:category:multimedia' => 'Multimédia',
	'admin:plugins:category:theme' => 'Motivy',
	'admin:plugins:category:widget' => 'Udělátka',
	'admin:plugins:category:utility' => 'Nástroje',

	'admin:plugins:markdown:unknown_plugin' => 'Neznámý doplněk.',
	'admin:plugins:markdown:unknown_file' => 'Neznámý soubor.',

	'admin:notices:delete_all' => 'Dismiss all %s notices',
	'admin:notices:could_not_delete' => 'Nemohu smazat oznámení.',
	'item:object:admin_notice' => 'Oznámení správce',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => 'Volby správce',

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

	'plugins:disabled' => 'Doplňky nebyly načteny protože ve složce mod je soubor pojmenovaný "disabled".',
	'plugins:settings:save:ok' => "Nastavení pro doplněk %s bylo úspěšně uloženo.",
	'plugins:settings:save:fail' => "Při ukládání nastavení pro doplněk %s se vyskytl se problém.",
	'plugins:usersettings:save:ok' => "Uživatelské nastavení pro doplněk %s bylo úspěšně uloženo.",
	'plugins:usersettings:save:fail' => "Při ukládání uživatelského nastavení pro doplněk %s se vyskytl problém.",
	'item:object:plugin' => 'Doplňky',
	'collection:object:plugin' => 'Plugins',

	'admin:plugins' => "Doplňky",
	'admin:plugins:activate_all' => 'Aktivovat všechny',
	'admin:plugins:deactivate_all' => 'Deaktivovat všechny',
	'admin:plugins:activate' => 'Aktivovat',
	'admin:plugins:deactivate' => 'Deaktivovat',
	'admin:plugins:description' => "Tento správcovský panel umožňuje ovládat a nastavovat nástroje nainstalované na vašich stránkách.",
	'admin:plugins:opt:linktext' => "Nastavit nástroje...",
	'admin:plugins:opt:description' => "Umožňuje nastavit nainstalované nástroje.",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Jméno",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorie',
	'admin:plugins:label:licence' => "Licence",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Informace",
	'admin:plugins:label:files' => "Soubory",
	'admin:plugins:label:resources' => "Zdroje",
	'admin:plugins:label:screenshots' => "Snímky obrazovky",
	'admin:plugins:label:repository' => "Kód",
	'admin:plugins:label:bugtracker' => "Nahlásit problém",
	'admin:plugins:label:donate' => "Podpořit darem",
	'admin:plugins:label:moreinfo' => 'více informací',
	'admin:plugins:label:version' => 'Verze',
	'admin:plugins:label:location' => 'Umístění',
	'admin:plugins:label:contributors' => 'Přispěvatelé',
	'admin:plugins:label:contributors:name' => 'Jméno',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Webové stránky',
	'admin:plugins:label:contributors:username' => 'Komunitní uživatelské jméno',
	'admin:plugins:label:contributors:description' => 'Popis',
	'admin:plugins:label:dependencies' => 'Závislosti',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'Tento doplněk má nesplněné závislosti a nemůže být aktivován. Zkontrolujte závislosti pod "více informací".',
	'admin:plugins:warning:invalid' => 'Tento doplněk je chybný: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Podívejte se do <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">dokumentace projektu Elgg</a> na rady s odstraňováním problémů.',
	'admin:plugins:cannot_activate' => 'nemohu aktivovat',
	'admin:plugins:cannot_deactivate' => 'nemohu deaktivovat',
	'admin:plugins:already:active' => 'Vybrané doplňky (či doplněk) jsou již aktivní.',
	'admin:plugins:already:inactive' => 'Vybrané doplňky (či doplněk) jsou již neaktivní.',

	'admin:plugins:set_priority:yes' => "Změněno pořadí %s.",
	'admin:plugins:set_priority:no' => "Nemohu změnit pořadí %s.",
	'admin:plugins:set_priority:no_with_msg' => "Nemohu změnit pořadí %s. Chyba: %s",
	'admin:plugins:deactivate:yes' => "Deaktivováno %s.",
	'admin:plugins:deactivate:no' => "Nemohu deaktivovat %s.",
	'admin:plugins:deactivate:no_with_msg' => "Nemohu deaktivovat %s. Chyba: %s",
	'admin:plugins:activate:yes' => "Aktivován %s.",
	'admin:plugins:activate:no' => "Nemohu aktivovat %s.",
	'admin:plugins:activate:no_with_msg' => "Nemohu aktivovat %s. Chyba: %s",
	'admin:plugins:categories:all' => 'Všechny kategorie',
	'admin:plugins:plugin_website' => 'Webové stránky doplňku',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Verze %s',
	'admin:plugin_settings' => 'Nastavení doplňku',
	'admin:plugins:warning:unmet_dependencies_active' => 'Tento doplněk je aktivní ale nemá splněné závislosti. Můžete se dostat do problémů. Podívejte se na "více informací".',

	'admin:plugins:dependencies:type' => 'Typ',
	'admin:plugins:dependencies:name' => 'Jméno',
	'admin:plugins:dependencies:expected_value' => 'Očekávaná hodnota',
	'admin:plugins:dependencies:local_value' => 'Současná hodnota',
	'admin:plugins:dependencies:comment' => 'Komentář',

	'admin:statistics:description' => "Toto je přehled statistik vašich stánek. Pokud potřebujete podrobnější statistiky, je k dispozici profesionální správcovská součást.",
	'admin:statistics:opt:description' => "Zobrazí statistické informace o uživatelích a objektech na vašich stránkách.",
	'admin:statistics:opt:linktext' => "Zobrazit statistiky...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "Subjekty na stránkách",
	'admin:statistics:label:numusers' => "Počet uživatelů",
	'admin:statistics:label:numonline' => "Počet připojených uživatelů",
	'admin:statistics:label:onlineusers' => "Aktuálně připojení uživatelé",
	'admin:statistics:label:admins'=>"Správci",
	'admin:statistics:label:version' => "Verze Elgg",
	'admin:statistics:label:version:release' => "Vydání",
	'admin:statistics:label:version:version' => "Verze",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Show PHPInfo',
	'admin:server:label:web_server' => 'Webový server',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Umístění logu',
	'admin:server:label:php_version' => 'Verze PHP',
	'admin:server:label:php_ini' => 'Umístění PHP ini souboru',
	'admin:server:label:php_log' => 'PHP Log',
	'admin:server:label:mem_avail' => 'Dostupná paměť',
	'admin:server:label:mem_used' => 'Použitá paměť',
	'admin:server:error_log' => "Log webového serveru",
	'admin:server:label:post_max_size' => 'Maximální velikost POST',
	'admin:server:label:upload_max_filesize' => 'Maximální velikost nahrávání',
	'admin:server:warning:post_max_too_small' => '(Pozn: pro schopnost nahrávat tuto velikost musí být post_max_size  větší než tato hodnota)',
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
	
	'admin:user:label:search' => "Hledat uživatele:",
	'admin:user:label:searchbutton' => "Hledat",

	'admin:user:ban:no' => "Uživateli nemohu zakázat přístup",
	'admin:user:ban:yes' => "Přístup by uživateli zakázán.",
	'admin:user:self:ban:no' => "Nemůžete zakázat přístup sám sobě",
	'admin:user:unban:no' => "Uživateli nemohu zrušit zakázaný přístup",
	'admin:user:unban:yes' => "Uživateli byl zrušen zákaz přístupu.",
	'admin:user:delete:no' => "Nemohu smazat uživatele",
	'admin:user:delete:yes' => "Uživatel %s byl smazán",
	'admin:user:self:delete:no' => "Nemůžete smazat sám sebe",

	'admin:user:resetpassword:yes' => "Heslo obnoveno, oznámeno uživateli.",
	'admin:user:resetpassword:no' => "Heslo nemůže být obnoveno.",

	'admin:user:makeadmin:yes' => "Uživatel je nyní správce.",
	'admin:user:makeadmin:no' => "Z tohoto uživatele nemůžeme udělat správce.",

	'admin:user:removeadmin:yes' => "Uživatel nadále není správcem.",
	'admin:user:removeadmin:no' => "Tomuto uživateli nemůžeme odebrat správcovská práva.",
	'admin:user:self:removeadmin:no' => "Nemůžete odebrat správcovská práva sám sobě.",

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Nastavit položky hlavní nabídky',
	'admin:menu_items:description' => 'Vyberte jaké položky chcete zobrazovat jako zajímavé odkazy. Nepoužité položky budou přidány jako "Další" na konci výpisu.',
	'admin:menu_items:hide_toolbar_entries' => 'Odebrat odkazy z nabídky v liště s nástroji?',
	'admin:menu_items:saved' => 'Položky nabídky byly uloženy.',
	'admin:add_menu_item' => 'Přidat vlastní položku nabídky',
	'admin:add_menu_item:description' => 'Pro přidání vlastní položky do nabídky navigace vyplňte "Zobrazované jméno" a URL.',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Neznámý typ udělátka',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Upravte následující soubor robots.txt",
	'admin:robots.txt:plugins' => "Doplňky do souboru robots.txt přidávají následující záznamy",
	'admin:robots.txt:subdir' => "Nástroj robots.txt nebude fungovat protože Elgg je nainstalován v podadresáři.",
	'admin:robots.txt:physical' => "Nástroj robots.txt nebude fungovat protože existuje soubor robots.txt",

	'admin:maintenance_mode:default_message' => 'Stránky jsou vypnuté z důvodu údržby',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Režim údržby',
	'admin:maintenance_mode:message_label' => 'Zpráva zobrazená uživatelům když je zapnutý režim údržby.',
	'admin:maintenance_mode:saved' => 'Nastavení režimu údržby bylo uloženo.',
	'admin:maintenance_mode:indicator_menu_item' => 'Stránky jsou v režimu údržby.',
	'admin:login' => 'Přihlášení správce',

/**
 * User settings
 */

	'usersettings:description' => "Panel nastavení uživatelů umožňuje řídit veškeré osobní nastavení, od správy uživatelů po chování doplňků. ",

	'usersettings:statistics' => "Vaše statistiky",
	'usersettings:statistics:opt:description' => "Zobrazí statistické informace o uživatelích a objektech na vašich stránkách.",
	'usersettings:statistics:opt:linktext' => "Statistiky účtu",

	'usersettings:statistics:login_history' => "Historie přihlášení",
	'usersettings:statistics:login_history:date' => "Datum",
	'usersettings:statistics:login_history:ip' => "IP adresa",

	'usersettings:user' => "%s - nastavení",
	'usersettings:user:opt:description' => "Zde můžete měnit nastavení uživatele.",
	'usersettings:user:opt:linktext' => "Změnit nastavení",

	'usersettings:plugins' => "Nástroje",
	'usersettings:plugins:opt:description' => "Upravte nastavení (pokud je) ve vašich aktivních nástrojích.",
	'usersettings:plugins:opt:linktext' => "Nastavit nástroje",

	'usersettings:plugins:description' => "Tento panel umožňuje ovládat a měnit osobní nastavení pro nástroje nainstalované vaším správcem.",
	'usersettings:statistics:label:numentities' => "Váš obsah",

	'usersettings:statistics:yourdetails' => "Vaše detaily",
	'usersettings:statistics:label:name' => "Celé jméno",
	'usersettings:statistics:label:email' => "E-mail",
	'usersettings:statistics:label:membersince' => "Členem od",
	'usersettings:statistics:label:lastlogin' => "Poslední přihlášení",

/**
 * Activity river
 */

	'river:all' => 'Veškerá aktivita stránek',
	'river:mine' => 'Moje aktivita',
	'river:owner' => 'Aktivita uživatele %s',
	'river:friends' => 'Aktivita přátel',
	'river:select' => 'Zobrazená aktivita: %s',
	'river:comments:more' => '+%u dalších',
	'river:comments:all' => 'Zobrazit všech %u komentářů',
	'river:generic_comment' => 'přidal/a komentář k %s %s',

/**
 * Icons
 */

	'icon:size' => "Velikost ikony",
	'icon:size:topbar' => "Horní lišta",
	'icon:size:tiny' => "Mrňavá",
	'icon:size:small' => "Malá",
	'icon:size:medium' => "Střední",
	'icon:size:large' => "Velká",
	'icon:size:master' => "Obrovská",
	
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Uložit",
	'save_go' => "Save, and go to %s",
	'reset' => 'Vynulovat',
	'publish' => "Zveřejnit",
	'cancel' => "Zrušit",
	'saving' => "Ukládám...",
	'update' => "Aktualizovat",
	'preview' => "Náhled",
	'edit' => "Upravit",
	'delete' => "Smazat",
	'accept' => "Přijmout",
	'reject' => "Zavrhnout",
	'decline' => "Odmítnout",
	'approve' => "Schválit",
	'activate' => "Aktivovat",
	'deactivate' => "Deaktivovat",
	'disapprove' => "Nesouhlasit",
	'revoke' => "Odebrat",
	'load' => "Načíst",
	'upload' => "Nahrát",
	'download' => "Stáhnout",
	'ban' => "Zakázat přístup",
	'unban' => "Zrušit zákaz přístupu",
	'banned' => "Zakázán přístup",
	'enable' => "Povolit",
	'disable' => "Zakázat",
	'request' => "Požádat",
	'complete' => "Hotovo",
	'open' => 'Otevřít',
	'close' => 'Zavřít',
	'hide' => 'Skrýt',
	'show' => 'Zobrazit',
	'reply' => "Odpovědět",
	'more' => 'Více',
	'more_info' => 'Více informací',
	'comments' => 'Komentáře',
	'import' => 'Import',
	'export' => 'Export',
	'untitled' => 'Nepojmenovaný',
	'help' => 'Pomoc',
	'send' => 'Odeslat',
	'post' => 'Odeslat',
	'submit' => 'Odeslat',
	'comment' => 'Přidat komentář',
	'upgrade' => 'Aktualizace',
	'sort' => 'Třídit',
	'filter' => 'Filtrovat',
	'new' => 'Nový',
	'add' => 'Přidat',
	'create' => 'Vytvořit',
	'remove' => 'Odstranit',
	'revert' => 'Vrátit',
	'validate' => 'Validate',
	'read_more' => 'Read more',

	'site' => 'Stránky',
	'activity' => 'Aktivita',
	'members' => 'Členové',
	'menu' => 'Nabídka',

	'up' => 'Nahoru',
	'down' => 'Dolů',
	'top' => 'Na začátek',
	'bottom' => 'Na konec',
	'right' => 'Vpravo',
	'left' => 'Vlevo',
	'back' => 'Zpět',

	'invite' => "Pozvat",

	'resetpassword' => "Obnovit heslo",
	'changepassword' => "Změnit heslo",
	'makeadmin' => "Povýšit na správce",
	'removeadmin' => "Odebrat práva správce",

	'option:yes' => "Ano",
	'option:no' => "Ne",

	'unknown' => 'Neznámý',
	'never' => 'Nikdy',

	'active' => 'Aktivní',
	'total' => 'Celkem',

	'ok' => 'OK',
	'any' => 'Jakákoliv',
	'error' => 'Chyba',

	'other' => 'Ostatní',
	'options' => 'Možnosti',
	'advanced' => 'Rozšířený',

	'learnmore' => "Klikněte sem a dozvíte se více.",
	'unknown_error' => 'Neznámá chyba',

	'content' => "obsah",
	'content:latest' => 'Nejnovější aktivita',
	'content:latest:blurb' => 'Veškerý nejnovější obsah těchto stránek případně zobrazíte kliknutím sem.',

	'link:text' => 'zobrazit odkaz',

/**
 * Generic questions
 */

	'question:areyousure' => 'Jste si jistý/á?',

/**
 * Status
 */

	'status' => 'Stav',
	'status:unsaved_draft' => 'Neuložený koncept',
	'status:draft' => 'Koncept',
	'status:unpublished' => 'Nepublikováno',
	'status:published' => 'Publikováno',
	'status:featured' => 'Zajímavé',
	'status:open' => 'Otevřený',
	'status:closed' => 'Zavřený',

/**
 * Generic sorts
 */

	'sort:newest' => 'Nejnovější',
	'sort:popular' => 'Oblíbené',
	'sort:alpha' => 'Abecedně',
	'sort:priority' => 'Priorita',

/**
 * Generic data words
 */

	'title' => "Název",
	'description' => "Popis",
	'tags' => "Štítky",
	'all' => "Vše",
	'mine' => "Moje",

	'by' => 'od',
	'none' => 'žádný',

	'annotations' => "Poznámky",
	'relationships' => "Relace",
	'metadata' => "Metadata",
	'tagcloud' => "Zásobník štítků",

	'on' => 'Zapnuto',
	'off' => 'Vypnuto',

/**
 * Entity actions
 */

	'edit:this' => 'Upravit',
	'delete:this' => 'Smazat',
	'comment:this' => 'Přidat komentář',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Jste si jistý/á, že chcete smazat tuto položku?",
	'deleteconfirm:plural' => "Jste si jistý/á, že chcete smazat tyto položky?",
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'Uživatelský účet byl vytvořen',
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

	'systemmessages:dismiss' => "kliknutím potvrdíte",


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

	'importsuccess' => "Import dat byl úspěšný",
	'importfail' => "Import OpenDD dat selhal.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "právě teď",
	'friendlytime:minutes' => "před %s minutami",
	'friendlytime:minutes:singular' => "před minutou",
	'friendlytime:hours' => "před %s hodinami",
	'friendlytime:hours:singular' => "před hodinou",
	'friendlytime:days' => "před %s dny",
	'friendlytime:days:singular' => "včera",
	'friendlytime:date_format' => 'j. F Y G:i',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "za %s minut",
	'friendlytime:future:minutes:singular' => "za minutu",
	'friendlytime:future:hours' => "za %s hodin",
	'friendlytime:future:hours:singular' => "za hodinu",
	'friendlytime:future:days' => "za %s dní",
	'friendlytime:future:days:singular' => "včera",

	'date:month:01' => 'leden %s',
	'date:month:02' => 'únor %s',
	'date:month:03' => 'březen %s',
	'date:month:04' => 'duben %s',
	'date:month:05' => 'květen %s',
	'date:month:06' => 'červen %s',
	'date:month:07' => 'červenec %s',
	'date:month:08' => 'srpen %s',
	'date:month:09' => 'září %s',
	'date:month:10' => 'říjen %s',
	'date:month:11' => 'listopad %s',
	'date:month:12' => 'prosinec %s',

	'date:month:short:01' => '%s. led',
	'date:month:short:02' => '%s. úno',
	'date:month:short:03' => '%s. bře',
	'date:month:short:04' => '%s. dub',
	'date:month:short:05' => '%s. kvě',
	'date:month:short:06' => '%s. čvn',
	'date:month:short:07' => '%s. čvc',
	'date:month:short:08' => '%s. srp',
	'date:month:short:09' => '%s. zář',
	'date:month:short:10' => '%s. říj',
	'date:month:short:11' => '%s. lis',
	'date:month:short:12' => '%s. pro',

	'date:weekday:0' => 'neděle',
	'date:weekday:1' => 'pondělí',
	'date:weekday:2' => 'úterý',
	'date:weekday:3' => 'středa',
	'date:weekday:4' => 'čtvrtek',
	'date:weekday:5' => 'pátek',
	'date:weekday:6' => 'sobota',

	'date:weekday:short:0' => 'ne',
	'date:weekday:short:1' => 'po',
	'date:weekday:short:2' => 'út',
	'date:weekday:short:3' => 'st',
	'date:weekday:short:4' => 'čt',
	'date:weekday:short:5' => 'pá',
	'date:weekday:short:6' => 'so',

	'interval:minute' => 'Každou minutu',
	'interval:fiveminute' => 'Každých pět minut',
	'interval:fifteenmin' => 'Každou čtvrthodinu',
	'interval:halfhour' => 'Každou půlhodinu',
	'interval:hourly' => 'Každou hodinu',
	'interval:daily' => 'Denně',
	'interval:weekly' => 'Týdně',
	'interval:monthly' => 'Měsíčně',
	'interval:yearly' => 'Ročně',

/**
 * System settings
 */

	'installation:sitename' => "Jméno vašich stránek:",
	'installation:sitedescription' => "Krátký popis vašich stránek (volitelné):",
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
	'installation:wwwroot' => "URL stránek:",
	'installation:path' => "Úplná cesta k instalaci systému Elgg:",
	'installation:dataroot' => "Úplná cesta k datovému adresáři:",
	'installation:dataroot:warning' => "Tento adresář si musíte vyrobit ručně. Měl by být v jiném adresáři než je nainstalován systém Elgg.",
	'installation:sitepermissions' => "Výchozí nastavení přístupových práv:",
	'installation:language' => "Výchozí jazyk vašich stránek:",
	'installation:debug' => "Ovládá množství informací zapsaných do logu serveru.",
	'installation:debug:label' => "Upovídanost logů:",
	'installation:debug:none' => 'Vypnout zápisy do logu (doporučeno)',
	'installation:debug:error' => 'Zapisovat pouze kritické chyby',
	'installation:debug:warning' => 'Zapisovat chyby a varování',
	'installation:debug:notice' => 'Zapisovat všechny chyby, varování a upozornění',
	'installation:debug:info' => 'Zapisovat všechno',

	// Walled Garden support
	'installation:registration:description' => 'Registrace uživatelů je standardně povolena. Vypněte pokud nechcete, aby se uživatelé registrovali sami.',
	'installation:registration:label' => 'Povolit nové registrace uživatelů',
	'installation:walled_garden:description' => 'Povolením této volby zamezíte nečlenům prohlížení stránek s výjimkou součástí označených jako veřejné (např. přihlášení a registrace).',
	'installation:walled_garden:label' => 'Omezit stánky pouze pro členy',

	'installation:view' => "Zadejte pohled, který bude použit jako výchozí na stránkách nebo ponechte prázdné pro výchozí pohled (jste-li na pochybách, nechte jak je):",

	'installation:siteemail' => "E-mailová adresa stránek (použita při rozesílání systémových e-mailů):",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Výchozí počet položek na stránce",

	'admin:site:access:warning' => "Toto nastavení pravidel sdílení je doporučeno uživatelům při vytváření nového obsahu. Jeho změna nezpůsobí změnu pravidel obsahu.",
	'installation:allow_user_default_access:description' => "Povolením této volby umožníte uživatelům nastavit jejich vlastní pravidla sdílení, která mají přednost před systémovým doporučením.",
	'installation:allow_user_default_access:label' => "Povolit uživatelům jejich výchozí úroveň sdílení",

	'installation:simplecache:description' => "Technologie simple cache zvyšuje výkon ukládáním statického obsahu do mezipaměti a to včetně některých CSS a JavaScript souborů.",
	'installation:simplecache:label' => "Používat simple chache (doporučeno)",

	'installation:cache_symlink:description' => "Symbolický odkaz na adresář simple cache umožňuje serveru vynechat zpracování při vydávání statických pohledů, což výrazně zvyšuje výkon a snižuje zátěž serveru.",
	'installation:cache_symlink:label' => "Používat symbolický odkaz na adresář simple cache (doporučeno)",
	'installation:cache_symlink:warning' => "Symbolický odkaz byl vytvořen. Pokud z nějakého důvodu chcete odkaz odebrat, smažte adresář symbolického odkazu z vašeho serveru.",
	'installation:cache_symlink:paths' => 'Správně nastavený symbolický odkaz musí odkazovat z <i>%s</i> na <i>%s</i>',
	'installation:cache_symlink:error' => "Díky nastavení vašeho serveru není možno vyrobit symbolický odkaz automaticky. Nahlédněte prosím do dokumentace a zřiďte symbolický odkaz ručně.",

	'installation:minify:description' => "Simple cache také může zvýšit výkon kompresí JavaScript a CSS souborů (vyžaduje zapnutou simple cache).",
	'installation:minify_js:label' => "Komprimovat JavaScript (doporučeno)",
	'installation:minify_css:label' => "Komprimovat CSS (doporučeno)",

	'installation:htaccess:needs_upgrade' => "Musíte aktualizovat soubor .htaccess tak, aby cesta byla vložena do GET parametru __elgg_uri (pro inspiraci můžete použít install/config/htaccess.dist).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg se nemůže připojit sám k sobě aby ověřil pravidla přepisu. Zkontrolujte že curl pracuje a nejsou žádná omezení IP bránící připojení z localhostu.",

	'installation:systemcache:description' => "Systémová mezipaměť snižuje čas načítání Elgg ukládáním dat do souborů.",
	'installation:systemcache:label' => "Používat systémovou mezipaněť (doporučeno)",

	'admin:legend:system' => 'Systém',
	'admin:legend:caching' => 'Vyrovnávací paměť',
	'admin:legend:content_access' => 'Přístup k obsahu',
	'admin:legend:site_access' => 'Přístup ke stránkám',
	'admin:legend:debug' => 'Ladění a výpisy',
	
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	
	'upgrading' => 'Aktualizace...',
	'upgrade:core' => 'Vaše instalace Elgg byla aktualizována.',
	'upgrade:unlock' => 'Odemknout aktualizaci',
	'upgrade:unlock:confirm' => "Databáze je zamčena jinou aktualizací. Spuštění několika aktualizací naráz je nebezpečné. Měl/a byste pokračovat pouze pokud víte, že neprobíhá jiná aktualizace. Odemknout?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Nemohu aktualizovat, probíhá jiná aktualizace. Pro odstranění zámku aktualizace navštivte sekci správce.",
	'upgrade:unlock:success' => "Aktualizace odemčena úspěšně.",
	'upgrade:unable_to_upgrade' => 'Nelze aktualizovat.',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (dříve OAuth Lib) byla během aktualizace deaktivována. Pokud ji požadujete, aktivujte ji prosím ručně.',
	'upgrade:site_secret_warning:moderate' => "Doporučujeme vám zvýšit zabezpečení vašich stránek přegenerováním šifrovacího klíče. Viz. Nastavovat &gt; Nastavení &gt; Rozšířená nastavení",
	'upgrade:site_secret_warning:weak' => "Důrazně vám doporučujeme zvýšit zabezpečení vašich stránek přegenerováním šifrovacího klíče. Viz. Nastavovat &gt; Nastavení &gt; Rozšířená nastavení",

	'deprecated:function' => '%s() bylo nahrazeno %s()',

	'admin:pending_upgrades' => 'Stránky mají nevyřízené aktualizace, které požadují váš bezodkladný zásah.',
	'admin:view_upgrades' => 'Zobrazit nevyřízené aktualizace',
	'item:object:elgg_upgrade' => 'Aktualizace stránek',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Vaše instalace je aktuální!',

	'upgrade:item_count' => 'Je zde <b>%s</b> položek, které vyžadují aktualizaci.',
	'upgrade:warning' => '<b>Varování:</b> na rozsáhlých stránkách může tato aktualizace trvat velice dlouho!',
	'upgrade:success_count' => 'Aktualizováno:',
	'upgrade:error_count' => 'Chyby:',
	'upgrade:finished' => 'Aktualizace dokončena',
	'upgrade:finished_with_errors' => '<p>Aktualizace dokončena s chybami. Obnovte stránku a zkuste aktualizaci spustit znovu.</p></p><br />Pokud se bude chyba opakovat, zkontrolujte chybový log serveru, ten může obsahovat důvod. Pomoc s opravou chyby můžete najít ve <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">skupině technické podpory</a> pro komunitu Elgg.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Zarovnat GUID sloupce v databázi',
	
/**
 * Welcome
 */

	'welcome' => "Vítejte",
	'welcome:user' => 'Vítejte %s',

/**
 * Emails
 */

	'email:from' => 'Od',
	'email:to' => 'Komu',
	'email:subject' => 'Předmět',
	'email:body' => 'Text',

	'email:settings' => "Nastavení e-mailu",
	'email:address:label' => "E-mailová adresa",
	'email:address:password' => "Password",
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "Nová e-mailová adresa byla uložena. Je požadováno ověření.",
	'email:save:fail' => "Nová e-mailová adresa nemohla být uložena.",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s z vás udělal/a svého přítele!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "Heslo bylo změněno!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Heslo obnoveno!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "Požadavek na změnu hesla.",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

%s",

/**
 * user default access
 */

	'default_access:settings' => "Vaše výchozí úroveň sdílení",
	'default_access:label' => "Výchozí úroveň sdílení",
	'user:default_access:success' => "Výchozí úroveň sdílení byla uložena.",
	'user:default_access:failure' => "Výchozí úroveň sdílení není možné uložit.",

/**
 * Comments
 */

	'comments:count' => "%s komentářů",
	'item:object:comment' => 'Komentáře',
	'collection:object:comment' => 'Comments',

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "Přidat komentář",
	'generic_comments:edit' => "Upravit komentář",
	'generic_comments:post' => "Odeslat komentář",
	'generic_comments:text' => "Komentář",
	'generic_comments:latest' => "Nejnovější komentáře",
	'generic_comment:posted' => "Váš komentář byl úspěšně odeslán.",
	'generic_comment:updated' => "Váš komentář byl úspěšně aktualizován.",
	'entity:delete:object:comment:success' => "The comment was successfully deleted.",
	'generic_comment:blank' => "Omlouváme se, ale aby bylo možné komentář uložit, musíte do něj nejdříve něco napsat.",
	'generic_comment:notfound' => "Bohužel jsme nemohli najít požadovaný komentář.",
	'generic_comment:notfound_fallback' => "Bohužel jsme nemohli najít požadovaný komentář, ale přesměrovali jsme vás na místo, kde byl zanechán.",
	'generic_comment:failure' => "Při ukládání komentáře nastala nečekaná chyba.",
	'generic_comment:none' => 'Žádné komentáře',
	'generic_comment:title' => 'Komentováno uživatelem %s',
	'generic_comment:on' => '%s na %s',
	'generic_comments:latest:posted' => 'odeslal/a',

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

	'byline' => 'Od %s',
	'byline:ingroup' => 've skupině %s',
	'entity:default:missingsupport:popup' => 'Tato položka nemůže být správně zobrazena. Možná požaduje podporu doplňku, který již není nainstalován.',

	'entity:delete:item' => 'Položka',
	'entity:delete:item_not_found' => 'Položka nebyla nalezena.',
	'entity:delete:permission_denied' => 'Nemáte oprávnění smazat tuto položku.',
	'entity:delete:success' => '%s bylo odstraněno.',
	'entity:delete:fail' => '%s nemůže být odstraněno.',

	'entity:can_delete:invaliduser' => 'Nemohu zkontrolovat canDelete() pro user_guid [%s] neboť tento uživatel neexistuje.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Formulář neobsahuje pole __token nebo ',
	'actiongatekeeper:tokeninvalid' => "Platnost používané stránky vypršela. Zkuste to prosím znovu.",
	'actiongatekeeper:timeerror' => 'Platnost používané stránky vypršela. Obnovte ji prosím a zkuste to znovu.',
	'actiongatekeeper:pluginprevents' => 'Váš formulář bohužel nemohl být z neznámého důvodu odeslán.',
	'actiongatekeeper:uploadexceeded' => 'Velikost nahraných souboru(ů) překračuje limit nastavený správcem těchto stránek.',
	'actiongatekeeper:crosssitelogin' => "Přihlašování z jiné domény bohužel není povoleno. Zkuste to znovu.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'a, pak, ale, ona, jeho, jí, její, jemu, ne, o, teď, proto, nicméně, ještě, rovněž, jinak, tudíž, naopak, spíš, následkem, navíc, přesto, místo toho, zatím, tedy, toto, zdá se, co, koho, komu, jehož, kdokoli, komukoliv',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Štítky',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Chyba spojení s %s. Můžete očekávat problémy při ukládání obsahu. Obnovte prosím tuto stránku.',
	'js:security:token_refreshed' => 'Spojení s %s obnoveno!',
	'js:lightbox:current' => "obrázek %s uživatele %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Postaveno na projektu Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
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
	"cs" => "Čeština",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
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
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
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
	"kn" => "Kannada",
	"ko" => "Korean",
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
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portuguese (Brazil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romanian (Romania)",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovenština",
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
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => 'Vyžadováno',

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
