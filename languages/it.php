<?php
     /**
	 * Elgg Core language pack
	 * 
	 * @package ElggCore
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * ****************************************
     * @Italian Language Pack
     * @Core System
     * @version: beta 
     * @revision: 2063
     * @translation by Lord55  
     * @link http://www.nobilityofequals.com
     ****************************************/

	$italian = array(

		/**
		 * Sites   ###Siti###
		 */
	
			'item:site' => 'Siti',
	
		/**
		 * Sessions   ###Sessioni###
		 */
			
			'login' => "Entra",
			'loginok' => "Sei entrato.",
			'loginerror' => "Non possiamo farti entrare. Questo può accadere perchè non hai ancora validato il tuo account, o i dati che hai fornito non erano corretti. Assicurati che i tuoi dati siano corretti e per favore riprova.",
	
			'logout' => "Esci",
			'logoutok' => "Sei uscito.",
			'logouterror' => "Non possiamo farti uscire. Per favore riprova.",
	
		/**
		 * Errors   ###Errori###
		 */
			'exception:title' => "Benvenuto  Elgg.",
	
			'InstallationException:CantCreateSite' => "Unable to create a default ElggSite with credentials Name:%s, Url: %s",
		
			'actionundefined' => "The requested action (%s) was not defined in the system.",
			'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
	
			'notfound' => "The requested resource could not be found, or you do not have access to it.",
			
			'SecurityException:Codeblock' => "Denied access to execute privileged code block",
			'DatabaseException:WrongCredentials' => "Elgg couldn't connect to the database using the given credentials %s@%s (pw: %s).",
			'DatabaseException:NoConnect' => "Elgg couldn't select the database '%s', please check that the database is created and you have access to it.",
			'SecurityException:FunctionDenied' => "Access to privileged function '%s' is denied.",
			'DatabaseException:DBSetupIssues' => "There were a number of issues: ",
			'DatabaseException:ScriptNotFound' => "Elgg couldn't find the requested database script at %s.",
			
			'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d",
			'InvalidParameterException:NonElggObject' => "Passing a non-ElggObject to an ElggObject constructor!",
			'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d is not a valid %s",
			
			'PluginException:MisconfiguredPlugin' => "%s is a misconfigured plugin.",
			
			'InvalidParameterException:NonElggUser' => "Passing a non-ElggUser to an ElggUser constructor!",
			
			'InvalidParameterException:NonElggSite' => "Passing a non-ElggSite to an ElggSite constructor!",
			
			'InvalidParameterException:NonElggGroup' => "Passing a non-ElggGroup to an ElggGroup constructor!",
	
			'IOException:UnableToSaveNew' => "Unable to save new %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
			'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",
			
			'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
			'IOException:NotDirectory' => "%s is not a directory.",
			
			'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
			'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
			'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",
			
			'ClassException:ClassnameNotClass' => "%s is not a %s.",
			'ClassNotFoundException:MissingClass' => "Class '%s' was not found, missing plugin?",
			'InstallationException:TypeNotSupported' => "Type %s is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.",

			'ImportException:ImportFailed' => "Could not import element %d",
			'ImportException:ProblemSaving' => "There was a problem saving %s",
			'ImportException:NoGUID' => "New entity created but has no GUID, this should not happen.",
			
			'ImportException:GUIDNotFound' => "Entity '%d' could not be found.",
			'ImportException:ProblemUpdatingMeta' => "There was a problem updating '%s' on entity '%d'",
			
			'ExportException:NoSuchEntity' => "No such entity GUID:%d", 
			
			'ImportException:NoODDElements' => "No OpenDD elements found in import data, import failed.",
			'ImportException:NotAllImported' => "Not all elements were imported.",
			
			'InvalidParameterException:UnrecognisedFileMode' => "Unrecognised file mode '%s'",
			'InvalidParameterException:MissingOwner' => "All files must have an owner!",
			'IOException:CouldNotMake' => "Could not make %s",
			'IOException:MissingFileName' => "You must specify a name before opening a file.",
			'ClassNotFoundException:NotFoundNotSavedWithFile' => "Filestore not found or class not saved with file!",
			'NotificationException:NoNotificationMethod' => "No notification method specified.",
			'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
			'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
			'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
			'NotificationException:MissingParameter' => "Missing a required parameter, '%s'",
			
			'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
			'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
			'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
			'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
			'DatabaseException:NoACL' => "No access control was provided on query",
			
			'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",
			
			'InvalidParameterException:GUIDNotFound' => "GUID:%s could not be found, or you can not access it.",
			'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
			'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
			'InvalidParameterException:NoDataFound' => "Could not find any data.",
			'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
			'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
			'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",
			
			'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
			'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
			'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.", 
			
			'ConfigurationException:NoSiteID' => "No site ID has been specified.",
			'InvalidParameterException:UnrecognisedMethod' => "Unrecognised call method '%s'",
			'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
			'APIException:ParameterNotArray' => "%s does not appear to be an array.",
			'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
			'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
			'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
			'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
			'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
			'CallException:InvalidCallMethod' => "%s must be called using '%s'",
			'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
			'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
			'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
			'APIException:NotGetOrPost' => "Request method must be GET or POST",
			'APIException:MissingAPIKey' => "Missing X-Elgg-apikey HTTP header",
			'APIException:MissingHmac' => "Missing X-Elgg-hmac header",
			'APIException:MissingHmacAlgo' => "Missing X-Elgg-hmac-algo header",
			'APIException:MissingTime' => "Missing X-Elgg-time header",
			'APIException:TemporalDrift' => "X-Elgg-time is too far in the past or future. Epoch fail.",
			'APIException:NoQueryString' => "No data on the query string",
			'APIException:MissingPOSTHash' => "Missing X-Elgg-posthash header",
			'APIException:MissingPOSTAlgo' => "Missing X-Elgg-posthash_algo header",
			'APIException:MissingContentType' => "Missing content type for post data",
			'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
			'SecurityException:DupePacket' => "Packet signature already seen.",
			'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
			'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.",
	
			'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
			'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
			'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",
	
			'PluginException:NoPluginName' => "The plugin name could not be found",
	
			'ConfigurationException:BadDatabaseVersion' => "The database backend you have installed doesn't meet the basic requirements to run Elgg. Please consult your documentation.",
			'ConfigurationException:BadPHPVersion' => "You need at least PHP version 5.2 to run Elgg.",
			
	
			'InstallationException:DatarootNotWritable' => "Your data directory %s is not writable.",
			'InstallationException:DatarootUnderPath' => "Your data directory %s must be outside of your install path.",
			'InstallationException:DatarootBlank' => "Non hai specificato una cartella dati.",
	
		/**
		 * User details   ###Visualizza nome###
		 */

			'name' => "Vedi nome",
			'email' => "Indirizzo Email",
			'username' => "Nome utente",
			'password' => "Password",
			'passwordagain' => "Password (di nuovo per la verifica)",
			'admin_option' => "Nomina questo utente Amministratore?",
	
		/**
		 * Access   ###Accesso###
		 */
	
			'ACCESS_PRIVATE' => "Privato",
			'ACCESS_LOGGED_IN' => "Membri",
			'ACCESS_PUBLIC' => "Pubblico",
			'PRIVATE' => "Privato",
			'LOGGED_IN' => "Membri",
			'PUBLIC' => "Pubblico",
			'access' => "Accedi",
	
		/**
		 * Dashboard and widgets   ###Dashboard e widget###
		 */
	
			'dashboard' => "Dashboard",
            'dashboard:configure' => "Modifica pagina",
			'dashboard:nowidgets' => "La Dashboard è la tua porta d'ingresso nel sito. Clicca 'Modifica pagina' per aggiungere i Widget che ti permetteranno di tenere traccia di tutte le interazioni dentro il sistema.",

			'widgets:add' => 'Aggiungi Widget alla tua pagina',
			'widgets:add:description' => "Scegli le caratteristiche che vorresti aggiungere alla tua pagina spostandoli dalla <b>Galleria-Widget</b> sulla destra, su una delle tre aree-Widget qui sotto, e posizionali dove vorresti farli apparire.

Per rimuovere un Widget, riportalo indietro nella <b>Galleria-Widget</b>.",
			'widgets:position:fixed' => '(Posizione sistemata sulla pagina)',
	
			'widgets' => "Widget",
			'widget' => "Widget",
			'item:object:widget' => "Widget",
			'layout:customise' => "Personalizza la grafica",
			'widgets:gallery' => "Galleria Widget",
			'widgets:leftcolumn' => "Sinistra-Area Widget",
			'widgets:fixed' => "Posizione sistemata",
			'widgets:middlecolumn' => "Centro-Area Widget",
			'widgets:rightcolumn' => "Destra-Area Widget",
			'widgets:profilebox' => "Modulo profilo",
			'widgets:panel:save:success' => "Il tuo Widget è stato salvato con successo.",
			'widgets:panel:save:failure' => "C'è stato un problema nel salvare il tuo Widget. Per favore riprova.",
			'widgets:save:success' => "Il Widget è stato salvato con successo.",
			'widgets:save:failure' => "Non possiamo salvare il tuo widget. Per favore riprova.",
			
	
		/**
		 * Groups   ###Gruppi###
		 */
	
			'group' => "Gruppo", 
			'item:group' => "Gruppi",
	
		/**
		 * Profile   ###Profilo###
		 */
	
			'profile' => "Profilo",
			'user' => "Utente",
			'item:user' => "Utenti",

		/**
		 * Profile menu items and titles   ####Argomenti del menu profilo e titoli###
		 */
	
			'profile:yours' => "Tuo profilo",
			'profile:user' => "Profilo di %s",
	
			'profile:edit' => "Modifica profilo",
			'profile:editicon' => "Invia una nuova foto nel profilo",
			'profile:profilepictureinstructions' => "La Foto-profilo è un'immagine che viene visualizzata sulla tua pagina-profilo. <br /> Potrai cambiarla quante volte vorrai. (Formati file accettati: GIF, JPG o PNG)",
			'profile:icon' => "Foto del profilo",
			'profile:createicon' => "Crea il tuo avatar",
			'profile:currentavatar' => "Avatar corrente",
			'profile:createicon:header' => "Foto del profilo",
			'profile:profilepicturecroppingtool' => "Tool di ritaglio foto del profilo",
			'profile:createicon:instructions' => "Clicca e trascina un quadrato qui sotto per definire quanta immagine vuoi tagliare. Un'anteprima dell'immagina tagliata apparirà nel riquadro sulla destra. Quando sarai contento dell'anteprima, clicca 'Crea il tuo avatar'. Questa immagine tagliata sarà utilizzata in ogni parte del sito come il tuo avatar. ",
	
			'profile:editdetails' => "Modifica dettagli",
			'profile:editicon' => "Modifica icona del profilo",
	
			'profile:aboutme' => "Chi sono", 
			'profile:description' => "Chi sono",
			'profile:briefdescription' => "Descrizione breve",
			'profile:location' => "Località",
			'profile:skills' => "Attitudini",  
			'profile:interests' => "Interessi", 
			'profile:contactemail' => "Indirizzo email",
			'profile:phone' => "Telefono",
			'profile:mobile' => "Cellulare",
			'profile:website' => "Sito Web",

			'profile:river:update' => "%s ha aggiornato il suo profilo",
			'profile:river:iconupdate' => "%s ha aggiornato l'icona sul suo profilo",
	
		/**
		 * Profile status messages   ###Messaggi sullo stato del profilo###
		 */
	
			'profile:saved' => "Il tuo profilo è stato salvato con successo.",
			'profile:icon:uploaded' => "La foto sul tuo profilo è stata inviata con successo.",
	
		/**
		 * Profile error messages   ###Messaggio di errore sul profilo###
		 */
	
			'profile:noaccess' => "Non hai il permesso di modificare questo profilo.",
			'profile:notfound' => "Scusaci; non possiamo trovare il profilo specificato.",
			'profile:cantedit' => "Scusaci; non hai il permesso di modificare questo profilo.",
			'profile:icon:notfound' => "Scusaci; c'è un problema nell'inviare la tua foto sul profilo.",
	
		/**
		 * Friends   ###Amici###
		 */
	
			'friends' => "Amici",
			'friends:yours' => "Tuoi amici",
			'friends:owned' => "Amici di %s",
			'friend:add' => "Aggiungi amico",
			'friend:remove' => "Rimuovi amico",
	
			'friends:add:successful' => "Hai aggiunto con successo %s come un amico.",
			'friends:add:failure' => "Non possiamo aggiungere %s come un amico. Per favore riprova.",
	
			'friends:remove:successful' => "Hai rimosso con successo %s dai tuoi amici.",
			'friends:remove:failure' => "Non possiamo rimuovere %s dai tuoi amici. Per favore riprova.",
	
			'friends:none' => "Questo utente non ha ancora aggiunto nessuno come amico.",
			'friends:none:you' => "Non hai aggiunto nessuno come amico! Inizia a cercare in base ai tuoi interessi gli amici da seguire.",
	
			'friends:none:found' => "Nessun amico è stato trovato.",
	
			'friends:of:none' => "Nessuno ancora ha aggiunto questo utente come amico.",
			'friends:of:none:you' => "Nessuno ti ha ancora aggiunto come amico. Inizia aggiungendo contenuti e a riempire il tuo profilo per permettere alle persone di preferirti!",
	
			'friends:of' => "Amici di",
			'friends:of:owned' => "Persone che hanno fatto di %s un amico",

			 'friends:num_display' => "Numero di amici da visualizzare",
			 'friends:icon_size' => "Dimensione icona",
			 'friends:tiny' => "minuscola",
			 'friends:small' => "piccola",
			 'friends' => "Amici",
			 'friends:of' => "Amici di",
			 'friends:collections' => "Collezione di amici",
			 'friends:collections:add' => "Nuova collezione di amici",
			 'friends:addfriends' => "Aggiungi amici",
			 'friends:collectionname' => "Collezione di nomi",
			 'friends:collectionfriends' => "Amici nella collezione",
			 'friends:collectionedit' => "Modifica questa collezione",
			 'friends:nocollections' => "Non hai ancora nessuna collezione.",
			 'friends:collectiondeleted' => "La tua collezione è stata cancellata.",
			 'friends:collectiondeletefailed' => "Non siamo abilitati a cancellare la collezione. We were unable to delete the collection. Nemmeno tu hai il permesso, o qualche altro problema è avvenuto.",
			 'friends:collectionadded' => "La tua collezione è stata creata con successo.",
			 'friends:nocollectionname' => "Devi dare alla tua collezione un nome prima di poterla salvare.",
		
	        'friends:river:created' => "%s ha aggiunto il widget Amici.",
	        'friends:river:updated' => "%s ha aggiornato il suo widget Amici.",
	        'friends:river:delete' => "%s ha rimosso il suo widget Amici.",
	        'friends:river:add' => "%s ha aggiunto qualcuno come amico.",
	
		/**
		 * Feeds   ###Feeds###
		 */
			'feed:rss' => 'Sottoscriviti al feed',
			'feed:odd' => 'Sincronizza con OpenDD',
	
		/**
		 * River   ###River###
		 */
			'river' => "River",			
			'river:relationship:friend' => 'è ora amico con',

		/**
		 * Plugins   ###Plugin###
		 */
			'plugins:settings:save:ok' => "I settaggi per il plugin %s sono stati salvati con successo.",
			'plugins:settings:save:fail' => "C'è stato un problema nel salvare i settaggi per il plugin %s.",
			'plugins:usersettings:save:ok' => "I settaggi-utente per il plugin %s sono stati salvati con successo.",
			'plugins:usersettings:save:fail' => "C'è stato un problema nel salvare i settaggi-utente per il plugin %s.",
			
		/**
		 * Notifications   ###Notifiche###
		 */
			'notifications:usersettings' => "Configurazione Notifiche",
			'notifications:methods' => "Per favore specifica quale metodo vuoi permettere.",
	
			'notifications:usersettings:save:ok' => "La configurazione-Notifiche è stata salvata con successo.",
			'notifications:usersettings:save:fail' => "C'è stato un problema nel salvare la tua configurazione-Notifiche.",
		/**
		 * Search   ###Ricerca###
		 */
	
			'search' => "Ricerca",
			'searchtitle' => "Ricerca: %s",
			'users:searchtitle' => "Ricercando per utenti: %s",
			'advancedsearchtitle' => "%s con risultati trovati %s",
			'notfound' => "Nessun risultato trovato.",
			'next' => "Avanti",
			'previous' => "Indietro",
	
			'viewtype:change' => "Cambia il tipo di lista",
			'viewtype:list' => "Vedi lista",
			'viewtype:gallery' => "Galleria",
	
			'tag:search:startblurb' => "Argomenti con tags trovati '%s':",

			'user:search:startblurb' => "Utenti trovati '%s':",
			'user:search:finishblurb' => "Vedi altri, clicca qui.",
	
		/**
		 * Account  ###Account###
		 */
	
			'account' => "Account",
			'settings' => "Settaggi",
            'tools' => "Tools",
            'tools:yours' => "I tuoi Tools",
	
			'register' => "Registrati",
			'registerok' => "Hai registrato con successo %s. Per attivare il tuo account,per favore conferma il tuo indirizzo email cliccando sul link che ti abbia inviato.",
			'registerbad' => "La tua registrazione non è avvenuta con successo. Il tuo nome-utente può essere già in uso, la tua password può non essere valida, o il tuo nome-utente o password possono essere troppo brevi.",
			'registerdisabled' => "La registrazione è stata disabilitata dall'amministratore del sistema.",
	
			'registration:notemail' => 'Un Indirizzo email fornito non sembra essere un valido indirizzo email.',
			'registration:userexists' => 'Quel nome-utente già esiste',
			'registration:usernametooshort' => 'Il tuo nome-utente deve avere una lunghezza di minimo 4 caratteri.',
			'registration:passwordtooshort' => 'La password deve avere una lunghezza di minimo 6 caratteri.',
			'registration:dupeemail' => 'Questo indirizzo email è già stato registrato.',
			'registration:invalidchars' => 'Scusaci, il tuo indirizzo email contiene caratteri non validi.',
			'registration:emailnotvalid' => 'Scusaci,un indirizzo email che hai inserito non è valido su questo sistema',
			'registration:passwordnotvalid' => 'Scusaci, la password che hai inserito non è valida su questo sistema',
			'registration:usernamenotvalid' => 'Scusaci, il nome-utente che hai inserito non è valido su questo sistema',
	
			'adduser' => "Aggiugi utente",
			'adduser:ok' => "Hai aggiunto con successo un nuovo utente.",
			'adduser:bad' => "Il nuovo utente non può essere creato.",
			
			'item:object:reported_content' => "Riporta argomento",
	
			'user:set:name' => "Configurazione del Nome-Account",
			'user:name:label' => "Il tuo nome",
			'user:name:success' => "Hai cambiato con successo il tuo nome su questo sistema.",
			'user:name:fail' => "Non puoi cambiare il tuo nome su questo sistema.",
	
			'user:set:password' => "Password-Account",
			'user:password:label' => "La tua nuova password",
			'user:password2:label' => "La tua nuova password di nuovo",
			'user:password:success' => "Password cambiata",
			'user:password:fail' => "Non puoi cambiare la password su questo sistema.",
			'user:password:fail:notsame' => "Le due passwords non sono identiche!",
			'user:password:fail:tooshort' => "La Password è troppo breve!",
	
			'user:set:language' => "Configurazione Lingua",
			'user:language:label' => "La tua lingua",
			'user:language:success' => "La configurazione della tua lingua è stata aggiornata.",
			'user:language:fail' => "La configurazione della tua lingua non può essere salvata.",
	
			'user:username:notfound' => 'Nome-utente %s non trovato.',
	
			'user:password:lost' => 'Password persa',
			'user:password:resetreq:success' => 'Richiesta nuova password avvenuta con successo, email inviata',
			'user:password:resetreq:fail' => 'Non puoi richiedere una nuova password.',
	
			'user:password:text' => 'Per generare una nuova password, inserisci il tuo nome-utente qui sotto. Ti manderemo per email un indirizzo di una pagina di verifica univoca, clicca sul link nel corpo del messaggio e una nuova password ti sarà inviata.',
	
		/**
		 * Administration   ###Amministrazione###
		 */

			'admin:configuration:success' => "I tuoi settaggi sono stati salvati.",
			'admin:configuration:fail' => "I tuoi settaggi non possono essere salvati.",
	
			'admin' => "Amministrazione",
			'admin:description' => "Il pannello amministrativo ti permette di controllare tutti gli aspetti del sistema, dall'amministrazione dell'utente a come far funzionare i plugin. Scegli un'opzione qui sotto per iniziare.",
			
			'admin:user' => "Amministrazione Utante",
			'admin:user:description' => "Questo pannello di amministrazione ti permette di controllare i settaggi dell'utente per il tuo sito. Scegli un'opzione qui sotto per iniziare.",
			'admin:user:adduser:label' => "Clicca qui per aggiungere un nuovo utente...",
			'admin:user:opt:linktext' => "Configura gli utenti...",
			'admin:user:opt:description' => "Configura gli utenti e l'informazione sull'account. ",
			
			'admin:site' => "Amministrazione sito",
			'admin:site:description' => "Questo pannello amministrativo ti permette di controllare i settaggi globali per il tuo sito. Scegli un'opzione qui sotto per iniziare.",
			'admin:site:opt:linktext' => "Configura il sito...",
			'admin:site:opt:description' => "Configura i settaggi tecnici e non-tecnici del sito. ",
			
			'admin:plugins' => "Amministrazione Tool",
			'admin:plugins:description' => "Questo pannello di ammministrazione ti permette di controllare e configurara i Tools installati sul tuo sito.",
			'admin:plugins:opt:linktext' => "Configura i Tools...",
			'admin:plugins:opt:description' => "Configura i Tools installati sul sito. ",
			'admin:plugins:label:author' => "Autore",
			'admin:plugins:label:copyright' => "Copyright",
			'admin:plugins:label:licence' => "Licenza",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "Plugin %s è stato disabilitato con successo.",
			'admin:plugins:disable:no' => "Plugin %s non può essere disabilitato.",
			'admin:plugins:enable:yes' => "Plugin %s è stato abilitato con successo.",
			'admin:plugins:enable:no' => "Plugin %s non può essere abilitato.",
	
			'admin:statistics' => "Statistiche",
			'admin:statistics:description' => "Questa è una visione d'insieme delle statistiche sul tuo sito. Se hai bisogno di statistiche più dettagliate , è disponibile un servizio amministrativo professionale.",
			'admin:statistics:opt:description' => "Visualizza i dati statistici riguardo gli utenti e gli oggetti sul tuo sito.",
			'admin:statistics:opt:linktext' => "Visualizza le statistiche...",
			'admin:statistics:label:basic' => "Statistiche di base del sito",
			'admin:statistics:label:numentities' => "Entità sul sito",
			'admin:statistics:label:numusers' => "Numero di utenti",
			'admin:statistics:label:numonline' => "Numbero di utenti online",
			'admin:statistics:label:onlineusers' => "Utenti online ora",
			'admin:statistics:label:version' => "Elgg version",
			'admin:statistics:label:version:release' => "Release",
			'admin:statistics:label:version:version' => "Versione",
	
			'admin:user:label:search' => "Cerca utenti:",
			'admin:user:label:seachbutton' => "Ricerca", 
	
			'admin:user:ban:no' => "Non puoi bandire l'utente",
			'admin:user:ban:yes' => "Utente esiliato.",
			'admin:user:unban:no' => "Non puoi riattivare l'utente",
			'admin:user:unban:yes' => "Utente riammesso.",
			'admin:user:delete:no' => "Non puoi cancellare l'utente",
			'admin:user:delete:yes' => "Utente cancellato.",
	
			'admin:user:resetpassword:yes' => "Password cambiata, l'utente è stato avvertito.",
			'admin:user:resetpassword:no' => "La Password non può essere cambiata.",
	
			'admin:user:makeadmin:yes' => "L'utente è ora un amministratore.",
			'admin:user:makeadmin:no' => "Non possiamo nominare questo utente come amministratore.",
			
		/**
		 * User settings   ### Settaggi dell' utente ###
		 */
			'usersettings:description' => "Il pannello dei settaggi utente ti permette di controllare tutti i tuoi settaggi personali, dall'amministrazione dell'utente a come i plugins si comportano. Scegli un'opzione qui sotto per iniziare.",
	
			'usersettings:statistics' => "Le tue statistiche",
			'usersettings:statistics:opt:description' => "Visualizza i dati statistici riguardo gli utenti e gli oggetti sul tuo sito.",
			'usersettings:statistics:opt:linktext' => "Statistiche dell'account",
	
			'usersettings:user' => "I tuoi settaggi",
			'usersettings:user:opt:description' => "Questo ti permette di controllare i settaggi dell'utente.",
			'usersettings:user:opt:linktext' => "Cambia i tuoi settaggi.",
	
			'usersettings:plugins' => "Strumenti",
			'usersettings:plugins:opt:description' => "Configura i settaggi per i tuoi strumenti attivi.",
			'usersettings:plugins:opt:linktext' => "Configura i tuoi strumenti...",
	
			'usersettings:plugins:description' => "Questo pannello ti permette di controllare e configurare i settaggi personali per gli strumenti installati dal tuo amministratore di sistema.",
			'usersettings:statistics:label:numentities' => "Le tue ???entità???",
	
			'usersettings:statistics:yourdetails' => "I tuoi dettagli",
			'usersettings:statistics:label:name' => "Nome completo",
			'usersettings:statistics:label:email' => "Email",
			'usersettings:statistics:label:membersince' => "Membro dal ",
			'usersettings:statistics:label:lastlogin' => "Ultima visita",
	
			
			
	
		/**
		 * Generic action words   ### Parole di azione generica ###
		 */
	
			'save' => "Salva",
			'cancel' => "Cancella",
			'saving' => "Salvataggio ...",
			'update' => "Aggiornamento",
			'edit' => "Modifica",
			'delete' => "Cancella",
			'load' => "Carica",
			'upload' => "Aggiorna",
			'ban' => "Bandisci",
			'unban' => "Riabilita",
			'enable' => "Attiva",
			'disable' => "Disattiva",
			'request' => "Richiedi",
			'complete' => "Completo",
	
			'invite' => "Invita",
	
			'resetpassword' => "Modifica password",
			'makeadmin' => "Nominalo amministratore",
	
			'option:yes' => "Si",
			'option:no' => "No",
	
			'unknown' => 'Sconosciuto',
	
			'active' => 'Attivo',
			'total' => 'Totale',
	
			'learnmore' => "Clicca qui per imparare di più.",
	
			'content' => "contenuto",
			'content:latest' => 'Attività recente',
			'content:latest:blurb' => 'In alternativa,clicca qui per vedere il contunuto recente sul sito.',
	
		/**
		 * Generic data words  ### Parole di valore generico ###
		 */
	
			'title' => "Titolo",
			'description' => "Descrizione",
			'tags' => "Tags",
			'spotlight' => "Riflettore",
			'all' => "Tutto",
	
			'by' => 'da',
	
			'annotations' => "Annotazioni",
			'relationships' => "Relazioni",
			'metadata' => "Metadata",
	
		/**
		 * Input / output strings  ### Input / output stringhe###
		 */

			'deleteconfirm' => "Sei sicuro di voler cancellare questo articolo?",
			'fileexists' => "Un file è già stato inviato. Per rimpiazzarlo,selezionalo qui sotto:",
	
		/**
		 * Import / export  ### Importa/Esporta###
		 */
			'importsuccess' => "L'importazione del valore è stata fatta con successo",
			'importfail' => "L'importazione OpenDD del valore è fallita.",
	
		/**
		 * Time   ### Tempo ###
		 */
	
			'friendlytime:justnow' => "proprio ora",
			'friendlytime:minutes' => "%s minuti fa",
			'friendlytime:minutes:singular' => "un minuto fa",
			'friendlytime:hours' => "%s ore fa",
			'friendlytime:hours:singular' => "un'ora fa",
			'friendlytime:days' => "%s giorni fa",
			'friendlytime:days:singular' => "ieri",
	
		/**
		 * Installation and system settings   ### Installazione e configurazioni del sistema ###
		 */
	
			'installation:error:htaccess' => "Elgg richiede un file chiamato .htaccess per essere collocato nella cartella root della sua installazione. Abbiamo provato a creala per te, ma Elgg non ha il permesso per scrivere in quella cartella. 

La creazione di questo file è facile. Copia i contenuti del riquadro sottostante in un programma di testo e salvalo come .htaccess

",
			'installation:error:settings' => "Elgg non può trovare i propri file di configurazione. Molte settaggi di Elgg saranno configurati per te, ma è necessario che tu ci fornisca i dettagli del tuo database.Per fare questo: 

1. Rinomina engine/settings.example.php a settings.php nella tua installazione di Elgg.

2. Aprilo in un programma di testo e inserisci i dettagli del tuo database. Se non li conosci, chiedi aiuto al tuo amministratore di sistema o al supporto tecnico. 

In alternativa,puoi inserire la configurazione del tuo database qui sotto e noi proveremo e faremo questo per te...",
	
			'installation:error:configuration' => "Una volta corretto ogni richiesta di configurazione, premi riaggiorna per provare di nuovo.",
	
			'installation' => "Installazione",
			'installation:success' => "Il database di Elgg è stato installato con successo.",
			'installation:configuration:success' => "I settaggi della tua configurazione iniziale sono stati salvati. Ora registra il tuo utente iniziale; questo sarà il tuo primo amministratore di sistema.",
	
			'installation:settings' => "Settaggi di sistema",
			'installation:settings:description' => "Ora che il database di Elgg è stato installato con successo, devi inserire un alcune informazioni per avere il tuo sito pienamente funzionante. Noi proviamo a supporre dove possiamo,ma <b>tu dovresti controllare questi dettagli.</b>",
	
			'installation:settings:dbwizard:prompt' => "Inserisci i settaggi del tuo database qui sotto e schiaccia salva:",
			'installation:settings:dbwizard:label:user' => "Database user",
			'installation:settings:dbwizard:label:pass' => "Database password",
			'installation:settings:dbwizard:label:dbname' => "Elgg database",
			'installation:settings:dbwizard:label:host' => "Database hostname (di solito 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Database table prefix (di solito 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "Non siamo abilitati a salvare il nuovo settings.php. Per favore salva il seguente file come engine/settings.php usando un programma di testo.",
	
			'installation:sitename' => "Il nome del tuo sito (es. \"Il mio sito di collaborazione sociale\"):",
			'installation:sitedescription' => "Breve descrizione del tuo sito(opzionale)",
			'installation:wwwroot' => "L'URL del sito, seguito da uno slash:",
			'installation:path' => "Il percorso completo alla root del tuo sito sul tuo disco,seguito da uno slash:",
			'installation:dataroot' => "Il percorso completo alla cartella dove hai inviato i files sarà memorizzato,seguito da uno slash:",
			'installation:dataroot:warning' => "Devi creare questa cartella manualmente. Potrebbe risiedere in una cartella differente rispetto alla tua installazione di Elgg.",
			'installation:language' => "La lingua principale per il tuo sito:",
			'installation:debug' => "La modalità debug fornisce informazioni extra che possono essere utilizzate per diagnosticare gli errori, però può rallentare il tuo sistema e per questo potrebbe essere utilizzata solo se avrai problemi:",
			'installation:debug:label' => "Attiva la modalità debug",
			'installation:usage' => "Questa opzione abilita Elgg a inviare l'uso delle statistiche anonime a Curverider.",
			'installation:usage:label' => "Invia l'uso di statistiche anonime",
			'installation:view' => "Inserisci la visualizzazione di base che sarà usata per il tuo sito o lascialo vuoto per la visualizzazione di default (se sei nel dubbio, lascialo in default):",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Benvenuto %s",
			'welcome_message' => "Benvenuto a questa installazione di Elgg.",
	
		/**
		 * Emails   ### Posta Elettronica ###
		 */
			'email:settings' => "Configurazioni delle Email",
			'email:address:label' => "Il tuo indirizzo email ",
			
			'email:save:success' => "Il nuovo indirizzo email è salvato, verifica richiesta.",
			'email:save:fail' => "Il tuo nuovo indirizzo email non può essere salvato.",
	
			'email:confirm:success' => "Hai confermato il tuo indirizzo email!",
			'email:confirm:fail' => "Il tuo indirizzo email non può essere verificato...",
	
			'friend:newfriend:subject' => "%s ha fatto di te un amico!",
			'friend:newfriend:body' => "%s ha fatto di te un amico!

Per vedere il proprio profilo,clicca qui:

	%s

Non puoi replicare a questa email.",
	
	
			'email:validate:subject' => "%s per favore conferma il tuo indirizzo email!",
			'email:validate:body' => "Ciao %s,

Per favore conferma il tuo indirizzo email cliccando sul link sottostante:

%s
",
			'email:validate:success:subject' => "Email validata %s!",
			'email:validate:success:body' => "Ciao %s,
			
Congratulazioni,hai validato con successo il tuo indirizzo email.",
	
	
			'email:resetpassword:subject' => "Password ricambiata!",
			'email:resetpassword:body' => "Ciao %s,
			
La tua password è stata ricambiata: %s",
	
	
			'email:resetreq:subject' => "Richiesta per una nuova password.",
			'email:resetreq:body' => "Ciao %s,
			
Qualcuno (dall'indirizzo IP %s) ha richiesto una nuova password per il proprio account.

Se questa è stata richiesta da te,clicca sul link sottostante,altrimenti ignora questa email.

%s
",

	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"Valore immesso mancante",
	
		/**
		 * Comments   ###  Commenti
		 */
	
			'comments:count' => "%s commenti",
			'generic_comments:add' => "Aggiungi un commento",
			'generic_comments:text' => "Commento",
			'generic_comment:posted' => "Il tuo commento è stato inviato con successo.",
			'generic_comment:deleted' => "Il tuo commento è stato cancellato con successo.",
			'generic_comment:blank' => "Spiacente; è necessario che effettivamente venga scritto qualcosa nel tuo commento prima di poter essere salvato.",
			'generic_comment:notfound' => "Spiacente; Non è possibile trovare l'argomento specificato.",
			'generic_comment:notdeleted' => "Spiacente; Non è possibile cancellare questo commento.",
			'generic_comment:failure' => "E' accaduto un errore inaspettato nell'aggiungere il tuo commento. Per favore riprova.",
	
			'generic_comment:email:subject' => 'Hai un nuovo commento!',
			'generic_comment:email:body' => "Hai un nuovo commento sul tuo argomento \"%s\" da %s. Dice:

			
%s


Per rispondere o visualizzare l'argomento originale,clicca qui:

	%s

Per visualizzare il profilo di %s, clicca qui:

	%s

Non puoi rispondere a questa email.",
	
		/**
		 * Entities   ### ???? entità o valore ????
		 */
			'entity:default:strapline' => 'Creati %s da %s',
			'entity:default:missingsupport:popup' => 'Questa entità non può essere visualizzata correttamente. Questo può accadere perchè è necessario il supporto fornito da un plugin che non è più installato.',
	
			'entity:delete:success' => 'Entità %s è stata cancellata',
			'entity:delete:fail' => 'Entità %s non è stata cancellata',
	
	
		/**
		 * Action gatekeeper   ###  ??? Azione portiere/custode???
		 */
			'actiongatekeeper:missingfields' => 'Il Modulo è mancante di __???nota/segno/simbolo/indice/??? o __ts campi.',
			'actiongatekeeper:tokeninvalid' => 'La ???nota/segno/simbolo/indice/??? fornita dal modulo non corrisponde a quella generata dal server.',
			'actiongatekeeper:timeerror' => 'Il Modulo è scaduto,per favore riaggiorna e prova di nuovo.',
			'actiongatekeeper:pluginprevents' => 'Una estensione ha impedito a questo Modulo di poter essere inviato.',
	
/*****************************************************************************************************/	
	    /**
		 * Custom  ###Personalizzazione###
		 * !!!Put here your custom words!!! ###!!!Inserisci qui le tue parole personalizzate!!!###
		 */
		
/*****************************************************************************************************/			
		
		
		/**
		 * Languages according to ISO 639-1 #### Lingue in base all' ISO 639-1 		 
         !!!IMPORTANTE: sono state inserite solo le lingue a cui segue "//" !!!
Azero 		    az 	
Armeno 		    hy 	
Basco 		    eu 	
Bielorusso 		be 	
Bulgaro 		bg 	
Catalano 		ca 	
Ceco 		    cs 	
Coreano 		ko 	
Corso 		    co 	
Croato 		    hr 	
Danese 		    da 	
Estone 		    et 	
Faroese 		fo 	
Gaelico 		gd 	
Galiziano 		gl 	
Gallese 		cy 	
Hindi 		    hi 	
Islandese 		is 	
Kazako 		    kk 	
Latino 		    la 	
Lettone 		lv 	
Lituano 		lt 	
Macedone 		mk 	
Malgascio 		mg 	
Maltese 		mt 	
Moldavo 		mo 	
Mongolo 		mn 	
Osseto 		    os 	
Pashtun 		ps 	
Persiano 		fa 		
Rumeno 		    ro 	
Sanscrito 		sa 	
Serbo 		    sr 	
Singalese 		si 	
Slovacco 		sk 		
		 */
			"aa" => "Afar",
			"ab" => "Abkhazian",
			"af" => "Afrikaans",
			"am" => "Amharic",
			"ar" => "Arabo",//
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
			"de" => "Tedesco",//
			"dz" => "Bhutani",
			"el" => "Greco moderno",//
			"en" => "Inglese",//
			"eo" => "Esperanto",
			"es" => "Spagnolo",//
			"et" => "Estonian",
			"eu" => "Basque",
			"fa" => "Persian",
			"fi" => "Finlandese",//
			"fj" => "Fiji",
			"fo" => "Faeroese",
			"fr" => "Francese",//
			"fy" => "Frisian",
			"ga" => "Irish",
			"gd" => "Scots / Gaelic",
			"gl" => "Galician",
			"gn" => "Guarani",
			"gu" => "Gujarati",
			"he" => "Ebraico",//
			"ha" => "Hausa",
			"hi" => "Hindi",
			"hr" => "Croatian",
			"hu" => "Ungherese",//
			"hy" => "Armenian",
			"ia" => "Interlingua",
			"id" => "Indonesiano",//
			"ie" => "Interlingue",
			"ik" => "Inupiak",
			"is" => "Icelandic",
			"it" => "Italiano",//
			"iu" => "Inuktitut",
			"iw" => "Hebrew (obsolete)",
			"ja" => "Giapponese",//
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
			"nl" => "Olandese",//
			"no" => "Norvegese",//
			"oc" => "Provenzale / Occitano",//
			"om" => "(Afan) Oromo",
			"or" => "Oriya",
			"pa" => "Punjabi",
			"pl" => "Polacco",//
			"ps" => "Pashto / Pushto",
			"pt" => "Portoghese",//
			"qu" => "Quechua",
			"rm" => "Rhaeto-Romance",
			"rn" => "Kirundi",
			"ro" => "Romanian",
			"ru" => "Russo",//
			"rw" => "Kinyarwanda",
			"sa" => "Sanskrit",
			"sd" => "Sindhi",
			"sg" => "Sangro",
			"sh" => "Serbo-Croatian",
			"si" => "Singhalese",
			"sk" => "Slovak",
			"sl" => "Sloveno",//
			"sm" => "Samoan",
			"sn" => "Shona",
			"so" => "Somalo ",//
			"sq" => "Albanese",//
			"sr" => "Serbian",
			"ss" => "Siswati",
			"st" => "Sesotho",
			"su" => "Sundanese",
			"sv" => "Svedese ",//
			"sw" => "Swahili",//
			"ta" => "Tamil",//
			"te" => "Tegulu",
			"tg" => "Tajik",
			"th" => "Thailandese",//
			"ti" => "Tigrinya",
			"tk" => "Turkmeno",//
			"tl" => "Tagalog",
			"tn" => "Setswana",
			"to" => "Tonga",
			"tr" => "Turco",//
			"ts" => "Tsonga",
			"tt" => "Tatar",
			"tw" => "Twi",
			"ug" => "Uigur",
			"uk" => "Ucraino",//
			"ur" => "Urdu",//
			"uz" => "Uzbeko ",//
			"vi" => "Vietnamita",//
			"vo" => "Volapuk",
			"wo" => "Wolof",
			"xh" => "Xhosa",
			"yi" => "Yiddish", //
			"yo" => "Yoruba",
			"za" => "Zuang",
			"zh" => "Cinese",//
			"zu" => "Zulu",
	);
	
	add_translation("it",$italian);

?>
