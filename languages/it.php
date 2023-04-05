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

	'item:site:site' => 'Sito',
	'collection:site:site' => 'Sito',

/**
 * Sessions
 */

	'login' => "Accedi",
	'loginok' => "Accesso riuscito.",
	'login:empty' => "Specificare nome utente (email) e password",
	'login:baduser' => "Impossibile caricare il tuo profilo utente",

	'logout' => "Esci",
	'logoutok' => "Uscita completata.",
	'logouterror' => "Impossibile uscire. Riprovare.",
	'session_expired' => "La sessione è scaduta. <a href='javascript:location.reload(true)'>Ricaricare</a> la pagina per accedere nuovamente.",
	'session_changed_user' => "Hai avuto accesso come altro utente. Devi <a href='javascript:location.reload(true)'>ricaricare</a> la pagina.",

	'loggedinrequired' => "Occorre accedere per visualizzare la pagina richiesta.",
	'adminrequired' => "Occorre essere amministratori per visualizzare la pagina richiesta.",
	'membershiprequired' => "Occorre essere membri di questo gruppo per visualizzare la pagina richiesta.",
	'limited_access' => "Permessi insufficienti per visualizzare la pagina richiesta.",
	'invalid_request_signature' => "L'URL della pagina a cui si sta cercando di accedere non è valido o è scaduto.",

/**
 * Errors
 */

	'exception:title' => "Errore irreversibile",
	'exception:contact_admin' => 'Si è verificato un errore non recuperabile che è stato tracciato. Contattare l\'amministratore del sito fornendo le seguenti informazioni:',

	'actionnotfound' => "Impossibile trovare il file previsto dal comando %s.",
	'actionunauthorized' => 'Operazione non autorizzata',

	'ajax:error' => 'Errore inatteso durante una chiamata AJAX. Forse manca la connessione al server.',
	'ajax:not_is_xhr' => 'Non si può accedere alle viste AJAX in modo diretto',
	'ElggEntity:Error:SetEnabled' => 'Usa %s al posto dell\'impostazione magica per "abilitato"',
	'ElggUser:Error:SetAdmin' => 'Usa %s al posto del setter magico per "admin"',

	'PluginException:CannotStart' => 'Il plugin %s (guid: %s) è stato disattivato perché impossibile avviarlo. Motivo: %s',
	'PluginException:InvalidID' => "%s non è un ID di plugin valido.",
	'ElggPlugin:MissingID' => 'ID del plugin mancante (guid %s)',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Impossibile includere %s del plugin %s (guid: %s) a %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Impossibile aprire il percorso delle viste del plugin %s (guid: %s) a %s.',
	'ElggPlugin:InvalidAndDeactivated' => '%s è un plugin non valido ed è stato disattivato.',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Ci sono altri plugin che indicano %s come dipendenza.  Occorre prima di disabilitare il plugin %s occorre disabilitare i seguenti plugin:',

	'ElggMenuBuilder:Trees:NoParents' => 'Trovate voci di menu che non possono essere collegate al loro elemento padre',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'La voce di menu [%s] risulta priva dell\'elemento padre [%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Trovata doppia registrazione della voce di menu [%s]',

	'RegistrationException:EmptyPassword' => 'I campi delle password non possono essere vuoti',
	'RegistrationException:PasswordMismatch' => 'Le password devono coincidere',
	'LoginException:BannedUser' => 'Sei stato espulso da questo sito e quindi non puoi accedere.',
	'LoginException:UsernameFailure' => 'Impossibile accedere. Per favore verifica nome utente (email) e password',
	'LoginException:PasswordFailure' => 'Impossibile accedere. Per favore verifica nome utente (email) e password',
	'LoginException:AccountLocked' => 'Il tuo account è stato bloccato per numero tentativi di accesso troppo elevato.',
	'LoginException:ChangePasswordFailure' => 'Verifica della password attuale fallita.',
	'LoginException:Unknown' => 'Impossibile accedere a causa di un errore non identificato.',

	'UserFetchFailureException' => 'Impossibile verificare i permessi per user_guid [%s] perchè l\'utente non esiste.',
	'EntityNotFoundException' => 'Il contenuto, a cui stavi tentando di accedere, è stato rimosso o non disponi delle autorizzazioni per accedervi.',
	'EntityPermissionsException' => 'Non disponi di autorizzazioni sufficienti per questa azione.',
	'GatekeeperException' => 'Non disponi delle autorizzazioni per visualizzare la pagina a cui stai tentando di accedere',
	'BadRequestException' => 'Richiesta non valida',

	'viewfailure' => 'Si è verificato un errore interno nella vista %s',
	'changebookmark' => 'Per favore cambia il segnalibro di questa pagina.',
	'error:missing_data' => 'Ci sono dei dati mancanti nella tua richiesta',
	'save:fail' => 'Si è verificato un problema durante il salvataggio dei tuoi dati',
	'save:success' => 'I tuoi dati sono stati salvati',

	'error:default:title' => 'Oops...',
	'error:default:content' => 'Oops... qualcosa è andato storto.',
	'error:400:title' => 'Richiesta non valida',
	'error:400:content' => 'Spiacenti, la richiesta è o non valida o incompleta.',
	'error:403:title' => 'Vietato',
	'error:403:content' => 'Spiacenti, impossibile accedere alla pagina richiesta.',
	'error:404:title' => 'Pagina non trovata',
	'error:404:content' => 'Spiacenti. Pagina richiesta non trovata.',

	'upload:error:ini_size' => 'Il file che si è tentando di caricare è troppo grande.',
	'upload:error:form_size' => 'Il file che si è tentando di caricare è troppo grande.',
	'upload:error:partial' => 'Il caricamento del file non è stato completato.',
	'upload:error:no_file' => 'Nessun file selezionato.',
	'upload:error:no_tmp_dir' => 'Impossibile salvare il file caricato.',
	'upload:error:cant_write' => 'Impossibile salvare il file caricato.',
	'upload:error:extension' => 'Impossibile salvare il file caricato.',
	'upload:error:unknown' => 'Il caricamento del file non è riuscito.',

/**
 * Table columns
 */

/**
 * User details
 */

	'name' => "Nome visualizzato",
	'email' => "Indirizzo email",
	'username' => "Nome utente",
	'loginusername' => "Nome utente o email",
	'password' => "Password",
	'passwordagain' => "Password (di nuovo, per verifica)",
	'admin_option' => "Rendere questo utente un amministratore?",
	'autogen_password_option' => "Generare automaticamente una password sicura?",

/**
 * Access
 */
	'access:label:logged_out' => "Utenti disconnessi",
	'access:label:friends' => "Amici",
	'access' => "Accesso",
	'access:limited:label' => "Limitato",
	'access:help' => "Il livello d'accesso",
	'access:read' => "Accesso in lettura",
	'access:write' => "Accesso in scrittura",
	'access:admin_only' => "Solo amministratori",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Pannello di controllo",
	'dashboard:nowidgets' => "Il tuo pannello di controllo ti permette di tracciare le attività e i contenuti di questo sito che più ti interessano.",

	'widgets:add' => 'Aggiungi un widget',
	'widgets:add:description' => "Clicca su pulsante qualsiasi in basso per aggiungere il widget alla pagina.",
	'widget:unavailable' => 'Hai già aggiunto questo widget',
	'widget:numbertodisplay' => 'Numero di elementi da mostrare',

	'widget:delete' => 'Rimuovi %s',
	'widget:edit' => 'Personalizza questo widget',

	'item:object:widget' => "Widget",
	'collection:object:widget' => 'Widget',
	'widgets:add:success' => "Il widget è stato aggiunto.",
	'widgets:add:failure' => "Impossibile aggiungere il tuo widget.",
	'widgets:move:failure' => "Impossibile memorizzare la posizione del nuovo widget.",
	'widgets:remove:failure' => "Impossibile rimuovere questo widget",
	'widgets:not_configured' => "Questo widget non è ancora configurato",
	
/**
 * Groups
 */

	'group' => "Gruppo",
	'item:group' => "Gruppi",
	'collection:group' => 'Gruppi',
	'item:group:group' => "Gruppi",
	'collection:group:group' => 'Gruppi',

/**
 * Users
 */

	'user' => "Utente",
	'item:user' => "Utenti",
	'collection:user' => 'Utenti',
	'item:user:user' => 'Utente',
	'collection:user:user' => 'Utenti',
	'notification:user:user:remove_admin' => "Invia una notifica, quando i diritti di amministratore di un utente vengono revocati",

	'friends' => "Amici",

	'avatar' => 'Immagine del profilo',
	'avatar:edit' => 'Modifica immagine del profilo',
	'avatar:upload:instructions' => "L'immagine del tuo profilo viene mostrata ovunque nel sito. Puoi cambiarla quando vuoi (formati di file accettati: GIF, JPG o PNG)",
	'avatar:upload:success' => 'Immagine del profilo caricata',
	'avatar:upload:fail' => 'Impossibile caricare l\'immagine del profilo',
	'avatar:resize:fail' => 'Impossibile ridimensionare l\'immagine del profilo',
	'avatar:remove:success' => 'Rimozione dell\'immagine del profilo completata',
	'avatar:remove:fail' => 'Impossibile rimuovere l\'immagine del profilo',
	'action:user:validate:error' => "Si è verificato un errore durante la convalida di %s",

/**
 * Feeds
 */
	'feed:rss' => 'Feed RSS per questa pagina',
/**
 * Links
 */
	'link:view' => 'visualizza link',
	'link:view:all' => 'Visualizza tutto',


/**
 * River
 */
	'river' => "Attività",
	'river:user:friend' => "%s è ora amico di %s",
	'river:update:user:avatar' => '%s ha una nuova immagine del profilo',
	'river:posted:generic' => '%s inviato',
	'river:ingroup' => 'nel gruppo %s',
	'river:none' => 'Nessuna attività',
	'river:update' => 'Aggiornamento di %s',
	'river:delete' => 'Rimuovi questo elemento',
	'river:delete:success' => 'L\'elemento di attività è stato eliminato.',
	'river:delete:fail' => 'L\'elemento di attività non può essere eliminato.',
	'river:delete:lack_permission' => 'Permessi insufficienti per eliminare questo elemento di attività.',
	'river:subject:invalid_subject' => 'Utente non valido',
	'activity:owner' => 'Visualizza attività',

/**
 * Relationships
 */

/**
 * Notifications
 */
	'notification:method:email' => 'Email',
	'notification:method:delayed_email' => 'E-mail ritardata',
	'usersettings:notifications:menu:filter:settings' => "Impostazioni",
	'usersettings:notifications:timed_muting:help' => "Se non desideri ricevere notifiche, durante un certo periodo (ad esempio una vacanza), puoi impostare una data di inizio, e di fine, per disabilitare temporaneamente tutte le notifiche",
	'usersettings:notifications:timed_muting:start' => "Primo giorno",
	'usersettings:notifications:timed_muting:warning' => "Al momento le tue notifiche sono temporaneamente disabilitate",
	
	'usersettings:notifications:save:ok' => "Le impostazioni di notifica sono state salvate correttamente.",
	'notification:subject' => 'Notifica su %s',
	'notification:body' => 'Visualizza la nuova attività qui %s',

	'notifications:mute:title' => "Silenzia le notifiche",
	'notifications:mute:container' => "da '%s'",
	'notifications:mute:group' => "scritto nel gruppo '%s'",
	'notifications:mute:user' => "scritto dall'utente '%s'",

/**
 * Search
 */

	'search' => "Cerca",
	'notfound' => "Nessun risultato trovato.",

	'viewtype:change' => "Cambia tipo di elenco",
	'viewtype:list' => "Vista a elenco",
	'viewtype:gallery' => "Galleria",
	'search:go' => 'Vai',
	'userpicker:only_friends' => 'Solo amici',

/**
 * Account
 */

	'account' => "Profilo",
	'settings' => "Impostazioni",
	'tools' => "Strumenti",
	'settings:edit' => 'Modifica impostazioni',

	'register' => "Iscriviti",
	'registerok' => "Ti sei registrato a %s.",
	'registerbad' => "L'iscrizione non è avvenuta a causa di un errore.",
	'registerdisabled' => "L'iscrizione è stata disabilitata dall'amministratore del sistema",
	'register:fields' => 'Tutti i campi sono obbligatori',
	'registration:notemail' => 'L\'indirizzo email fornito non sembra valido.',
	'registration:userexists' => 'Il nome utente  è già utilizzato',
	'registration:usernametooshort' => 'Il nome utente deve contenere almeno %u caratteri.',
	'registration:usernametoolong' => 'Il nome utente è troppo lungo. Può avere un massimo di %u caratteri',
	'registration:dupeemail' => 'Questo indirizzo email è già utilizzato.',
	'registration:invalidchars' => 'Spiacenti, il nome utente contiene il carattere %s che non è valido. I caratteri seguenti non sono validi: %s',
	'registration:emailnotvalid' => 'Spiacenti, l\'indirizzo email inserito non è valido in questo sistema',
	'registration:passwordnotvalid' => 'Spiacenti, la password inserita non è valida in questo sistema',
	'registration:usernamenotvalid' => 'Spiacenti, il nome utente inserito non è valido in questo sistema',

	'adduser:ok' => "Nuovo utente aggiunto",
	
	'user:set:name' => "Impostazioni del nome di profilo",
	'user:name:label' => "Nome visualizzato",
	'user:name:success' => "Il nome visualizzato è stato cambiato nel sistema.",
	'user:name:fail' => "Impossibile cambiare il nome visualizzato nel sistema.",

	'user:set:password' => "Password del profilo",
	'user:current_password:label' => 'Password attuale',
	'user:password:label' => "Nuova password",
	'user:password2:label' => "Ripetere la nuova password",
	'user:password:success' => "Password cambiata",
	'user:changepassword:unknown_user' => 'Utente non valido.',
	'user:changepassword:change_password_confirm' => 'Questo cambierà la tua password.',

	'user:set:language' => "Impostazioni della lingua",
	'user:language:label' => "Lingua",
	'user:language:success' => "Le impostazioni della lingua sono state aggiornate.",

	'user:username:notfound' => 'Nome utente %s non trovato.',

	'user:password:lost' => 'Password smarrita',
	'user:password:changereq:success' => 'Richiesta di una nuova password completata, email inviata.',

	'user:password:text' => 'Per richiedere una nuova password inserire il tuo nome utente o l\'indirizzo email con cui ti sei registrato e clicca sul pulsante Richiedi',

	'user:persistent' => 'Ricordami',

/**
 * Password requirements
 */
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Amministrazione',
	'menu:page:header:configure' => 'Configurazione',
	'menu:page:header:develop' => 'Sviluppo',
	'menu:page:header:default' => 'Altro',

	'admin:view_site' => 'Visualizza il sito',
	'admin:loggedin' => 'Sei l\'utente %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Impostazioni salvate.",
	'admin:configuration:fail' => "Impossibile salvare le impostazioni.",
	'admin:configuration:dataroot:relative_path' => 'Impossibile impostare "%s" come dataroot perché non è un percorso assoluto.',
	'admin:configuration:default_limit' => 'Il numero minimo di elementi per pagina deve essere almeno pari a 1.',

	'admin:unknown_section' => 'Sezione di amministrazione non valida.',

	'admin' => "Amministrazione",
	'admin:description' => "Il pannello di amministrazione permette di controllare ogni aspetto del sistema, dalla gestione degli utenti al comportamento dei plugin. Selezionare un'opzione qui sotto per cominciare.",
	'admin:performance:simplecache:settings:warning' => "Si consiglia di configurare l'impostazione simplecache in settings.php.
L'impostazione di simplecache, nel file settings.php, migliora le prestazioni di memorizzazione nella cache.
Consente a Elgg di saltare la connessione al database quando servono file JavaScript e CSS memorizzati nella cache",
	'admin:performance:systemcache' => 'Cache di sistema',
	'admin:performance:php:open_basedir:warning' => 'Sono in atto alcune limitazioni di open_basedir, che potrebbero influire sulle prestazioni.',
	
	'admin:statistics' => 'Statistiche',
	'admin:server' => 'Server',
	'admin:cron:msg' => 'Messaggio',
	'admin:cron:started' => 'Cron job per "%s" avviati alle %s',
	'admin:cron:started:actual' => 'Intervallo cron "%s" ha iniziato l\'elaborazione alle %s',
	'admin:cron:complete' => 'Cron job per "%s" completati alle %s',

	'admin:appearance' => 'Appearance',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilities',
	'admin:configure_utilities' => 'Utilities',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Utenti",
	'admin:users:online' => 'Online in questo momento',
	'admin:users:newest' => 'Più recenti',
	'admin:users:admins' => 'Amministratori',
	'admin:users:banned' => 'Banned',
	'admin:users:existingadmins' => 'Elenco degli amministratori esistenti',
	'admin:users:add' => 'Aggiungi un nuovo utente',
	'admin:users:description' => "Questo pannello di amministrazione permette di controllare le impostazioni del sito. Selezionare un'opzione qui sotto per iniziare.",
	'admin:users:adduser:label' => "Cliccare qui per aggiungere un nuovo utente...",
	'admin:users:opt:linktext' => "Configura utenti...",
	'admin:users:opt:description' => "Configura utenti e informazioni dei profili",
	'admin:users:find' => 'Trova',
	'admin:users:unvalidated' => 'Utenti non ancora validati',
	'admin:users:unvalidated:no_results' => 'Nessun utente non ancora validato',
	'admin:users:unvalidated:registered' => 'Registrato: %s',
	'admin:users:unvalidated:change_email' => 'Cambia indirizzo email',
	'admin:users:details:statistics' => 'Statistiche sui contenuti',
	
	'admin:configure_utilities:maintenance' => 'Modalità di manutenzione',
	'admin:upgrades' => 'Aggiornamenti',
	'admin:upgrades:db' => 'Aggiornamenti del database',
	'admin:upgrades:db:end_time' => 'Tempo scaduto',
	'admin:upgrades:db:duration' => 'Durata',
	'admin:upgrades:menu:completed' => 'Aggiornamenti completati',
	'admin:upgrades:menu:db' => 'Aggiornamenti del database',
	'admin:upgrades:run' => 'Esegui subito gli aggiornamenti',
	'admin:upgrades:error:invalid_upgrade' => 'L\'entità %s non esiste o non è un\'istanza valida di ElggUpgrade',
	'admin:upgrades:completed' => 'Aggiornamento "%s" completato alle %s',
	'admin:upgrades:completed:errors' => 'Aggiornamento "%s" completato al %s, ma rilevato/i %s errore/i',

	'admin:settings' => 'Impostazioni',
	'admin:settings:basic' => 'Impostazioni di base',
	'admin:settings:advanced' => 'Impostazioni avanzate',
	'admin:settings:users' => 'Utenti',
	'admin:site_settings' => "Configurazioni del Sito",
	'admin:site:description' => "Questo pannello di amministrazione permette di gestire le impostazioni globali del sito. Selezionare un'opzione qui sotto per iniziare.",
	'admin:site:opt:linktext' => "Configura sito...",
	'admin:settings:in_settings_file' => 'Queste impostazioni sono definite in settings.php',

	'site_secret:current_strength' => 'Forza della chiave',
	'site_secret:strength:weak' => "Debole",
	'site_secret:strength_msg:weak' => "Si raccomanda vivamente di rigenerare la chiave di sicurezza del sito.",
	'site_secret:strength:moderate' => "Moderata",
	'site_secret:strength_msg:moderate' => "Si raccomanda di rigenerare la chiave del sito per garantire una migliore sicurezza al sito.",
	'site_secret:strength:strong' => "Forte",
	'site_secret:strength_msg:strong' => "La chiave di sicurezza del sito è sufficientemente forte. Non è necessario rigenerarla.",

	'admin:dashboard' => 'Pannello di controllo',
	'admin:widget:online_users' => 'Utenti online',
	'admin:widget:online_users:help' => 'Elenca gli utenti nel sito in questo momento',
	'admin:widget:new_users' => 'Nuovi utenti',
	'admin:widget:new_users:help' => 'Elenca i nuovi utenti',
	'admin:widget:banned_users' => 'Utenti espulsi',
	'admin:widget:banned_users:help' => 'Elenca gli utenti espulsi',
	'admin:widget:content_stats' => 'Statistiche sui contenuti',
	'admin:widget:content_stats:help' => 'Tiene traccia dei contenuti creati dagli utenti',
	'admin:widget:cron_status' => 'Stato delle attività pianificate',
	'admin:widget:cron_status:help' => 'Mostra lo stato dell\'ultimo lavoro completato da Attività pianificate',
	'admin:statistics:numentities:other' => 'Altre entità',
	'admin:statistics:queue:name' => 'Nome',

	'admin:widget:admin_welcome' => 'Benvenuto',
	'admin:widget:admin_welcome:help' => "Piccola introduzione all'area amministrativa di Elgg",
	'admin:widget:admin_welcome:intro' => 'Benvenuti in Elgg! In questo momento siete di fronte al pannello di controllo che viene utilizzato per tenere sotto controllo quello che sta succedendo nel sito.',

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Consultate anche le risorse accessibili tramite i link di fondo pagina e soprattutto grazie che state usando Elgg!',

	'admin:widget:control_panel' => 'Pannello di controllo',
	'admin:widget:control_panel:help' => "Fornisce un facile accesso ai controlli più comuni",

	'admin:cache:flush' => 'Rinfresca la cache',
	'admin:cache:flushed' => "La cache del sito è stata rinfrescata",
	'admin:cache:purged' => "Le cache, del sito, sono state eliminate",

	'admin:footer:faq' => 'FAQ amministrative',
	'admin:footer:manual' => 'Manuale dell\'amministratore',
	'admin:footer:community_forums' => 'Forum della comunità di Elgg',
	'admin:footer:blog' => 'Blog di Elgg',

	'admin:plugins:category:all' => 'Tutti i plugin',
	'admin:plugins:category:active' => 'Plugin attivi',
	'admin:plugins:category:inactive' => 'Plugin inattivi',
	'admin:plugins:category:admin' => 'Amministrazione',
	'admin:plugins:category:bundled' => 'Inclusi',
	'admin:plugins:category:nonbundled' => 'Non inclusi',
	'admin:plugins:category:content' => 'Contenuti',
	'admin:plugins:category:development' => 'Sviluppo',
	'admin:plugins:category:enhancement' => 'Miglioramento',
	'admin:plugins:category:api' => 'Servizi/API',
	'admin:plugins:category:communication' => 'Comunicazione',
	'admin:plugins:category:security' => 'Sicurezza e Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Temi',
	'admin:plugins:category:widget' => 'Widget',
	'admin:plugins:category:utility' => 'Utilità',

	'admin:plugins:markdown:unknown_plugin' => 'Plugin sconosciuto.',
	'admin:plugins:markdown:unknown_file' => 'File sconosciuto.',
	'admin:notices:could_not_delete' => 'Impossibile eliminare l\'avviso.',
	'item:object:admin_notice' => 'Avviso amministrativo',
	'collection:object:admin_notice' => 'Notifiche per l\'amministratore',

	'admin:options' => 'Opzioni amministrative',
	'admin:security:information:https:warning' => "Si consiglia di proteggere il tuo sito Web utilizzando HTTPS, questo aiuta a proteggere i dati (ad esempio le password) dall'essere sniffati tramite la connessione Internet.",
	'admin:security:information:password_length' => 'Lunghezza minima della password',
	'admin:security:information:username_length:warning' => "Si raccomanda che il nome utente sia di minimo 4 caratteri.",
	'admin:security:information:php:session_gc:lifetime' => "Durata della sessione %s secondi",
	
	'admin:security:settings' => 'Impostazioni',
	'admin:security:settings:label:account' => 'Profilo',
	'admin:security:settings:label:notifications' => 'Notifiche',
	'admin:security:settings:label:site_secret' => 'Chiave segreta del sito',
	
	'admin:security:settings:notify_admins' => 'Avvisa tutti gli amministratori del sito quando un amministratore viene aggiunto o rimosso',
	
	'admin:security:settings:notify_user_admin' => 'Avvisa l\'utente quando il ruolo di amministratore viene aggiunto o rimosso',
	'admin:security:settings:notify_user_admin:help' => 'Questo invierà una notifica all\'utente che il ruolo di amministratore è stato aggiunto/rimosso dal proprio account.',
	
	'admin:security:settings:notify_user_ban' => 'Avvisa l\'utente quando il suo account viene (de)bannato',
	'admin:security:settings:protect_upgrade:help' => 'Questo proteggerà upgrade.php, quindi avrai bisogno di un token valido o dovrai essere un amministratore.',
	'admin:security:settings:protect_cron:help' => 'Questo proteggerà gli URL /cron con un token: solo se viene fornito un token valido verrà eseguito il cron.',
	'admin:security:settings:protect_cron:toggle' => 'Mostra/nascondi URL cron',
	'admin:security:settings:disable_password_autocomplete:help' => 'I dati inseriti in questi campi verranno memorizzati nella cache dal browser. Un utente malintenzionato, che possa accedere al browser della vittima, potrebbe rubare queste informazioni. Ciò è particolarmente importante se l\'applicazione viene comunemente utilizzata in computer condivisi come cyber cafè o terminal aeroportuali. Se lo disattivi, gli strumenti di gestione delle password non possono più compilare automaticamente questi campi. La configurazione dell\'attributo di completamento automatico, può essere specifico del browser.',
	
	'admin:security:settings:min_password_length' => "Lunghezza minima della password",
	'admin:security:settings:min_password_special:help' => "Configura il numero minimo di caratteri speciali (!@$%^&*()<>,.?/[]{}-=_+) che devono essere inclusi in una password. Usa 0 per non includere, vuoto per nessun requisito.",
	
	'admin:site:secret:regenerated' => "La chiave del tuo sito è stato rigenerata",
	
	'admin:notification:make_admin:admin:subject' => 'Un nuovo amministratore del sito è stato aggiunto a %s',
	'admin:notification:remove_admin:admin:subject' => 'Un amministratore del sito è stato rimosso da %s',
	'user:notification:ban:subject' => 'Il tuo account su %s è stato bannato',
	'user:notification:unban:body' => 'Il tuo account su %s non è più bannato. Puoi accedere nuovamente al sito.

Per andare sul sito, clicca qui:
%s',
	
	'admin:notification:unvalidated_users:subject' => "Utenti in attesa di approvazione su %s",

/**
 * Plugins
 */
	'plugins:settings:save:ok' => "Impostazioni per il plugin %s salvate con successo.",
	'plugins:settings:remove:fail' => "Si è verificato un errore durante la rimozione di tutte le impostazioni per il plug-in %s",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Nome",
	'admin:plugins:label:authors' => "Autori",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categorie',
	'admin:plugins:label:licence' => "Licenza",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "File",
	'admin:plugins:label:resources' => "Risorse",
	'admin:plugins:label:screenshots' => "Videate",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Segnala problema",
	'admin:plugins:label:donate' => "Dona",
	'admin:plugins:label:moreinfo' => 'ulteriori informazioni',
	'admin:plugins:label:version' => 'Versione',
	'admin:plugins:label:location' => 'Ubicazione',
	'admin:plugins:label:priority' => 'Priorità',
	'admin:plugins:label:dependencies' => 'Dipendenze',
	'admin:plugins:already:active' => 'I plugin selezionati sono già attivi.',
	'admin:plugins:already:inactive' => 'I plugin selezionati sono già inattivi.',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versione %s',
	'admin:plugin_settings' => 'Impostazioni plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'Questo plugin è attivo, ma ha delle dipendenze non soddisfatte. Si possono verificare dei problemi. Consultare "ulteriori informazioni" in basso per maggiori dettagli.',

	'admin:statistics:description' => "Questa è una panoramica sulle statistiche di questo sito. Se si desiderano statistiche più dettagliate, è disponibile uno strumento di amministrazione professionale.",
	'admin:statistics:opt:description' => "Visualizza informazioni statistiche sugli utenti e gli oggetti di questo sito.",
	'admin:statistics:opt:linktext' => "Visualizza statistiche...",
	'admin:statistics:label:user' => "Statistiche utente",
	'admin:statistics:label:numentities' => "Entità nel sito",
	'admin:statistics:label:numusers' => "Numero di utenti",
	'admin:statistics:label:numonline' => "Numero di utenti online",
	'admin:statistics:label:onlineusers' => "Utenti online in questo momento",
	'admin:statistics:label:admins'=>"Amministratori",
	'admin:statistics:label:version' => "Versione di Elgg",
	'admin:statistics:label:version:release' => "Versione",
	'admin:statistics:label:version:version' => "Rilascio",
	'admin:statistics:label:version:code' => "Versione Codice",
	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Server Web',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Ubicazione Log',
	'admin:server:label:php_version' => 'Versione PHP',
	'admin:server:label:php_ini' => 'Ubicazione file PHP ini',
	'admin:server:label:php_log' => 'Log PHP',
	'admin:server:label:mem_avail' => 'Memoria disponibile',
	'admin:server:label:mem_used' => 'Memoria usata',
	'admin:server:error_log' => "Log degli errori del server web",
	'admin:server:label:post_max_size' => 'Dimensione massima del POST',
	'admin:server:label:upload_max_filesize' => 'Dimensione massima di upload',
	'admin:server:warning:post_max_too_small' => '(Nota: post_max_size deve essere maggiore di questo valore per permettere degli upload di questa dimensione)',
	'admin:server:redis:inactive' => 'Redis non è configurato su questo server o non è stato ancora configurato nella configurazione di Elgg.
Per migliorare le prestazioni, si consiglia di abilitare e configurare redis (o memcache).',

	'admin:server:label:opcache' => 'cache OP',
	
	'admin:server:requirements:php_extension' => "Estensione PHP: %s",
	'admin:server:requirements:php_extension:required' => "Questa estensione PHP è necessaria per il corretto funzionamento di Elgg",
	'admin:server:requirements:database:server:required' => "Elgg richiede MySQL v5.5.3, o superiore, per il suo database",
	'admin:server:requirements:database:client:required' => "Elgg richiede pdo_mysql per connettersi al server del database",
	
	'admin:user:label:search' => "Trova utenti:",
	'admin:user:label:searchbutton' => "Cerca",

	'admin:user:ban:no' => "Impossibile espellere l'utente",
	'admin:user:ban:yes' => "Utente espulso.",
	'admin:user:self:ban:no' => "Non puoi espellere te stesso!",
	'admin:user:unban:no' => "Impossibile riammettere l'utente",
	'admin:user:unban:yes' => "Utente riammesso.",
	'admin:user:delete:no' => "Impossibile eliminare l'utente",
	'admin:user:delete:yes' => "L'utente %s è stato eliminato",
	'admin:user:self:delete:no' => "Non puoi eliminare te stesso!",

	'admin:user:resetpassword:yes' => "Password azzerata, notifica inviata all'utente.",
	'admin:user:resetpassword:no' => "La password non può essere azzerata.",

	'admin:user:makeadmin:yes' => "L'utente è ora un amministratore.",
	'admin:user:makeadmin:no' => "Impossibile rendere amministratore questo utente.",

	'admin:user:removeadmin:yes' => "L'utente non è più amministratore.",
	'admin:user:removeadmin:no' => "Impossibile rimuovere i privilegi di amministratore a questo utente.",
	'admin:user:self:removeadmin:no' => "Non puoi rimuovere i tuoi propri privilegi di amministratore.",
	'admin:menu_items:configure' => 'Configura le voci del menu principale',
	'admin:menu_items:description' => 'Seleziona l\'ordine delle voci di menu del sito. Gli elementi non configurati saranno aggiunti alla fine dell\'elenco.',
	'admin:menu_items:hide_toolbar_entries' => 'Sicuri di voler rimuovere i collegamenti dalla barra dei menu?',
	'admin:menu_items:saved' => 'Voci di menu salvate.',
	'admin:add_menu_item' => 'Aggiungi una voce di menu personalizzata',
	'admin:add_menu_item:description' => 'Specifica il nome da visualizzare e un URL per aggiungere una voce personalizzata al menu di navigazione.',
	'admin:default_widgets:unknown_type' => 'Tipo di widget sconosciuto',
	'admin:default_widgets:instructions' => 'Aggiungi, rimuovi, posiziona e configura widget predefiniti per la pagina widget selezionata. Queste modifiche riguarderanno solo i nuovi utenti del sito.',

	'admin:robots.txt:instructions' => "Modifica qui sotto il file robots.txt di questo sito",
	'admin:robots.txt:plugins' => "I plugin stanno aggiungendo le seguenti cose al file robots.txt",
	'admin:robots.txt:subdir' => "Lo strumento robots.txt non funzionerà perché Elgg è installato in una sotto cartella",
	'admin:robots.txt:physical' => "Lo strumento robots.txt non funzionerà perché un file robots.txt è fisicamente presente",

	'admin:maintenance_mode:default_message' => 'Questo sito non è al momento disponibile perché in manutenzione',
	'admin:maintenance_mode:mode_label' => 'Modalità di manutenzione',
	'admin:maintenance_mode:message_label' => 'Messaggio visualizzato agli utenti quando la modalità di manutenzione è attiva',
	'admin:maintenance_mode:saved' => 'Le impostazioni della modalità di manutenzione sono state salvate.',
	'admin:maintenance_mode:indicator_menu_item' => 'Il sito è in manutenzione.',
	'admin:login' => 'Login amministratore',

/**
 * User settings
 */

	'usersettings:statistics' => "Statistiche",
	'usersettings:statistics:opt:linktext' => "Statistiche del profilo utente",

	'usersettings:user' => "Impostazioni di %s",
	'usersettings:user:opt:linktext' => "Cambia le tue impostazioni",

	'usersettings:plugins:opt:linktext' => "Configura i tuoi strumenti",
	
	'usersettings:statistics:yourdetails' => "I tuoi dati",
	'usersettings:statistics:label:name' => "Nome completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:lastlogin' => "Ultimo accesso",
	'usersettings:statistics:label:membersince' => "Membro dal",
	'usersettings:statistics:label:numentities' => "I tuoi contenuti",

/**
 * Activity river
 */

	'river:all' => 'Attività dell\'intero sito',
	'river:mine' => 'Mie attività',
	'river:owner' => 'Attività di %s',
	'river:friends' => 'Attività degli amici',
	'river:select' => 'Mostra %s',
	'river:comments:all' => 'Visualizza tutti i %u commenti',

/**
 * Icons
 */

	'icon:size' => "Dimensioni icona",
	'icon:size:topbar' => "Barra superiore",
	'icon:size:tiny' => "Minuscola",
	'icon:size:small' => "Piccola",
	'icon:size:medium' => "Media",
	'icon:size:large' => "Grande",
	'icon:size:master' => "Grandissima",
	'entity:edit:icon:file:help' => "Lascia vuoto per mantenere l'icona corrente.",

/**
 * Generic action words
 */

	'save' => "Salva",
	'reset' => 'Azzera',
	'publish' => "Pubblica",
	'cancel' => "Elimina",
	'saving' => "Salvataggio in corso...",
	'update' => "Aggiorna",
	'preview' => "Anteprima",
	'edit' => "Modifica",
	'delete' => "Elimina",
	'accept' => "Accetta",
	'reject' => "Respingi",
	'decline' => "Rifiuta",
	'approve' => "Approva",
	'activate' => "Attiva",
	'deactivate' => "Disattiva",
	'disapprove' => "Rifiuta",
	'revoke' => "Revoca",
	'load' => "Carica",
	'upload' => "Carica",
	'download' => "Scarica",
	'ban' => "Espelli",
	'unban' => "Riammetti",
	'banned' => "Espulso",
	'enable' => "Abilita",
	'disable' => "Disabilita",
	'request' => "Richiedi",
	'complete' => "Completa",
	'open' => 'Apri',
	'close' => 'Chiudi',
	'hide' => 'Nascondi',
	'show' => 'Mostra',
	'reply' => "Rispondi",
	'more' => 'Di più',
	'more_info' => 'Più informazioni',
	'comments' => 'Commenti',
	'import' => 'Importa',
	'export' => 'Esporta',
	'untitled' => 'Senza titolo',
	'help' => 'Aiuto',
	'send' => 'Invia',
	'post' => 'Pubblica',
	'submit' => 'Invia',
	'comment' => 'Commenta',
	'upgrade' => 'Aggiorna',
	'sort' => 'Ordina',
	'filter' => 'Filtra',
	'new' => 'Nuovo',
	'add' => 'Aggiungi',
	'create' => 'Crea',
	'remove' => 'Rimuovi',
	'revert' => 'Ripristina',
	'validate' => 'Valida',
	'read_more' => 'Per saperne di più',
	'next' => 'Successivo',
	'previous' => 'Precedente',
	
	'site' => 'Sito',
	'activity' => 'Attività',
	'members' => 'Utenti',
	'menu' => 'Menu',
	'item' => 'Elemento',

	'up' => 'Su',
	'down' => 'Giù',
	'top' => 'In cima',
	'bottom' => 'In fondo',
	'right' => 'Destra',
	'left' => 'Sinistra',
	'back' => 'Indietro',

	'invite' => "Invita",

	'resetpassword' => "Azzera password",
	'changepassword' => "Cambia password",
	'makeadmin' => "Rendi amministratore",
	'removeadmin' => "Rimuovi amministratore",

	'option:yes' => "Sì",
	'option:no' => "No",

	'unknown' => 'Sconosciuto',
	'never' => 'Mai',

	'active' => 'Attivi',
	'total' => 'Totale',
	'unvalidated' => 'Utenti non ancora validati',
	
	'ok' => 'OK',
	'any' => 'Qualsiasi',
	'error' => 'Errore',

	'other' => 'Altro',
	'options' => 'Opzioni',
	'advanced' => 'Avanzate',

	'learnmore' => "Clicca qui per saperne di più",
	'unknown_error' => 'Errore sconosciuto',

	'content' => "contenuti",
	'content:latest' => 'Ultime attività',

	'link:text' => 'visualizza collegamento',

/**
 * Generic questions
 */

	'question:areyousure' => 'Si è sicuri?',

/**
 * Status
 */

	'status' => 'Stato',
	'status:unsaved_draft' => 'Bozza non salvata',
	'status:draft' => 'Bozza',
	'status:unpublished' => 'Non pubblicato',
	'status:published' => 'Pubblicato',
	'status:featured' => 'In evidenza',
	'status:open' => 'Aperto',
	'status:closed' => 'Chiuso',
	'status:unavailable' => 'Non disponibile',
	'status:active' => 'Attivi',

/**
 * Generic sorts
 */

	'sort:newest' => 'Ultimi',
	'sort:popular' => 'Popolari',
	'sort:alpha' => 'Alfabetico',
	'sort:priority' => 'Priorità',

/**
 * Generic data words
 */

	'title' => "Titolo",
	'description' => "Descrizione",
	'tags' => "Tag",
	'all' => "Tutti",
	'mine' => "Mie",

	'by' => 'di',
	'none' => 'nessuno',

	'annotations' => "Annotazioni",
	'relationships' => "Relazioni",
	'metadata' => "Metadato",
	'tagcloud' => "Nuvola di tag",

	'on' => 'On',
	'off' => 'Off',
	'number_counter:view:thousand' => "%sK",

/**
 * Entity actions
 */

	'edit:this' => 'Modifica questo',
	'delete:this' => 'Elimina questo',
	'comment:this' => 'Commenta questo',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Si è sicuri di voler eliminare questo elemento?",
	'deleteconfirm:plural' => "Si è sicuri di voler eliminare questi elementi?",

/**
 * User add
 */

	'useradd:subject' => 'Profilo utente creato',

/**
 * Messages
 */
	'messages:title:error' => 'Errore',
	'messages:title:warning' => 'Warning',
	'messages:title:help' => 'Aiuto',
	'messages:title:notice' => 'Notifica',
	'messages:title:info' => 'Info',

/**
 * Time
 */

	'input:date_format' => 'A-m-g',
	'input:date_format:datepicker' => 'aa-mm-gg', // jQuery UI datepicker format
	
	'friendlytime:justnow' => "proprio ora",
	'friendlytime:minutes' => "%s minuti fa",
	'friendlytime:minutes:singular' => "un minuto fa",
	'friendlytime:hours' => "%s ore fa",
	'friendlytime:hours:singular' => "un'ora fa",
	'friendlytime:days' => "%s giorni fa",
	'friendlytime:days:singular' => "ieri",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'g M A',

	'friendlytime:future:minutes' => "tra %s minuti",
	'friendlytime:future:minutes:singular' => "tra un minuto",
	'friendlytime:future:hours' => "tra %s ore",
	'friendlytime:future:hours:singular' => "tra un'ora",
	'friendlytime:future:days' => "tra %s giorni",
	'friendlytime:future:days:singular' => "domani",

	'date:month:01' => 'Gennaio %s',
	'date:month:02' => 'Febbraio %s',
	'date:month:03' => 'Marzo %s',
	'date:month:04' => 'Aprile %s',
	'date:month:05' => 'Maggio %s',
	'date:month:06' => 'Giugno %s',
	'date:month:07' => 'Luglio %s',
	'date:month:08' => 'Agosto %s',
	'date:month:09' => 'Settembre %s',
	'date:month:10' => 'Ottobre %s',
	'date:month:11' => 'Novembre %s',
	'date:month:12' => 'Dicembre %s',

	'date:month:short:01' => 'Gen %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'Mag %s',
	'date:month:short:06' => 'Giu %s',
	'date:month:short:07' => 'Lug %s',
	'date:month:short:08' => 'Ago %s',
	'date:month:short:09' => 'Set %s',
	'date:month:short:10' => 'Ott %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dic %s',

	'date:weekday:0' => 'Domenica',
	'date:weekday:1' => 'Lunedì',
	'date:weekday:2' => 'Martedì',
	'date:weekday:3' => 'Mercoledì',
	'date:weekday:4' => 'Giovedì',
	'date:weekday:5' => 'Venerdì',
	'date:weekday:6' => 'Sabato',

	'date:weekday:short:0' => 'Dom',
	'date:weekday:short:1' => 'Lun',
	'date:weekday:short:2' => 'Mar',
	'date:weekday:short:3' => 'Mer',
	'date:weekday:short:4' => 'Gio',
	'date:weekday:short:5' => 'Ven',
	'date:weekday:short:6' => 'Sab',

	'interval:minute' => 'Ogni minuto',
	'interval:fiveminute' => 'Ogni cinque minuti',
	'interval:fifteenmin' => 'Ogni quindici minuti',
	'interval:halfhour' => 'Ogni mezz\'ora',
	'interval:hourly' => 'Ogni ora',
	'interval:daily' => 'Giornaliero',
	'interval:weekly' => 'Settimanale',
	'interval:monthly' => 'Mensile',
	'interval:yearly' => 'Annuale',

/**
 * System settings
 */

	'installation:sitename' => "Nome del sito:",
	'installation:sitedescription' => "Breve descrizione del sito (opzionale):",
	'installation:sitedescription:help' => "Con i plug-in in bundle questo appare solo nel meta tag descrizione per i risultati dei motori di ricerca.",
	'installation:sitepermissions' => "Permessi di accesso predefiniti:",
	'installation:language' => "Lingua predefinita del sito:",
	'installation:debug' => "Determina la quantità di informazioni scritte nel log del server.",
	'installation:debug:label' => "Livello di Log:",
	'installation:debug:none' => 'Disabilita il log (raccomandato)',
	'installation:debug:error' => 'Registra solo gli errori critici',
	'installation:debug:warning' => 'Registra errori e allarmi',
	'installation:debug:notice' => 'Registra tutti gli errori, gli allarmi e gli avvisi',
	'installation:debug:info' => 'Registra tutto',

	// Walled Garden support
	'installation:registration:description' => 'La registrazione degli utenti è abilitata in modo predefinito. Disabilitare questa opzione per impedire che gli utenti possano registrarsi in modo autonomo.',
	'installation:registration:label' => 'Permetti ai nuovi utenti di registrarsi',
	'installation:adminvalidation:notification:description' => 'Se abilitato, gli amministratori del sito, riceveranno una notifica che ci sono convalide utenti in sospeso. Un amministratore può disabilitare la notifica nella pagina delle impostazioni personali.',
	'installation:adminvalidation:notification:direct' => 'Diretto',
	'installation:walled_garden:description' => 'Abilitare questa opzione per impedire agli utenti non registrati di visualizzare i contenuti del sito ad eccezione delle pagine pubbliche (come ad esempio login e registrazione)',
	'installation:walled_garden:label' => 'Limita le pagine agli utenti registrati',

	'installation:siteemail' => "Indirizzo email del sito (usato quando si inviano email di sistema):",
	'installation:default_limit' => "Numero predefinito di elementi per pagina",

	'admin:site:access:warning' => "Questa è l'impostazione sulla privacy predefinita quando gli utenti creano nuovi contenuti. Il cambiamento di questa impostazione non modifica l'accesso ai contenuti.",
	'installation:allow_user_default_access:description' => "Questa impostazione permette agli utenti di poter impostare il livello di privacy sovrascrivendo il livello di privacy suggerito dal sistema.",
	'installation:allow_user_default_access:label' => "Permetti agli utenti di definire i livelli di accesso",

	'installation:simplecache:description' => "La cache semplice aumenta le prestazioni memorizzando contenuti statici come file CSS e JavaScript.",
	'installation:simplecache:label' => "Utilizza la cache semplice (consigliato)",

	'installation:cache_symlink:description' => "Il link simbolico alla cartella della cache semplice permette al server di fornire viste statiche evitando l'engine, migliorando considerevolmente le prestazioni e riducendo il carico del server.",
	'installation:cache_symlink:label' => "Usare un link simbolico alla cache semplice (raccomandato)",
	'installation:cache_symlink:warning' => "È stato stabilito un link simbolico. Se per qualche ragione si vuole eliminare tale link eliminare la cartella del link simbolico dal server.",
	'installation:cache_symlink:error' => "A causa della configurazione del server non è possibile stabilire un link simbolico automaticamente. Fare riferimento alla documentazione e creare un link simbolico manualmente.",

	'installation:minify:description' => "La cache semplice può anche aumentare le prestazioni comprimendo file JavaScript e CSS. (Richiede che la cache semplice sia abilitata.)",
	'installation:minify_js:label' => "Comprimi JavaScript (consigliato)",
	'installation:minify_css:label' => "Comprimi CSS (consigliato)",

	'installation:htaccess:needs_upgrade' => "Occorre aggiornare il file .htaccess in modo che il percorso sia iniettato nel parametro GET __elgg_uri (si può usare htaccess_dist come guida).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg non si può connettere a sé stesso per verificare le regole rewrite in modo appropriato. Verificare che curl stia funzionando correttamente e che non ci siano limitazioni IP che impediscono connessioni locali.",

	'installation:systemcache:description' => "La cache di sistema riduce il tempo di caricamento del motore di Elgg memorizzando i dati in file.",
	'installation:systemcache:label' => "Utilizzare la cache di sistema (consigliato)",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content' => 'Contenuti',
	'admin:legend:content_access' => 'Accesso ai contenuti',
	'admin:legend:site_access' => 'Accesso al sito',
	'admin:legend:debug' => 'Debug e Log',
	'config:i18n:who_can_change_language:admin_only' => "Solo amministratori",
	'config:remove_branding:label' => "Rimuovi il marchio Elgg",
	'config:content:pagination_behaviour:ajax-append' => "Aggiungi i nuovi dati dell'elenco prima, o dopo, l'elenco",
	'config:content:pagination_behaviour:ajax-append-auto' => "Aggiungi i nuovi dati dell'elenco prima o dopo l'elenco (automaticamente se scorri nella vista)",
	'config:email' => "Email",
	'config:email_html_part:help' => "La posta in uscita verrà racchiusa in un modello HTML",
	'config:email_html_part_images:base64' => "Codifica Base64",

	'upgrading' => 'Aggiornamento in corso...',
	'upgrade:core' => 'L\'installazione di Elgg è stata aggiornata.',
	'upgrade:unlock' => 'Sblocca aggiornamenti',
	'upgrade:unlock:confirm' => "Il database è bloccato per ulteriori aggiornamenti. Eseguire più aggiornamenti simultaneamente è pericoloso. Si dovrebbe continuare solo se si è sicuri che un altro aggiornamento non è in esecuzione. Sbloccare?",
	'upgrade:locked' => "Impossibile aggiornare. Un altro aggiornamento è in esecuzione. Per rimuovere il blocco all'aggiornamento visitare la sezione di amministrazione.",
	'upgrade:unlock:success' => "L'aggiornamento è stato sbloccato.",

	'admin:pending_upgrades' => 'Il sito ha degli aggiornamenti in sospeso che richiedono la vostra immediata attenzione.',
	'admin:view_upgrades' => 'Visualizza aggiornamenti in sospeso.',
	'item:object:elgg_upgrade' => 'Aggiornamenti del sito',
	'collection:object:elgg_upgrade' => 'Aggiornamenti del sito',
	'admin:upgrades:none' => 'Questa installazione è aggiornata!',

	'upgrade:success_count' => 'Aggiornati:',
	'upgrade:finished' => 'Aggiornamento completato',
	'upgrade:finished_with_errors' => '<p>L\'aggiornamento è terminato con degli errori. Aggiornare la pagina e provare a eseguire nuovamente l\'aggiornamento.</p><br>Se l\'errore si manifesta nuovamente, controllare il log file degli errori del server per trovare le possibili cause. Per eliminare l\'errore si può cercare aiuto nel <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> della comunità di Elgg.',
	
	// Strings specific for the database guid columns reply upgrade
	
/**
 * Welcome
 */

	'welcome' => "Benvenuto",
	'welcome:user' => 'Benvenuto %s',

/**
 * Emails
 */

	'email:from' => 'Da',
	'email:to' => 'A',
	'email:subject' => 'Oggetto',
	'email:body' => 'Corpo',

	'email:settings' => "Impostazioni email",
	'email:address:label' => "Indirizzo email",
	'email:address:password' => "Password",

	'email:save:success' => "Nuovo indirizzo email salvato. È richiesta la verifica.",
	'email:save:fail' => "Impossibile salvare il nuovo indirizzo email.",

	'friend:newfriend:subject' => "%s ti ha aggiunto agli amici!",

	'email:changepassword:subject' => "Password cambiata!",
	'email:changepassword:body' => "La tua password è stata modificata.",

	'email:resetpassword:subject' => "Password azzerata!",

	'email:changereq:subject' => "Richiesta di cambio password.",
	'email:confirm:email:old:body' => "Il tuo indirizzo email su '%s' è stato cambiato-
Da adesso in avanti riceverai le notifiche su '%s'

Se non hai richiesto tu questa modifica, contatta un amministratore del sito.
%s",

/**
 * user default access
 */

	'default_access:settings' => "Il tuo livello di accesso predefinito",
	'default_access:label' => "Accesso predefinito",
	'user:default_access:success' => "Il tuo nuovo livello di accesso predefinito è stato salvato.",
	'user:default_access:failure' => "Impossibile salvare il tuo nuovo livello di accesso predefinito.",

/**
 * Comments
 */

	'comments:count' => "%s commenti",
	'item:object:comment' => 'Commenti',
	'collection:object:comment' => 'Commenti',

	'generic_comments:add' => "Aggiungi un commento",
	'generic_comments:edit' => "Modifica commento",
	'generic_comments:post' => "Invia commento",
	'generic_comments:text' => "Commento",
	'generic_comments:latest' => "Ultimi commenti",
	'generic_comment:posted' => "Il tuo commento è stato inviato.",
	'generic_comment:updated' => "Il commento è stato aggiornato.",
	'entity:delete:object:comment:success' => "Commento eliminato con successo.",
	'generic_comment:blank' => "Spiacenti, devi scrivere qualcosa nel commento per poterlo salvare.",
	'generic_comment:notfound' => "Spiacenti, impossibile trovare il commento specificato.",
	'generic_comment:failure' => "Errore inatteso durante il salvataggio del commento.",
	'generic_comment:none' => 'Nessun commento',
	'generic_comment:title' => 'Commento di %s',
	'generic_comment:on' => '%s su %s',
	'generic_comments:latest:posted' => 'ha inviato un',

/**
 * Entities
 */

	'byline' => 'Di %s',
	'byline:ingroup' => 'nel gruppo %s',
	
	'entity:delete:item' => 'Elemento',
	'entity:delete:item_not_found' => 'Elemento non trovato.',
	'entity:delete:permission_denied' => 'Permessi insufficienti per visualizzare questo elemento.',
	'entity:delete:success' => 'L\'elemento %s è stato eliminato',
	'entity:delete:fail' => 'L\'elemento %s non può essere eliminato',
	
	'entity:subscribe' => "Sottoscrivi",
	'entity:subscribe:fail' => "Si è verificato un errore durante l'iscrizione a %s",
	'entity:unsubscribe:success' => "Hai annullato con successo l'iscrizione a %s",
	
	'entity:mute' => "Silenzia le notifiche",

/**
 * Annotations
 */
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Il modulo è privo dei campi __token o __ts',
	'actiongatekeeper:tokeninvalid' => "La pagina che stavi utilizzando è scaduta. Per favore riprova.",
	'actiongatekeeper:timeerror' => 'La pagina che stavi utilizzando è scaduta. Per favore ricarica la pagina e riprova.',
	'actiongatekeeper:pluginprevents' => 'Spiacenti. Il tuo modulo non può essere inviato per ragioni sconosciute.',
	'actiongatekeeper:uploadexceeded' => 'La dimensione dei file caricati supera il limite impostato dall\'amministratore del sito',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Impossibile contattare %s. Si potrebbero riscontrare problemi nel salvare i contenuti. Si prega di ricaricare questa pagina.',
	'js:lightbox:current' => "immagine %s di %s",

/**
 * Diagnostics
 */
	
/**
 * Miscellaneous
 */
	'elgg:powered' => "Generato da Elgg",
	
/**
 * Cli commands
 */
	'cli:cache:purge:description' => "Elimina le cache di Elgg",
	
	'cli:database:seed:description' => "alimenta il database con entità false",
	'cli:database:seed:argument:create' => "Crea sempre nuove entità durante il seeding",
	'cli:database:seed:log:error:faker' => "Questo è uno strumento per sviluppatori attualmente inteso solo a scopo di test. Si prega di astenersi dall'utilizzarlo.",
	
	'cli:database:unseed:description' => "Rimuove false entità con seeding dal database",
	
	'cli:plugins:list:description' => "Elenca tutti i plugin installati nel sito.",
	'cli:plugins:list:error:status' => "%s non è uno stato valido. Le opzioni consentite sono: %s",
	'cli:upgrade:batch:finished' => "Gli aggiornamenti in corso sono terminati",
	'cli:upgrade:batch:notfound' => "Nessuna classe di aggiornamento trovata per %s",

	'cli:upgrade:list:description' => "Elenca tutti gli aggiornamenti nel sistema",
	'cli:upgrade:list:completed' => "Aggiornamenti completati",
	'cli:upgrade:list:notfound' => "Non  ho trovato aggiornamenti",
	
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
	"cs" => "Czech",
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
	"is" => "Icelandic",
	"it" => "Italiano",
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
	"to" => "A",
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

	"field:required" => 'Richiesto',

	"core:upgrade:2017080900:title" => "Modifica la codifica del database per il supporto multibyte",
);
