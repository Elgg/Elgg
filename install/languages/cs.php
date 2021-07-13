<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'install:title' => 'Instalace projektu Elgg',
	'install:welcome' => 'Vítejte',
	'install:requirements' => 'Test požadavků',
	'install:database' => 'Instalace databáze',
	'install:settings' => 'Nastavení stránek',
	'install:admin' => 'Vytvoření účtu správce',
	'install:complete' => 'Hotovo',

	'install:next' => 'Další',
	'install:refresh' => 'Obnovit',
	
	'install:requirements:instructions:success' => "Váš server prošel kontrolou požadavků.",
	'install:requirements:instructions:failure' => "Váš server neprošel kontrolou požadavků. Až opravíte následující problémy, obnovte tuto stránku. Pokud potřebujete další pomoc, navštivte odkazy na řešení problémů, které se nacházejí na konci této stránky.",
	'install:requirements:instructions:warning' => "Váš server prošel kontrolou požadavků, ale vyskytlo se minimálně jedno varování. Doporučujeme vám navštívit stránku s popisem problémů při instalaci.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Webový server',
	'install:require:settings' => 'Soubor s nastavením',
	'install:require:database' => 'Databáze',

	'install:check:php:version' => 'Elgg požaduje PHP %s nebo vyšší. Tento server používá verzi %s.',
	'install:check:php:extension' => 'Elgg požaduje rozšíření PHP %s.',
	'install:check:php:extension:recommend' => 'Je doporučováno nainstalované rozšíření PHP %s.',
	'install:check:php:open_basedir' => 'Direktiva PHP open_basedir může zabránit projektu Elgg zapisovat soubory do jeho datového adresáře.',
	'install:check:php:safe_mode' => 'Běh PHP v bezpečném režimu /safe mode/ není doporučené a může způsobovat problémy.',
	'install:check:php:arg_separator' => 'Aby Elgg pracoval, musí být arg_separator.output nastaven na & a hodnota na vašem serveru je %s',
	'install:check:php:register_globals' => 'Register globals musí být vypnuto.',
	'install:check:php:session.auto_start' => "Aby projekt Elgg pracoval, musí být session.auto_start vypnuté. Buď změňte nastavení vašeho serveru nebo tuto direktivu přidejte do souboru .htaccess projektu Elgg.",
	'install:check:readsettings' => 'V instalačním adresáři je soubor s nastavením, ale webový server ho nemůže přečíst. Buď soubor smažte nebo mu změňte práva pro čtení.',

	'install:check:php:success' => "PHP na vašem serveru vyhovuje všem požadavkům projektu Elgg.",
	'install:check:rewrite:success' => 'Test pravidel pro přepis byl úspěšný.',
	'install:check:database' => 'Požadavky na databázi budou zkontrolovány ve chvíli jejího načtení.',

	'install:database:instructions' => "Pokud jste dosud pro Elgg nevytvořil/a databázi, učiňte tak nyní. Potom zadejte následující hodnoty pro její inicializaci.",
	'install:database:error' => 'Při vytváření databáze se vyskytl problém a instalace nemůže pokračovat. Zkontrolujte předcházející zprávu a opravte všechny problémy. Pokud potřebujete další pomoc, navštivte odkaz na řešení problémů na konci stánky nebo se obraťte na komunitní fórum projektu Elgg.',

	'install:database:label:dbuser' =>  'Uživatel databáze',
	'install:database:label:dbpassword' => 'Heslo databáze',
	'install:database:label:dbname' => 'Jméno databáze',
	'install:database:label:dbhost' => 'Hostitel databáze',
	'install:database:label:dbprefix' => 'Předpona tabulek databáze',
	'install:database:label:timezone' => "Časová zóna",

	'install:database:help:dbuser' => 'Uživatel s plnými právy k MySQL databázi, kterou jste pro Elgg vytvořil/a',
	'install:database:help:dbpassword' => 'Heslo výše uvedeného uživatele',
	'install:database:help:dbname' => 'Jméno databáze projektu Elgg',
	'install:database:help:dbhost' => 'Jméno hostitele /hostname/ MySQL serveru (obvykle localhost)',
	'install:database:help:dbprefix' => "Předpona přidaná ke všem tabulkám projektu Elgg (obvykle elgg_)",
	'install:database:help:timezone' => "Výchozí časová zóna ve které budou stránky provozovány",

	'install:settings:label:sitename' => 'Jméno stránek',
	'install:settings:label:siteemail' => 'E-mailová adresa stránek',
	'install:settings:label:path' => 'Adresář s instalací projektu Elgg',
	'install:settings:label:language' => 'Jazyk stránek',
	'install:settings:label:siteaccess' => 'Výchozí úroveň sdílení pro stránky',
	'install:label:combo:dataroot' => 'Elgg vytvoří datový adresář',

	'install:settings:help:sitename' => 'Jméno vašich nových stránek',
	'install:settings:help:siteemail' => 'E-mailová adresa použitá projektem Elgg pro komunikaci s uživateli',
	'install:settings:help:path' => 'Adresář s kódem projektu Elgg (obvykle je instalátorem odvozen správně)',
	'install:settings:help:dataroot:apache' => 'Můžete adresář nechat vyrobit instalátorem nebo zadat adresář, který jste již vytvořil/a pro ukládání souborů uživatelů (po kliknutí na Další budou na tomto adresáři ověřena práva)',
	'install:settings:help:language' => 'Výchozí jazyk stránek',
	'install:settings:help:siteaccess' => 'Výchozí úroveň sdílení pro obsah vytvořený uživateli',

	'install:admin:instructions' => "Nyní nastal okamžik k vytvoření účtu správce.",

	'install:admin:label:displayname' => 'Zobrazené jméno',
	'install:admin:label:email' => 'E-mailová adresa',
	'install:admin:label:username' => 'Uživatelské jméno',
	'install:admin:label:password1' => 'Heslo',
	'install:admin:label:password2' => 'Heslo znovu',

	'install:admin:help:displayname' => 'Jméno tohoto účtu, které je zobrazeno na stránkách',
	'install:admin:help:username' => 'Uživatelské jméno používané při přihlašování',
	'install:admin:help:password1' => "Heslo musí mít alespoň %u znaků",
	'install:admin:help:password2' => 'Zadejte heslo znovu pro potvrzení ',

	'install:admin:password:mismatch' => 'Hesla se musí shodovat.',
	'install:admin:password:empty' => 'Heslo nemůže být prázdné.',
	'install:admin:password:tooshort' => 'Vaše heslo je příliš krátké',
	'install:admin:cannot_create' => 'Není možné vytvořit účet správce.',

	'install:complete:instructions' => 'Vaše stránky jsou nyní připraveny k použití. Klikem na následující tlačítko na ně přejdete.',
	'install:complete:gotosite' => 'Přejít na stránky',

	'InstallationException:CannotLoadSettings' => 'Elgg nemůže načíst soubor s nastavením. Buď neexistuje, nebo je problém s přístupovými právy.',

	'install:success:database' => 'Databáze byla nainstalována.',
	'install:success:settings' => 'Nastavení stránek bylo uloženo.',
	'install:success:admin' => 'Účet správce byl vytvořen.',

	'install:error:htaccess' => 'Není možné vytvořit soubor .htaccess',
	'install:error:settings' => 'Není možné vytvořit soubor s konfigurací',
	'install:error:databasesettings' => 'S těmito údaji se není možné přihlásit to databáze.',
	'install:error:database_prefix' => 'Předpona databáze obsahuje neplatný znak',
	'install:error:nodatabase' => 'Není možné použít databázi %s. Pravděpodobně neexistuje.',
	'install:error:cannotloadtables' => 'Nemohu načíst tabulky databáze',
	'install:error:tables_exist' => 'V databázi již existují tabulky projektu Elgg. Můžete je buď smazat, nebo instalátor spustit znovu, aby se je pokusil použít. Pro druhou možnost v poli s adresou odstraňte z URL \'?step=database\' a stiskněte Enter.',
	'install:error:readsettingsphp' => 'Není možné číst /elgg-config/settings.example.php',
	'install:error:writesettingphp' => 'Není možné zapsat /elgg-config/settings.php',
	'install:error:requiredfield' => 'pole %s je povinné',
	'install:error:relative_path' => '"%s" nevypadá jako absolutní cesta k datovému adresáři',
	'install:error:datadirectoryexists' => 'Datový adresář %s neexistuje.',
	'install:error:writedatadirectory' => 'Webový server nemůže zapisovat do datového adresáře %s.',
	'install:error:locationdatadirectory' => 'Z důvodu bezpečnosti musí být datový adresář %s mimo cestu instalace.',
	'install:error:emailaddress' => '%s není platná e-mailová adresa',
	'install:error:createsite' => 'Není možné vytvořit stránky.',
	'install:error:savesitesettings' => 'Není možné uložit nastavení strřánek',
	'install:error:loadadmin' => 'Není možné načíst účet správce.',
	'install:error:adminaccess' => 'Není možné dát novému účtu správcovská práva.',
	'install:error:adminlogin' => 'Není možné automaticky přihlásit nový správcovský účet.',
	'install:error:rewrite:apache' => 'Myslíme si, že na vašem serveru je spuštěn webový server Apache.',
	'install:error:rewrite:nginx' => 'Myslíme si, že na vašem serveru je spuštěn webový server Nginx.',
	'install:error:rewrite:lighttpd' => 'Myslíme si, že na vašem serveru je spuštěn webový server Lighttpd.',
	'install:error:rewrite:iis' => 'Myslíme si, že na vašem serveru je spuštěn webový server IIS.',
	'install:error:rewrite:htaccess:write_permission' => 'Webový server nemá práva vytvořit v adresáři projektu Elgg soubor .htaccess. Musíte ručně zkopírovat install/config/htaccess.dist do .htaccess, případně změnit práva na příslušném adresáři.',
	'install:error:rewrite:htaccess:read_permission' => 'V adresáři projektu Elgg je soubor .htaccess, ale webový server nemá práva na jeho čtení.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'V adresáři projektu Elgg je soubor .htaccess, který nebyl vytvořen instalátorem. Odstraňte ho prosím.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Zdá se, že v adresáři projektu Elgg je starý soubor .htaccess, který neobsahuje pravidla přepisu pro otestování webového serveru.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Během vytváření souboru .htaccess se vyskytla neznámá chyba. Musíte ručně zkopírovat install/config/htaccess.dist do .htaccess v adresáři projektu Elgg.',
	'install:error:rewrite:altserver' => 'Nezdařil se test pravidel přepisu. Musíte nastavit váš webový server s pravidly přepisu projektu Elgg a zkusit to znovu.',
	'install:error:rewrite:unknown' => 'Uf, nebyli jsme schopni zjistit jaký webový server používáte a test pravidel přepisu selhal. Nemůžeme nabídnou žádnou určitou radu. Zkuste prosím odkaz na řešení problémů.',
	'install:warning:rewrite:unknown' => 'Váš server neposkytuje automatické testování pravidel přepisu a váš prohlížeč je nedokáže otestovat pomocí JavaScriptu. Můžete pokračovat v instalaci, ale na stránkách mohou nastat problémy. Pravidla je možné otestovat ručně kliknutím na tento odkaz: <a href="%s" target="_blank">test</a>. Pokud pravidla fungují, uvidíte slovo "success" /úspěch/.',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Došlo k neodstranitelné chybě, která byla zaznamenána. Pokud jste správce těchto stránek, zkontrolujte soubor s nastavením, jinak se obraťte na správce s následujícími údaji:',
	'DatabaseException:WrongCredentials' => "Projekt Elgg se zadanými údaji nemůže přihlásit to databáze. Zkontrolujte soubor s nastavením.",
);
