<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Siti',

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
	'session_expired' => "Sessione scaduta. Ricaricare la pagina per accedere.",

	'loggedinrequired' => "Occorre accedere per visualizzare la pagina richiesta.",
	'adminrequired' => "Occorre essere amministratori per visualizzare la pagina richiesta.",
	'membershiprequired' => "Occorre essere membri di questo gruppo per visualizzare la pagina richiesta.",
	'limited_access' => "Permessi insufficienti per visualizzare la pagina richiesta.",


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
	'ElggPlugin:Exception:CannotIncludeFile' => 'Impossibile includere %s del plugin %s (guid: %s) a %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Impossibile aprire il percorso delle viste del plugin %s (guid: %s) a %s.',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Impossibile registrare la lingua del plugin %s (guid: %s) a %s.',
	'ElggPlugin:Exception:NoID' => 'Nessun ID per il plugin guid %s!',
	'PluginException:NoPluginName' => "Impossibile trovare il nome del plugin.",
	'PluginException:ParserError' => 'Errore di parsing del File Manifest con l\'API versione %s nel plugin %s.',
	'PluginException:NoAvailableParser' => 'Impossibile trovare un parser per il File Manifest con l\'API versione %s nel plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Attributo '%s' mancante nel File Manifest del plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s è un plugin non valido ed è stato disattivato.',

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

	'deprecatedfunction' => 'Attenzione: questo codice usa la funzione non approvata \'%s\' che non è compatibile con questa versione di Elgg.',

	'pageownerunavailable' => 'Attenzione: il proprietario %d di questa pagina non è accessibile!',
	'viewfailure' => 'Si è verificato un errore interno nella vista %s',
	'view:missing_param' => "Il parametro richiesto '%s' non si trova nella vista %s",
	'changebookmark' => 'Per favore cambia il segnalibro di questa pagina.',
	'noaccess' => 'I contenuti che stavi tentando di visualizzare sono stati rimossi, o non hai i permessi per visualizzarli.',
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
 * User details
 */

	'name' => "Nome visualizzato",
	'email' => "Indirizzo email",
	'username' => "Nome utente",
	'loginusername' => "Nome utente o email",
	'password' => "Password",
	'passwordagain' => "Password (di nuovo, per verifica)",
	'admin_option' => "Rendere questo utente un amministratore?",

/**
 * Access
 */

	'PRIVATE' => "Privato",
	'LOGGED_IN' => "Utenti loggati",
	'PUBLIC' => "Pubblico",
	'LOGGED_OUT' => "Utenti non collegati",
	'access:friends:label' => "Amici",
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
	'widgets:panel:close' => "Chiudi il pannello dei widget",
	'widgets:position:fixed' => '(posizione fissa nella pagina)',
	'widget:unavailable' => 'Hai già aggiunto questo widget',
	'widget:numbertodisplay' => 'Numero di elementi da mostrare',

	'widget:delete' => 'Rimuovi %s',
	'widget:edit' => 'Personalizza questo widget',

	'widgets' => "Widget",
	'widget' => "Widget",
	'item:object:widget' => "Widget",
	'widgets:save:success' => "Il Widget è stato salvato.",
	'widgets:save:failure' => "Impossibile salvare il tuo widget.",
	'widgets:add:success' => "Il widget è stato aggiunto.",
	'widgets:add:failure' => "Impossibile aggiungere il tuo widget.",
	'widgets:move:failure' => "Impossibile memorizzare la posizione del nuovo widget.",
	'widgets:remove:failure' => "Impossibile rimuovere questo widget",

/**
 * Groups
 */

	'group' => "Gruppo",
	'item:group' => "Gruppi",

/**
 * Users
 */

	'user' => "Utente",
	'item:user' => "Utenti",

/**
 * Friends
 */

	'friends' => "Amici",
	'friends:yours' => "I tuoi amici",
	'friends:owned' => "Amici di %s",
	'friend:add' => "Aggiungi un amico",
	'friend:remove' => "Rimuovi un amico",

	'friends:add:successful' => "Hai aggiunto %s agli amici.",
	'friends:add:failure' => "Impossibile aggiungere %s agli amici.",

	'friends:remove:successful' => "Hai rimosso %s dagli amici.",
	'friends:remove:failure' => "Impossibile rimuovere %s dai tuoi amici.",

	'friends:none' => "Ancora nessun amico.",
	'friends:none:you' => "Non hai ancora amici.",

	'friends:none:found' => "Nessun amico trovato.",

	'friends:of:none' => "Nessuno ha ancora aggiunto questo utente ai suoi amici.",
	'friends:of:none:you' => "Nessuno ti ha ancora aggiunto ai suoi amici. Inizia a inserire dei contenuti e a farti conoscere; compila il tuo profilo per farti trovare dagli altri!",

	'friends:of:owned' => "Persone che hanno aggiunto %s ai loro amici.",

	'friends:of' => "Amici di",
	'friends:collections' => "Collezione di amici",
	'collections:add' => "Nuova collezione",
	'friends:collections:add' => "Nuova collezione di amici",
	'friends:addfriends' => "Seleziona amici",
	'friends:collectionname' => "Nome della collezione",
	'friends:collectionfriends' => "Amici nella collezione",
	'friends:collectionedit' => "Modifica questa collezione",
	'friends:nocollections' => "Non hai ancora delle collezioni.",
	'friends:collectiondeleted' => "La tua collezione è stata eliminata.",
	'friends:collectiondeletefailed' => "Impossibile eliminare la collezione. O mancano i privilegi, o si è verificato qualche altro problema.",
	'friends:collectionadded' => "Collezione creata.",
	'friends:nocollectionname' => "Devi dare un nome alla collezione per poterla creare..",
	'friends:collections:members' => "Membri della collezione",
	'friends:collections:edit' => "Modifica collezione",
	'friends:collections:edited' => "Collezione salvata",
	'friends:collection:edit_failed' => 'Impossibile salvare la collezione.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Immagine del profilo',
	'avatar:noaccess' => "Non sei autorizzato a modificare l'immagine del profilo di questo utente",
	'avatar:create' => 'Crea la tua immagine del profilo',
	'avatar:edit' => 'Modifica immagine del profilo',
	'avatar:preview' => 'Anteprima',
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

	'profile:edit' => 'Modifica profilo',
	'profile:aboutme' => "Informazioni personali",
	'profile:description' => "Informazioni personali",
	'profile:briefdescription' => "Breve descrizione",
	'profile:location' => "Luogo",
	'profile:skills' => "Competenze",
	'profile:interests' => "Interessi",
	'profile:contactemail' => "Email",
	'profile:phone' => "Telefono",
	'profile:mobile' => "Cellulare",
	'profile:website' => "Sito web",
	'profile:twitter' => "Nome utente su Twitter",
	'profile:saved' => "Il tuo profilo è stato salvato.",

	'profile:field:text' => 'Testo breve',
	'profile:field:longtext' => 'Area di testo ampia',
	'profile:field:tags' => 'Tag',
	'profile:field:url' => 'Indirizzo web',
	'profile:field:email' => 'Indirizzo email',
	'profile:field:location' => 'Luogo',
	'profile:field:date' => 'Data',

	'admin:appearance:profile_fields' => 'Modifica i campi del profilo',
	'profile:edit:default' => 'Modifica i campi del profilo',
	'profile:label' => "Etichetta del profilo",
	'profile:type' => "Tipo di profilo",
	'profile:editdefault:delete:fail' => 'Impossibile rimuovere il campo dal profilo',
	'profile:editdefault:delete:success' => 'Eliminato il campo dal profilo',
	'profile:defaultprofile:reset' => 'I campi del profilo sono stati ripristinati ai valori predefiniti',
	'profile:resetdefault' => 'Riporta i campi del profilo ai loro valori predefiniti',
	'profile:resetdefault:confirm' => 'Sicuri di voler eliminare i campi personalizzati dal profilo?',
	'profile:explainchangefields' => "Utilizzando il modulo sottostante è possibile sostituire i campi predefiniti del profilo con dei campi personalizzati. \n\n Assegnare al nuovo campo di profilo un'etichetta, ad es. 'Squadra del cuore', quindi selezionare il tipo di campo (ad es, testo, url, tag), infine fare click sul pulsante 'Aggiungi'. \n Per disporre i campi nell'ordine desiderato trascinarli per la maniglia posta dopo il campo etichetta..\n Per modificare un campo etichetta fare click sul testo dell'etichetta per renderlo modificabile. \n\n In qualsiasi momento è possibile tornare alle impostazioni di partenza predefinite del profilo, ma si perderanno tutte le informazioni già inserite nei campi personalizzati sulle pagine del profilo.",
	'profile:editdefault:success' => 'Aggiunto nuovo campo di profilo',
	'profile:editdefault:fail' => 'Il profilo predefinito non può essere salvato',
	'profile:field_too_long' => 'Impossibile salvare le informazioni del profilo perché la sezione  "%s" è troppo lunga.',
	'profile:noaccess' => "Permessi insufficienti per modificare questo profilo.",
	'profile:invalid_email' => '%s deve essere un indirizzo email valido.',


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
	'river:friend:user:default' => "%s è ora in amicizia con %s",
	'river:update:user:avatar' => '%s ha una nuova immagine del profilo',
	'river:update:user:profile' => '%s ha aggiornato il suo profilo',
	'river:noaccess' => 'Permessi insufficienti per visualizzare questo elemento.',
	'river:posted:generic' => '%s inviato',
	'riveritem:single:user' => 'un utente',
	'riveritem:plural:user' => 'alcuni utenti',
	'river:ingroup' => 'nel gruppo %s',
	'river:none' => 'Nessuna attività',
	'river:update' => 'Aggiornamento di %s',
	'river:delete' => 'Rimuovi questo elemento di attività',
	'river:delete:success' => 'Un elemento di Attività è stato eliminato',
	'river:delete:fail' => 'Impossibile eliminare l\'elemento di Attività',
	'river:subject:invalid_subject' => 'Utente non valido',
	'activity:owner' => 'Visualizza attività',

	'river:widget:title' => "Attività",
	'river:widget:description' => "Visualizza le attività recenti",
	'river:widget:type' => "Tipo di attività",
	'river:widgets:friends' => 'Attività degli amici',
	'river:widgets:all' => 'Tutte le attività del sito',

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

	'user:password:lost' => 'Password smarrita',
	'user:password:changereq:success' => 'Richiesta di una nuova password completata, email inviata.',
	'user:password:changereq:fail' => 'Impossibile richiedere una nuova password.',

	'user:password:text' => 'Per richiedere una nuova password inserire il tuo nome utente o l\'indirizzo email con cui ti sei registrato e clicca sul pulsante Richiedi',

	'user:persistent' => 'Ricordami',

	'walled_garden:welcome' => 'Benvenuto in',

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

	'admin:statistics' => "Statistiche",
	'admin:statistics:overview' => 'Panoramica',
	'admin:statistics:server' => 'Informazioni sul server',
	'admin:statistics:cron' => 'Attività pianificate',
	'admin:cron:record' => 'Lavori recenti delle attività pianificate',
	'admin:cron:period' => 'Periodo attività pianificate',
	'admin:cron:friendly' => 'Ultimo completato',
	'admin:cron:date' => 'Data e ora',

	'admin:appearance' => 'Aspetto',
	'admin:administer_utilities' => 'Utilità',
	'admin:develop_utilities' => 'Utilità',
	'admin:configure_utilities' => 'Utilità',
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

	'admin:administer_utilities:maintenance' => 'Modalità di manutenzione',
	'admin:upgrades' => 'Aggiornamenti',

	'admin:settings' => 'Impostazioni',
	'admin:settings:basic' => 'Impostazioni di base',
	'admin:settings:advanced' => 'Impostazioni avanzate',
	'admin:site:description' => "Questo pannello di amministrazione permette di gestire le impostazioni globali del sito. Selezionare un'opzione qui sotto per iniziare.",
	'admin:site:opt:linktext' => "Configura sito...",
	'admin:settings:in_settings_file' => 'Queste impostazioni sono definite in settings.php',

	'admin:legend:security' => 'Sicurezza',
	'admin:site:secret:intro' => 'Elgg utilizza una chiave per generare token di sicurezza per utilizzi diversi.',
	'admin:site:secret_regenerated' => "La chiave di sicurezza del sito è stata rigenerata.",
	'admin:site:secret:regenerate' => "Rigenera chiave di sicurezza del sito",
	'admin:site:secret:regenerate:help' => "Nota: La rigenerazione della chiave di sicurezza del sito può impattare su alcuni utenti rendendo inutilizzabili i token utilizzati nei cookie \"ricordami\", nella richiesta di validazione email, nei codici di invito, ecc..",
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
	'widget:content_stats:type' => 'Tipo di contenuti',
	'widget:content_stats:number' => 'Numero',

	'admin:widget:admin_welcome' => 'Benvenuto',
	'admin:widget:admin_welcome:help' => "Piccola introduzione all'area amministrativa di Elgg",
	'admin:widget:admin_welcome:intro' =>
'Benvenuti in Elgg! In questo momento siete di fronte al pannello di controllo che viene utilizzato per tenere sotto controllo quello che sta succedendo nel sito.',

	'admin:widget:admin_welcome:admin_overview' =>
"La navigazione all'interno dell'area amministrativa è garantita dal menu a destra che è organizzato in tre sezioni:\n\n
<dl>	
<dt>Amministrazione</dt>
<dd>Sezione dedicata alle attività quotidiane come il controllo dei contenuti segnalati, la verifica di chi è online e la visualizzazione delle statistiche.</dd>
<dt>Configurazione</dt>
<dd>Sezione dedicata alle attività occasionali come l'impostazione del nome del sito o l'attivazione di un nuovo plugin.</dd>
<dt>Sviluppo</dt>
<dd>Sezione dedicata agli sviluppatori che stanno sviluppando un nuovo plugin o realizzando un nuovo tema grafico. (È richiesto un plugin developer).</dd>
</dl>",

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

	'admin:notices:could_not_delete' => 'Impossibile eliminare l\'avviso.',
	'item:object:admin_notice' => 'Avviso amministrativo',

	'admin:options' => 'Opzioni amministrative',

/**
 * Plugins
 */

	'plugins:disabled' => 'Impossibile caricare i plugin per la presenza di un file chiamato "disabled" nella cartella mod.',
	'plugins:settings:save:ok' => "Le impostazioni per il plugin %s sono state salvate.",
	'plugins:settings:save:fail' => "Si è verificato un problema durante il salvataggio del plugin %s.",
	'plugins:usersettings:save:ok' => "Le impostazioni utente del plugin %s sono state salvate.",
	'plugins:usersettings:save:fail' => "Si è verificato un problema durante il salvataggio delle impostazioni utente del plugin %s.",
	'item:object:plugin' => 'Plugin',

	'admin:plugins' => "Plugin",
	'admin:plugins:activate_all' => 'Attiva tutti',
	'admin:plugins:deactivate_all' => 'Disattiva tutti',
	'admin:plugins:activate' => 'Attiva',
	'admin:plugins:deactivate' => 'Disattiva',
	'admin:plugins:description' => "Questo pannello amministrativo permette di controllare e configurare gli strumenti installati in questo sito.",
	'admin:plugins:opt:linktext' => "Configura strumenti...",
	'admin:plugins:opt:description' => "Configura gli strumenti installati in questo sito.",
	'admin:plugins:label:author' => "Autore",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categorie',
	'admin:plugins:label:licence' => "Licenza",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Codice",
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

	'admin:plugins:warning:elgg_version_unknown' => 'Questo plugin fa uso di un Manifest File obsoleto che non specifica una versione di Elgg compatibile. Probabilmente non funzionerà!',
	'admin:plugins:warning:unmet_dependencies' => 'Questo plugin non può essere attivato perché ha delle dipendenze non soddisfatte. Verificare le dipendenze in ulteriori informazioni.',
	'admin:plugins:warning:invalid' => 'Questo plugin non è valido: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Consulta la <a rel="nofollow" href="http://docs.elgg.org/Invalid_Plugin">documentazione di Elgg</a> per suggerimenti sulla risoluzione dei problemi.',
	'admin:plugins:cannot_activate' => 'impossibile attivare',

	'admin:plugins:set_priority:yes' => "%s riordinato.",
	'admin:plugins:set_priority:no' => "Impossibile riordinare %s.",
	'admin:plugins:set_priority:no_with_msg' => "Impossibile riordinare %s. Errore: %s",
	'admin:plugins:deactivate:yes' => "%s disattivato.",
	'admin:plugins:deactivate:no' => "Impossibile disattivare %s.",
	'admin:plugins:deactivate:no_with_msg' => "Impossibile disattivare %s. Errore: %s",
	'admin:plugins:activate:yes' => "%s attivato.",
	'admin:plugins:activate:no' => "Impossibile attivare %s.",
	'admin:plugins:activate:no_with_msg' => "Impossibile attivare %s. Errore: %s",
	'admin:plugins:categories:all' => 'Tutte le categorie',
	'admin:plugins:plugin_website' => 'Sito web dei plugin',
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
	'admin:statistics:label:basic' => "Statistiche di base del sito",
	'admin:statistics:label:numentities' => "Entità nel sito",
	'admin:statistics:label:numusers' => "Numero di utenti",
	'admin:statistics:label:numonline' => "Numero di utenti online",
	'admin:statistics:label:onlineusers' => "Utenti online in questo momento",
	'admin:statistics:label:admins'=>"Amministratori",
	'admin:statistics:label:version' => "Versione di Elgg",
	'admin:statistics:label:version:release' => "Versione",
	'admin:statistics:label:version:version' => "Rilascio",

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

	'admin:appearance:menu_items' => 'Voci di menu',
	'admin:menu_items:configure' => 'Configura le voci del menu principale',
	'admin:menu_items:description' => 'Selezionare quali voci di menu si vogliono impostare come collegamenti sempre visibili. Le voci non utilizzate saranno aggiunte ad "Altro" al fondo dell\'elenco.',
	'admin:menu_items:hide_toolbar_entries' => 'Sicuri di voler rimuovere i collegamenti dalla barra dei menu?',
	'admin:menu_items:saved' => 'Voci di menu salvate.',
	'admin:add_menu_item' => 'Aggiungi una voce di menu personalizzata',
	'admin:add_menu_item:description' => 'Specifica il nome da visualizzare e un URL per aggiungere una voce personalizzata al menu di navigazione.',

	'admin:appearance:default_widgets' => 'Widget predefiniti',
	'admin:default_widgets:unknown_type' => 'Tipo di widget sconosciuto',
	'admin:default_widgets:instructions' => 'Aggiungi, rimuovi, posiziona e configura i widget predefiniti sulle pagine selezionate.
Queste modifiche avranno effetto solo sui nuovi utenti del sito.',

	'admin:robots.txt:instructions' => "Modifica qui sotto il file robots.txt di questo sito",
	'admin:robots.txt:plugins' => "I plugin stanno aggiungendo le seguenti cose al file robots.txt",
	'admin:robots.txt:subdir' => "Lo strumento robots.txt non funzionerà perché Elgg è installato in una sotto cartella",
	'admin:robots.txt:physical' => "Lo strumento robots.txt non funzionerà perché un file robots.txt è fisicamente presente",

	'admin:maintenance_mode:default_message' => 'Questo sito non è al momento disponibile perché in manutenzione',
	'admin:maintenance_mode:instructions' => 'La modalità di manutenzione dovrebbe essere attivata in occasione di aggiornamenti e altre importanti modifiche al sito. Quando è attiva, solo gli amministratori possono accedere al sito e navigare in esso.',
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
	'icon:size:master' => "Grandissima",
		
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
	'fileexists' => "È già stato caricato un file. Per sostituirlo, selezionalo qui sotto:",

/**
 * User add
 */

	'useradd:subject' => 'Profilo utente creato',
	'useradd:body' => '
%s,

è stato creato un profilo utente per te su %s. Per accedere, visita:

%s

e accedi utilizzando queste credenziali:

Nome utente: %s
Password: %s

Una volta entrato/a ti invitiamo caldamente a cambiare la tua password.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "clicca per scartare",


/**
 * Import / export
 */
		
	'importsuccess' => "Importazione dei dati completata",
	'importfail' => "Impossibile importare i dati OpenDD.",

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

	'date:weekday:0' => 'Domenica',
	'date:weekday:1' => 'Lunedì',
	'date:weekday:2' => 'Martedì',
	'date:weekday:3' => 'Mercoledì',
	'date:weekday:4' => 'Giovedì',
	'date:weekday:5' => 'Venerdì',
	'date:weekday:6' => 'Sabato',
	
	'interval:minute' => 'Ogni minuto',
	'interval:fiveminute' => 'Ogni cinque minuti',
	'interval:fifteenmin' => 'Ogni quindici minuti',
	'interval:halfhour' => 'Ogni mezz\'ora',
	'interval:hourly' => 'Ogni ora',
	'interval:daily' => 'Giornaliero',
	'interval:weekly' => 'Settimanale',
	'interval:monthly' => 'Mensile',
	'interval:yearly' => 'Annuale',
	'interval:reboot' => 'Al riavvio',

/**
 * System settings
 */

	'installation:sitename' => "Nome del sito:",
	'installation:sitedescription' => "Breve descrizione del sito (opzionale):",
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

	'installation:httpslogin' => "Abilita questa opzione per permettere il login degli utenti con protocollo HTTPS. Il server web deve avere il protocollo HTTPS abilitato per poter abilitare questa opzione!",
	'installation:httpslogin:label' => "Abilita il login tramite HTTPS",
	'installation:view' => "Specificare la vista che deve essere usata in modo predefinito per il sito, o lasciare in bianco per abilitare la vista preimpostata (nel dubbio lasciare come preimpostato):",

	'installation:siteemail' => "Indirizzo email del sito (usato quando si inviano email di sistema):",
	'installation:default_limit' => "Numero predefinito di elementi per pagina",

	'admin:site:access:warning' => "Questa è l'impostazione sulla privacy predefinita quando gli utenti creano nuovi contenuti. Il cambiamento di questa impostazione non modifica l'accesso ai contenuti.",
	'installation:allow_user_default_access:description' => "Questa impostazione permette agli utenti di poter impostare il livello di privacy sovrascrivendo il livello di privacy suggerito dal sistema.",
	'installation:allow_user_default_access:label' => "Permetti agli utenti di definire i livelli di accesso",

	'installation:simplecache:description' => "La cache semplice aumenta le prestazioni memorizzando contenuti statici come file CSS e JavaScript.",
	'installation:simplecache:label' => "Utilizza la cache semplice (consigliato)",

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

	'upgrading' => 'Aggiornamento in corso...',
	'upgrade:db' => 'Il database è stato aggiornato.',
	'upgrade:core' => 'L\'installazione di Elgg è stata aggiornata.',
	'upgrade:unlock' => 'Sblocca aggiornamenti',
	'upgrade:unlock:confirm' => "Il database è bloccato per ulteriori aggiornamenti. Eseguire più aggiornamenti simultaneamente è pericoloso. Si dovrebbe continuare solo se si è sicuri che un altro aggiornamento non è in esecuzione. Sbloccare?",
	'upgrade:locked' => "Impossibile aggiornare. Un altro aggiornamento è in esecuzione. Per rimuovere il blocco all'aggiornamento visitare la sezione di amministrazione.",
	'upgrade:unlock:success' => "L'aggiornamento è stato sbloccato.",
	'upgrade:unable_to_upgrade' => 'Abilitare per aggiornare.',
	'upgrade:unable_to_upgrade_info' =>
		'Questa installazione non può essere aggiornata a causa di viste obsolete.
⇥⇥sono state identificate nella cartella delle viste del core di Elgg. Queste viste non sono approvate e devono essere rimosse per far funzionare Elgg in modo corretto. Se non avete apportato modifiche al core di Egg potete
⇥⇥eliminare semplicemente la cartella delle viste e sostituirla con quella presente nelle ultime versioni di Elgg scaricabili da <a href="http://elgg.org">elgg.org</a>.<br /><br />
⇥⇥Se avete bisogno di istruzioni dettagliate visitate <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
⇥⇥Documentazione sull\'aggiornamento di Elgg</a>. Se avete bisogno di assistenza rivolgetevi al 
⇥⇥<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'La API di Twitter (in precedenza Twitter Service) è stata disattivata durante l\'aggiornamento. Attivarla manualmente se necessario.',
	'update:oauth_api:deactivated' => 'OAuth API (in precedenza OAuth Lib) è stata disattivata durante l\'aggiornamento. Attivarla manualmente se necessario.',
	'upgrade:site_secret_warning:moderate' => "Si consiglia di rigenerare la chiave di sicurezza del sito per aumentare la sicurezza del sistema. Vedere Configurazione &gt; Impostazioni &gt; Chiave di sicurezza del sito",
	'upgrade:site_secret_warning:weak' => "Siete vivamente pregati di rigenerare la chiave di sicurezza del sito per aumentare la sicurezza del sistema. Vedere Configurazione &gt; Impostazioni &gt; Avanzate",

	'deprecated:function' => '%s() non è stata approvata da %s()',

	'admin:pending_upgrades' => 'Il sito ha degli aggiornamenti in sospeso che richiedono la vostra immediata attenzione.',
	'admin:view_upgrades' => 'Visualizza aggiornamenti in sospeso.',
 	'admin:upgrades' => 'Aggiornamenti',
	'item:object:elgg_upgrade' => 'Aggiornamenti del sito',
	'admin:upgrades:none' => 'Questa installazione è aggiornata!',

	'upgrade:item_count' => 'Ci sono <b>%s</b> elementi che devono essere aggiornati.',
	'upgrade:warning' => '<b>Attenzione:</b> Su un sito di grandi dimensioni questo aggiornamento potrebbe richiedere molto tempo per essere completato!',
	'upgrade:success_count' => 'Aggiornati:',
	'upgrade:error_count' => 'Errori:',
	'upgrade:river_update_failed' => 'Aggiornamento dell\'attività per l\'elemento id %s non riuscito',
	'upgrade:timestamp_update_failed' => 'Failed to update the timestamps for item id %s',
	'upgrade:finished' => 'Aggiornamento completato',
	'upgrade:finished_with_errors' => '<p>L\'aggiornamento è terminato con degli errori. Aggiornare la pagina e provare a eseguire nuovamente l\'aggiornamento.</p><br>Se l\'errore si manifesta nuovamente, controllare il log file degli errori del server per trovare le possibili cause. Per eliminare l\'errore si può cercare aiuto nel <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> della comunità di Elgg.',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Aggiornamento dei commenti',
	'upgrade:comment:create_failed' => 'Conversione del commento id %s in entità non riuscito.',
	'admin:upgrades:commentaccess' => 'Aggiornamento dell\'accesso ai commenti',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Aggiornamento della cartella dati',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Aggiornamento delle risposte di discussione',
	'discussion:upgrade:replies:create_failed' => 'Conversione della risposta di discussione id %s in entità non riuscita.',

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

	'email:save:success' => "Nuovo indirizzo email salvato. È richiesta la verifica.",
	'email:save:fail' => "Impossibile salvare il nuovo indirizzo email.",

	'friend:newfriend:subject' => "%s ti ha aggiunto agli amici!",
	'friend:newfriend:body' => "%s ti ha aggiunto agli amici!

Per visualizzare il suo profilo, clicca qui:

%s

Per favore non rispondere a questa email.",

	'email:changepassword:subject' => "Password cambiata!",
	'email:changepassword:body' => "Ciao %s,

la tua password è stata cambiata.",

	'email:resetpassword:subject' => "Password azzerata!",
	'email:resetpassword:body' => "Ciao %s,

la tua password è stata reimpostata a: %s",

	'email:changereq:subject' => "Richiesta di cambio password.",
	'email:changereq:body' => "Ciao %s,

qualcuno (dall'indirizzo IP %s) ha richiesto un cambio password per il tuo account.

Se sei stato/a tu, clicca sul link in basso. In caso contrario ignora questo messaggio email.

%s
",

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

	'river:comment:object:default' => '%s ha commentato %s',

	'generic_comments:add' => "Aggiungi un commento",
	'generic_comments:edit' => "Modifica commento",
	'generic_comments:post' => "Invia commento",
	'generic_comments:text' => "Commento",
	'generic_comments:latest' => "Ultimi commenti",
	'generic_comment:posted' => "Il tuo commento è stato inviato.",
	'generic_comment:updated' => "Il commento è stato aggiornato.",
	'generic_comment:deleted' => "Il commento è stato eliminato.",
	'generic_comment:blank' => "Spiacenti, devi scrivere qualcosa nel commento per poterlo salvare.",
	'generic_comment:notfound' => "Spiacenti, impossibile trovare il commento specificato.",
	'generic_comment:notfound_fallback' => "Spiacenti, impossibile trovare il commento specificato, ma ti abbiamo indirizzato alla pagina a cui era stato aggiunto.",
	'generic_comment:notdeleted' => "Spiacenti, impossibile eliminare questo commento.",
	'generic_comment:failure' => "Errore inatteso durante il salvataggio del commento.",
	'generic_comment:none' => 'Nessun commento',
	'generic_comment:title' => 'Commento di %s',
	'generic_comment:on' => '%s su %s',
	'generic_comments:latest:posted' => 'ha inviato un',

	'generic_comment:email:subject' => 'Hai un nuovo commento!',
	'generic_comment:email:body' => "Hai un nuovo commento su \"%s\" da %s. Dice:

%s

Per rispondere o visualizzare i contenuti originali clicca qui:

%s

Per vedere il profilo di %s, clicca qui:

%s

Per favore non rispondere a questa email.",

/**
 * Entities
 */
	
	'byline' => 'Di %s',
	'entity:default:strapline' => 'Creato %s da %s',
	'entity:default:missingsupport:popup' => 'Questo elemento non può essere visualizzato correttamente. Probabilmente perché richiede il supporto fornito da un plugin non più disponibile nel sistema.',

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
	"eu_es" => "Basco (Spagna)",
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
	"pt_br" => "Brazilian Portuguese",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Rumeno (Romania)",
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
	"sr_latin" => "Serbo (Latino)",
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
);
