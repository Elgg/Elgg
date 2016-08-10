<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sito',

/**
 * Sessions
 */

	'login' => "Entra",
	'loginok' => "Benvenuto/a su  .",
	'loginerror' => "Non è stato possibile farti entrare. Per favore controlla username e password e riprova, grazie.",
	'login:empty' => "Username/email e password sono necessari",
	'login:baduser' => "Impossibile caricare il tuo profilo utente",
	'auth:nopams' => "Errore interno, nessun metodo di autenticazione installato",

	'logout' => "Esci",
	'logoutok' => "Sei uscito/a",
	'logouterror' => "Non è stato possibile uscire. Per favore riprova",
	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "Devi essere esntrato/a per vedere la pagina richieta.",
	'adminrequired' => "Devi essere amministratore per vedere la pagina richiesta.",
	'membershiprequired' => "Devi essere un membro di questo gruppo per vedere la pagina richiesta.",
	'limited_access' => "Non hai i permessi per vedere la pagina richiesta.",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "Errore fatale",
	'exception:contact_admin' => 'Errore non previsto. Contatta l\'amministratore indicando le seguenti informazioni:',

	'actionundefined' => "L'azione richiesta (%s) non è stata definita nel sistema.",
	'actionnotfound' => "Il file comando di %s non è stato trovato",
	'actionloggedout' => "Spiacenti, non puoi eseguire questa azione se non sei entrato/a.",
	'actionunauthorized' => 'Non sei autorizzato/a per questa operazione',

	'ajax:error' => 'Errore inatteso durante una chiamata AJAX. Forse la connessione al server si è persa',
	'ajax:not_is_xhr' => 'Non puoi accedere alla visualizzazione AJAX direttamente',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) è un plugin non configurato e non è stato abilitato.",
	'PluginException:CannotStart' => '%s (guid: %s) non può avviarsi ed è stata disattivata. Motivo: %s',
	'PluginException:InvalidID' => "%s is an invalid plugin ID.",
	'PluginException:InvalidPath' => "%s is an invalid plugin path.",
	'PluginException:InvalidManifest' => 'Invalid manifest file for plugin %s',
	'PluginException:InvalidPlugin' => '%s is not a valid plugin.',
	'PluginException:InvalidPlugin:Details' => '%s is not a valid plugin: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',
	'ElggPlugin:MissingID' => 'Missing plugin ID (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Missing ElggPluginPackage for plugin ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Non trovo il file richiesto "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'La cartella del plugin deve essere rinominata in "%s" per corrispondere all\'ID nel manifesto.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Its manifest contains an invalid dependency type "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Its manifest contains an invalid provides type "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'There is an invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:NoID' => 'No ID for plugin guid %s!',
	'PluginException:NoPluginName' => "Nome del plugin non trovato.",
	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',

	'ElggPlugin:Dependencies:Requires' => 'Requires',
	'ElggPlugin:Dependencies:Suggests' => 'Suggests',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflicts',
	'ElggPlugin:Dependencies:Conflicted' => 'Conflicted',
	'ElggPlugin:Dependencies:Provides' => 'Provides',
	'ElggPlugin:Dependencies:Priority' => 'Priority',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg version',
	'ElggPlugin:Dependencies:PhpVersion' => 'Versione PHP.',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'After %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Before %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s is not installed',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Missing',

	'ElggPlugin:Dependencies:ActiveDependent' => 'There are other plugins that list %s as a dependency.  You must disable the following plugins before disabling this one: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Trovate voci del menu senza voci superiori a cui linkare',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Voce del menu [%s] trovata senza voce superiore [%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Trovata doppia registrazione della voce menu [%s]',

	'RegistrationException:EmptyPassword' => 'I campi delle password non possono essere vuoti',
	'RegistrationException:PasswordMismatch' => 'Le password devono coincidere',
	'LoginException:BannedUser' => 'Sei stato/a bannato/a da questo sito, non puoi entrare. Se credi ci sia stato un errore scrivi agli amministratori.',
	'LoginException:UsernameFailure' => 'Non è stato possibile farti entrare. Per favore verifica username/email e password',
	'LoginException:PasswordFailure' => 'Non è stato possibile farti entrare. Per favore verifica username/email e password',
	'LoginException:AccountLocked' => 'Il tuo account è stato bloccato per numero tentativi d\'ingresso troppo elevato',
	'LoginException:ChangePasswordFailure' => 'Controllo della password attuale fallito',
	'LoginException:Unknown' => 'Impossibile farti entrare per un errore sconosciuto, riprova per favore.',

	'UserFetchFailureException' => 'Cannot check permission for user_guid [%s] as the user does not exist.',

	'deprecatedfunction' => 'Attenzione: questo codice usa la funzione non più approvata \'%s\' e non compatibile con questa versione di Elgg.',

	'pageownerunavailable' => 'Attenzione: il proprietario %d di questa pagina non è accessibile!',
	'viewfailure' => 'Errore interno in %s',
	'view:missing_param' => "Il parametro richiesto '%s' non si trova nella vista %s",
	'changebookmark' => 'Per favore cambia il segnalibro per questa pagina',
	'noaccess' => 'Devi essere entrato/a per vedere questo contenuto oppure il contenuto è stato rimosso o non hai i permessi per visualizzarlo',
	'error:missing_data' => 'Manca qualche dato nella tua richiesta',
	'save:fail' => 'Salvataggio dati fallito',
	'save:success' => 'Dati salvati',

	'error:default:title' => 'Mmm....',
	'error:default:content' => 'Mmm....qualcosa è andato storto.',
	'error:400:title' => 'Richiesta non valida',
	'error:400:content' => 'Spiacenti, la richiesta non è valida o è incompleta.',
	'error:403:title' => 'Vietato',
	'error:403:content' => 'Spiacenti, non sei autorizzato/a ad accedere alla pagina richiesta.',
	'error:404:title' => 'Pagina non trovata',
	'error:404:content' => 'Spiacenti. Pagina richiesta non trovata.',

	'upload:error:ini_size' => 'Il file che hai cercato di caricare è troppo grande.',
	'upload:error:form_size' => 'Il file che hai cercato di caricare è troppo grande.',
	'upload:error:partial' => 'Il caricamento del file non è stato completato.',
	'upload:error:no_file' => 'Campo input file vuoto',
	'upload:error:no_tmp_dir' => 'Non possiamo salvare il file caricato.',
	'upload:error:cant_write' => 'Non possiamo salvare il file caricato.',
	'upload:error:extension' => 'Non possiamo salvare il file caricato.',
	'upload:error:unknown' => 'Errore sconosciuto',


/**
 * User details
 */

	'name' => "Nome visualizzato",
	'email' => "Indirizzo email",
	'username' => "Nome utente",
	'loginusername' => "Username o email",
	'password' => "Password",
	'passwordagain' => "Password (di nuovo, per verifica)",
	'admin_option' => "Rendere questo utente un amministratore?",
	'autogen_password_option' => "Automatically generate a secure password?",

/**
 * Access
 */

	'PRIVATE' => "Privato",
	'LOGGED_IN' => "Utenti loggati",
	'PUBLIC' => "Pubblico",
	'LOGGED_OUT' => "Utenti non collegati",
	'access:friends:label' => "Amici",
	'access' => "Accesso",
	'access:overridenotice' => "Nota: per la policy del gruppo, questo contenuto sarà accessibile solo ai suoi membri.",
	'access:limited:label' => "Limitato",
	'access:help' => "Il livello d'accesso",
	'access:read' => "Accesso in lettura",
	'access:write' => "Accesso in scrittura",
	'access:admin_only' => "Riservato agli amministratori",
	'access:missing_name' => "Nome del livello di accesso non presente",
	'access:comments:change' => "Questa discussione al momento è visualizzabile solo da un'audience limitata. Fà attenzione a scegliere con chi condividerla.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "La <b>Bacheca</b> è il tuo punto di accesso alla piattaforma. Clicca su 'Modifica pagina' per aggiungere i <b>Gadget</b>, per tenere traccia dei contenuti e delle attività all'interno di  .",

	'widgets:add' => 'Aggiungi widget',
	'widgets:add:description' => "Clicca su qualsiasi pulsante widget in basso per aggiungerlo alla pagina",
	'widgets:panel:close' => "Chiudi il pannello dei widget",
	'widgets:position:fixed' => '(posizione fissa nella pagina)',
	'widget:unavailable' => 'Hai già aggiunto questo gadget',
	'widget:numbertodisplay' => 'Numero di elementi da mostrare',

	'widget:delete' => 'Rimuovi %s',
	'widget:edit' => 'Personalizza questo gadget',

	'widgets' => "Widget",
	'widget' => "Widget",
	'item:object:widget' => "Widget",
	'widgets:save:success' => "Widget salvato con successo.",
	'widgets:save:failure' => "Non è stato possibile salvare il tuo widget.",
	'widgets:add:success' => "Gadget aggiunto con successo",
	'widgets:add:failure' => "Non è stato possibile aggiungere il tuo gadget",
	'widgets:move:failure' => "Non è stato possibile registrare la posizione del nuovo gadget",
	'widgets:remove:failure' => "Impossibile rimuovere questo gadget",

/**
 * Groups
 */

	'group' => "Gruppo",
	'item:group' => "Gruppi",

/**
 * Users
 */

	'user' => "Membro",
	'item:user' => "Membri",

/**
 * Friends
 */

	'friends' => "Amici",
	'friends:yours' => "I tuoi amici",
	'friends:owned' => "Amici di %s",
	'friend:add' => "Aggiungi amico/a",
	'friend:remove' => "Rimuovi amico/a",

	'friends:add:successful' => "Hai aggiunto %s agli amici.",
	'friends:add:failure' => "Non è stato possibile aggiungere %s agli amici.",

	'friends:remove:successful' => "Hai rimosso %s dagli amici.",
	'friends:remove:failure' => "Non è stato possibile rimuovere %s dai tuoi amici.",

	'friends:none' => "Ancora nessun amico/a.",
	'friends:none:you' => "Non hai ancora amici.",

	'friends:none:found' => "Nessun amico trovato.",

	'friends:of:none' => "Ancora nessuno ha aggiunto questo utente tra i suoi amici.",
	'friends:of:none:you' => "Nessuno ti ha ancora aggiunto tra i suoi amici. Inizia ad inserire contenuti e farti conoscere; compila il tuo profilo per farti trovare dagli utenti della community.",

	'friends:of:owned' => "Persone che hanno aggiunto %s tra i loro amici.",

	'friends:of' => "Amici di",
	'friends:collections' => "Gruppi di amici",
	'collections:add' => "Nuova lista",
	'friends:collections:add' => "Nuovo gruppo di amici",
	'friends:addfriends' => "Seleziona amici",
	'friends:collectionname' => "Nome gruppo di amici",
	'friends:collectionfriends' => "Amici nel gruppo",
	'friends:collectionedit' => "Modifica questo gruppo",
	'friends:nocollections' => "Non hai ancora definito gruppi di amici",
	'friends:collectiondeleted' => "Il gruppo di amici è stato cancellato.",
	'friends:collectiondeletefailed' => "Impossibile cancellare il gruppo di amici: o non hai i permessi o c'è stato qualche altro problema.",
	'friends:collectionadded' => "Gruppo di amici creato.",
	'friends:nocollectionname' => "Devi dare un nome al gruppo di amici per crearlo.",
	'friends:collections:members' => "Membri del gruppo di amici.",
	'friends:collections:edit' => "Modifica gruppo di amici",
	'friends:collections:edited' => "Collezione salvata",
	'friends:collection:edit_failed' => 'Non è stato possibile salvare la collezione',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Immagine del profilo',
	'avatar:noaccess' => "Non sei autorizzato/a a modificare l'avatar di questo utente",
	'avatar:create' => 'Crea la tua immagine del profilo',
	'avatar:edit' => 'Modifica immagine del profilo',
	'avatar:preview' => 'Anteprima',
	'avatar:upload' => 'Carica una nuova immagine del profilo',
	'avatar:current' => 'Immagine attuale del profilo',
	'avatar:remove' => 'Rimuovi l\'immagine del profilo ed inserisci quella di default',
	'avatar:crop:title' => 'Strumento ritaglio immagine',
	'avatar:upload:instructions' => "L'immagine del tuo profilo viene mostrata in tutto il network. Puoi cambiarlo quando vuoi (file permessi: GIF, JPG o PNG)",
	'avatar:create:instructions' => 'Clicca e trascina il quadrato in basso per titagliare la tua immagine del profilo come vuoi. Un\'anteprima comparirà nel box a destra. Quando sei soddisfatto/a clicca su Crea la tua immagine del profilo. Questa versione ritagliata sarà usata in tutto il network come tua immagine del profilo',
	'avatar:upload:success' => 'Immagine caricata con successo',
	'avatar:upload:fail' => 'Caricamento immagine fallito',
	'avatar:resize:fail' => 'Ridimensionamento immagine fallito',
	'avatar:crop:success' => 'Immagine ritagliata con successo',
	'avatar:crop:fail' => 'Ritaglio immagine fallito',
	'avatar:remove:success' => 'Rimozione immagine del profilo avvenuta con successo',
	'avatar:remove:fail' => 'Rimozione immagine fallita',

	'profile:edit' => 'Modifica profilo',
	'profile:aboutme' => "Informazioni personali",
	'profile:description' => "Informazioni personali",
	'profile:briefdescription' => "Breve descrizione",
	'profile:location' => "Città",
	'profile:skills' => "Competenze",
	'profile:interests' => "Interessi",
	'profile:contactemail' => "Email",
	'profile:phone' => "Telefono",
	'profile:mobile' => "Cellulare",
	'profile:website' => "Sito web",
	'profile:twitter' => "Username su Twitter",
	'profile:saved' => "Il tuo profilo è stato salvato con successo.",

	'profile:field:text' => 'Breve testo',
	'profile:field:longtext' => 'Area testo lungo',
	'profile:field:tags' => 'Tag',
	'profile:field:url' => 'Indirizzo web',
	'profile:field:email' => 'Indirizzo email',
	'profile:field:location' => 'Luogo',
	'profile:field:date' => 'Data',

	'admin:appearance:profile_fields' => 'Edita i campi del profilo',
	'profile:edit:default' => 'Modifica i campi del profilo',
	'profile:label' => "Etichetta del profilo",
	'profile:type' => "Tipo di profilo",
	'profile:editdefault:delete:fail' => 'Rimozione dell\'elemento dal profilo predefinito non riuscita',
	'profile:editdefault:delete:success' => 'Elemento rimosso correttamente dal profilo predefinito!',
	'profile:defaultprofile:reset' => 'Profilo di sistema predefinito resettato',
	'profile:resetdefault' => 'Resetta il profilo predefinito',
	'profile:resetdefault:confirm' => 'Sicuro/a di voler cancellare i tuoi campi profilo personalizzati?',
	'profile:explainchangefields' => "È possibile sostituire i campi del profilo esistente con il proprio utilizzando il modulo sottostante. In primo luogo dai una nuova etichetta al profilo, ad esempio, 'Team favorito'. Dopodiché è necessario selezionare il tipo di campo, ad esempio, tag, url, testo ed altro ancora. In qualsiasi momento puoi tornare al profilo di default.",
	'profile:editdefault:success' => 'Elemento aggiunto correttamente al profilo predefinito',
	'profile:editdefault:fail' => 'Il profilo predefinito non può essere salvato',
	'profile:field_too_long' => 'Non possiamo salvae il tuo profilo perchè la sezione "%s" è troppo lunga.',
	'profile:noaccess' => "Non hai il permesso di modificare questo profilo.",
	'profile:invalid_email' => '%s deve essere un indirizzo email valido.',


/**
 * Feeds
 */
	'feed:rss' => 'Feed RSS per questa pagina',
/**
 * Links
 */
	'link:view' => 'visualizza il link',
	'link:view:all' => 'Visualizza tutto',


/**
 * River
 */
	'river' => "Corso",
	'river:friend:user:default' => "%s ora è amico/a di %s",
	'river:update:user:avatar' => '%s ha caricato una nuova immagine del profilo',
	'river:update:user:profile' => '%s ha aggiornato il suo profilo',
	'river:noaccess' => 'Non hai i permessi per visualizzare questo elemento.',
	'river:posted:generic' => '%s inserito',
	'riveritem:single:user' => 'un utente',
	'riveritem:plural:user' => 'più membri',
	'river:ingroup' => 'nel gruppo %s',
	'river:none' => 'Nessuna attività',
	'river:update' => 'Aggiornamento di %s',
	'river:delete' => 'Rimuovi questo elemento',
	'river:delete:success' => 'Elemento del river cancellato',
	'river:delete:fail' => 'Non è stato possibile eliminare l\'elemento del river',
	'river:delete:lack_permission' => 'You lack permission to delete this activity item',
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => 'Utente non valido',
	'activity:owner' => 'Visualizza attività',

	'river:widget:title' => "Attività",
	'river:widget:description' => "Visualizza le attività recenti",
	'river:widget:type' => "Tipo di attività",
	'river:widgets:friends' => 'Attività degli amici',
	'river:widgets:all' => 'Attività del network',

/**
 * Notifications
 */
	'notifications:usersettings' => "Impostazioni notifiche",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "Impostazioni notifiche salvate",
	'notifications:usersettings:save:fail' => "Problema durante il salvataggio delle impostazioni delle notifiche",

	'notification:subject' => 'Notifiche su %s',
	'notification:body' => 'Visualizza la nuova attività qui %s',

/**
 * Search
 */

	'search' => "Cerca",
	'searchtitle' => "Cerca: %s",
	'users:searchtitle' => "Cerca tra i membri: %s",
	'groups:searchtitle' => "Cerca tra i gruppi: %s",
	'advancedsearchtitle' => "%s con risultati che corrispondono a %s",
	'notfound' => "Nessun risultato trovato.",
	'next' => "Successivo",
	'previous' => "Precedente",

	'viewtype:change' => "Cambia tipo di elenco",
	'viewtype:list' => "Visualizza elenco",
	'viewtype:gallery' => "Galleria",

	'tag:search:startblurb' => "Elemento con tag corrispondenti a '%s':",

	'user:search:startblurb' => "Membri corrispondenti '%s':",
	'user:search:finishblurb' => "Per visualizzare altro, clicca qui.",

	'group:search:startblurb' => "Gruppi corrispondenti a '%s':",
	'group:search:finishblurb' => "Per visualizzare altro clicca qui:",
	'search:go' => 'Vai',
	'userpicker:only_friends' => 'Solo amici',

/**
 * Account
 */

	'account' => "Profilo",
	'settings' => "Impostazioni",
	'tools' => "Strumenti",
	'settings:edit' => 'Edita impostazioni',

	'register' => "Registrati",
	'registerok' => "Complimenti, ti sei registrato/a con successo su %s.",
	'registerbad' => "La registrazione non è avvenuta per un errore sconosciuto",
	'registerdisabled' => "Registrazione disabilitata dagli amministratori",
	'register:fields' => 'Tutti i campi sono obbligatori',

	'registration:notemail' => 'L\'indirizzo email fornito non sembra valido',
	'registration:userexists' => 'Username già utilizzato',
	'registration:usernametooshort' => 'Il tuo username deve avere un minimo di %u caratteri.',
	'registration:usernametoolong' => 'Il tuo nome utente è troppo lungo. Può essere di massimo %u caratteri',
	'registration:passwordtooshort' => 'La tua password deve avere un minimo di %u caratteri.',
	'registration:dupeemail' => 'Questo indirizzo email è già registrato',
	'registration:invalidchars' => 'Il tuo username contiene il carattere %s non valido. I caratteri seguenti non sono validi: %s',
	'registration:emailnotvalid' => 'Spiacenti, l\'indirizzo email inserito non è valido nel nostro sistema.',
	'registration:passwordnotvalid' => 'Spiacenti, la password inserita non è valida nel nostro sistema',
	'registration:usernamenotvalid' => 'Spiacenti, il nome utente inserito non è valido nel nostro sistema',

	'adduser' => "Aggiungi utente",
	'adduser:ok' => "Utente aggiunto con successo",
	'adduser:bad' => "Non è stato possibile creare il nuovo utente",

	'user:set:name' => "Impostazioni nome profilo",
	'user:name:label' => "Nome visualizzato",
	'user:name:success' => "Nome visualizzato cambiato con successo.",
	'user:name:fail' => "Non è stato possibile cambiare il nome visualizzato",

	'user:set:password' => "Password del profilo",
	'user:current_password:label' => 'Password attuale',
	'user:password:label' => "Nuova password",
	'user:password2:label' => "Ripeti la nuova password",
	'user:password:success' => "Password cambiata",
	'user:password:fail' => "Non è stato possibile cambiare la password",
	'user:password:fail:notsame' => "Le password non coincidono",
	'user:password:fail:tooshort' => "Password troppo corta quindi poco sicura",
	'user:password:fail:incorrect_current_password' => 'La password attuale inserita non è corretta',
	'user:changepassword:unknown_user' => 'Utente non valido',
	'user:changepassword:change_password_confirm' => 'Questo cambierà la tua password',

	'user:set:language' => "Impostazioni della lingua",
	'user:language:label' => "Lingua",
	'user:language:success' => "Impostazioni lingua aggiornate",
	'user:language:fail' => "Non è stato possibile salvare le impostazioni della lingua.",

	'user:username:notfound' => 'Nome utente %s non trovato.',

	'user:password:lost' => 'Password smarrita',
	'user:password:changereq:success' => 'Password richiesta con successo, email inviata.',
	'user:password:changereq:fail' => 'Non è stato possibile richiedere una nuova password.',

	'user:password:text' => 'Per richiedere una nuova password inserisci il tuo nome utente o l\'indirizzo email con cui ti sei registrato/a e clicca sul pulsante Richiedi',

	'user:persistent' => 'Ricordami',

	'walled_garden:welcome' => 'Benvenuto/a a',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Amministra',
	'menu:page:header:configure' => 'Configura',
	'menu:page:header:develop' => 'Sviluppa',
	'menu:page:header:default' => 'Altro',

	'admin:view_site' => 'Visualizza il sito',
	'admin:loggedin' => 'Entrato/a come %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Impostazioni salvate",
	'admin:configuration:fail' => "Non è stato possibile salvare le tue impostazioni.",
	'admin:configuration:dataroot:relative_path' => 'Cannot set "%s" as the dataroot because it is not an absolute path.',
	'admin:configuration:default_limit' => 'Il numero di elementi per pagina deve essere almeno 1.',

	'admin:unknown_section' => 'Invalid Admin Section.',

	'admin' => "Amministrazione",
	'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",

	'admin:statistics' => "Statistiche",
	'admin:statistics:overview' => 'Overview',
	'admin:statistics:server' => 'Server Info',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Latest Cron Jobs',
	'admin:cron:period' => 'Cron period',
	'admin:cron:friendly' => 'Last completed',
	'admin:cron:date' => 'Date and time',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:complete' => 'Cron jobs for "%s" completed at %s',

	'admin:appearance' => 'Appearance',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilities',
	'admin:configure_utilities' => 'Utilities',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Users",
	'admin:users:online' => 'Currently Online',
	'admin:users:newest' => 'Newest',
	'admin:users:admins' => 'Administrators',
	'admin:users:add' => 'Add New User',
	'admin:users:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
	'admin:users:adduser:label' => "Click here to add a new user...",
	'admin:users:opt:linktext' => "Configure users...",
	'admin:users:opt:description' => "Configure users and account information.",
	'admin:users:find' => 'Find',

	'admin:administer_utilities:maintenance' => 'Modalità manutenzione ed aggiornamento',
	'admin:upgrades' => 'Upgrades',

	'admin:settings' => 'Settings',
	'admin:settings:basic' => 'Basic Settings',
	'admin:settings:advanced' => 'Advanced Settings',
	'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
	'admin:site:opt:linktext' => "Configure site...",
	'admin:settings:in_settings_file' => 'This setting is configured in settings.php',

	'admin:legend:security' => 'Security',
	'admin:site:secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:site:secret_regenerated' => "Your site secret has been regenerated.",
	'admin:site:secret:regenerate' => "Regenerate site secret",
	'admin:site:secret:regenerate:help' => "Note: This may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	'site_secret:current_strength' => 'Key Strength',
	'site_secret:strength:weak' => "Weak",
	'site_secret:strength_msg:weak' => "We strongly recommend that you regenerate your site secret.",
	'site_secret:strength:moderate' => "Moderate",
	'site_secret:strength_msg:moderate' => "We recommend you regenerate your site secret for the best site security.",
	'site_secret:strength:strong' => "Strong",
	'site_secret:strength_msg:strong' => "✓ Your site secret is sufficiently strong.",

	'admin:dashboard' => 'Bacheca',
	'admin:widget:online_users' => 'Utenti online',
	'admin:widget:online_users:help' => 'Lists the users currently on the site',
	'admin:widget:new_users' => 'Nuovi utenti',
	'admin:widget:new_users:help' => 'Lists the newest users',
	'admin:widget:banned_users' => 'Utenti bannati',
	'admin:widget:banned_users:help' => 'Elenca gli utenti bannati',
	'admin:widget:content_stats' => 'Content statistics',
	'admin:widget:content_stats:help' => 'Keep track of the content created by your users',
	'admin:widget:cron_status' => 'Stato del cron',
	'admin:widget:cron_status:help' => 'Mostra lo stato dell\'ultimo lavoro completato dal cron',
	'widget:content_stats:type' => 'Content type',
	'widget:content_stats:number' => 'Number',

	'admin:widget:admin_welcome' => 'Benvenuto/a',
	'admin:widget:admin_welcome:help' => "A short introduction to Elgg's admin area",
	'admin:widget:admin_welcome:intro' =>
'Welcome to Elgg! Right now you are looking at the administration dashboard. It\'s useful for tracking what\'s happening on the site.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into three sections:

	<dl>

		<dt>Administer</dt><dd>Everyday tasks like monitoring reported content, checking who is online, and viewing statistics.</dd>

		<dt>Configure</dt><dd>Occasional tasks like setting the site name or activating a plugin.</dd>

		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>

	</dl>",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br>Be sure to check out the resources available through the footer links and thank you for using Elgg!',

	'admin:widget:control_panel' => 'Control panel',
	'admin:widget:control_panel:help' => "Provides easy access to common controls",

	'admin:cache:flush' => 'Flush the caches',
	'admin:cache:flushed' => "The site's caches have been flushed",

	'admin:footer:faq' => 'Administration FAQ',
	'admin:footer:manual' => 'Administration Manual',
	'admin:footer:community_forums' => 'Elgg Community Forums',
	'admin:footer:blog' => 'Elgg Blog',

	'admin:plugins:category:all' => 'All plugins',
	'admin:plugins:category:active' => 'Active plugins',
	'admin:plugins:category:inactive' => 'Inactive plugins',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Bundled',
	'admin:plugins:category:nonbundled' => 'Non-bundled',
	'admin:plugins:category:content' => 'Content',
	'admin:plugins:category:development' => 'Development',
	'admin:plugins:category:enhancement' => 'Enhancements',
	'admin:plugins:category:api' => 'Service/API',
	'admin:plugins:category:communication' => 'Communication',
	'admin:plugins:category:security' => 'Security and Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Themes',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Utilities',

	'admin:plugins:markdown:unknown_plugin' => 'Unknown plugin.',
	'admin:plugins:markdown:unknown_file' => 'Unknown file.',

	'admin:notices:could_not_delete' => 'Could not delete notice.',
	'item:object:admin_notice' => 'Admin notice',

	'admin:options' => 'Admin options',

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Impostazioni per il plugin %s salvate con successo.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving user settings for the %s plugin.",
	'item:object:plugin' => 'Plugins',

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
	'admin:plugins:label:licence' => "License",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "File",
	'admin:plugins:label:resources' => "Risorse",
	'admin:plugins:label:screenshots' => "Videate",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Segnala problema",
	'admin:plugins:label:donate' => "Dona",
	'admin:plugins:label:moreinfo' => 'more info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Luogo',
	'admin:plugins:label:contributors' => 'Contributors',
	'admin:plugins:label:contributors:name' => 'Nome',
	'admin:plugins:label:contributors:email' => 'Email',
	'admin:plugins:label:contributors:website' => 'Sito web',
	'admin:plugins:label:contributors:username' => 'Nome utente nella community',
	'admin:plugins:label:contributors:description' => 'Descrizione',
	'admin:plugins:label:dependencies' => 'Dependencies',

	'admin:plugins:warning:unmet_dependencies' => 'This plugin has unmet dependencies and cannot be activated. Check dependencies under more info.',
	'admin:plugins:warning:invalid' => 'This plugin is invalid: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Check <a rel="nofollow" href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:cannot_activate' => 'cannot activate',
	'admin:plugins:already:active' => 'The selected plugin(s) are already active.',
	'admin:plugins:already:inactive' => 'The selected plugin(s) are already inactive.',

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
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'Plugin Settings',
	'admin:plugins:warning:unmet_dependencies_active' => 'This plugin is active but has unmet dependencies. You may encounter problems. See "more info" below for details.',

	'admin:plugins:dependencies:type' => 'Type',
	'admin:plugins:dependencies:name' => 'Name',
	'admin:plugins:dependencies:expected_value' => 'Expected Value',
	'admin:plugins:dependencies:local_value' => 'Actual value',
	'admin:plugins:dependencies:comment' => 'Comment',

	'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
	'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:basic' => "Basic site statistics",
	'admin:statistics:label:numentities' => "Entities on site",
	'admin:statistics:label:numusers' => "Numero di membri",
	'admin:statistics:label:numonline' => "Numero di membri online",
	'admin:statistics:label:onlineusers' => "Membri online",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Web Server',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Log Location',
	'admin:server:label:php_version' => 'PHP version',
	'admin:server:label:php_ini' => 'PHP ini file location',
	'admin:server:label:php_log' => 'PHP Log',
	'admin:server:label:mem_avail' => 'Memory available',
	'admin:server:label:mem_used' => 'Memory used',
	'admin:server:error_log' => "Web server's error log",
	'admin:server:label:post_max_size' => 'POST maximum size',
	'admin:server:label:upload_max_filesize' => 'Upload maximum size',
	'admin:server:warning:post_max_too_small' => '(Note: post_max_size must be larger than this value to support uploads of this size)',

	'admin:user:label:search' => "Trova membri:",
	'admin:user:label:searchbutton' => "Cerca",

	'admin:user:ban:no' => "Can not ban user",
	'admin:user:ban:yes' => "Utente bannato",
	'admin:user:self:ban:no' => "You cannot ban yourself",
	'admin:user:unban:no' => "Can not unban user",
	'admin:user:unban:yes' => "Utente non più bannato",
	'admin:user:delete:no' => "Can not delete user",
	'admin:user:delete:yes' => "Utente %s cancellato",
	'admin:user:self:delete:no' => "You cannot delete yourself",

	'admin:user:resetpassword:yes' => "Password reset, user notified.",
	'admin:user:resetpassword:no' => "Password could not be reset.",

	'admin:user:makeadmin:yes' => "User is now an admin.",
	'admin:user:makeadmin:no' => "We could not make this user an admin.",

	'admin:user:removeadmin:yes' => "User is no longer an admin.",
	'admin:user:removeadmin:no' => "We could not remove administrator privileges from this user.",
	'admin:user:self:removeadmin:no' => "You cannot remove your own administrator privileges.",

	'admin:appearance:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configure main menu items',
	'admin:menu_items:description' => 'Select which menu items you want to show as featured links.  Unused items will be added as "More" at the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Menu items saved.',
	'admin:add_menu_item' => 'Add a custom menu item',
	'admin:add_menu_item:description' => 'Fill out the Display name and URL to add custom items to your navigation menu.',

	'admin:appearance:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Unknown widget type',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.  These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Edit this site's robots.txt file below",
	'admin:robots.txt:plugins' => "Plugins are adding the following to the robots.txt file",
	'admin:robots.txt:subdir' => "The robots.txt tool will not work because Elgg is installed in a sub-directory",
	'admin:robots.txt:physical' => "Lo strumento robots.txt non funzionerà perché esiste già un file robots.txt",

	'admin:maintenance_mode:default_message' => 'Sito in manutenzione ed aggiornamento',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Modalità manutenzione ed aggiornamento',
	'admin:maintenance_mode:message_label' => 'Message displayed to users when maintenance mode is on',
	'admin:maintenance_mode:saved' => 'The maintenance mode settings were saved.',
	'admin:maintenance_mode:indicator_menu_item' => 'Sito in manutenzione ed aggiornamento',
	'admin:login' => 'Admin Login',

/**
 * User settings
 */

	'usersettings:description' => "The user settings panel allows you to control all your personal settings, from user management to how plugins behave. Choose an option below to get started.",

	'usersettings:statistics' => "Le tue statistiche",
	'usersettings:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'usersettings:statistics:opt:linktext' => "Statistiche de profilo",

	'usersettings:user' => "Impostazioni di %s",
	'usersettings:user:opt:description' => "Questo ti permette di controllare le impostazioni dell'utente",
	'usersettings:user:opt:linktext' => "Cambia le tue impostazioni",

	'usersettings:plugins' => "Strumenti",
	'usersettings:plugins:opt:description' => "Configura le tue impostazioni (se ce ne sono) per gli strumenti attivi.",
	'usersettings:plugins:opt:linktext' => "Configura i tuoi strumenti",

	'usersettings:plugins:description' => "Questo pannello ti permette di controllare e configurare le impostazioni personali degli strumenti installati dall'amministratore.",
	'usersettings:statistics:label:numentities' => "I tuoi contenuti",

	'usersettings:statistics:yourdetails' => "I tuoi dati",
	'usersettings:statistics:label:name' => "Nome completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membro dal",
	'usersettings:statistics:label:lastlogin' => "Ultimo ingresso",

/**
 * Activity river
 */

	'river:all' => 'Attività del network',
	'river:mine' => 'Mie attività',
	'river:owner' => 'Attività di %s',
	'river:friends' => 'Attività degli amici',
	'river:select' => 'Mostra %s',
	'river:comments:more' => '+%u altro',
	'river:comments:all' => 'Visualizza tutti i %u commenti',
	'river:generic_comment' => 'ha commentato %s %s',

	'friends:widget:description' => "Visualizza alcuni dei tuoi amici.",
	'friends:num_display' => "Numero di amici da visualizzare",
	'friends:icon_size' => "Dimensione icona",
	'friends:tiny' => "minuscola",
	'friends:small' => "piccola",

/**
 * Icons
 */

	'icon:size' => "Dimensioni icona",
	'icon:size:topbar' => "Barra superiore",
	'icon:size:tiny' => "Minuscola",
	'icon:size:small' => "Piccola",
	'icon:size:medium' => "Media",
	'icon:size:large' => "Grande",
	'icon:size:master' => "XL",

/**
 * Generic action words
 */

	'save' => "Salva",
	'reset' => 'Reset',
	'publish' => "Pubblica",
	'cancel' => "Cancella",
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
	'load' => "Carico",
	'upload' => "Carica",
	'download' => "Scarica",
	'ban' => "Banna",
	'unban' => "Elimina banna",
	'banned' => "Bannato",
	'enable' => "Abilita",
	'disable' => "Disabilita",
	'request' => "Richiedi",
	'complete' => "Completa",
	'open' => 'Apri',
	'close' => 'Chiudi',
	'hide' => 'Nascondi',
	'show' => 'Mostra',
	'reply' => "Rispondi",
	'more' => 'Apri...',
	'more_info' => 'Più info',
	'comments' => 'Commenti',
	'import' => 'Importa',
	'export' => 'Esporta',
	'untitled' => 'senza titolo',
	'help' => 'Aiuto',
	'send' => 'Invia',
	'post' => 'Pubblica',
	'submit' => 'Invia',
	'comment' => 'Commento',
	'upgrade' => 'Upgrade',
	'sort' => 'Ordine',
	'filter' => 'Filtro',
	'new' => 'Nuovo/a',
	'add' => 'Aggiungi',
	'create' => 'Crea',
	'remove' => 'Rimuovi',
	'revert' => 'Ritorna',

	'site' => 'Sito',
	'activity' => 'Attività',
	'members' => 'Membri',
	'menu' => 'Menu',

	'up' => 'Su',
	'down' => 'Giù',
	'top' => 'In cima',
	'bottom' => 'In fondo',
	'right' => 'Destra',
	'left' => 'Sinistra',
	'back' => 'Indietro',

	'invite' => "Invita",

	'resetpassword' => "Reimposta password",
	'changepassword' => "Cambia password",
	'makeadmin' => "Rendi amministratore",
	'removeadmin' => "Rimuovi amministratore",

	'option:yes' => "Si",
	'option:no' => "No",

	'unknown' => 'Sconosciuto',
	'never' => 'Mai',

	'active' => 'Attivo',
	'total' => 'Totale',

	'ok' => 'OK',
	'any' => 'Nessun',
	'error' => 'Errore',

	'other' => 'Altro',
	'options' => 'Opzioni',
	'advanced' => 'Avanzate',

	'learnmore' => "Clicca qui per saperne di più",
	'unknown_error' => 'Errore sconosciuto',

	'content' => "contenuto",
	'content:latest' => 'Attività recenti',
	'content:latest:blurb' => 'In alternativa clicca qui per visualizzare i contenuti recenti dell\'intero sito.',

	'link:text' => 'visualizza il link',

/**
 * Generic questions
 */

	'question:areyousure' => 'Sicuro/a?',

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

	'sort:newest' => 'Recenti',
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
	'mine' => "Miei",

	'by' => 'di',
	'none' => 'niente',

	'annotations' => "Annotazioni",
	'relationships' => "Relazioni",
	'metadata' => "Metadata",
	'tagcloud' => "Nuvola di tag",

	'on' => 'Acceso',
	'off' => 'Spento',

/**
 * Entity actions
 */

	'edit:this' => 'Edita',
	'delete:this' => 'Cancella',
	'comment:this' => 'Commenta',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Sicuro/a di voler cancellare questo elemento?",
	'deleteconfirm:plural' => "Sei sicuro/a che vuoi cancellare questi elementi?",
	'fileexists' => "Un file è già stato caricato. Per sostituirlo, selezionalo in basso:",

/**
 * User add
 */

	'useradd:subject' => 'Profilo utente creato',
	'useradd:body' => '%s,

è stato creato un utente su %s. Per entrare, visita:

%s

usando le credenziali:

Username: %s
Password: %s

Appena entrato/a ti invitiamo caldamente a cambiare la password.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "clicca per scartare",


/**
 * Import / export
 */

	'importsuccess' => "Importazione dati avvenuta con successo",
	'importfail' => "Importazione dati OpenDD fallita.",

/**
 * Time
 */

	'friendlytime:justnow' => "proprio ora",
	'friendlytime:minutes' => "%s minuti fa",
	'friendlytime:minutes:singular' => "un minuto fa",
	'friendlytime:hours' => "%s ore fa",
	'friendlytime:hours:singular' => "un'ora fa",
	'friendlytime:days' => "%s giorni fa",
	'friendlytime:days:singular' => "ieri",
	'friendlytime:date_format' => 'j F Y @ g:ia',

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
	'interval:hourly' => 'Orario',
	'interval:daily' => 'Giornaliero',
	'interval:weekly' => 'Settimanale',
	'interval:monthly' => 'Mensile',
	'interval:yearly' => 'Annuale',
	'interval:reboot' => 'Al riavvio',

/**
 * System settings
 */

	'installation:sitename' => "Nome del tuo sito:",
	'installation:sitedescription' => "Short description of your site (optional):",
	'installation:wwwroot' => "The site URL:",
	'installation:path' => "The full path of the Elgg installation:",
	'installation:dataroot' => "The full path of the data directory:",
	'installation:dataroot:warning' => "You must create this directory manually. It should be in a different directory to your Elgg installation.",
	'installation:sitepermissions' => "The default access permissions:",
	'installation:language' => "The default language for your site:",
	'installation:debug' => "Control the amount of information written to the server's log.",
	'installation:debug:label' => "Log level:",
	'installation:debug:none' => 'Turn off logging (recommended)',
	'installation:debug:error' => 'Log only critical errors',
	'installation:debug:warning' => 'Log errors and warnings',
	'installation:debug:notice' => 'Log all errors, warnings and notices',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'User registration is enabled by default. Turn this off if you do not want new users to be able to register on their own.',
	'installation:registration:label' => 'Allow new users to register',
	'installation:walled_garden:description' => 'Enable the site to run as a private network. This will not allow non logged-in users to view any site pages other than those specifically marked as public.',
	'installation:walled_garden:label' => 'Restrict pages to logged-in users',

	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:default_limit' => "Numero di default di elementi per pagina",

	'admin:site:access:warning' => "This is the privacy setting suggested to users when they create new content. Changing it does not change access to content",
	'installation:allow_user_default_access:description' => "Enable this to allow users to set their own suggested privacy setting that overrides the system suggestion.",
	'installation:allow_user_default_access:label' => "Allow user default access",

	'installation:simplecache:description' => "The simple cache increases performance by caching static content including some CSS and JavaScript files.",
	'installation:simplecache:label' => "Use simple cache (recommended)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "The simple cache can also improve performance by compressing JavaScript and CSS files. (Requires that simple cache is enabled.)",
	'installation:minify_js:label' => "Compress JavaScript (recommended)",
	'installation:minify_css:label' => "Compress JavaScript (recommended)",

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file so that the path is injected into the GET parameter __elgg_uri (you can use htaccess_dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg cannot connect to itself to test rewrite rules properly. Check that curl is working and there are no IP restrictions preventing localhost connections.",

	'installation:systemcache:description' => "The system cache decreases the loading time of the Elgg engine by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content_access' => 'Accesso al contenuto',
	'admin:legend:site_access' => 'Accesso al sito',
	'admin:legend:debug' => 'Debugging and Logging',

	'upgrading' => 'Upgrading...',
	'upgrade:core' => 'Your Elgg installation was upgraded.',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Upgrade unlocked successfully.",
	'upgrade:unable_to_upgrade' => 'Unable to upgrade.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views

		were detected in the Elgg core views directory. These views have been deprecated and need to be

		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can

		simply delete the views directory and replace it with the one from the latest

		package of Elgg downloaded from <a rel="nofollow" href="http://elgg.org">elgg.org</a>.<br><br>



		If you need detailed instructions, please visit the <a rel="nofollow" href="http://docs.elgg.org/wiki/Upgrading_Elgg">

		Upgrading Elgg documentation</a>.  If you require assistance, please post to the

		<a rel="nofollow" href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (previously Twitter Service) was deactivated during the upgrade. Please activate it manually if required.',
	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) was deactivated during the upgrade.  Please activate it manually if required.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure > Settings > Site Secret",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure > Settings > Site Secret",

	'deprecated:function' => '%s() was deprecated by %s()',

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
	'item:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:item_count' => 'There are <b>%s</b> items that need to be upgraded.',
	'upgrade:warning' => '<b>Warning:</b> On a large site this upgrade may take a significantly long time!',
	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Errors:',
	'upgrade:river_update_failed' => 'Failed to update the river entry for item id %s',
	'upgrade:timestamp_update_failed' => 'Failed to update the timestamps for item id %s',
	'upgrade:finished' => 'Upgrade finished',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p><br>If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Comments upgrade',
	'upgrade:comment:create_failed' => 'Failed to convert comment id %s to an entity.',
	'admin:upgrades:commentaccess' => 'Aggiornamento accesso commenti',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Data directory upgrade',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Discussion reply upgrade',
	'discussion:upgrade:replies:create_failed' => 'Failed to convert discussion reply id %s to an entity.',

/**
 * Welcome
 */

	'welcome' => "Ciao",
	'welcome:user' => 'Ciao %s',

/**
 * Emails
 */

	'email:from' => 'Da',
	'email:to' => 'A',
	'email:subject' => 'Oggetto',
	'email:body' => 'Corpo',

	'email:settings' => "Impostazioni email",
	'email:address:label' => "Indirizzo email",

	'email:save:success' => "Nuovo indirizzo email salvato. Richiesta verifica.",
	'email:save:fail' => "Non è stato possibile salvare il nuovo indirizzo email.",

	'friend:newfriend:subject' => "%s ti ha aggiunto agli amici!",
	'friend:newfriend:body' => "%s ti ha aggiunto agli amici!
Per visualizzare il suo profilo, clicca qui:

%s

Per favore non rispondere a questa email.",

	'email:changepassword:subject' => "Password cambiata",
	'email:changepassword:body' => "Ciao %s,
la tua password è stata cambiata.",

	'email:resetpassword:subject' => "Password reimpostata",
	'email:resetpassword:body' => "Ciao %s,
la tua password è stata reimpostata a: %s",

	'email:changereq:subject' => "Richiesta cambio password",
	'email:changereq:body' => "Ciao %s,
qualcuno (dall'indirizzo IP %s) ha richiesto un cambio password per il tuo account.
Se sei stato/a tu clicca sul link in basso altrimenti ignora questo messaggio email.

%s",

/**
 * user default access
 */

	'default_access:settings' => "Livello di accesso di default",
	'default_access:label' => "Livello di accesso di default",
	'user:default_access:success' => "Il tuo nuovo livello di accesso di default è stato salvato.",
	'user:default_access:failure' => "Non è stato possibile salvare il tuo nuovo livello di accesso.",

/**
 * Comments
 */

	'comments:count' => "%s commenti",
	'item:object:comment' => 'Commenti',

	'river:comment:object:default' => '%s ha commentato %s',

	'generic_comments:add' => "Aggiungi un commento",
	'generic_comments:edit' => "Modifica commento",
	'generic_comments:post' => "Posta commento",
	'generic_comments:text' => "Commento",
	'generic_comments:latest' => "Commenti recenti",
	'generic_comment:posted' => "Il tuo commento è stato aggiunto.",
	'generic_comment:updated' => "Commento aggiornato.",
	'generic_comment:deleted' => "Commento eliminato con successo.",
	'generic_comment:blank' => "Prima di salvare devi scrivere qualcosa nel commento.",
	'generic_comment:notfound' => "Elemento specificato non trovato.",
	'generic_comment:notfound_fallback' => "Spiacenti, non abbiamo trovato il commento specificato ma ti abbiamo indirizzato alla pagina a cui era stato aggiunto.",
	'generic_comment:notdeleted' => "Spiacenti, non è stato possibile eliminare questo commento.",
	'generic_comment:failure' => "Errore inatteso durante il salvataggio del commento.",
	'generic_comment:none' => 'Nessun commento',
	'generic_comment:title' => 'Commento di %s',
	'generic_comment:on' => '%s su %s',
	'generic_comments:latest:posted' => 'ha aggiunto un',

	'generic_comment:email:subject' => 'Hai un nuovo commento!',
	'generic_comment:email:body' => "Hai un nuovo commento su \"%s\" da %s.
Dice:

%s

Per rispondere o visualizzare il contenuto originale clicca qui:

%s

Per vedere il profilo di %s, clicca qui:

%s

Per favore non rispondere a questa email.",

/**
 * Entities
 */

	'byline' => 'Di %s',
	'byline:ingroup' => 'nel gruppo %s',
	'entity:default:strapline' => 'Creato %s da %s',
	'entity:default:missingsupport:popup' => 'Questo elemento non può essere visualizzato correttamente. Probabilmente è associato ad una funzionalità non più disponibile nel sistema.',

	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item not found.',
	'entity:delete:permission_denied' => 'You do not have permissions to delete this item.',
	'entity:delete:success' => '%s has been deleted.',
	'entity:delete:fail' => '%s could not be deleted.',

	'entity:can_delete:invaliduser' => 'Cannot check canDelete() for user_guid [%s] as the user does not exist.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Form is missing __token or __ts fields',
	'actiongatekeeper:tokeninvalid' => "La pagina che stavi visualizzando è scaduta. Per favore riprova aggiornando il browser e rientrando.",
	'actiongatekeeper:timeerror' => 'La pagina che stavi visualizzando è scaduta. Per favore riprova aggiornando il browser e rientrando.',
	'actiongatekeeper:pluginprevents' => 'Un\'estensione ha impedito al modulo di essere inviato.',
	'actiongatekeeper:uploadexceeded' => 'La dimensione dei file caricati supera i limiti impostati dall\'amministratore',
	'actiongatekeeper:crosssitelogin' => "Spiacenti, non è possibile entrare da un altro dominio. Per favore clicca sul logo Social Business World e poi riprova, grazie.",

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

	'js:security:token_refresh_failed' => 'Non è possibile collegarsi a %s. Potresti riscontrare problemi a salvare contenuti',
	'js:security:token_refreshed' => 'Connessione a %s ristabilita!',
	'js:lightbox:current' => "immagine %s di %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",

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

	"field:required" => 'Required',

);
