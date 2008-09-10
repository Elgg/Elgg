<?php

	$italian = array(

		/**
		 * Sites
		 */
	
			'item:site' => 'Siti',
	
		/**
		 * Sessions
		 */
			
			'login' => "Log in",
			'loginok' => "Hai effettuato l'accesso.",
			'loginerror' => "We couldn't log you in. This may be because you haven't validated your account yet, or the details you supplied were incorrect. Make sure your details are correct and please try again.",
	
			'logout' => "Log out",
			'logoutok' => "Non hai effettuato l'accesso.",
			'logouterror' => "We couldn't log you out. Please try again.",
	
		/**
		 * Errors
		 */
			'exception:title' => "Benvenuto su Elgg.",
	
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
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d non è un valido %s",
			
			'PluginException:MisconfiguredPlugin' => "%s è un plugin configurato male.",
			
			'InvalidParameterException:NonElggUser' => "Passing a non-ElggUser to an ElggUser constructor!",
			
			'InvalidParameterException:NonElggSite' => "Passing a non-ElggSite to an ElggSite constructor!",
			
			'InvalidParameterException:NonElggGroup' => "Passing a non-ElggGroup to an ElggGroup constructor!",
	
			'IOException:UnableToSaveNew' => "Unable to save new %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
			'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",
			
			'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
			'IOException:NotDirectory' => "%s non è una cartella.",
			
			'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
			'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
			'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",
			
			'ClassException:ClassnameNotClass' => "%s non è un %s.",
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
			'InstallationException:DatarootBlank' => "You have not specified a data directory.",
	
		/**
		 * User details
		 */

			'name' => "Visualizza nome",
			'email' => "Indirizzo email",
			'username' => "Username",
			'password' => "Password",
			'passwordagain' => "Password (ancora per verifica)",
			'admin_option' => "Fai diventare questo utente amministratore?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Privato",
			'ACCESS_LOGGED_IN' => "Utenti loggati",
			'ACCESS_PUBLIC' => "Pubblico",
			'PRIVATE' => "Privato",
			'LOGGED_IN' => "Utenti loggati",
			'PUBLIC' => "Pubblico",
			'access' => "Accesso",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "Dashboard",
            'dashboard:configure' => "Edit page",
			'dashboard:nowidgets' => "Your dashboard is your gateway into the site. Click 'Edit page' to add widgets to keep track of content and your life within the system.",

			'widgets:add' => 'Aggiungi widget alla tua pagina',
			'widgets:add:description' => "Choose the features you want to add to your page by dragging them from the <b>Widget gallery</b> on the right, to any of the three widget areas below, and position them where you would like them to appear.

To remove a widget drag it back to the <b>Widget gallery</b>.",
			'widgets:position:fixed' => '(Posizione fissa nella pagina)',
	
			'widgets' => "Widgets",
			'widget' => "Widget",
			'item:object:widget' => "Widgets",
			'layout:customise' => "Personalizza il layout",
			'widgets:gallery' => "Galleria widget",
			'widgets:leftcolumn' => "Left widgets",
			'widgets:fixed' => "Posizione fissa",
			'widgets:middlecolumn' => "Middle widgets",
			'widgets:rightcolumn' => "Right widgets",
			'widgets:profilebox' => "Profile box",
			'widgets:panel:save:success' => "Your widgets were successfully saved.",
			'widgets:panel:save:failure' => "There was a problem saving your widgets. Please try again.",
			'widgets:save:success' => "Il widget è stato salvato con successo.",
			'widgets:save:failure' => "Non sono riuscito a salvare il tuo widget. Prova ancora.",
			
	
		/**
		 * Groups
		 */
	
			'group' => "Gruppo", 
			'item:group' => "Gruppi",
	
		/**
		 * Profile
		 */
	
			'profile' => "Profilo",
			'user' => "Utente",
			'item:user' => "Utenti",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "Il tuo profilo",
			'profile:user' => "Profilo di %s",
	
			'profile:edit' => "Modifica il profilo",
			'profile:editicon' => "Carica una nuova immagine per il porfilo",
			'profile:profilepictureinstructions' => "The profile picture is the image that's displayed on your profile page. <br /> You can change it as often as you'd like. (File formats accepted: GIF, JPG or PNG)",
			'profile:icon' => "Immagine profilo",
			'profile:createicon' => "Create your avatar",
			'profile:currentavatar' => "Current avatar",
			'profile:createicon:header' => "Immagine profilo",
			'profile:profilepicturecroppingtool' => "Profile picture cropping tool",
			'profile:createicon:instructions' => "Click and drag a square below to match how you want your picture cropped.  A preview of your cropped picture will appear in the box on the right.  When you are happy with the preview, click 'Create your avatar'. This cropped image will be used throughout the site as your avatar. ",
	
			'profile:editdetails' => "Edit details",
			'profile:editicon' => "Edit profile icon",
	
			'profile:aboutme' => "About me", 
			'profile:description' => "About me",
			'profile:briefdescription' => "Brief description",
			'profile:location' => "Indirizzo",
			'profile:skills' => "Conoscenze",  
			'profile:interests' => "Interessi", 
			'profile:contactemail' => "Email",
			'profile:phone' => "Telefono",
			'profile:mobile' => "Cellulare",
			'profile:website' => "Sito Web",

			'profile:river:update' => "%s ha aggiornato il suo profilo",
			'profile:river:iconupdate' => "%s ha aggiornato l'icona del profilo",
	
		/**
		 * Profile status messages
		 */
	
			'profile:saved' => "Il tuo profilo è stato salvato con successo.",
			'profile:icon:uploaded' => "L'immagine del tuo profilo è stata caricata con successo.",
	
		/**
		 * Profile error messages
		 */
	
			'profile:noaccess' => "Non hai i permessi per modificare questo profilo.",
			'profile:notfound' => "Sorry; we could not find the specified profile.",
			'profile:cantedit' => "Sorry; you do not have permission to edit this profile.",
			'profile:icon:notfound' => "Sorry; there was a problem uploading your profile picture.",
	
		/**
		 * Friends
		 */
	
			'friends' => "Amici",
			'friends:yours' => "I tuoi amici",
			'friends:owned' => "Amici di %s",
			'friend:add' => "Aggiungi amico",
			'friend:remove' => "Rimuovi amico",
	
			'friends:add:successful' => "You have successfully added %s as a friend.",
			'friends:add:failure' => "We couldn't add %s as a friend. Please try again.",
	
			'friends:remove:successful' => "You have successfully removed %s from your friends.",
			'friends:remove:failure' => "We couldn't remove %s from your friends. Please try again.",
	
			'friends:none' => "This user hasn't added anyone as a friend yet.",
			'friends:none:you' => "You haven't added anyone as a friend! Search for your interests to begin finding people to follow.",
	
			'friends:none:found' => "No friends were found.",
	
			'friends:of:none' => "Nobody has added this user as a friend yet.",
			'friends:of:none:you' => "Nobody has added you as a friend yet. Start adding content and fill in your profile to let people find you!",
	
			'friends:of' => "Friends of",
			'friends:of:owned' => "People who have made %s a friend",

			 'friends:num_display' => "Numbero di amini da visualizzare",
			 'friends:icon_size' => "Grandezza icona",
			 'friends:tiny' => "minuscola",
			 'friends:small' => "piccola",
			 'friends' => "Amici",
			 'friends:of' => "Amici di",
			 'friends:collections' => "Collections of friends",
			 'friends:collections:add' => "New friends collection",
			 'friends:addfriends' => "Aggiungi amici",
			 'friends:collectionname' => "Collection name",
			 'friends:collectionfriends' => "Friends in collection",
			 'friends:collectionedit' => "Edit this collection",
			 'friends:nocollections' => "You do not yet have any collections.",
			 'friends:collectiondeleted' => "Your collection has been deleted.",
			 'friends:collectiondeletefailed' => "We were unable to delete the collection. Either you don't have permission, or some other problem has occurred.",
			 'friends:collectionadded' => "Your collection was successfuly created",
			 'friends:nocollectionname' => "You need to give your collection a name before it can be created.",
		
	        'friends:river:created' => "%s added the friends widget.",
	        'friends:river:updated' => "%s updated their friends widget.",
	        'friends:river:delete' => "%s removed their friends widget.",
	        'friends:river:add' => "%s add someone as a friend.",
	
		/**
		 * Feeds
		 */
			'feed:rss' => 'Sottoscrivi al feed',
			'feed:odd' => 'Syndicate OpenDD',
	
		/**
		 * River
		 */
			'river' => "River",			
			'river:relationship:friend' => 'è amico di',

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
			'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
			'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
			'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
			
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "Notification settings",
			'notifications:methods' => "Please specify which methods you want to permit.",
	
			'notifications:usersettings:save:ok' => "Your notification settings were successfully saved.",
			'notifications:usersettings:save:fail' => "There was a problem saving your notification settings.",
		/**
		 * Search
		 */
	
			'search' => "Cerca",
			'searchtitle' => "Cerca: %s",
			'users:searchtitle' => "Searching for users: %s",
			'advancedsearchtitle' => "%s with results matching %s",
			'notfound' => "Nessun risultato trovato.",
			'next' => "Prossimo",
			'previous' => "Precedente",
	
			'viewtype:change' => "Change listing type",
			'viewtype:list' => "List view",
			'viewtype:gallery' => "Gallery",
	
			'tag:search:startblurb' => "Items with tags matching '%s':",

			'user:search:startblurb' => "Users matching '%s':",
			'user:search:finishblurb' => "To view more, click here.",
	
		/**
		 * Account
		 */
	
			'account' => "Account",
			'settings' => "Preferenze",
            'tools' => "Tools",
            'tools:yours' => "Your tools",
	
			'register' => "Register",
			'registerok' => "You have successfully registered for %s. To activate your account, please confirm your email address by clicking on the link we sent you.",
			'registerbad' => "Your registration was unsuccessful. The username may already exist, your passwords might not match, or your username or password may be too short.",
			'registerdisabled' => "Registration has been disabled by the system administrator",
	
			'registration:notemail' => 'The email address you provided does not appear to be a valid email address.',
			'registration:userexists' => 'That username already exists',
			'registration:usernametooshort' => 'Your username must be a minimum of 4 characters long.',
			'registration:passwordtooshort' => 'The password must be a minimum of 6 characters long.',
			'registration:dupeemail' => 'This email address has already been registered.',
			'registration:invalidchars' => 'Sorry, your email address contains invalid characters.',
			'registration:emailnotvalid' => 'Sorry, the email address you entered is invalid on this system',
			'registration:passwordnotvalid' => 'Sorry, the password you entered is invalid on this system',
			'registration:usernamenotvalid' => 'Sorry, the username you entered is invalid on this system',
	
			'adduser' => "Aggiungi Utente",
			'adduser:ok' => "You have successfully added a new user.",
			'adduser:bad' => "The new user could not be created.",
			
			'item:object:reported_content' => "Reported items",
	
			'user:set:name' => "Account name settings",
			'user:name:label' => "Il tuo nome",
			'user:name:success' => "Successfully changed your name on the system.",
			'user:name:fail' => "Could not change your name on the system.",
	
			'user:set:password' => "Account password",
			'user:password:label' => "Your new password",
			'user:password2:label' => "Your new password again",
			'user:password:success' => "Password cambiata",
			'user:password:fail' => "Non puoi cambiare la password in questo sito.",
			'user:password:fail:notsame' => "Le due password non coincidono!",
			'user:password:fail:tooshort' => "La password è troppo corta!",
	
			'user:set:language' => "Preferenze lingua",
			'user:language:label' => "La tua lingua",
			'user:language:success' => "Your language settings have been updated.",
			'user:language:fail' => "Your language settings could not be saved.",
	
			'user:username:notfound' => 'Username %s non trovato.',
	
			'user:password:lost' => 'Password dimenticata',
			'user:password:resetreq:success' => 'Richiesta nuova password, email spedita',
			'user:password:resetreq:fail' => 'Non puoi richiedere una nuova password.',
	
			'user:password:text' => 'To generate a new password, enter your username below. We will send the address of a unique verification page to you via email click on the link in the body of the message and a new password will be sent to you.',
	
		/**
		 * Administration
		 */

			'admin:configuration:success' => "Your settings have been saved.",
			'admin:configuration:fail' => "Your settings could not be saved.",
	
			'admin' => "Administration",
			'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",
			
			'admin:user' => "User Administration",
			'admin:user:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
			'admin:user:adduser:label' => "Click here to add a new user...",
			'admin:user:opt:linktext' => "Configure users...",
			'admin:user:opt:description' => "Configure users and account information. ",
			
			'admin:site' => "Site Administration",
			'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
			'admin:site:opt:linktext' => "Configure site...",
			'admin:site:opt:description' => "Configure the site technical and non-technical settings. ",
			
			'admin:plugins' => "Tool Administration",
			'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
			'admin:plugins:opt:linktext' => "Configure tools...",
			'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
			'admin:plugins:label:author' => "Author",
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
	
		/**
		 * Languages according to ISO 639-1 #### Lingue in base all' ISO 639-1 		 
Albanese		sq 	
Arabo 		    ar 	
Azero 		    az 	
Armeno 		    hy 	
Basco 		    eu 	
Bielorusso 		be 	
Bulgaro 		bg 	
Catalano 		ca 	
Ceco 		    cs 	
Cinese 		    zh 	
Coreano 		ko 	
Corso 		    co 	
Croato 		    hr 	
Danese 		    da 	
Ebraico 		he 	
Estone 		    et 	
Faroese 		fo 	
Finlandese 		fi 	
Francese 		fr 	
Gaelico 		gd 	
Galiziano 		gl 	
Gallese 		cy 	
Giapponese 		ja 	
Greco moderno   el 	
Hindi 		    hi 	
Indonesiano     id 	
Inglese 		en 	
Islandese 		is 	
Italiano 		it 	
Kazako 		    kk 	
Latino 		    la 	
Lettone 		lv 	
Lituano 		lt 	
Macedone 		mk 	
Malgascio 		mg 	
Maltese 		mt 	
Moldavo 		mo 	
Mongolo 		mn 	
Norvegese 		no 	
Norvegese Bokmal 		nb 	
Norvegese Nynorsk 		nn 	
Olandese 		nl 	
Osseto 		    os 	
Pashtun 		ps 	
Persiano 		fa 	
Polacco 		pl 	
Portoghese 		pt 	
Provenzale / Occitano    oc 	
Rumeno 		    ro 	
Russo 		    ru 	
Sanscrito 		sa 	
Serbo 		    sr 	
Singalese 		si 	
Slovacco 		sk 	
Sloveno 		sl 	
Somalo 		    so 	
Spagnolo 		es 	
Svedese 		sv 	
Swahili 		sw 	
Tamil 		    ta 	
Tedesco 		de 	
Thailandese     th 	
Turco 		    tr 	
Turkmeno 		tk 	
Ucraino 		uk 	
Ungherese 		hu 	
Urdu 		    ur 	
Uzbeko 		    uz 	
Vietnamita 		vi 	
Yiddish 		yi 	
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
			"co" => "Corsican",
			"cs" => "Czech",
			"cy" => "Welsh",
			"da" => "Danish",
			"de" => "Tedesco",
			"dz" => "Bhutani",
			"el" => "Greco",
			"en" => "Inglese",
			"eo" => "Esperanto",
			"es" => "Spagnolo",
			"et" => "Estonian",
			"eu" => "Basque",
			"fa" => "Persian",
			"fi" => "Finnish",
			"fj" => "Fiji",
			"fo" => "Faeroese",
			"fr" => "Francese",
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
			"qu" => "Quechua",
			"rm" => "Rhaeto-Romance",
			"rn" => "Kirundi",
			"ro" => "Romanian",
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
			"zu" => "Zulu",
	);
	
	add_translation("it",$italian);

?>
