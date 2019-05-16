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

	'login' => "Accedi",
	'loginok' => "Accesso riuscito.",
	'loginerror' => "Accesso non riuscito. Riprovare dopo aver verificato nome utente e password.",
	'login:empty' => "Specificare nome utente (email) e password",
	'login:baduser' => "Impossibile caricare il tuo profilo utente",
	'auth:nopams' => "Errore interno. Nessun metodo di autenticazione disponibile.",

	'logout' => "Esci",
	'logoutok' => "Uscita completata.",
	'logouterror' => "Impossibile uscire. Riprovare.",
	'session_expired' => "La sessione è scaduta. <a href='javascript:location.reload(true)'>Ricaricare</a> la pagina per accedere nuovamente.",
	'session_changed_user' => "Hai avuto accesso come altro utente. Devi <a href='javascript:location.reload(true)'>ricaricare</a> la pagina.",

	'loggedinrequired' => "Occorre accedere per visualizzare la pagina richiesta.",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "Occorre essere amministratori per visualizzare la pagina richiesta.",
	'membershiprequired' => "Occorre essere membri di questo gruppo per visualizzare la pagina richiesta.",
	'limited_access' => "Permessi insufficienti per visualizzare la pagina richiesta.",
	'invalid_request_signature' => "L'URL della pagina a cui si sta cercando di accedere non è valido o è scaduto.",

/**
 * Errors
 */

	'exception:title' => "Errore irreversibile",
	'exception:contact_admin' => 'Si è verificato un errore non recuperabile che è stato tracciato. Contattare l\'amministratore del sito fornendo le seguenti informazioni:',

	'actionundefined' => "L'azione richiesta (%s) non è prevista dal sistema.",
	'actionnotfound' => "Impossibile trovare il file previsto dal comando %s.",
	'actionloggedout' => "Gli utenti non registrati non possono eseguire questa azione.",
	'actionunauthorized' => 'Operazione non autorizzata',

	'ajax:error' => 'Errore inatteso durante una chiamata AJAX. Forse manca la connessione al server.',
	'ajax:not_is_xhr' => 'Non si può accedere alle viste AJAX in modo diretto',

	'PluginException:MisconfiguredPlugin' => "Il plugin %s (guid: %s) è stato disabilitato perché non è configurato correttamente. Si prega di consultare il wiki di Elgg per scoprirne le cause (http://learn.elgg.org/).",
	'PluginException:CannotStart' => 'Il plugin %s (guid: %s) è stato disattivato perché impossibile avviarlo. Motivo: %s',
	'PluginException:InvalidID' => "%s non è un ID di plugin valido.",
	'PluginException:InvalidPath' => "%s non è un percorso valido per il plugin.",
	'PluginException:InvalidManifest' => 'Manifest File non valido per il plugin %s',
	'PluginException:InvalidPlugin' => '%s non è un plugin valido.',
	'PluginException:InvalidPlugin:Details' => '%s non è un plugin valido: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin non può avere un\'istanza nulla. Occorre passare un GUID, un plugin ID, o un percorso completo.',
	'ElggPlugin:MissingID' => 'ID del plugin mancante (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'ElggPluginPackage mancante per il plugin ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Il file richiesto "%s" è mancante.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'La cartella di questo plugin deve essere rinominata in "%s" per coincidere con l\'ID specificato nel suo Manifest File.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Il suo File Manifest contiene un tipo di dipendenza non valido "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Il suo File Manifest contiene un fornisce tipo "%s" non valido.',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'C\'è una dipendenza %s non valida "%s" nel plugin %s.  I plugin non possono avere conflitti con (o richiedere qualcosa che forniscono) essi stessi!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Error:ID' => 'Error in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error in plugin path "%s"',
	'ElggPlugin:Error:Unknown' => 'Undefined plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Impossibile includere %s del plugin %s (guid: %s) a %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Impossibile aprire il percorso delle viste del plugin %s (guid: %s) a %s.',
	'ElggPlugin:Exception:NoID' => 'Nessun ID per il plugin guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Package cannot be loaded',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest is missing or invalid',
	'PluginException:NoPluginName' => "Impossibile trovare il nome del plugin.",
	'PluginException:ParserError' => 'Errore di parsing del File Manifest con l\'API versione %s nel plugin %s.',
	'PluginException:NoAvailableParser' => 'Impossibile trovare un parser per il File Manifest con l\'API versione %s nel plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Attributo '%s' mancante nel File Manifest del plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s è un plugin non valido ed è stato disattivato.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => 'Richiede',
	'ElggPlugin:Dependencies:Suggests' => 'Suggerisce',
	'ElggPlugin:Dependencies:Conflicts' => 'È in conflitto',
	'ElggPlugin:Dependencies:Conflicted' => 'Era in conflitto',
	'ElggPlugin:Dependencies:Provides' => 'Fornisce',
	'ElggPlugin:Dependencies:Priority' => 'Priorità',

	'ElggPlugin:Dependencies:Elgg' => 'Versione di Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Versione PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Estensione PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Impostazione PHP ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Dopo %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Prima di %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s non è installato',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Mancante',

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

	'PageNotFoundException' => 'The page you are trying to view does not exist or you do not have permissions to view it',
	'EntityNotFoundException' => 'The content you were trying to access has been removed or you do not have permissions to access it.',
	'EntityPermissionsException' => 'You do not have sufficient permissions for this action.',
	'GatekeeperException' => 'You do not have permissions to view the page you are trying to access',
	'BadRequestException' => 'Bad request',
	'ValidationException' => 'Submitted data did not meet the requirements, please check your input.',
	'LogicException:InterfaceNotImplemented' => '%s must implement %s',

	'deprecatedfunction' => 'Attenzione: questo codice usa la funzione non più approvata \'%s\' e non compatibile con questa versione di Elgg.',

	'pageownerunavailable' => 'Attenzione: il proprietario %d di questa pagina non è accessibile!',
	'viewfailure' => 'Si è verificato un errore interno nella vista %s',
	'view:missing_param' => "Il parametro richiesto '%s' non si trova nella vista %s",
	'changebookmark' => 'Per favore cambia il segnalibro di questa pagina.',
	'noaccess' => 'I contenuti che stavi tentando di visualizzare sono stati rimossi, o non hai i permessi per visualizzarli.',
	'error:missing_data' => 'Ci sono dei dati mancanti nella tua richiesta',
	'save:fail' => 'Si è verificato un problema durante il salvataggio dei tuoi dati',
	'save:success' => 'I tuoi dati sono stati salvati',

	'forward:error' => 'Sorry. An error occurred while redirecting to you to another site.',

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

	'access:label:private' => "Private",
	'access:label:logged_in' => "Logged in users",
	'access:label:public' => "Public",
	'access:label:logged_out' => "Logged out users",
	'access:label:friends' => "Friends",
	'access' => "Accesso",
	'access:overridenotice' => "Nota: per decisione del gruppo, questi contenuti saranno accessibili solo ai membri del gruppo.",
	'access:limited:label' => "Limitato",
	'access:help' => "Il livello d'accesso",
	'access:read' => "Accesso in lettura",
	'access:write' => "Accesso in scrittura",
	'access:admin_only' => "Solo amministratori",
	'access:missing_name' => "Nome del livello di accesso mancante",
	'access:comments:change' => "Questa discussione è al momento visualizzabile solo da un pubblico selezionato. Fai attenzione a scegliere con chi condividerla.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Pannello di controllo",
	'dashboard:nowidgets' => "Il tuo pannello di controllo ti permette di tracciare le attività e i contenuti di questo sito che più ti interessano.",

	'widgets:add' => 'Aggiungi un widget',
	'widgets:add:description' => "Clicca su pulsante qualsiasi in basso per aggiungere il widget alla pagina.",
	'widgets:position:fixed' => '(posizione fissa nella pagina)',
	'widget:unavailable' => 'Hai già aggiunto questo widget',
	'widget:numbertodisplay' => 'Numero di elementi da mostrare',

	'widget:delete' => 'Rimuovi %s',
	'widget:edit' => 'Personalizza questo widget',

	'widgets' => "Widget",
	'widget' => "Widget",
	'item:object:widget' => "Widget",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "Il Widget è stato salvato.",
	'widgets:save:failure' => "Impossibile salvare il tuo widget.",
	'widgets:add:success' => "Il widget è stato aggiunto.",
	'widgets:add:failure' => "Impossibile aggiungere il tuo widget.",
	'widgets:move:failure' => "Impossibile memorizzare la posizione del nuovo widget.",
	'widgets:remove:failure' => "Impossibile rimuovere questo widget",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "Gruppo",
	'item:group' => "Gruppi",
	'collection:group' => 'Groups',
	'item:group:group' => "Group",
	'collection:group:group' => 'Groups',
	'groups:tool_gatekeeper' => "The requested functionality is currently not enabled in this group",

/**
 * Users
 */

	'user' => "Utente",
	'item:user' => "Utenti",
	'collection:user' => 'Users',
	'item:user:user' => 'User',
	'collection:user:user' => 'Users',

	'friends' => "Amici",
	'collection:friends' => 'Friends\' %s',

	'avatar' => 'Immagine del profilo',
	'avatar:noaccess' => "Non sei autorizzato a modificare l'immagine del profilo di questo utente",
	'avatar:create' => 'Crea la tua immagine del profilo',
	'avatar:edit' => 'Modifica immagine del profilo',
	'avatar:upload' => 'Carica una nuova immagine del profilo',
	'avatar:current' => 'Immagine del profilo attuale',
	'avatar:remove' => 'Rimuovi la tua immagine del profilo e inserisci quella predefinita',
	'avatar:crop:title' => 'Strumento per ritagliare l\'immagine',
	'avatar:upload:instructions' => "L'immagine del tuo profilo viene mostrata ovunque nel sito. Puoi cambiarla quando vuoi (formati di file accettati: GIF, JPG o PNG)",
	'avatar:create:instructions' => 'Clicca e trascina il quadrato in basso per ritagliare la tua immagine del profilo a piacimento. Un\'anteprima comparirà nel riquadro a destra. Una volta soddisfatti clicca su \'Crea la tua immagine del profilo\'. Questa versione ritagliata sarà usata in tutto il sito come tua immagine del profilo',
	'avatar:upload:success' => 'Immagine del profilo caricata',
	'avatar:upload:fail' => 'Impossibile caricare l\'immagine del profilo',
	'avatar:resize:fail' => 'Impossibile ridimensionare l\'immagine del profilo',
	'avatar:crop:success' => 'Ritaglio dell\'immagine del profilo completata',
	'avatar:crop:fail' => 'Impossibile ritagliare l\'immagine del profilo',
	'avatar:remove:success' => 'Rimozione dell\'immagine del profilo completata',
	'avatar:remove:fail' => 'Impossibile rimuovere l\'immagine del profilo',
	
	'action:user:validate:already' => "%s was already validated",
	'action:user:validate:success' => "%s has been validated",
	'action:user:validate:error' => "An error occurred while validating %s",

/**
 * Feeds
 */
	'feed:rss' => 'Feed RSS per questa pagina',
	'feed:rss:title' => 'RSS feed for this page',
/**
 * Links
 */
	'link:view' => 'visualizza link',
	'link:view:all' => 'Visualizza tutto',


/**
 * River
 */
	'river' => "Attività",
	'river:user:friend' => "%s is now a friend with %s",
	'river:update:user:avatar' => '%s ha una nuova immagine del profilo',
	'river:noaccess' => 'Permessi insufficienti per visualizzare questo elemento.',
	'river:posted:generic' => '%s inviato',
	'riveritem:single:user' => 'un utente',
	'riveritem:plural:user' => 'alcuni utenti',
	'river:ingroup' => 'nel gruppo %s',
	'river:none' => 'Nessuna attività',
	'river:update' => 'Aggiornamento di %s',
	'river:delete' => 'Rimuovi questo elemento',
	'river:delete:success' => 'L\'elemento di attività è stato eliminato.',
	'river:delete:fail' => 'L\'elemento di attività non può essere eliminato.',
	'river:delete:lack_permission' => 'Permessi insufficienti per eliminare questo elemento di attività.',
	'river:can_delete:invaliduser' => 'Impossibile controllare canDelete per user_guid [%s] perché l\'utente non esiste.',
	'river:subject:invalid_subject' => 'Utente non valido',
	'activity:owner' => 'Visualizza attività',

	

/**
 * Notifications
 */
	'notifications:usersettings' => "Impostazioni delle notifiche",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "Le impostazioni delle notifiche sono state salvate.",
	'notifications:usersettings:save:fail' => "Si è verificato un problema durante il salvataggio delle impostazioni delle notifiche.",

	'notification:subject' => 'Notifica su %s',
	'notification:body' => 'Visualizza la nuova attività qui %s',

/**
 * Search
 */

	'search' => "Cerca",
	'searchtitle' => "Cerca: %s",
	'users:searchtitle' => "Cerca tra gli utenti: %s",
	'groups:searchtitle' => "Cerca tra i gruppi: %s",
	'advancedsearchtitle' => "%s con risultati che corrispondono a %s",
	'notfound' => "Nessun risultato trovato.",
	'next' => "Successivo",
	'previous' => "Precedente",

	'viewtype:change' => "Cambia tipo di elenco",
	'viewtype:list' => "Vista a elenco",
	'viewtype:gallery' => "Galleria",

	'tag:search:startblurb' => "Elemento con tag corrispondenti a '%s':",

	'user:search:startblurb' => "Utenti corrispondenti '%s':",
	'user:search:finishblurb' => "Per visualizzare di più, clicca qui.",

	'group:search:startblurb' => "Gruppi corrispondenti a '%s':",
	'group:search:finishblurb' => "Per visualizzare di più, clicca qui:",
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

	'registration:noname' => 'Display name is required.',
	'registration:notemail' => 'L\'indirizzo email fornito non sembra valido.',
	'registration:userexists' => 'Il nome utente  è già utilizzato',
	'registration:usernametooshort' => 'Il nome utente deve contenere almeno %u caratteri.',
	'registration:usernametoolong' => 'Il nome utente è troppo lungo. Può avere un massimo di %u caratteri',
	'registration:passwordtooshort' => 'La password deve contenere un minimo di %u caratteri.',
	'registration:dupeemail' => 'Questo indirizzo email è già utilizzato.',
	'registration:invalidchars' => 'Spiacenti, il nome utente contiene il carattere %s che non è valido. I caratteri seguenti non sono validi: %s',
	'registration:emailnotvalid' => 'Spiacenti, l\'indirizzo email inserito non è valido in questo sistema',
	'registration:passwordnotvalid' => 'Spiacenti, la password inserita non è valida in questo sistema',
	'registration:usernamenotvalid' => 'Spiacenti, il nome utente inserito non è valido in questo sistema',

	'adduser' => "Aggiungi utente",
	'adduser:ok' => "Nuovo utente aggiunto",
	'adduser:bad' => "Impossibile creare il nuovo utente",

	'user:set:name' => "Impostazioni del nome di profilo",
	'user:name:label' => "Nome visualizzato",
	'user:name:success' => "Il nome visualizzato è stato cambiato nel sistema.",
	'user:name:fail' => "Impossibile cambiare il nome visualizzato nel sistema.",
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Could not change username on the system.",

	'user:set:password' => "Password del profilo",
	'user:current_password:label' => 'Password attuale',
	'user:password:label' => "Nuova password",
	'user:password2:label' => "Ripetere la nuova password",
	'user:password:success' => "Password cambiata",
	'user:password:fail' => "Impossibile cambiare la password nel sistema.",
	'user:password:fail:notsame' => "Le due password non coincidono!",
	'user:password:fail:tooshort' => "La password è troppo corta.",
	'user:password:fail:incorrect_current_password' => 'La password attuale inserita non è corretta.',
	'user:changepassword:unknown_user' => 'Utente non valido.',
	'user:changepassword:change_password_confirm' => 'Questo cambierà la tua password.',

	'user:set:language' => "Impostazioni della lingua",
	'user:language:label' => "Lingua",
	'user:language:success' => "Le impostazioni della lingua sono state aggiornate.",
	'user:language:fail' => "Impossibile salvare le impostazioni della lingua.",

	'user:username:notfound' => 'Nome utente %s non trovato.',
	'user:username:help' => 'Please be aware that changing a username will change all dynamic user related links',

	'user:password:lost' => 'Password smarrita',
	'user:password:hash_missing' => 'Regretfully, we must ask you to reset your password. We have improved the security of passwords on the site, but were unable to migrate all accounts in the process.',
	'user:password:changereq:success' => 'Richiesta di una nuova password completata, email inviata.',
	'user:password:changereq:fail' => 'Impossibile richiedere una nuova password.',

	'user:password:text' => 'Per richiedere una nuova password inserire il tuo nome utente o l\'indirizzo email con cui ti sei registrato e clicca sul pulsante Richiedi',

	'user:persistent' => 'Ricordami',

	'walled_garden:home' => 'Home',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Amministrazione',
	'menu:page:header:configure' => 'Configurazione',
	'menu:page:header:develop' => 'Sviluppo',
	'menu:page:header:information' => 'Information',
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

	'admin:statistics' => 'Statistiche',
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Latest Cron Jobs',
	'admin:cron:period' => 'Cron period',
	'admin:cron:friendly' => 'Last completed',
	'admin:cron:date' => 'Date and time',
	'admin:cron:msg' => 'Messaggio',
	'admin:cron:started' => 'Cron job per "%s" avviati alle %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
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
	'admin:users:add' => 'Aggiungi un nuovo utente',
	'admin:users:description' => "Questo pannello di amministrazione permette di controllare le impostazioni del sito. Selezionare un'opzione qui sotto per iniziare.",
	'admin:users:adduser:label' => "Cliccare qui per aggiungere un nuovo utente...",
	'admin:users:opt:linktext' => "Configura utenti...",
	'admin:users:opt:description' => "Configura utenti e informazioni dei profili",
	'admin:users:find' => 'Trova',
	'admin:users:unvalidated' => 'Unvalidated',
	'admin:users:unvalidated:no_results' => 'No unvalidated users.',
	'admin:users:unvalidated:registered' => 'Registered: %s',
	
	'admin:configure_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'Aggiornamenti',
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

	'admin:settings' => 'Impostazioni',
	'admin:settings:basic' => 'Impostazioni di base',
	'admin:settings:advanced' => 'Impostazioni avanzate',
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
	'admin:statistics:numentities' => 'Content Statistics',
	'admin:statistics:numentities:type' => 'Content type',
	'admin:statistics:numentities:number' => 'Number',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Benvenuto',
	'admin:widget:admin_welcome:help' => "Piccola introduzione all'area amministrativa di Elgg",
	'admin:widget:admin_welcome:intro' =>
'Benvenuti in Elgg! In questo momento siete di fronte al pannello di controllo che viene utilizzato per tenere sotto controllo quello che sta succedendo nel sito.',

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
	'admin:widget:admin_welcome:outro' => '<br />Consultate anche le risorse accessibili tramite i link di fondo pagina e soprattutto grazie che state usando Elgg!',

	'admin:widget:control_panel' => 'Pannello di controllo',
	'admin:widget:control_panel:help' => "Fornisce un facile accesso ai controlli più comuni",

	'admin:cache:flush' => 'Rinfresca la cache',
	'admin:cache:flushed' => "La cache del sito è stata rinfrescata",

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

	'admin:notices:delete_all' => 'Dismiss all %s notices',
	'admin:notices:could_not_delete' => 'Impossibile eliminare l\'avviso.',
	'item:object:admin_notice' => 'Avviso amministrativo',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => 'Opzioni amministrative',

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

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Impostazioni per il plugin %s salvate con successo.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving user settings for the %s plugin.",
	'item:object:plugin' => 'Plugins',
	'collection:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activate All',
	'admin:plugins:deactivate_all' => 'Deactivate All',
	'admin:plugins:activate' => 'Activate',
	'admin:plugins:deactivate' => 'Deactivate',
	'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
	'admin:plugins:opt:linktext' => "Configure tools...",
	'admin:plugins:opt:description' => "Configure the tools installed on the site.",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Nome",
	'admin:plugins:label:author' => "Autore",
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
	'admin:plugins:label:contributors' => 'Contributori',
	'admin:plugins:label:contributors:name' => 'Nome',
	'admin:plugins:label:contributors:email' => 'Email',
	'admin:plugins:label:contributors:website' => 'Sito web',
	'admin:plugins:label:contributors:username' => 'Nome utente nella community',
	'admin:plugins:label:contributors:description' => 'Descrizione',
	'admin:plugins:label:dependencies' => 'Dipendenze',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'This plugin has unmet dependencies and cannot be activated. Check dependencies under more info.',
	'admin:plugins:warning:invalid' => 'This plugin is invalid: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Check <a rel="nofollow" href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:cannot_activate' => 'cannot activate',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => 'I plugin selezionati sono già attivi.',
	'admin:plugins:already:inactive' => 'I plugin selezionati sono già inattivi.',

	'admin:plugins:set_priority:yes' => "Reordered %s.",
	'admin:plugins:set_priority:no' => "Could not reorder %s.",
	'admin:plugins:set_priority:no_with_msg' => "Could not reorder %s. Error: %s",
	'admin:plugins:deactivate:yes' => "Deactivated %s.",
	'admin:plugins:deactivate:no' => "Could not deactivate %s.",
	'admin:plugins:deactivate:no_with_msg' => "Could not deactivate %s. Error: %s",
	'admin:plugins:activate:yes' => "Activated %s.",
	'admin:plugins:activate:no' => "Could not activate %s.",
	'admin:plugins:activate:no_with_msg' => "Could not activate %s. Error: %s",
	'admin:plugins:categories:all' => 'All categories',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versione %s',
	'admin:plugin_settings' => 'Impostazioni plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'Questo plugin è attivo, ma ha delle dipendenze non soddisfatte. Si possono verificare dei problemi. Consultare "ulteriori informazioni" in basso per maggiori dettagli.',

	'admin:plugins:dependencies:type' => 'Tipo',
	'admin:plugins:dependencies:name' => 'Nome',
	'admin:plugins:dependencies:expected_value' => 'Valore atteso',
	'admin:plugins:dependencies:local_value' => 'Valore reale',
	'admin:plugins:dependencies:comment' => 'Commento',

	'admin:statistics:description' => "Questa è una panoramica sulle statistiche di questo sito. Se si desiderano statistiche più dettagliate, è disponibile uno strumento di amministrazione professionale.",
	'admin:statistics:opt:description' => "Visualizza informazioni statistiche sugli utenti e gli oggetti di questo sito.",
	'admin:statistics:opt:linktext' => "Visualizza statistiche...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "Entità nel sito",
	'admin:statistics:label:numusers' => "Numero di utenti",
	'admin:statistics:label:numonline' => "Numero di utenti online",
	'admin:statistics:label:onlineusers' => "Utenti online in questo momento",
	'admin:statistics:label:admins'=>"Amministratori",
	'admin:statistics:label:version' => "Versione di Elgg",
	'admin:statistics:label:version:release' => "Versione",
	'admin:statistics:label:version:version' => "Rilascio",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Show PHPInfo',
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

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configura le voci del menu principale',
	'admin:menu_items:description' => 'Selezionare quali voci di menu si vogliono impostare come collegamenti sempre visibili. Le voci non utilizzate saranno aggiunte ad "Altro" al fondo dell\'elenco.',
	'admin:menu_items:hide_toolbar_entries' => 'Sicuri di voler rimuovere i collegamenti dalla barra dei menu?',
	'admin:menu_items:saved' => 'Voci di menu salvate.',
	'admin:add_menu_item' => 'Aggiungi una voce di menu personalizzata',
	'admin:add_menu_item:description' => 'Specifica il nome da visualizzare e un URL per aggiungere una voce personalizzata al menu di navigazione.',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Tipo di widget sconosciuto',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Modifica qui sotto il file robots.txt di questo sito",
	'admin:robots.txt:plugins' => "I plugin stanno aggiungendo le seguenti cose al file robots.txt",
	'admin:robots.txt:subdir' => "Lo strumento robots.txt non funzionerà perché Elgg è installato in una sotto cartella",
	'admin:robots.txt:physical' => "Lo strumento robots.txt non funzionerà perché un file robots.txt è fisicamente presente",

	'admin:maintenance_mode:default_message' => 'Questo sito non è al momento disponibile perché in manutenzione',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Modalità di manutenzione',
	'admin:maintenance_mode:message_label' => 'Messaggio visualizzato agli utenti quando la modalità di manutenzione è attiva',
	'admin:maintenance_mode:saved' => 'Le impostazioni della modalità di manutenzione sono state salvate.',
	'admin:maintenance_mode:indicator_menu_item' => 'Il sito è in manutenzione.',
	'admin:login' => 'Login amministratore',

/**
 * User settings
 */

	'usersettings:description' => "Il pannello delle impostazioni utente permette di controllare tutte le tue impostazioni personali, dalla gestione dell'utente a come i plugin si comportano. Selezionare un'opzione qui sotto per iniziare.",

	'usersettings:statistics' => "Statistiche",
	'usersettings:statistics:opt:description' => "Visualizza le informazioni statistiche sugli utenti e gli oggetti di questo sito.",
	'usersettings:statistics:opt:linktext' => "Statistiche del profilo utente",

	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "Impostazioni di %s",
	'usersettings:user:opt:description' => "Questo ti permette di controllare le impostazioni dell'utente",
	'usersettings:user:opt:linktext' => "Cambia le tue impostazioni",

	'usersettings:plugins' => "Strumenti",
	'usersettings:plugins:opt:description' => "Configura le impostazioni degli strumenti attivi (se ce ne sono).",
	'usersettings:plugins:opt:linktext' => "Configura i tuoi strumenti",

	'usersettings:plugins:description' => "Questo pannello permette di controllare e configurare le impostazioni personali degli strumenti installati dall'amministratore.",
	'usersettings:statistics:label:numentities' => "I tuoi contenuti",

	'usersettings:statistics:yourdetails' => "I tuoi dati",
	'usersettings:statistics:label:name' => "Nome completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membro dal",
	'usersettings:statistics:label:lastlogin' => "Ultimo accesso",

/**
 * Activity river
 */

	'river:all' => 'Attività dell\'intero sito',
	'river:mine' => 'Mie attività',
	'river:owner' => 'Attività di %s',
	'river:friends' => 'Attività degli amici',
	'river:select' => 'Mostra %s',
	'river:comments:more' => '+%u altro',
	'river:comments:all' => 'Visualizza tutti i %u commenti',
	'river:generic_comment' => 'ha commentato %s %s',

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
	
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Salva",
	'save_go' => "Save, and go to %s",
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
	'validate' => 'Validate',
	'read_more' => 'Read more',

	'site' => 'Sito',
	'activity' => 'Attività',
	'members' => 'Utenti',
	'menu' => 'Menu',

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
	'content:latest:blurb' => 'In alternativa clicca qui per visualizzare i contenuti recenti dell\'intero sito.',

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
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'Profilo utente creato',
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

	'systemmessages:dismiss' => "clicca per scartare",


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

	'importsuccess' => "Importazione dei dati completata",
	'importfail' => "Impossibile importare i dati OpenDD.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "proprio ora",
	'friendlytime:minutes' => "%s minuti fa",
	'friendlytime:minutes:singular' => "un minuto fa",
	'friendlytime:hours' => "%s ore fa",
	'friendlytime:hours:singular' => "un'ora fa",
	'friendlytime:days' => "%s giorni fa",
	'friendlytime:days:singular' => "ieri",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

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
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
	'installation:wwwroot' => "URL del sito:",
	'installation:path' => "Percorso completo d'installazione di Elgg:",
	'installation:dataroot' => "Percorso completa della cartella dati:",
	'installation:dataroot:warning' => "Occorre creare questa cartella manualmente. Deve trovarsi in una cartella differente da quella d'installazione di Elgg.",
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
	'installation:walled_garden:description' => 'Abilitare questa opzione per impedire agli utenti non registrati di visualizzare i contenuti del sito ad eccezione delle pagine pubbliche (come ad esempio login e registrazione)',
	'installation:walled_garden:label' => 'Limita le pagine agli utenti registrati',

	'installation:view' => "Specificare la vista che deve essere usata in modo predefinito per il sito, o lasciare in bianco per abilitare la vista preimpostata (nel dubbio lasciare come preimpostato):",

	'installation:siteemail' => "Indirizzo email del sito (usato quando si inviano email di sistema):",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Numero predefinito di elementi per pagina",

	'admin:site:access:warning' => "Questa è l'impostazione sulla privacy predefinita quando gli utenti creano nuovi contenuti. Il cambiamento di questa impostazione non modifica l'accesso ai contenuti.",
	'installation:allow_user_default_access:description' => "Questa impostazione permette agli utenti di poter impostare il livello di privacy sovrascrivendo il livello di privacy suggerito dal sistema.",
	'installation:allow_user_default_access:label' => "Permetti agli utenti di definire i livelli di accesso",

	'installation:simplecache:description' => "La cache semplice aumenta le prestazioni memorizzando contenuti statici come file CSS e JavaScript.",
	'installation:simplecache:label' => "Utilizza la cache semplice (consigliato)",

	'installation:cache_symlink:description' => "Il link simbolico alla cartella della cache semplice permette al server di fornire viste statiche evitando l'engine, migliorando considerevolmente le prestazioni e riducendo il carico del server.",
	'installation:cache_symlink:label' => "Usare un link simbolico alla cache semplice (raccomandato)",
	'installation:cache_symlink:warning' => "È stato stabilito un link simbolico. Se per qualche ragione si vuole eliminare tale link eliminare la cartella del link simbolico dal server.",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
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
	'admin:legend:content_access' => 'Accesso ai contenuti',
	'admin:legend:site_access' => 'Accesso al sito',
	'admin:legend:debug' => 'Debug e Log',
	
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	
	'upgrading' => 'Aggiornamento in corso...',
	'upgrade:core' => 'L\'installazione di Elgg è stata aggiornata.',
	'upgrade:unlock' => 'Sblocca aggiornamenti',
	'upgrade:unlock:confirm' => "Il database è bloccato per ulteriori aggiornamenti. Eseguire più aggiornamenti simultaneamente è pericoloso. Si dovrebbe continuare solo se si è sicuri che un altro aggiornamento non è in esecuzione. Sbloccare?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Impossibile aggiornare. Un altro aggiornamento è in esecuzione. Per rimuovere il blocco all'aggiornamento visitare la sezione di amministrazione.",
	'upgrade:unlock:success' => "L'aggiornamento è stato sbloccato.",
	'upgrade:unable_to_upgrade' => 'Abilitare per aggiornare.',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (in precedenza OAuth Lib) è stata disattivata durante l\'aggiornamento. Attivarla manualmente se necessario.',
	'upgrade:site_secret_warning:moderate' => "Si consiglia di rigenerare la chiave di sicurezza del sito per aumentare la sicurezza del sistema. Vedere Configurazione &gt; Impostazioni &gt; Chiave di sicurezza del sito",
	'upgrade:site_secret_warning:weak' => "Siete vivamente pregati di rigenerare la chiave di sicurezza del sito per aumentare la sicurezza del sistema. Vedere Configurazione &gt; Impostazioni &gt; Avanzate",

	'deprecated:function' => '%s() non è stata approvata da %s()',

	'admin:pending_upgrades' => 'Il sito ha degli aggiornamenti in sospeso che richiedono la vostra immediata attenzione.',
	'admin:view_upgrades' => 'Visualizza aggiornamenti in sospeso.',
	'item:object:elgg_upgrade' => 'Aggiornamenti del sito',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Questa installazione è aggiornata!',

	'upgrade:item_count' => 'Ci sono <b>%s</b> elementi che devono essere aggiornati.',
	'upgrade:warning' => '<b>Attenzione:</b> Su un sito di grandi dimensioni questo aggiornamento potrebbe richiedere molto tempo per essere completato!',
	'upgrade:success_count' => 'Aggiornati:',
	'upgrade:error_count' => 'Errori:',
	'upgrade:finished' => 'Aggiornamento completato',
	'upgrade:finished_with_errors' => '<p>L\'aggiornamento è terminato con degli errori. Aggiornare la pagina e provare a eseguire nuovamente l\'aggiornamento.</p><br>Se l\'errore si manifesta nuovamente, controllare il log file degli errori del server per trovare le possibili cause. Per eliminare l\'errore si può cercare aiuto nel <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> della comunità di Elgg.',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
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
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "Nuovo indirizzo email salvato. È richiesta la verifica.",
	'email:save:fail' => "Impossibile salvare il nuovo indirizzo email.",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s ti ha aggiunto agli amici!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "Password cambiata!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Password azzerata!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "Richiesta di cambio password.",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

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
	'collection:object:comment' => 'Comments',

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "Aggiungi un commento",
	'generic_comments:edit' => "Modifica commento",
	'generic_comments:post' => "Invia commento",
	'generic_comments:text' => "Commento",
	'generic_comments:latest' => "Ultimi commenti",
	'generic_comment:posted' => "Il tuo commento è stato inviato.",
	'generic_comment:updated' => "Il commento è stato aggiornato.",
	'entity:delete:object:comment:success' => "The comment was successfully deleted.",
	'generic_comment:blank' => "Spiacenti, devi scrivere qualcosa nel commento per poterlo salvare.",
	'generic_comment:notfound' => "Spiacenti, impossibile trovare il commento specificato.",
	'generic_comment:notfound_fallback' => "Spiacenti, impossibile trovare il commento specificato, ma ti abbiamo indirizzato alla pagina a cui era stato aggiunto.",
	'generic_comment:failure' => "Errore inatteso durante il salvataggio del commento.",
	'generic_comment:none' => 'Nessun commento',
	'generic_comment:title' => 'Commento di %s',
	'generic_comment:on' => '%s su %s',
	'generic_comments:latest:posted' => 'ha inviato un',

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

	'byline' => 'Di %s',
	'byline:ingroup' => 'nel gruppo %s',
	'entity:default:missingsupport:popup' => 'Questo elemento non può essere visualizzato correttamente. Probabilmente perché richiede il supporto fornito da un plugin non più disponibile nel sistema.',

	'entity:delete:item' => 'Elemento',
	'entity:delete:item_not_found' => 'Elemento non trovato.',
	'entity:delete:permission_denied' => 'Permessi insufficienti per visualizzare questo elemento.',
	'entity:delete:success' => 'L\'elemento %s è stato eliminato',
	'entity:delete:fail' => 'L\'elemento %s non può essere eliminato',

	'entity:can_delete:invaliduser' => 'Non è possibile verificare canDelete per l\'utente _guid [%s] perché l\'utente non esiste.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Il modulo è privo dei campi __token o __ts',
	'actiongatekeeper:tokeninvalid' => "La pagina che stavi utilizzando è scaduta. Per favore riprova.",
	'actiongatekeeper:timeerror' => 'La pagina che stavi utilizzando è scaduta. Per favore ricarica la pagina e riprova.',
	'actiongatekeeper:pluginprevents' => 'Spiacenti. Il tuo modulo non può essere inviato per ragioni sconosciute.',
	'actiongatekeeper:uploadexceeded' => 'La dimensione dei file caricati supera il limite impostato dall\'amministratore del sito',
	'actiongatekeeper:crosssitelogin' => "Spiacenti, l'accesso al sito da un dominio differente non è permesso. Per favore riprovare.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tag',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Impossibile contattare %s. Si potrebbero riscontrare problemi nel salvare i contenuti. Si prega di ricaricare questa pagina.',
	'js:security:token_refreshed' => 'Connessione a %s ristabilita!',
	'js:lightbox:current' => "immagine %s di %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Generato da Elgg",

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
	//"in" => "Indonesiano",
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
