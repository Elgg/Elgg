<?php
return array(
	'install:title' => 'Installa Elgg',
	'install:welcome' => 'Benvenuti',
	'install:requirements' => 'Verifica requisiti',
	'install:database' => 'Installazione database',
	'install:settings' => 'Configura sito',
	'install:admin' => 'Crea account amministrativo',
	'install:complete' => 'Completato',

	'install:next' => 'Successivo',
	'install:refresh' => 'Aggiorna',

	'install:welcome:instructions' => "Installing Elgg has 6 simple steps and reading this welcome is the first one!

If you haven't already, read through the installation instructions included with Elgg (or click the instructions link at the bottom of the page).

If you are ready to proceed, click the Next button.",
	'install:requirements:instructions:success' => "Il server ha superato positivamente le verifiche dei requisiti.",
	'install:requirements:instructions:failure' => "Il server non ha superato le verifiche dei requisiti. Dopo aver risolto i problemi elencati sotto, aggiornate questa pagina. Consultate i collegamenti sulla risoluzione dei problemi al fondo di questa pagina se avete bisogno di ulteriore aiuto.",
	'install:requirements:instructions:warning' => "Il server ha superato positivamente le verifiche dei requisiti, ma c'è almeno un messaggio che richiede attenzione. Raccomandiamo di consultare la pagina sulla risoluzione dei problemi di installazione per ulteriori dettagli.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Server web',
	'install:require:settings' => 'File delle impostazioni',
	'install:require:database' => 'Database',

	'install:check:root' => 'Your web server does not have permission to create an .htaccess file in the root directory of Elgg. You have two choices:

1. Change the permissions on the root directory

2. Copy the file install/config/htaccess.dist to .htaccess',

	'install:check:php:version' => 'Elgg richiede PHP %s o superiore. Questo server sta usando la versione %s.',
	'install:check:php:extension' => 'Elgg richiede l\'estensione PHP %s.',
	'install:check:php:extension:recommend' => 'Si raccomanda di installare l\'estensione PHP %s.',
	'install:check:php:open_basedir' => 'La direttiva PHP open_basedir potrebbe impedire a Elgg di salvare file nella sua cartella dati.',
	'install:check:php:safe_mode' => 'Si sconsiglia di utilizzare Elgg in safe-mode perché potrebbe causare problemi ad Elgg.',
	'install:check:php:arg_separator' => 'arg_separator.output deve essere & perché Elgg possa funzionare, invece il valore del server è %s',
	'install:check:php:register_globals' => 'register-globals deve essere impostato a off.',
	'install:check:php:session.auto_start' => "session.auto_start deve essere off per far funzionare Elgg. Cambiare la configurazione del server o aggiungere questa direttiva al file .htaccess di Elgg.",

	'install:check:installdir' => 'Your web server does not have permission to create the settings.php file in your installation directory. You have two choices:

1. Change the permissions on the elgg-config directory of your Elgg installation

2. Copy the file %s/settings.example.php to elgg-config/settings.php and follow the instructions in it for setting your database parameters.',
	'install:check:readsettings' => 'È presente un file di impostazioni nella cartella di installazione, ma il web server non riesce a leggerlo. È possibile eliminarlo o cambiare i suoi permessi di lettura.',

	'install:check:php:success' => "Il PHP del server soddisfa tutti i requisiti richiesti da Elgg.",
	'install:check:rewrite:success' => 'Il test delle regole rewrite è andato a buon fine.',
	'install:check:database' => 'I requisiti del database vengono verificati nel momento in cui Elgg carica il suo database.',

	'install:database:instructions' => "Se non avete ancora creato un database per Elgg, fatelo ora. Quindi specificate i valori richiesti qui sotto per inizializzare il database di Elgg.",
	'install:database:error' => 'Si è verificato un errore durante la creazione del database di Elgg e l\'installazione non può continuare. Analizzare il messaggio qui sopra e correggere ogni problema. Se serve maggiore aiuto, visitare il collegamento sulla risoluzione dei problemi di installazione o chiedere aiuto sul forum della communità di Elgg.',

	'install:database:label:dbuser' =>  'Nome utente del database',
	'install:database:label:dbpassword' => 'Password del database',
	'install:database:label:dbname' => 'Nome del database',
	'install:database:label:dbhost' => 'Host del database',
	'install:database:label:dbprefix' => 'Prefisso delle tabelle del database',
	'install:database:label:timezone' => "Fuso orario",

	'install:database:help:dbuser' => 'Un utente che abbia privilegi completi sul database MySQL creato per Elgg.',
	'install:database:help:dbpassword' => 'Password dell\'account utente del database qui sopra',
	'install:database:help:dbname' => 'Nome del database di Elgg',
	'install:database:help:dbhost' => 'Nome dell\'host del database di MySQL (normalmente localhost)',
	'install:database:help:dbprefix' => "Il prefisso preposto a tutte le tabelle di Elgg (normalmente elgg_)",
	'install:database:help:timezone' => "Il fuso orario predefinito in cui il sito opera",

	'install:settings:instructions' => 'We need some information about the site as we configure Elgg. If you haven\'t <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">created a data directory</a> for Elgg, you need to do so now.',

	'install:settings:label:sitename' => 'Nome del sito',
	'install:settings:label:siteemail' => 'Indirizzo dell\'email del sito',
	'install:database:label:wwwroot' => 'Site URL',
	'install:settings:label:path' => 'Cartella di installazione di Elgg',
	'install:database:label:dataroot' => 'Data Directory',
	'install:settings:label:language' => 'Lingua del sito',
	'install:settings:label:siteaccess' => 'Accesso al sito predefinito',
	'install:label:combo:dataroot' => 'Elgg crea una cartella dati',

	'install:settings:help:sitename' => 'Il nome del vostro sito Elgg',
	'install:settings:help:siteemail' => 'Indirizzo email utilizzato da Elgg per comunicazioni con gli utenti',
	'install:database:help:wwwroot' => 'The address of the site (Elgg usually guesses this correctly)',
	'install:settings:help:path' => 'La cartella in cui è stato copiato il codice di Elgg  (normalmente Elgg lo suggerisce in modo corretto)',
	'install:database:help:dataroot' => 'The directory that you created for Elgg to save files (the permissions on this directory are checked when you click Next). It must be an absolute path.',
	'install:settings:help:dataroot:apache' => 'Potete scegliere di far creare la cartella dati a Elgg o specificare una cartella che avete creato in precedenza per contenere i file degli utenti (i permessi di questa cartella sono verificati quando premete su Successivo)',
	'install:settings:help:language' => 'La lingua predefinita dell\'interfaccia del sito',
	'install:settings:help:siteaccess' => 'Il livello di accesso predefinito per i nuovi contenuti creati dagli utenti',

	'install:admin:instructions' => "È il momento di creare un profilo amministrativo.",

	'install:admin:label:displayname' => 'Nome visualizzato',
	'install:admin:label:email' => 'Indirizzo email',
	'install:admin:label:username' => 'Nome utente',
	'install:admin:label:password1' => 'Password',
	'install:admin:label:password2' => 'Di nuovo la password',

	'install:admin:help:displayname' => 'Il nome visualizzato nel sito per questo profilo',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Nome utente del profilo per accedere al sito',
	'install:admin:help:password1' => "La password deve essere lunga almeno %u caratteri",
	'install:admin:help:password2' => 'Digitare nuovamente la password per confermare',

	'install:admin:password:mismatch' => 'Le password devono coincidere.',
	'install:admin:password:empty' => 'La password non può essere vuota.',
	'install:admin:password:tooshort' => 'La password è troppo corta',
	'install:admin:cannot_create' => 'Impossibile creare il profilo amministrativo.',

	'install:complete:instructions' => 'Il sito di Elgg è ora pronto per essere usato. Cliccare il pulsante qui sotto per essere reindirizzati al sito.',
	'install:complete:gotosite' => 'Vai al sito',
	'install:complete:admin_notice' => 'Welcome to your Elgg site! For more options, see the %s.',
	'install:complete:admin_notice:link_text' => 'settings pages',

	'InstallationException:UnknownStep' => '%s è un passo di installazione sconosciuto.',
	'InstallationException:MissingLibrary' => 'Impossibile caricare %s',
	'InstallationException:CannotLoadSettings' => 'Elgg non riesce a caricare il file delle impostazioni. O non esiste o ci sono dei problemi con i permessi dei file.',

	'install:success:database' => 'Il database è stato installato.',
	'install:success:settings' => 'Le impostazioni del sito sono state salvate.',
	'install:success:admin' => 'Il profilo amministrativo è stato creato.',

	'install:error:htaccess' => 'Impossibile creare un file .htaccess',
	'install:error:settings' => 'Impossibile creare il file delle impostazioni',
	'install:error:settings_mismatch' => 'The settings file value for "%s" does not match the given $params.',
	'install:error:databasesettings' => 'Impossibile connettersi al database con le impostazioni specificate.',
	'install:error:database_prefix' => 'Caratteri non validi nel prefisso del database',
	'install:error:oldmysql2' => 'MySQL must be version 5.5.3 or above. Your server is using %s.',
	'install:error:nodatabase' => 'Impossibile usare il database %s. Potrebbe non esistere.',
	'install:error:cannotloadtables' => 'Impossibile caricare le tabelle del database',
	'install:error:tables_exist' => 'Esistono già delle tabelle di Elgg nel database. È necessario eliminare tali tabelle o riavviare l\'installer e proveremo ad usarle. Per riavviare l\'installer, rimuovere \'?step=database\' dall\'URL nella barra degli indirizzi del browser e premere Invio.',
	'install:error:readsettingsphp' => 'Impossibile leggere engine/settings.example.php',
	'install:error:writesettingphp' => 'Impossibile scrivere engine/settings.php',
	'install:error:requiredfield' => '%s è richiesto',
	'install:error:relative_path' => 'Pensiamo che "%s" non sia un percorso assoluto per la vostra cartella dati',
	'install:error:datadirectoryexists' => 'La cartella dati %s non esiste.',
	'install:error:writedatadirectory' => 'La cartella dati %s non è scrivibile dal server web.',
	'install:error:locationdatadirectory' => 'La cartella dati %s deve trovarsi fuori dal percorso di installazione per ragioni di sicurezza.',
	'install:error:emailaddress' => '%s non è un indirizzo email valido',
	'install:error:createsite' => 'Impossibile creare il sito.',
	'install:error:savesitesettings' => 'Impossibile salvare le impostazioni',
	'install:error:loadadmin' => 'Impossibile caricare l\'utente amministratore.',
	'install:error:adminaccess' => 'Impossibile attribuire i privilegi di amministratore al nuovo profilo utente.',
	'install:error:adminlogin' => 'Impossibile fare accedere il nuovo utente amministratore in modo automatico.',
	'install:error:rewrite:apache' => 'Pensiamo che il vostro server web sia Apache.',
	'install:error:rewrite:nginx' => 'Pensiamo che il vostro server web sia Nginx.',
	'install:error:rewrite:lighttpd' => 'Pensiamo che il vostro server web sia Lighttpd.',
	'install:error:rewrite:iis' => 'Pensiamo che il vostro server web sia IIS.',
	'install:error:rewrite:allowoverride' => "The rewrite test failed and the most likely cause is that AllowOverride is not set to All for Elgg's directory. This prevents Apache from processing the .htaccess file which contains the rewrite rules.
\n\nA less likely cause is Apache is configured with an alias for your Elgg directory and you need to set the RewriteBase in your .htaccess. There are further instructions in the .htaccess file in your Elgg directory.",
	'install:error:rewrite:htaccess:write_permission' => 'Il server web non ha i permessi per creare il file .htaccess nella cartella di Elgg. Occorre copiare manualmente install/config/htaccess.dist nella cartella di Elgg rinominandolo .htaccess, o cambiare i permessi della cartella.',
	'install:error:rewrite:htaccess:read_permission' => 'Esiste un file .htaccess nella cartella di Elgg, ma il server web non ha i permessi per leggerlo.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Esiste un file .htaccess nella cartella di Elgg che non è stato creato da Elgg. Si prega di rimuoverlo.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Sembra che ci sia un vecchio .htaccess nella cartella di Elgg. Non contiene le regole rewrite necessarie a testare il server web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Si è verificato un errore sconosciuto durante la creazione del file .htaccess.  Occorre copiare manualmente install/config/htaccess.dist nella cartella di Elgg rinominandolo .htaccess ',
	'install:error:rewrite:altserver' => 'Il test delle regole rewrite non è stato superato. Occorre configurare il server web con le regole rewrite di Elgg e provare di nuovo.',
	'install:error:rewrite:unknown' => 'Uffa. Non riusciamo a capire che tipo di web server è installato che non ha superato il test delle regole rewrite. Non vi possiamo fornire nessun consiglio specifico. Vi preghiamo di consultare il collegamento sulla risoluzione dei problemi.',
	'install:warning:rewrite:unknown' => 'Il server non supporta il test automatico delle regole rewrite e il vostro browser non supporta il test tramite JavaScript. Potete continuare l\'installazione, ma potreste riscontrare dei problemi col sito. Potete testarre manualmente le regole rewrite cliccando il collegamento seguente: <a href="%s" target="_blank">test</a>. Leggerete la parola "success" se le regole funzionano.',
	'install:error:wwwroot' => '%s is not a valid URL',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Si è verificato un errore non recuperabile che è stato registrato nel log. Se siete l\'amministratore del sito verificate il file delle impostazioni, oppure contattate l\'amministratore del sito fornendo le seguenti informazioni:',
	'DatabaseException:WrongCredentials' => "Elgg non si può connettere al database con le credenziali fornite. Verificare il file delle impostazioni.",
);
