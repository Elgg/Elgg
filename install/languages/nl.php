<?php
return array(
	'install:title' => 'Elgg installatie',
	'install:welcome' => 'Welkom',
	'install:requirements' => 'Nakijken van de vereisten',
	'install:database' => 'Database-installatie',
	'install:settings' => 'Configureer site',
	'install:admin' => 'Maak een adminaccount aan',
	'install:complete' => 'Afgerond',

	'install:next' => 'Volgende',
	'install:refresh' => 'Vernieuw',

	'install:welcome:instructions' => "Het installeren van Elgg gebeurt in 6 eenvoudige stappen. Het lezen van deze pagina is stap 1!

Indien je het nog niet hebt gedaan, lees de Elgg installatie instructies (of bekijk de link onderaan de pagina).

Als je klaar bent on verder te gaan, klik op de Volgende knop.",
	'install:requirements:instructions:success' => "Jouw server voldoet aan de systeemeisen!",
	'install:requirements:instructions:failure' => "Jouw server heeft de systeemeisentest niet doorstaan. Vernieuw de pagina nadat je onderstaande problemen hebt opgelost. Controleer de links met betrekking tot foutopsporing onderaan deze pagina als je hulp nodig hebt.",
	'install:requirements:instructions:warning' => "Jouw server heeft de systeemeisentest doorstaan, maar er is minstens één waarschuwing. We raden je aan om de pagina met foutoplossingen te bekijken. ",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Webserver',
	'install:require:settings' => 'Instellingenbestand',
	'install:require:database' => 'Database',

	'install:check:root' => 'De webserver heeft geen rechten op een .htaccess bestand aan te maken in de hoofdmap van Elgg. Er zijn twee keuzes:

1. Wijzig de rechten op de hoofdmap

2. Kopieer het bestand install/config/htaccess.dist naar .htaccess',

	'install:check:php:version' => 'Elgg vereist PHP-versie %s of nieuwer. Jouw server gebruikt versie %s.',
	'install:check:php:extension' => 'Elgg vereist de PHP-uitbreiding %s.',
	'install:check:php:extension:recommend' => 'We raden aan om de PHP-uitbreiding %s te installeren.',
	'install:check:php:open_basedir' => 'De open_basedir PHP-aanwijzing kan voorkomen dat Elgg bestanden in de datamap opslaat.',
	'install:check:php:safe_mode' => 'We raden het af om PHP in veilige modus te draaien. Dat kan problemen met Elgg veroorzaken. ',
	'install:check:php:arg_separator' => 'arg_separator.output moet & zijn om Elgg te laten werken. Jouw server is %s.',
	'install:check:php:register_globals' => 'Register globals moet uitgeschakeld zijn.',
	'install:check:php:session.auto_start' => "session.auto_start moet uitgeschakeld zijn om Elgg te laten werken. Verander de configuratie van je server of voeg deze richtlijn toe aan het .htaccess bestand van Elgg.",

	'install:check:installdir' => 'De webserver heeft geen rechten op het bestande settings.php aan te maken in de installatiemap. Er zijn twee keuzes:

1. Wijzig de rechten van de elgg-config map in de Elgg installatie

2. Kopieer het bestand %s/settings.example.php naar elgg-config/settings.php en volg de instructies in het bestand om de database configuratie af te ronden',
	'install:check:readsettings' => 'Er staat een instellingenbestand in de installatie map, maar de webserver kan dit niet lezen. Je kunt het bestand verwijderen of de leesbevoegdheden ervan wijzigen.',

	'install:check:php:success' => "De PHP van jouw webserver voldoet aan de eisen van Elgg.",
	'install:check:rewrite:success' => 'De test voor de rewrite rules is geslaagd.',
	'install:check:database' => 'De database-eisen worden gecontroleerd wanneer Elgg de database laadt.',

	'install:database:instructions' => "Als je nog geen database aangemaakt hebt voor Elgg, doe dit dan nu. Daarna vul je de gegevens hieronder in om de database van Elgg te initialiseren.",
	'install:database:error' => 'Er was een fout bij het aanmaken van de Elgg-database. De installatie kan niet verdergaan. Bekijk de boodschap hierboven en verhelp de problemen. Als je meer hulp nodig hebt, klik dan op de installatie hulplink hieronder of vraag hulp in de Elgg community-fora.',

	'install:database:label:dbuser' =>  'Database gebruikersnaam',
	'install:database:label:dbpassword' => 'Database wachtwoord',
	'install:database:label:dbname' => 'Database naam',
	'install:database:label:dbhost' => 'Database host',
	'install:database:label:dbprefix' => 'Database table voorvoegsel',
	'install:database:label:timezone' => "Tijdzone",

	'install:database:help:dbuser' => 'De gebruiker die de volledige bevoegdheid heeft tot de MySQL-database die je aangemaakt hebt voor Elgg',
	'install:database:help:dbpassword' => 'Wachtwoord voor de databasegebruiker hierboven',
	'install:database:help:dbname' => 'Naam van de Elgg-database',
	'install:database:help:dbhost' => 'Hostnaam van de MySQL-server (meestal localhost)',
	'install:database:help:dbprefix' => "Het voorvoegsel dat voor alle tabellen van Elgg gebruikt wordt (meestal elgg_)",
	'install:database:help:timezone' => "De standaard tijdzone waarin de site zal werken",

	'install:settings:instructions' => 'We hebben wat informatie nodig over de site terwijl we Elgg configureren. Als je nog geen <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">datamap hebt aangemaakt</a> voor Elgg, moet je dit nu doen.',

	'install:settings:label:sitename' => 'Sitenaam',
	'install:settings:label:siteemail' => 'Site e-mailadres',
	'install:database:label:wwwroot' => 'Site URL',
	'install:settings:label:path' => 'Elgg installatiemap',
	'install:database:label:dataroot' => 'Datamap',
	'install:settings:label:language' => 'Sitetaal',
	'install:settings:label:siteaccess' => 'Standaard toegangsniveau van de site',
	'install:label:combo:dataroot' => 'Elgg maakt de datamap aan',

	'install:settings:help:sitename' => 'De naam van je nieuwe Elgg site',
	'install:settings:help:siteemail' => 'E-mail adres gebruikt door Elgg voor de communicatie met gebruikers ',
	'install:database:help:wwwroot' => 'Het adres van de site (Elgg raadt dit meestal correct)',
	'install:settings:help:path' => 'De map waar je de Elgg code opgeladen hebt (Elgg raadt dit meestal correct)',
	'install:database:help:dataroot' => 'De map die je aanmaakte voor Elgg om bestanden op te slaan (de bevoegdheden van deze map zullen nagekeken worden als je op volgende klik). Dit moet een absoluut path zijn.',
	'install:settings:help:dataroot:apache' => 'Je hebt de optie dat Elgg de datamap aanmaakt of Elgg gebruikt de map die jij al aanmaakte om gebruikers bestanden in op te slaan (de bevoegdheden van deze map zullen nagekeken worden als je op volgende klik)',
	'install:settings:help:language' => 'De standaard taal van de site',
	'install:settings:help:siteaccess' => 'Het standaard toegangsniveau voor nieuwe gegevens aangemaakt door gebruikers',

	'install:admin:instructions' => "Nu is het tijd om een administrator account aan te maken.",

	'install:admin:label:displayname' => 'Weergavenaam',
	'install:admin:label:email' => 'E-mailadres',
	'install:admin:label:username' => 'Gebruikersnaam',
	'install:admin:label:password1' => 'Wachtwoord',
	'install:admin:label:password2' => 'Wachtwoord opnieuw',

	'install:admin:help:displayname' => 'De naam die weergegeven wordt op deze site voor dit account',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Account gebruikersnaam gebruikt om in te loggen',
	'install:admin:help:password1' => "Het wachtwoord van het account moet minimaal %u karakters lang zijn.",
	'install:admin:help:password2' => 'Typ het wachtwoord nogmaals in om te bevestigen',

	'install:admin:password:mismatch' => 'Wachtwoorden moeten gelijk zijn',
	'install:admin:password:empty' => 'Wachtwoord mag niet leeg zijn',
	'install:admin:password:tooshort' => 'Het wachtwoord is te kort',
	'install:admin:cannot_create' => 'Een admin account kon niet aangemaakt worden.',

	'install:complete:instructions' => 'Jouw Elgg site is nu klaar om gebruikt te worden. Klik op de knop hier onder om naar jouw site te gaan.',
	'install:complete:gotosite' => 'Ga naar de site',
	'install:complete:admin_notice' => 'Welkom op je ELgg website! Voor meer opties zie de %s.',
	'install:complete:admin_notice:link_text' => 'instellingen pagina\'s',

	'InstallationException:UnknownStep' => '%s is een onbekende installatie stap.',
	'InstallationException:MissingLibrary' => 'Kon %s niet laden',
	'InstallationException:CannotLoadSettings' => 'Elgg kon het instellingen bestand niet laden. Ofwel bestaat het niet, ofwel is er een probleem met de bevoegdheden.',

	'install:success:database' => 'Database is geïnstalleerd.',
	'install:success:settings' => 'De site instellingen zijn opgeslagen.',
	'install:success:admin' => 'Admin account is aangemaakt.',

	'install:error:htaccess' => 'Er kon geen .htaccess bestand aangemaakt worden',
	'install:error:settings' => 'Er kon geen instellingen bestand aangemaakt worden',
	'install:error:settings_mismatch' => 'De waarde van "%s" in het instellingen bestand komt niet overeen met de opgegeven $params.',
	'install:error:databasesettings' => 'Kon met deze instellingen niet met de database verbinden.',
	'install:error:database_prefix' => 'Ongeldige karakters in het database voorvoegsel',
	'install:error:oldmysql2' => 'MySQL moet versie 5.5.3 zijn of hoger. Jouw server gebruikt %s.',
	'install:error:nodatabase' => 'Niet mogelijk om database %s te gebruiken. Mogelijk bestaat hij niet.',
	'install:error:cannotloadtables' => 'Kan de database tables niet laden',
	'install:error:tables_exist' => 'Er bestaan alreeds Elgg tabellen in de database. Je moet eerst deze tabellen verwijderen of herstart de installatie en we zullen proberen deze tabellen te gebruiken. Om de installatie te herstarten verwijder \'?step=database\' uit de URL in de adresbalk van je browser en druk op Enter.',
	'install:error:readsettingsphp' => 'Kan /elgg-config/settings.example.php niet lezen',
	'install:error:writesettingphp' => 'Kan niet naar /elgg-config/settings.php schrijven',
	'install:error:requiredfield' => '%s is vereist',
	'install:error:relative_path' => 'We denken dat "%s" niet een absoluut pad is naar je data map',
	'install:error:datadirectoryexists' => 'Je data map %s bestaat niet.',
	'install:error:writedatadirectory' => 'Je data map %s is niet schrijfbaar door de webserver.',
	'install:error:locationdatadirectory' => 'Je data map %s moet buiten je installatie pad staan voor veiligheidsredenen.',
	'install:error:emailaddress' => '%s is geen geldig e-mailadres',
	'install:error:createsite' => 'Kan site niet aanmaken',
	'install:error:savesitesettings' => 'Site instellingen konden niet worden opgeslagen',
	'install:error:loadadmin' => 'De admin gebruiker kan niet worden ingeladen.',
	'install:error:adminaccess' => 'Het is niet gelukt om het nieuwe account beheerrechten te geven.',
	'install:error:adminlogin' => 'De beheerder kon niet automatisch worden aangemeld.',
	'install:error:rewrite:apache' => 'We denken dat je server op Apache draait.',
	'install:error:rewrite:nginx' => 'We denken dat je server op Nginx draait.',
	'install:error:rewrite:lighttpd' => 'We denken dat je server op Lighttpd draait.',
	'install:error:rewrite:iis' => 'We denken dat je server op IIS draait.',
	'install:error:rewrite:allowoverride' => "De rewrite test is mislukt en de meest waarschijnlijke reden is dat AllowOverride niet op All is ingesteld voor de map van Elgg. Dit weerhoudt Apache ervan om het .htaccess bestand te verwerken. Hierin staat de rewrite regels.				\n\nEen minder waarschijnlijke reden is dat Apache geconfigureerd is met een alias voor de Elgg map and dat je RewriteBase in het .htaccess bestand moet instellen. Aanvullende instructies kun je in het .htaccess bestand in de Elgg map terugvinden.",
	'install:error:rewrite:htaccess:write_permission' => 'Je webserver heeft onvoldoende rechten om een .htaccess-bestand in de hoofdmap van Elgg te plaatsen. Je zult handmatig het bestand vanuit install/config/htaccess.dist naar .htaccess moeten kopiëren of je moet de rechten op de installatie map aanpassen.',
	'install:error:rewrite:htaccess:read_permission' => 'Er is een .htaccess bestand in de Elgg map, maar de webserver mag het niet lezen.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Er is een .htaccess bestand in de Elgg map, maar die is niet door Elgg aangemaakt. Verwijder het bestand.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Het lijkt er op dat er een oude versie van Elgg\'s .htaccess bestand in de Elgg map staat. Het bevat niet de rewrite rule zodat de webserver getest kan worden.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Een onbekende fout is opgetreden tijdens het aanmaken van het .htaccess bestand. Je zult deze handmatig vanuit install/config/htaccess.dist naar .htaccess moeten kopieren.',
	'install:error:rewrite:altserver' => 'De rewrite rules test is mislukt. Je moet de webserver configureren met de juiste rewrite rules en het opnieuw proberen.',
	'install:error:rewrite:unknown' => 'Oef. We kunnen niet bepalen welke webserver op je site draait en de rewrite rules test is gefaald. We kunnen je geen specifiek advies geven om het op te lossen. Check de troubleshooting link voor meer informatie.',
	'install:warning:rewrite:unknown' => 'Je server ondersteunt niet het automatisch testen van de rewrite rules en je browser ondersteunt niet de controle via JavaScript. Je kunt de installatie vervolgen, maar je kunt problemen met je site ervaren. Je kunt de rewrite rules handmatig testen via deze link: <a href="%s" target="_blank">test</a>. Je zult het woord success zien als het werkt.',
	'install:error:wwwroot' => '%s is geen geldige URL',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Er is een onherstelbare fout opgetreden en gelogd. Indien je de beheerder bent, controleer je settings bestand. Ben je geen beheerder, neem dan contact op met een sitebeheerder met de volgende informatie:',
	'DatabaseException:WrongCredentials' => "Elgg kan met deze instellingen niet met de database verbinden. Controleer het settings bestand.",
);
