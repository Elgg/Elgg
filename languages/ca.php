<?php

return array( 
/**
 * Sites
 */
	 'item:site'  =>  "Llocs" ,


		
/**
 * Sessions
 */
		'login' => "Iniciar sessi&oacute;",
		'loginok' => "Has iniciat sessi&oacute;",
		'loginerror' => "Inici de sessi&oacute; incorrecte. Verifica les teves credencials i intenta-ho de nou",
		'login:empty' => "El nom d'usuari i contrasenya s&oacute;n requerits",
		'login:baduser' => "No s'ha pogut carregar el teu compte d'usuari",
		'auth:nopams' => "Error intern. No es troba un m&egrave;tode d'autenticaci&oacute; instal·lat",
		
		'logout' => "Tancar sessi&oacute;",
		'logoutok' => "S'ha tancat la sessi&oacute;",
		'logouterror' => "No s'ha pogut tancar la sessi&oacute;, si us plau intenta-ho de nou",
		
		'loggedinrequired' => "Has d'estar autenticat per poder veure aquesta p&agrave;gina",
		'adminrequired' => "Has de ser un administrador per poder veure aquesta p&agrave;gina",
		'membershiprequired' => "Has de ser membre del grup per poder veure aquesta p&agrave;gina",

	
	
	
/**
 * Errors
 */
	'exception:title' => "Error Fatal",
	
	'actionundefined' => "La acci&oacute; (%s) sol&middot;licitada no est&agrave; definida al sistema",
	'actionnotfound' => "El log d'accions per %s no s'ha trobat",
	'actionloggedout' => "Ho sentim, no es pot realitzar aquesta acci&oacute; sense identificar-se",
	'actionunauthorized' => "No tens els permisos necesaris per realitzar aquesta acci&oacute;",
	
	'InstallationException:SiteNotInstalled' => "No s'ha pogut procesar la sol&middot;licitud. El lloc "
	. " no es troba configurat o la base de dades es troba caiguda",
	'InstallationException:MissingLibrary' => "No s'ha pogut carregar %s",
	'InstallationException:CannotLoadSettings' => "No s'ha pogut carregar l'arxiu de configuraci&oacute;, potser no existeix o hi ha un error de configuraci&oacute; de permisos",
	
	'SecurityException:Codeblock' => "Acc&eacute;s denegat per l'execuci&oacute; de bloc de codi privilegiat",
	'DatabaseException:WrongCredentials' => "No s'ha pugut connectar a la base de dades amb les credencials prove&iuml;des. Verifica l'arxiu de configuraci&oacute;",
	'DatabaseException:NoConnect' => "No es pot consultar la base de dades '%s', si us plau verifica que la base de dades existeix i que hi tens permisos",
	'SecurityException:FunctionDenied' => "Acc&eacute;s denegat a la funci&oacute; privilegiada '%s'",
	'DatabaseException:DBSetupIssues' => "S'han trobat una quantitat d'errors: ",
	'DatabaseException:ScriptNotFound' => "No s'ha pogut trobar l'script de base de dades %s",
	'DatabaseException:InvalidQuery' => "Consulta inv&agrave;lida",
	
	'IOException:FailedToLoadGUID' => "Error al carregar una nova %s de GUID: %d",
	'InvalidParameterException:NonElggObject' => "Passant un no-ElggObject a un constructor ElggObject!",
	'InvalidParameterException:UnrecognisedValue' => "No es reconeix el valor passat al constructor",
	
	'InvalidClassException:NotValidElggStar' => "GUID: %d no és un %s v&agrave;lid",
	
	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) és un plugin desconfigurat que ha estat deshabilitat. Si us plau, revisa la Wiki d'Elgg per m&eacute;s informaci&oacute; (http://docs.elgg.org/wiki/)",
	'PluginException:CannotStart' => "%s (guid: %s) no pot iniciar-se. Motiu: %s",
	'PluginException:InvalidID' => "%s no és un ID de plugin v&agrave;lid",
	'PluginException:InvalidPath' => "%s és un path de plugin inv&agrave;lid",
	'PluginException:InvalidManifest' => "Arxiu de manifest inv&agrave;lid pel plugin %s",
	'PluginException:InvalidPlugin' => "%s no és un plugin v&agrave;lid",
	'PluginException:InvalidPlugin:Details' => "%s no és un plugin v&agrave;lid: %s",
	
	'ElggPlugin:MissingID' => "No es troba l'ID del plugin (guid %s)",
	'ElggPlugin:NoPluginPackagePackage' => "Manca ElggPluginPackage pel plugin amb ID %s (guid %s)",

	'ElggPluginPackage:InvalidPlugin:MissingFile' => "Manca l'arxiu %s al package",
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => "Tipus de depend&egrave;ncia '%s' inv&agrave;lida",
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => "Tipus '%s' provist inv&agrave;lid",
	'ElggPluginPackage:InvalidPlugin:CircularDep' => "Depend&egrave;ncia %s inv&agrave;lida '%s' al plugin %s. Els plugins no poden entrar en conlicte amb altres requerits!",

	'ElggPlugin:Exception:CannotIncludeFile' => "No pot incloure's %s pel plugin %s (guid: %s) a %s. Verifica els permisos!",
	'ElggPlugin:Exception:CannotRegisterViews' => "No pot cargar-se el directori 'views' pel plugin %s (guid: %s) a %s. Verifica els permisos!",
	'ElggPlugin:Exception:CannotRegisterLanguages' => "No poden registrar-se llenguatges pel plugin %s (guid: %s) a %s.  Verifica els permisos!",
	'ElggPlugin:Exception:NoID' => "No s'ha trobat l'ID pel plugin amb guid %s!",

	'PluginException:ParserError' => "Error processant el manifiest amb versi&oacute; d'API %s al plugin %s",
	'PluginException:NoAvailableParser' => "No es troba un processador pel manifest de la versi&oacute; de l'API %s al plugin %s",
	'PluginException:ParserErrorMissingRequiredAttribute' => "Manca l'atribut '%s' al manifest del plugin %s",

	'ElggPlugin:Dependencies:Requires' => 'Requereix',
	'ElggPlugin:Dependencies:Suggests' => 'Suggereix',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflictes',
	'ElggPlugin:Dependencies:Conflicted' => 'En conflicte',
	'ElggPlugin:Dependencies:Provides' => 'Proveeix',
	'ElggPlugin:Dependencies:Priority' => 'Prioritat',

	'ElggPlugin:Dependencies:Elgg' => 'Versi&oacute; Elgg',
	'ElggPlugin:Dependencies:PhpExtension' => 'Extensi&oacute; PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Configuraci&oacute; PHP ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Despr&eacute;s %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Abans %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s no instal&middot;lat',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Manca',

	'ElggPlugin:InvalidAndDeactivated' => "%s no és un plugin v&agrave;lid i s'ha deshabilitat",

	'InvalidParameterException:NonElggUser' => "Passant un no-ElggUser a un constructor ElggUser!",
	
	'InvalidParameterException:NonElggSite' => "Passant un no-ElggSite a un constructor ElggSite!",

	'InvalidParameterException:NonElggGroup' => "Passant un no-ElggGroup a un constructor ElggGroup!",

	'IOException:UnableToSaveNew' => "No s'ha pogut guardar un nou %s",

	'InvalidParameterException:GUIDNotForExport' => "No s'ha especificat un GUID durant l'exportaci&oacute;, aix&ograve; no hauria de passar",
	'InvalidParameterException:NonArrayReturnValue' => "Funci&oacute; de serialitzaci&oacute; d'entitat passada a un par&agrave;metre de retorn no-array",

	'ConfigurationException:NoCachePath' => "Path de mem&ograve;ria cau seteat en Null!",
	'IOException:NotDirectory' => "%s no és un directori",

	'IOException:BaseEntitySaveFailed' => "No s'ha pogut guardar una nova entitat!",
	'InvalidParameterException:UnexpectedODDClass' => "import() passat a una classe ODD inesperat",
	'InvalidParameterException:EntityTypeNotSet' => "Ha de setear-se el tipus d'entitat",

	'ClassException:ClassnameNotClass' => "%s no és un %s",
	'ClassNotFoundException:MissingClass' => "Classe '%s' no trobada, manca alg&uacute; plugin?",
	'InstallationException:TypeNotSupported' => "No es reconeix el tipus %s. Aix&ograve; indica un error en la instal&middot;laci&oacute;, segurament causat per una actualizaci&oacute; incompleta",

	'ImportException:ImportFailed' => "No s'ha pogut importar l'element %d",
	'ImportException:ProblemSaving' => "S'ha trobat un problema al guardar %s",
	'ImportException:NoGUID' => "S'ha creat una nova entitat sense GUID, aix&ograve; no ha de passar",

	'ImportException:GUIDNotFound' => "No s'ha pogut trobar l'entitat '%d'",
	'ImportException:ProblemUpdatingMeta' => "S'ha produ&iuml;t un error actualitzant '%s' en l'entitat '%d'",

	'ExportException:NoSuchEntity' => "GUID d'entitat inv&agrave;lid: %d",

	'ImportException:NoODDElements' => "No s'han trobat elements OpenDD per la importaci&oacute;, la importaci&oacute; ha fallat",
	'ImportException:NotAllImported' => "No s'han importat tots els elements",

	'InvalidParameterException:UnrecognisedFileMode' => "Mode d'arxiu '%s' no reconegut",
	'InvalidParameterException:MissingOwner' => "L'arxiu %s (guid: %d) (guid del propietari: %d) no t&eacute; propietari!",
	'IOException:CouldNotMake' => "No s'ha pogut realitzar %s",
	'IOException:MissingFileName' => "Has d'especificar un nom abans d'obrir un arxiu",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "No ha pogut carregar-se la classe de repositori %s per l'arxiu %u",
	'NotificationException:NoNotificationMethod' => "No s'ha especificat un m&egrave;tode de notificaci&oacute;",
	'NotificationException:NoHandlerFound' => "No s'ha trobat un controlador '%s' o no és executable",
	'NotificationException:ErrorNotifyingGuid' => "S'ha donat un error al notificar %d",
	'NotificationException:NoEmailAddress' => "No ha pogut carregar-se la adreça de Email pel GUID:%d",
	'NotificationException:MissingParameter' => "Manca par&agrave;metre requerit: '%s'",

	'DatabaseException:WhereSetNonQuery' => "On no contingui WhereQueryComponent",
	'DatabaseException:SelectFieldsMissing' => "Manquen camps en l'estil de consulta",
	'DatabaseException:UnspecifiedQueryType' => "Tipus de consulta no reconegut o no especificat",
	'DatabaseException:NoTablesSpecified' => "No s'han trobat les taules per la consulta",
	'DatabaseException:NoACL' => "No s'ha especificat el control d'acc&eacute;s en la consulta",

	'InvalidParameterException:NoEntityFound' => "No s'ha trobat l'entitat, potser que no existeixi o que no tinguis els permisos necessaris sobre ella",

	'InvalidParameterException:GUIDNotFound' => "No s'ha pugut trobar el GUID: %s, o no tens acc&eacute;s a ella",
	'InvalidParameterException:IdNotExistForGUID' => "Ho sentim, '%s' no existeix pel guid: %d",
	'InvalidParameterException:CanNotExportType' => "Ho sentim, no es troba implementada l'exportaci&oacute; de '%s'",
	'InvalidParameterException:NoDataFound' => "No s'han trobat resultats",
	'InvalidParameterException:DoesNotBelong' => "No pertany a la entitat",
	'InvalidParameterException:DoesNotBelongOrRefer' => "No pertany o es refereix a l'entitat",
	'InvalidParameterException:MissingParameter' => "Manca par&agrave;metre, ha de proveir un GUID",
	'InvalidParameterException:LibraryNotRegistered' => '%s no és una llibreria registrada',

	'APIException:ApiResultUnknown' => "Els resultats de l'API no són coneguts, aix&ograve; no ha de passar",
	'ConfigurationException:NoSiteID' => "No s'ha especificat un ID del lloc",
	'SecurityException:APIAccessDenied' => "Ho sentim, l'acc&eacute;s a l'API ha estat deshabilitat per a l'administrador",
	'SecurityException:NoAuthMethods' => "No s'han trobat m&egrave;todes d'autenticaci&oacute; per a processar la sol&iuml;licitut",
	'SecurityException:UnexpectedOutputInGatekeeper' => "Sortida inesperada en resultat gatekeeper. Aturant l'execuci&oacute; per seguretat. Revisa http://docs.elgg.org/ per m&eacute;s informaci&oacute;",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "M&egrave;tode o funci&oacute; no especificat en la crida a expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "Estructures d'Array són inv&agrave;lides en crides a la funci&oacute; '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "M&egrave;tode http %s no reconegut pel m&egrave;tode '%s' de l'API",
	'APIException:MissingParameterInMethod' => "Manca par&agrave;metre %s en m&egrave;tode %s",
	'APIException:ParameterNotArray' => "%s no és un Array",
	'APIException:UnrecognisedTypeCast' => "Tipus no reconegut en casting %s per a la variable '%s' en el m&egrave;tode '%s'",
	'APIException:InvalidParameter' => "S'ha trobat un par&agrave;metre inv&agrave;lid per '%s' en el m&egravetode '%s'",
	'APIException:FunctionParseError' => "%s(%s) t&eacute; un error de processat",
	'APIException:FunctionNoReturn' => "%s(%s) no ha retornat cap valor",
	'APIException:APIAuthenticationFailed' => "La crida al m&egrave;tode ha fallat en l'autenticaci&oacute; de l'API",
	'APIException:UserAuthenticationFailed' => "La crida al m&egrave;tode ha fallat en l'autenticaci&oacute; de l'usuari",
	'SecurityException:AuthTokenExpired' => "El token d'autenticaci&oacute; no se troba o b&eacute; ha expirat",
	'CallException:InvalidCallMethod' => "%s ha de cridar-se utilizant '%s'",
	'APIException:MethodCallNotImplemented' => "La crida al m&egrave;tode '%s' no es troba implementada",
	'APIException:FunctionDoesNotExist' => "La funci&oacute; pel m&egrave;tode '%s' no es executable",
	'APIException:AlgorithmNotSupported' => "No se soporta o s'ha deshabilitat l'algoritme '%s'",
	'ConfigurationException:CacheDirNotSet' => "Directori de mem&ograve;ria cau 'cache_path' no establert",
	'APIException:NotGetOrPost' => "El m&egrave;tode de Request ha de ser GET o POST",
	'APIException:MissingAPIKey' => "Manca clau API",
	'APIException:BadAPIKey' => "Clau API incorrecta",
	'APIException:MissingHmac' => "Manca capçalera X-Elgg-hmac",
	'APIException:MissingHmacAlgo' => "Manca capçalera X-Elgg-hmac-algo",
	'APIException:MissingTime' => "Manca capçalera X-Elgg-time",
	'APIException:MissingNonce' => "Manca capçalera X-Elgg-nonce",
	'APIException:TemporalDrift' => "X-Elgg-time és molt lluny&agrave; en el passat o en el futur. Fallida Epoch",
	'APIException:NoQueryString' => "No hi han dades en la query string",
	'APIException:MissingPOSTHash' => "Manca capçalera X-Elgg-posthash",
	'APIException:MissingPOSTAlgo' => "Manca capçalera X-Elgg-posthash_algo",
	'APIException:MissingContentType' => "Manca Content type per a post data",
	'SecurityException:InvalidPostHash' => "Hash de POST data inv&agrave;lid - S'esperava %s per&ograve; s'ha rebut %s",
	'SecurityException:DupePacket' => "Signatura de paquet ja vista",
	'SecurityException:InvalidAPIKey' => "Clau API inv&agrave;lida o manca",
	'NotImplementedException:CallMethodNotImplemented' => "La crida al m&egrave;tode '%s' no est&agrave; soportat",
	 
	'NotImplementedException:XMLRPCMethodNotImplemented' => "Crida al m&egrave;tode XML-RPC '%s' no implementada",
	'InvalidParameterException:UnexpectedReturnFormat' => "La crida al m&egrave;tode '%s' ha retornat un resultat inesperat",
	'CallException:NotRPCCall' => "La crida no sembla ser una crida XML-RPC v&agrave;lida",
	 
	'PluginException:NoPluginName' => "No s'ha pugut trobar el nom del plugin",
	 
	'SecurityException:authenticationfailed' => "No s'ha pogut autenticar l'usuari",
	 
	'CronException:unknownperiod' => "%s no és un per&iacute;ode reconeixible",
	 
	'SecurityException:deletedisablecurrentsite' => "No pot eliminar o deshabilitar el lloc que est&agrave; veient en aquest moment!",
	 
	'RegistrationException:EmptyPassword' => "Els camps de contrasenyes s&oacute;n obligatoris",
	'RegistrationException:PasswordMismatch' => "Les contrasenyes han de coincidir",
	'LoginException:BannedUser' => "El teu ingr&eacute;s ha estat bloquejat moment&agrave;neament",
	'LoginException:UsernameFailure' => "No s'ha pogut iniciar la sessi&oacute;. Si us plau, verifica el teu nom d'usuari i contrasenya",
	'LoginException:PasswordFailure' => "No s'ha pogut iniciar la sessi&oacute;. Si us plau, verifica el teu nom d'usuari i contrasenya",
	'LoginException:AccountLocked' => "El teu compte ha estat bloquejat per la quantitat d'intents erronis d'inici de sessi&oacute;",
	 
	'memcache:notinstalled' => "M&ograve;dul memcache del PHP no instal&middot;lat, has d'instal&middot;lar el m&ograve;dul php5-memcache",
	'memcache:noservers' => "No hi han servers memcache definits, si us plau popula la variable $CONFIG->memcache_servers",
	'memcache:versiontoolow' => "Memcache requereix com a m&iacute;nim la versi&oacute; %s per el seu funcionament, s'est&agrave; executant la versi&oacute; %s",
	'memcache:noaddserver' => "Suport per a m&uacute;ltiples servidors deshabilitat, has d'actualitzar la llibreria memcache PECL",
	 
	'deprecatedfunction' => "Precauci&oacute;: Aquest codi utilitza la funci&oacute; obsoleta \"%s\" que no és compatible amb aquesta versi&oacute; d'Elgg",
 
	'pageownerunavailable' => "Precauci&oacute;: L'administrador de p&agrave;gina %d no es troba accesible!",
	'viewfailure' => "S'ha donat un error intern en la vista %s",
	'changebookmark' => "Si us plau, modifica el teu &iacute;ndex per aquesta vista",

	
	
	
/**
 * API
 */
	'system.api.list' => "Llista de totes les crides de l'API del sistema",
	'auth.gettoken' => "La crida permet a l'usuari obtenir un testimoni d'autenticació que pot utilitzar per a futures crides a l'API enviant-lo com a paràmetre auth_token",
	
	
	
	
/**
 * User details
 */
	'name' => "Nom",
	'email' => "Adreça de correu electrònic",
	'username' => "Nom de l'usuari",
	'loginusername' => "Nom de l'usuari o adreça de correu electrònic",
	'password' => "Contrasenya",
	'passwordagain' => "Contrasenya (verificació)",
	'admin_option' => "Voleu que l'usuari sigui administrador?",
	
	
	
	
/**
 * Access
 */
	'PRIVATE' => "Privat",
	'LOGGED_IN' => "Usuaris connectats",
	'PUBLIC' => "Tots",
	'access:friends:label' => "Contactes",
	'access' => "Accés",
	
	
	
	
/**
 * Dashboard and widgets
 */
	'dashboard' => "Quadre de control",
	'dashboard:nowidgets' => "El quadre de control us permet seguir l'activitat i el contingut que us pugui interessar del lloc web",
	
	'widgets:add' => "Afegir un enginy",
	'widgets:add:description' => "Per afegir un enginy a la pàgina premeu el botó",
	'widgets:position:fixed' => "(Posició fixa a la pàgina)",
	'widget:unavailable' => "S'ha afegit l'enginy",
	'widget:numbertodisplay' => "Quantitat d'elements a visualitzar",
	
	'widget:delete' => "Suprimir %s",
	'widget:edit' => "Personalitzar l'enginy",
	
	'widgets' => "Enginys",
	'widget' => "Enginy",
	'item:object:widget' => "Enginys",
	'widgets:save:success' => "L'enginy s'ha desat correctament",
	'widgets:save:failure' => "No s'ha pogut desar l'enginy. Torneu-ho a intentar",
	'widgets:add:success' => "L'enginy s'ha afegit correctament",
	'widgets:add:failure' => "No s'ha pogut afegir l'enginy",
	'widgets:move:failure' => "No s'ha pogut desar la posició de l'enginy",
	'widgets:remove:failure' => "No s'ha pogut suprimir l'enginy",	
	
	
		
	
/**
  * Groups
	*/
	'group' => "Grup",
	'item:group' => "Grups",




/**
	* Users
	*/
	'user' => "Usuari/a",
	'item:user' => "Usuaris/es",
	
	
	

/**
	* Friends
	*/
	'friends' => "Contactes",
	'friends:yours' => "Els teus contactes",
	'friends:owned' => "Contacte de %s",
	'friend:add' => "Nou contacte",
	'friend:remove' => "Esborrar contacte",
	
	'friends:add:successful' => "S'ha afegit a %s com a contacte",
	'friends:add:failure' => "No s'ha pogut afegir a %s com a contacte. Si us plau, intenta-ho de nou",
	
	'friends:remove:successful' => "S'ha esborrat a %s dels teus contactes",
	'friends:remove:failure' => "No s'ha pogut esborrar a %s dels teus contactes. Si us plau intenta-ho de nou",
	
	'friends:none' => "Aquest/a usuari/a encara no t&eacute; contactes",
	'friends:none:you' => "Encara no tens contactes",
	
	'friends:none:found' => "No s'han trobat contactes",
	
	'friends:of:none' => "Ning&uacute; ha afegit encara a aquest/a usuari/a com a contacte",
	'friends:of:none:you' => "Ning&uacute; t'ha afegit encara com a contacte. Pots completar el teu perfil i afegir continguts perque la gent et trobi!",
	
	'friends:of:owned' => "Contactes de %s",
	
	'friends:of' => "Contactes de",
	'friends:collections' => "Col&middot;leccions de contactes",
	'collections:add' => "Nova col&middot;lecci&oacute;",
	'friends:collections:add' => "Nova col&middot;lecci&oacute; de contactes",
	'friends:addfriends' => "Sel&middot;leccionar contactes",
	'friends:collectionname' => "Nom de la col&middot;lecci&oacute;",
	'friends:collectionfriends' => "Contactes en la col&middot;lecci&oacute;",
	'friends:collectionedit' => "Editar aquesta col&middot;lecci&oacute;",
	'friends:nocollections' => "Encara no tens col&middot;leccions",
	'friends:collectiondeleted' => "La col&middot;lecci&oacute; ha estat esborrada",
	'friends:collectiondeletefailed' => "No s'ha pogut eliminar la col&middot;lecci&oacute;",
	'friends:collectionadded' => "La col&middot;lecci&oacute; s'ha creat correctament",
	'friends:nocollectionname' => "Has de posar un nom a la col&middot;lecci&oacute; abans de crear-la",
	'friends:collections:members' => "Membres d'aquesta col&middot;lecci&oacute;",
	'friends:collections:edit' => "Editar col&middot;lecci&oacute;",
	'friends:collections:edited' => "Col&middot;lecci&oacute; guardada",
	'friends:collection:edit_failed' => "No s'ha pogut guardar la col&middot;lecci&oacute;",
	
	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
	
	'avatar' => 'Imatge de perfil',
	'avatar:create' => 'Crea la teva imatge de perfil',
	'avatar:edit' => 'Editar imatge de perfil',
	'avatar:preview' => 'Previsualitzar',
	'avatar:upload' => 'Pujar nova imatge de perfil',
	'avatar:current' => 'Imatge de perfil actual',
	'avatar:crop:title' => "Eina de retall d'imatge de perfil",
	'avatar:upload:instructions' => "La teva imatge de perfil ja es mostra a la plateforma. Podr&agrave;s modificar-la sempre que vulguis (Formats d'arxiu acceptats: GIF, JPG o PNG)",
	'avatar:create:instructions' => "Fes click i arrossega un quadrat per a sel&middot;leccionar la retallada de la imatge. Veur&agrave;s una previsualitzaci&oacute; a la caixa de la dreta. Quan estiguis d'acord amb la previsualitzaci&oacute;, fes click a \'Crea la teva imatge de perfil\'. La versi&oacute; retallada ser&agrave; la utilitzada per a mostrar a la plataforma",
	'avatar:upload:success' => 'Imatge de perfil pujada correctament',
	'avatar:upload:fail' => 'Ha fallat la pujada de la imatge de perfil',
	'avatar:resize:fail' => 'Error al modificar el tamany de la imatge de perfil',
	'avatar:crop:success' => 'Retallada de la imatge de perfil finalitzada correctament',
	'avatar:crop:fail' => 'Error en la retallada de la imatge de perfil',
	
	'profile:edit' => 'Editar perfil',
	'profile:aboutme' => "Sobre mi",
	'profile:description' => "Sobre mi",
	'profile:briefdescription' => "Descripci&oacute; curta",
	'profile:location' => "Ubicaci&oacute;",
	'profile:skills' => "Habilitats",
	'profile:interests' => "Interessos",
	'profile:contactemail' => "Email de contacte",
	'profile:phone' => "Tel&egrave;fon",
	'profile:mobile' => "M&ograve;vil",
	'profile:website' => "Lloc Web",
	'profile:twitter' => "Usuari de Twitter",
	'profile:saved' => "El teu perfil s'ha guardat correctament",
		
	'admin:appearance:profile_fields' => 'Editar camps de perfil',
	'profile:edit:default' => 'Editar camps de perfil',
	'profile:label' => "Etiqueta de perfil",
	'profile:type' => "Tipus de perfil",
	'profile:editdefault:delete:fail' => 'Error en eliminar &iacute;tem de perfil per defecte',
	'profile:editdefault:delete:success' => '&Iacute;tem de perfil per defecte eliminat!',
	'profile:defaultprofile:reset' => 'Reinici de perfil de sistema per defecte',
	'profile:resetdefault' => 'Reiniciar perfil de sistema per defecte',
	'profile:explainchangefields' => "Pots canviar els camps del perfil utilitzant el formulari. \n\n Introdueix un nou nom de camp de perfil, per exemple, 'Tecnologia renovable favorita', despr&eacute;s sel&middot;lecciona el tipus de camp (eg. texte, url, tags), i fes click al bot&oacute; 'Afegir'. Per a endreçar els camps, arrossega el control al costat de l'etiqueta del camp. Per a editar l'etiqueta del camp, fes click en el texte de l'etiqueta per a tornar-ho editable. \n\n Pots tornar a la disposici&oacute; original del perfil en qualsevol moment, per&ograve; perdr&agrave;s la informaci&oacute; creada en els camps personalitzats del perfil",
	'profile:editdefault:success' => 'Element afegit al perfil per defecte correctament',
	'profile:editdefault:fail' => "No s'ha pugut guardar el perfil per defecte",
	
	
	
	
/**
	* Feeds
	*/
	'feed:rss' => 'Canal RSS per aquesta p&agrave;gina',
	



/**
	* Links
	*/
	'link:view' => 'Veure enllaç',
	'link:view:all' => 'Veure tots',

	
	

/**
	* River
	*/
	'river' => "River",
	'river:friend:user:default' => "%s ara &eacute;s contacte de %s",
	'river:update:user:avatar' => '%s t&eacute; una nova imatge de perfil',
	'river:noaccess' => 'No tens permisos per a veure aquest element',
	'river:posted:generic' => '%s publicat',
	'riveritem:single:user' => 'un/a usuari/a',
	'riveritem:plural:user' => 'alguns/es usuaris/es',
	'river:ingroup' => 'en el grup %s',
	'river:none' => 'Sense activitat',
	
	'river:widget:title' => "Activitat",
	'river:widget:description' => "Veure la &uacute;ltima activitat",
	'river:widget:type' => "Tipus d'activitat",
	'river:widgets:friends' => 'Activitat de contactes',
	'river:widgets:all' => "Tota l'activitat del lloc",

	
	

/**
	* Notifications
	*/
	'notifications:usersettings' => "Configuraci&oacute; de notificacions",
	'notifications:methods' => "Si us plau, indica els m&egrave;todes que vols habilitar",
	
	'notifications:usersettings:save:ok' => "La teva configuraci&oacute; de notificacions s'ha guardat correctament",
	'notifications:usersettings:save:fail' => "Hi ha hagut un error al guardar la configuraci&oacute; de notificacions",
	
	'user.notification.get' => 'Retornar la configuraci&oacute; de notificacions per un/a usuari/a',
	'user.notification.set' => 'Guardar la configuraci&oacute; de notificacions per un/a usuari/a',
	
	

	
/**
	* Search
	*/
	'search' => "Cercar",
	'searchtitle' => "Cercar: %s",
	'users:searchtitle' => "Cercar per usuaris/es: %s",
	'groups:searchtitle' => "Cercar per grups: %s",
	'advancedsearchtitle' => "%s amb coincid&egrave;ncies en resultats %s",
	'notfound' => "No s'han trobat resultats",
	'next' => "Seg&uuml;ent",
	'previous' => "Anterior",
	
	'viewtype:change' => "Modificar tipus de llista",
	'viewtype:list' => "Veure llista",
	'viewtype:gallery' => "Galeria",
	
	'tag:search:startblurb' => "&Iacute;tems amb etiquetes que coincideixen amb '%s':",
	
	'user:search:startblurb' => "Usuaris/es que coincideixen amb '%s':",
	'user:search:finishblurb' => "Click aqu&iacute; per a veure m&eacute;s",
	
	'group:search:startblurb' => "Grups que coincideixen amb '%s':",
	'group:search:finishblurb' => "Click aqu&iacute; per a veure m&eacute;s",
	'search:go' => 'Anar',
	'userpicker:only_friends' => 'Nom&eacute;s contactes',
	
	

	
/**
	* Account
	*/
	'account' => "Compte",
	'settings' => "Configuraci&oacute;",
	'tools' => "Eines",
	
	'register' => "Registrar-se",
	'registerok' => "T'has registrat correctament per %s",
	'registerbad' => "No t'has pugut registrar degut a un error desconegut",
	'registerdisabled' => "El registre s'ha deshabilitat per l'administrador del sistema",
	
	'registration:notemail' => "No has ingressat una adreça d'email v&agrave;lida",
	'registration:userexists' => "El nom d'usuari/a ja existeix",
	'registration:usernametooshort' => "El nom d'usuari/a ha de tenir un m&iacute;nim de %u car&agrave;cters",
	'registration:passwordtooshort' => "La contrasenya ha de tenir un m&iacute;nim de %u car&agrave;cters",
	'registration:dupeemail' => "Ja es troba registrada la adreça d'email",
	'registration:invalidchars' => "El teu nom d'usuari/a t&eacute; car&agrave;acters inv&agrave;lids: %s. Aquests s&oacute;n tots els car&agrave;cters que es troban invalidats: %s",
	'registration:emailnotvalid' => "La adreça d'email que has ingressat &eacute;s inv&agrave;lid al sistema",
	'registration:passwordnotvalid' => 'La contrasenya que has ingressat es inv&agrave;lid al sistema',
	'registration:usernamenotvalid' => "El nom d'usuari/a que has ingressat &eacute;s inv&agrave;lid al sistema",
	
	'adduser' => "Nou usuari/a",
	'adduser:ok' => "S'ha enregistrat correctament un nou usuari/a",
	'adduser:bad' => "No s'ha pogut enregistrar el nou usuari/a",
	
	'user:set:name' => "Configuraci&oacute; del nom de compte",
	'user:name:label' => "El meu nom per a mostrar",
	'user:name:success' => "S'ha modificat correctament el seu nom a la plataforma",
	'user:name:fail' => "No s'ha pogut modificar el seu nom a la plataforma. Si us plau, assegura't que no es massa llarg i prova de nou",
	
	'user:set:password' => "Contrasenya del compte",
	'user:current_password:label' => 'Contrasenya actual',
	'user:password:label' => "Nova contrasenya",
	'user:password2:label' => "Confirmar nova contrasenya",
	'user:password:success' => "Contrasenya modificada",
	'user:password:fail' => "No s'ha pogut modificar la contrasenya a la plataforma",
	'user:password:fail:notsame' => "Les dues contrasenyes no coincideixen!",
	'user:password:fail:tooshort' => "La contrasenya &eacute;s massa curta!",
	'user:password:fail:incorrect_current_password' => "La contrasenya ingressada &eacute;s incorrecta",
	'user:resetpassword:unknown_user' => "Usuari/a inv&agrave;lid",
	'user:resetpassword:reset_password_confirm' => "Al modificar la contrasenya rebr&agrave;s la nova a la direcci&oacute; d'email registrada",
	
	'user:set:language' => "Configuraci&oacute; de l'idioma",
	'user:language:label' => "El teu idioma",
	'user:language:success' => "S'ha actualitzat la teva configuraci&oacute; d'idioma",
	'user:language:fail' => "No s'ha pogut actualitzar la teva configuraci&oacute; d'idioma",
	
	'user:username:notfound' => "No es troba l'usuari/a %s",
	
	'user:password:lost' => "He oblidat la meva contrasenya",
	'user:password:resetreq:success' => "Sol&middot;licitut de nova contrasenya confirmada, se t'ha enviat un email",
	'user:password:resetreq:fail' => "No s'ha pugut sol&middot;licitar una nova contrasenya",
	
	'user:password:text' => "Per a sol&middot;licitar una nova contrasenya ingressa el teu nom d'usuari/a i pressiona el bot&oacute; de sota",
	
	'user:persistent' => "Recorda-m'ho",
	
	'walled_garden:welcome' => "Benvingut a",
	
	
	
	
/**
	* Administration
	*/
	'menu:page:header:administer' => "Administrar",
	'menu:page:header:configure' => "Configurar",
	'menu:page:header:develop' => "Desenvolupar",
	'menu:page:header:default' => "Altres",
	
	'admin:view_site' => "Veure lloc web",
	'admin:loggedin' => "Sessió iniciada com a %s",
	'admin:menu' => "Menú",
	
	'admin:configuration:success' => "S'ha desat la vostra configuració",
	'admin:configuration:fail' => "No s'ha pogut desar la vostra configuració",
	
	'admin:unknown_section' => "La secció d'administració no és vàlida",
	
	'admin' => "Administració",
	'admin:description' => "El tauler d'administració us permet organitzar tots els aspectes del sistema, des de la gestió dels usuaris al comportament de les extensions. Per començar, seleccioneu una opció",
	
	'admin:statistics' => "Estadístiques",
	'admin:statistics:overview' => "Resum",
	
	'admin:appearance' => "Aparença",
	'admin:utilities' => "Utilitats",
	
	'admin:users' => "Usuaris",
	'admin:users:online' => "Connectats",
	'admin:users:newest' => "El més nou",
	'admin:users:add' => "Afegir un usuari",
	'admin:users:description' => "El tauler d'administració us permet gestionar la configuració d'usuaris de la xarxa. Per començar, seleccioneu una opció",
	'admin:users:adduser:label' => "Premeu per afegir un nou usuari..",
	'admin:users:opt:linktext' => "Configuració dels usuaris..",
	'admin:users:opt:description' => "Configuració dels usuaris i informació dels comptes..",
	'admin:users:find' => "Cercar",
	
	'admin:settings' => "Configuració",
	'admin:settings:basic' => "Configuració Bàsica",
	'admin:settings:advanced' => "Configuració Avançada",
	'admin:site:description' => "El tauler d'administració us permet gestionar la configuració global de la xarxa. Per començar, seleccioneu una opció",
	'admin:site:opt:linktext' => "Configurar el lloc web..",
	'admin:site:access:warning' => "Les modificacions del control d'accés s'activaran en el proper accés",
	
	'admin:dashboard' => "Tauler de control",
	'admin:widget:online_users' => "Usuaris connectats",
	'admin:widget:online_users:help' => "Llista d'usuaris connectats a la xarxa",
	'admin:widget:new_users' => "Nous usuaris",
	'admin:widget:new_users:help' => "Fes una llista dels usuaris més nous",
	'admin:widget:content_stats' => "Estadístiques del contingut",
	'admin:widget:content_stats:help' => "Seguiment del contingut creat pels usuaris de la xarxa",
	'widget:content_stats:type' => "Tipus de contingut",
	'widget:content_stats:number' => "Número",
	
	'admin:widget:admin_welcome' => "Benvinguts",
	'admin:widget:admin_welcome:help' => "Aquesta és l'àrea d'administració",
	'admin:widget:admin_welcome:intro' =>
"Benvinguts! Us trobeu al tauler de control de l'administració. És una eina útil per a visualitzar les novetats de la xarxa",
	
	'admin:widget:admin_welcome:admin_overview' =>
"La navegació per l'àrea d'administració es troba al menú de la dreta i s'organitza en"
. " tres seccions:
	<dl>
		<dt>Administrar</dt><dd>Tasques diàries com monitoritzar els continguts, verificar els usuaris connectats i visualitzar les estadístiques.</dd>
		<dt>Configurar</dt><dd>Tasques ocasionals com establir el nom de la xarxa social i activar/desactivar extensions.</dd>
		<dt>Desenvolupar</dt><dd>Per a desenvolupadors d'extensions i disseny de temes personalitzats. (Necessita l'extensió de desenvolupador.)</dd>
	</dl>
	",
	
	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => "<br />Verifiqueu els recursos disponibles als enllaços del peu de pàgina. Gràcies per utilitzar Elgg!",
	
	'admin:footer:faq' => "PMFs d'administració",
	'admin:footer:manual' => "Manual d'Administració",
	'admin:footer:community_forums' => "Fòrums de la comunitat de l'Elgg",
	'admin:footer:blog' => "Bloc de l'Elgg",
	
	'admin:plugins:category:all' => "Totes les extensions",
	'admin:plugins:category:active' => "Extensions activades",
	'admin:plugins:category:inactive' => "Extensions desactivades",
	'admin:plugins:category:admin' => "Administració",
	'admin:plugins:category:bundled' => "Inclòs",
	'admin:plugins:category:content' => "Contingut",
	'admin:plugins:category:development' => "Desenvolupament",
	'admin:plugins:category:enhancement' => "Millores",
	'admin:plugins:category:api' => "Servei/API",
	'admin:plugins:category:communication' => "Comunicació",
	'admin:plugins:category:security' => "Seguretat i Spam",
	'admin:plugins:category:social' => "Social",
	'admin:plugins:category:multimedia' => "Multimèdia",
	'admin:plugins:category:theme' => "Temes",
	'admin:plugins:category:widget' => "Enginys",
	
	'admin:plugins:sort:priority' => "Prioritat",
	'admin:plugins:sort:alpha' => "Alfabètic",
	'admin:plugins:sort:date' => "Les més noves",
	
	'admin:plugins:markdown:unknown_plugin' => "Extensió desconeguda",
	'admin:plugins:markdown:unknown_file' => "Arxiu desconegut",
	
	
	'admin:notices:could_not_delete' => "No s'ha pogut suprimir la notificació",
	
	'admin:options' => "Opcions d'administració",
	
	
	
	
/**
 * Plugins
 */
	'plugins:settings:save:ok' => "La configuració de l'extensió %s s'ha desat correctament",
	'plugins:settings:save:fail' => "S'ha produït un error al desar la configuració de l'extensió %s",
	'plugins:usersettings:save:ok' => "S'ha desat la configuració de l'usuari per a l'extensió %s",
	'plugins:usersettings:save:fail' => "S'ha produït un error al desar la configuració de l'usuari per a l'extensió %s",
	'item:object:plugin' => "Extensions",
	
	'admin:plugins' => "Extensions",
	'admin:plugins:activate_all' => "Activar-les tots",
	'admin:plugins:deactivate_all' => "Desactivar-les tots",
	'admin:plugins:activate' => "Activar",
	'admin:plugins:deactivate' => "Desactivar",
	'admin:plugins:description' => "El tauler us permet controlar i configurar les eines instal·lades al lloc web",
	'admin:plugins:opt:linktext' => "Configurar les eines..",
	'admin:plugins:opt:description' => "Configurar les eines instal·lades al lloc web.",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => "Categories",
	'admin:plugins:label:licence' => "Llicència",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => "més informació",
	'admin:plugins:label:version' => "Versió",
	'admin:plugins:label:location' => "Ubicació",
	'admin:plugins:label:dependencies' => "Dependències",
	
	'admin:plugins:warning:elgg_version_unknown' => "L'arxiu de manifest de l'extensió és obsolet i no especifica una versió compatible de l'Elgg. És molt probable que no funcioni!",
	'admin:plugins:warning:unmet_dependencies' => "L'extensió té dependències desconegudes i no s'activarà. Podeu consultar les dependències a l'apartat de més informació",
	'admin:plugins:warning:invalid' => "%s no és una extensió vàlida de l'Elgg. Per a solucionar el problema visiteu <a href='http://docs.elgg.org/Invalid_Plugin'>la documentació de l'Elgg</a>",
	'admin:plugins:cannot_activate' => "no s'ha pogut activar",
	
	'admin:plugins:set_priority:yes' => "Tornar a ordenar %s",
	'admin:plugins:set_priority:no' => "No s'ha pogut tornar a ordenar %s",
	'admin:plugins:deactivate:yes' => "Desactivar %s",
	'admin:plugins:deactivate:no' => "No s'ha pogut desactivar %s",
	'admin:plugins:activate:yes' => "S'ha activat %s",
	'admin:plugins:activate:no' => "No s'ha pogut activar %s",
	'admin:plugins:categories:all' => "Totes les categories",
	'admin:plugins:plugin_website' => "Lloc web de l'extensió",
	'admin:plugins:author' => "%s",
	'admin:plugins:version' => "Versió %s",
	'admin:plugins:simple' => "Simple",
	'admin:plugins:advanced' => "Avançada",
	'admin:plugin_settings' => "Configuració de l'extensió",
	'admin:plugins:simple_simple_fail' => "No s'ha pogut desar la configuració",
	'admin:plugins:simple_simple_success' => "S'ha desat la configuració",
	'admin:plugins:simple:cannot_activate' => "No s'ha pogut activar l'extensió. Per a més informació verifiqueu-ne l'àrea d'administració avançada.",
	'admin:plugins:warning:unmet_dependencies_active' => "L'extensió està activada però disposa de dependències desconegudes. Es poden produir erros en el seu funcionament. Per accedir a informació més detallada aneu a l'apartat \"Més informació\"",
	
	'admin:plugins:dependencies:type' => "Tipus",
	'admin:plugins:dependencies:name' => "Nom",
	'admin:plugins:dependencies:expected_value' => "Valor de Prova",
	'admin:plugins:dependencies:local_value' => "Valor Actual",
	'admin:plugins:dependencies:comment' => "Comentari",
	
	'admin:statistics:description' => "És un resum de les estadístiques del lloc web. Si necessiteu estadístiques més avançades podeu accedir-hi a través de la funcionalitat d'administració professional",
	'admin:statistics:opt:description' => "Veure la informació estadística dels usuaris i els objectes del lloc web",
	'admin:statistics:opt:linktext' => "Veure les estadístiques..",
	'admin:statistics:label:basic' => "Estadístiques bàsiques del lloc web",
	'admin:statistics:label:numentities' => "Entitats del lloc web",
	'admin:statistics:label:numusers' => "Nombre d'usuaris",
	'admin:statistics:label:numonline' => "Nombre d'usuaris connectats",
	'admin:statistics:label:onlineusers' => "Usuaris connectats",
	'admin:statistics:label:version' => "Versió de l'Elgg",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versió",
    'admin:statistics:groups' => "Activitat grups",
	
	'admin:user:label:search' => "Trobar usuari:",
	'admin:user:label:searchbutton' => "Cercar",
	
	'admin:user:ban:no' => "No s'ha pogut bloquejar l'usuari",
	'admin:user:ban:yes' => "S'ha bloquejat l'usuari",
	'admin:user:self:ban:no' => "No us podeu desbloquejar",
	'admin:user:unban:no' => "No s'ha pogut desbloquejar l'usuari",
	'admin:user:unban:yes' => "S'ha desbloquejat l'usuari",
	'admin:user:delete:no' => "No s'ha pogut eliminar l'usuari",
	'admin:user:delete:yes' => "S'ha eliminat l'usuari %s",
	'admin:user:self:delete:no' => "No us podeu eliminar",
	
	'admin:user:resetpassword:yes' => "S'ha pogut tornar a establir la contrasenya. Es notificarà l'usuari",
	'admin:user:resetpassword:no' => "No s'ha pogut tornar a establir la contrasenya",
	
	'admin:user:makeadmin:yes' => "L'usuari és administrador",
	'admin:user:makeadmin:no' => "No s'ha pogut establir l'usuari com a administrador",
	
	'admin:user:removeadmin:yes' => "L'usuari ja no és administrador",
	'admin:user:removeadmin:no' => "No podeu treure els drets d'administrador a l'usuari",
	'admin:user:self:removeadmin:no' => "No podeu suprimir els drets d'administrador",
	
	'admin:appearance:menu_items' => "Elements del menú",
	'admin:menu_items:configure' => "Configurar els elements del menú principal",
	'admin:menu_items:description' => "Seleccioneu els elements del menú que voleu mostrar com a enllaços preferits. Els elements no seleccionats seran accessibles a través de l'element \"Més\" situat al final de la llista",
	'admin:menu_items:hide_toolbar_entries' => "Voleu treure els enllaços del menú de la barra d'eines ?",
	'admin:menu_items:saved' => "Elements del menú desats",
	'admin:add_menu_item' => "Afegir un element del menú personalitzat",
	'admin:add_menu_item:description' => "Per agregar un element al menú personalitzat introduïu el nom que es visualitzarà i la direcció URL associada",
	
	'admin:appearance:default_widgets' => "Enginys predeterminats",
	'admin:default_widgets:unknown_type' => "El tipus d'enginy és desconegut",
	'admin:default_widgets:instructions' => "Afegir, treure, noure i configurar els enginys predeterminats a la pàgina d'enginys seleccionada"
	. " Els canvis només s'aplicaran als nous usuaris",
	
	
	
	
/**
 * User settings
 */
	'usersettings:description' => "El tauler de configuració us permet parametritzar les vostres preferències personals, des de l'administració d'usuaris al comportament de les extensions. Per a començar, seleccioneu una opció",
	
	'usersettings:statistics' => "Les vostres estadístiques",
	'usersettings:statistics:opt:description' => "Veure la informació estadística dels usuaris i objectes de la xarxa",
	'usersettings:statistics:opt:linktext' => "Estadístiques del compte",
	
	'usersettings:user' => "Les vostres preferències",
	'usersettings:user:opt:description' => "Aquesta funcionalitat us permet definir les vostres preferències",
	'usersettings:user:opt:linktext' => "Modificar les vostres preferències",
	
	'usersettings:plugins' => "Eines",
	'usersettings:plugins:opt:description' => "Preferències de configuració de les vostres eines actives",
	'usersettings:plugins:opt:linktext' => "Configureu les vostres eines",
	
	'usersettings:plugins:description' => "El tauler us permet definir les preferències personals de les eines habilitades per l'administrador del sistema",
	'usersettings:statistics:label:numentities' => "El contingut",
	
	'usersettings:statistics:yourdetails' => "La vostra informació detallada",
	'usersettings:statistics:label:name' => "Nom complet",
	'usersettings:statistics:label:email' => "Adreça de correu electrònic",
	'usersettings:statistics:label:membersince' => "Membre des de",
	'usersettings:statistics:label:lastlogin' => "Últim accés",
	



/**
 * Activity river
 */
  'river:all' => "Activitat de tota la xarxa",
	'river:mine' => "La meva activitat",
	'river:friends' => "L'activitat dels meus amics",
	'river:select' => "Mostrar %s",
	'river:comments:more' => "+%u més",
	'river:generic_comment' => "comentat a %s %s",
	
	'friends:widget:description' => "Mostra alguns dels vostres amics",
	'friends:num_display' => "Quantitat d'amics a mostrar",
	'friends:icon_size' => "Mida de la icona",
	'friends:tiny' => "molt petita",
	'friends:small' => "petita",
	



/**
 * Generic action words
 */
	'save' => "Guardar",
	'reset' => "Reiniciar",
	'publish' => "Publicar",
	'cancel' => "Cancel·lar",
	'saving' => "Desant..",
	'update' => "Actualitzar",
	'preview' => "Previsualitzar",
	'edit' => "Editar",
	'delete' => "Eliminar",
	'accept' => "Acceptar",
	'load' => "Carregar",
	'upload' => "Pujar",
	'ban' => "Bloquejar",
	'unban' => "Desbloquejar",
	'banned' => "Bloquejat",
	'enable' => "Habilitar",
	'disable' => "Inhabilitar",
	'request' => "Sol·licitud",
	'complete' => "Completa",
	'open' => "Obrir",
	'close' => "Tancar",
	'reply' => "Respondre",
	'more' => "Més",
	'comments' => "Comentaris",
	'import' => "Importar",
	'export' => "Exportar",
	'untitled' => "Sense Títol",
	'help' => "Ajuda",
	'send' => "Enviar",
	'post' => "Publicar",
	'submit' => "Enviar",
	'comment' => "Comentar",
	'upgrade' => "Actualitzar",
	'sort' => "Ordenar",
	'filter' => "Filtrar",
	
	'site' => "Lloc web",
	'activity' => "Activitat",
	'members' => "Membres",
	
	'up' => "Amunt",
	'down' => "Avall",
	'top' => "Primer",
	'bottom' => "Últim",
	
	'more' => "més",
	
	'invite' => "Convidat",
	
	'resetpassword' => "Tornar a establir la contrassenya",
	'makeadmin' => "Afegir un administrador",
	'removeadmin' => "Suprimir l'administrador",
	
	'option:yes' => "Sí",
	'option:no' => "No",
	
	'unknown' => "Desconegut",
	
	'active' => "Actiu",
	'total' => "Total",
	
	'learnmore' => "Premeu per a veure més",
	
	'content' => "contingut",
	'content:latest' => "Última activitat",
	'content:latest:blurb' => "Premeu alternativament per veure el contingut actualitzat de la xarxa.",
	
	'link:text' => "veure l'enllaç",
	

	
	
/**
	* Generic questions
	*/
	'question:areyousure' => "N'esteu segur?",

	
	
	
/**
	* Generic data words
	*/
	'title' => "Títol",
	'description' => "Descripció",
	'tags' => "Etiquetes",
	'spotlight' => "Enfocament",
	'all' => "Tots",
	'mine' => "Meu",
	
	'by' => "per",
	'none' => "res",
	
	'annotations' => "Anotacions",
	'relationships' => "Relacions",
	'metadata' => "Metadades",
	'tagcloud' => "Núvol d'etiquetes",
	'tagcloud:allsitetags' => "Etiquetes de tot el lloc web",
	
	


/**
	* Entity actions
	*/
	'edit:this' => "Editar",
	'delete:this' => "Suprimir",
	'comment:this' => "Comentar",

	
	
	
/**
	* Input / output strings
	*/
	'deleteconfirm' => "Segur que voleu suprimir l'element ?",
	'fileexists' => "S'ha penjat l'arxiu. Per a substituir-lo, seleccioneu:",	
	
	
	
	
/**
	* User add
	*/
	'useradd:subject' => "S'ha creat el compte d'usuari",
	'useradd:body' => "
%s,
	
S'ha creat el vostre compte d'usuari a %s. Per iniciar una sessió visiteu:
	
%s

i inicieu la sessió amb les credencials següents:
	
Nom d'usuari: %s
contrasenya: %s
	
Us recomanem que modifiqueu la vista contrasenya un cop autenticats.
",
	
	
	
	
/**
	* System messages
	**/
	'systemmessages:dismiss' => "Premeu-lo per tancar",

	
	
	
/**
	* Import / export
	*/
	'importsuccess' => "La importació s'ha realitzat correctament",
	'importfail' => "S'ha produït un error en la importació de dades de l'OpenDD",
	
	
	
	
/**
	* Time
	*/
	'friendlytime:justnow' => "ara",
	'friendlytime:minutes' => "fa %s minuts",
	'friendlytime:minutes:singular' => "fa un minut",
	'friendlytime:hours' => "fa %s hores",
	'friendlytime:hours:singular' => "fa una hora",
	'friendlytime:days' => "fa %s dies",
	'friendlytime:days:singular' => "ahir",
	'friendlytime:date_format' => "j F Y @ g:ia",
	
	'date:month:01' => "Gener %s",
	'date:month:02' => "Febrer %s",
	'date:month:03' => "Març %s",
	'date:month:04' => "Abril %s",
	'date:month:05' => "Maig %s",
	'date:month:06' => "Juny %s",
	'date:month:07' => "Juliol %s",
	'date:month:08' => "Agost %s",
	'date:month:09' => "Setembre %s",
	'date:month:10' => "Octubre %s",
	'date:month:11' => "Novembre %s",
	'date:month:12' => "Desembre %s",
	
	
	
	
/**
	* System settings
	*/
	'installation:sitename' => "El nom del lloc web:",
	'installation:sitedescription' => "Breu descripció del lloc web (opcional):",
	'installation:wwwroot' => "URL del lloc web:",
	'installation:path' => "El camí sencer a la instal·lació de l'Elgg:",
	'installation:dataroot' => "El camí sencer al directori de dades:",
	'installation:dataroot:warning' => "El directori s'ha de crear manualment. El directori ha d'estar en un directori diferent al de la instal·lació de l'Elgg",
	'installation:sitepermissions' => "Permisos d'accés predeterminats:",
	'installation:language' => "Idioma predeterminat:",
	'installation:debug' => "El mode Depuració ofereix informació extra que es pot utilitzar per avaluar incidències. L'activació del mode pot disminuir la velocitat de funcionament del sistema i només s'ha d'utilitzar quan es detectin problemes:",
	'installation:debug:none' => "Inhabilitar el mode Depuració (recomanat)",
	'installation:debug:error' => "Mostrar només els errors crítics",
	'installation:debug:warning' => "Mostrar només les alertes crítiques",
	'installation:debug:notice' => "Mostrar tots els errors, alertes i informació d'incidències",
	
	// Walled Garden support
	'installation:registration:description' => "El registre està activitat per defecte. Podeu desactivar l'opció per evitar que els usuaris es puguin registrar",
	'installation:registration:label' => "Habilitar el registre de nous usuaris",
	'installation:walled_garden:description' => "Habilitar l'execució del lloc web en una xarxa privada. L'opció impedeix la visualització de qualsevol pàgina no pública als usuaris no registrats",
	'installation:walled_garden:label' => "Restringir l'accés a les pàgines a usuaris registrats",
	
	'installation:httpslogin' => "Habiliteu l'opció per forçar l'autenticació HTTPS. Perquè funcioni també caldrà que habiliteu l'autenticació HTTPS al servidor",
	'installation:httpslogin:label' => "Habilitar l'autenticació HTTPS",
	'installation:view' => "Introduïu la vista predeterminada del lloc web o deixeu-ho en blanc per utilitzar la vista predeterminada (en cas de dubte deixeu la predeterminada):",
	
	'installation:siteemail' => "Adreça de correu electrònic del lloc web (s'utilitza per enviar correus electrònics del sistema):",
	
	'installation:disableapi' => "L'Elgg disposa d'una API per al desenvolupament de serveis web que permet que aplicacions remotes puguin interactuar amb el lloc web",
	'installation:disableapi:label' => "Habilitar la API de serveis web de l'Elgg",
	
	'installation:allow_user_default_access:description' => "Permet que els usuaris puguin establir el seu propi nivell d'accés predeterminat. Permet sobreescriure els nivell d'accés del sistema",
	'installation:allow_user_default_access:label' => "Habilitar l'accés predeterminat dels usuaris",
	
	'installation:simplecache:description' => "La memòria cau simple augmenta el rendiment en l'emmagatzematge de contingut estàtic com poden ser fulls  CSS i arxius JavaScript. En la majoria dels casos la funció està activada",
	'installation:simplecache:label' => "Utilitzar la memòria cau simple (recomanat)",
	
	'installation:viewpathcache:description' => "La memòria cau de camins de les vistes redueix els temps de càrrega de les extensions guardant la ubicació dels arxius",
	'installation:viewpathcache:label' => "Utilitzar la memòria cau de camins de les vistes (recomanat)",
	
	'upgrading' => "S'està actualitzant..",
	'upgrade:db' => "S'ha actualitzat la base de dades",
	'upgrade:core' => "S'ha actualitzat la instal·lació de l'Elgg",
	'upgrade:unable_to_upgrade' => "No s'ha pogut actualitzar",
	'upgrade:unable_to_upgrade_info' =>
	"La instal·lació no es pot actualitzar perquè s'han detectat vistes velles al directori de vistes
	del nucli de l'Elgg. Aquestes vistes estan obsoletes i s'han de suprimir per assegurar que l'Elgg funciona
	correctament. Si no heu realitzat modificacions de l'Elgg podeu suprimir el directori de vistes i
	reemplaçar-lo amb l'últim del paquet d'instal·lació de l'Elgg disponible a <a href='http://elgg.org'>elgg.org</a>.<br /><br />
	
	
	Si necessiteu instruccions detallades visiteu la <a href='http://docs.elgg.org/wiki/Upgrading_Elgg'>
	Documentació d'actualització de l'Elgg</a>. Si necessiteu ajuda visiteu els
	<a href='http://community.elgg.org/pg/groups/discussion/'>Fòrums de suport a la comunitat</a>",
		
	'update:twitter_api:deactivated' => "Durant l'actualització s'ha desactivat l'API de Twitter (anteriorment Twitter Service). Si la voleu utilitzar activeu-la manualment",
	'update:oauth_api:deactivated' => "Durant l'actualització s'ha desactivat l'API OAuth (anteriorment OAuth Lib). Si la voleu utilitzar activeu-la manualment",
	
	'deprecated:function' => "%s() ha quedat obsoleta per %s()",
	
	
	
	
/**
	* Welcome
	*/
	'welcome' => "Benvingut/da",
	'welcome:user' => "Benvingut/da %s",
	
	
	
	
/**
	* Emails
	*/
	'email:settings' => "Configuració del correu electrònic",
	'email:address:label' => "Adreça del correu electrònic",
	
	'email:save:success' => "S'ha desat la nova adreça de correu electrònic. S'ha sol·licitat la seva verificació",
	'email:save:fail' => "No s'ha pogut desar la nova adreça de correu electrònic",
	
	'friend:newfriend:subject' => "%s t'ha afegit a la seva llista de contactes!",
	'friend:newfriend:body' => "%s t'ha afegit a la seva llista de contactes!
	
	Per visualitzar el seu perfil premeu:
	
%s

Siusplau, no respongueu a aquest correu electrònic",
	
	
	
	'email:resetpassword:subject' => "S'ha restablert la contrasenya!",
	'email:resetpassword:body' => "Hola %s,
	
S'ha restablert la vostra contrasenya: %s",
	
	
	'email:resetreq:subject' => "Sol·licitud de nova contrasenya",
	'email:resetreq:body' => "Hola %s,

Algú (des de l'adreça IP %s) ha sol·licitat una nova contrasenya pel vostre compte.
	
Si heu fet la sol·licitud premeu l'enllaç de sota. En cas contrari, ignoreu aquest correu electrònic.
	
%s
",
	
	
	
	
/**
	* user default access
	*/
	'default_access:settings' => "El vostre nivell d'accés predeterminat",
	'default_access:label' => "Nivell d'accés predeterminat",
	'user:default_access:success' => "S'ha desat el nivell d'accés predeterminat",
	'user:default_access:failure' => "No s'ha pogut desar el nivell d'accés predeterminat",
	
	
	
	
/**
	* XML-RPC
	*/
	'xmlrpc:noinputdata' => "Dades pendents",
	
	
	
	
/**
	* Comments
	*/
	'comments:count' => "%s comentaris",
	
	'riveraction:annotation:generic_comment' => "%s ha comentat a %s",
	
	'generic_comments:add' => "Comentar",
	'generic_comments:post' => "Publicar un comentari",
	'generic_comments:text' => "Comentar",
	'generic_comments:latest' => "Últims comentaris",
	'generic_comment:posted' => "S'ha publicat el vostre comentari",
	'generic_comment:deleted' => "S'ha suprimit el vostre comentari",
	'generic_comment:blank' => "Heu d'introduir un comentari abans de poder desar-lo",
	'generic_comment:notfound' => "No s'ha pogut trobar l'element indicat",
	'generic_comment:notdeleted' => "No s'ha pogut suprimir el comentari",
	'generic_comment:failure' => "S'ha produït un error a l'intentar afegir el vostre comentari. Torneu-ho a intentar",
	'generic_comment:none' => "Sense comentaris",
	
	'generic_comment:email:subject' => "Teniu un comentari nou!",
	'generic_comment:email:body' => "Teniu un comentari nou a \"%s\" de %s. Diu:
	
	
%s
	
	
Per respondre o veure l'original, premeu:
	
%s
	
Per veure el perfil de %s, premeu:
	
%s
	
Siusplau, no respongueu a aquest correu",
	
	
	
	
/**
	* Entities
	*/
	'byline' => "Per %s",
	'entity:default:strapline' => "Creat %s per %s",
	'entity:default:missingsupport:popup' => "L'entitat no s'ha pogut mostrar correctament. Es pot deure a que el suport que ofereix una extensió ja no estigui instal·lat al sistema",
	
	'entity:delete:success' => "S'ha suprimit l'entitat %s",
	'entity:delete:fail' => "No s'ha pogut suprimir l'entitat %s",
	
	
	
	
/**
	* Action gatekeeper
	*/
	'actiongatekeeper:missingfields' => "Falten camps _token o camps _ts",
	'actiongatekeeper:tokeninvalid' => "S'ha trobat un error (el token no coincideix). Probablement es degui al venciment de la pàgina. Torneu-ho a intentar",
	'actiongatekeeper:timeerror' => "La pàgina que estàveu utilitzant ha vençut. Actualitzeu-la i torneu-ho a intentar",
	'actiongatekeeper:pluginprevents' => "El formulari no s'ha enviat perquè una extensió no ho ha autoritzat",
	
	
	
	
/**
	* Word blacklists
	*/
	'word:blacklist' => "and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever",
	
	
	
	
/**
	* Tag labels
	*/
	'tag_names:tags' => "Etiquetes",
	'tags:site_cloud' => "Núvol d'etiquetes del lloc web",
	
	
	
	
/**
	* Javascript
	*/
	'js:security:token_refresh_failed' => "No s'ha pogut restaurar la connexió amb %s. Es poden produir problemes al desar continguts al lloc web",
	'js:security:token_refreshed' => "S'ha restaurat la connexió amb %s!",	
	
	
	

/**
 * Languages according to ISO 639-1
 */
	 'aa'  =>  "Afar" , 
	 'ab'  =>  "Abkhazià" , 
	 'af'  =>  "Afrikaans" , 
	 'am'  =>  "Amharic" , 
	 'ar'  =>  "Aràbic" , 
	 'as'  =>  "Assamese" , 
	 'ay'  =>  "Aymara" , 
	 'az'  =>  "Azerbaijani" , 
	 'ba'  =>  "Bashkir" , 
	 'be'  =>  "Bielorus" , 
	 'bg'  =>  "Búlgar" , 
	 'bh'  =>  "bihari" , 
	 'bi'  =>  "Bislama" , 
	 'bn'  =>  "Bengali;Bangla" , 
	 'bo'  =>  "Tibetà" , 
	 'br'  =>  "Bretó" , 
	 'ca'  =>  "Català" , 
	 'co'  =>  "Corsa" , 
	 'cs'  =>  "Xec" , 
	 'cy'  =>  "Galés" , 
	 'da'  =>  "Danès" , 
	 'de'  =>  "Alemà" , 
	 'dz'  =>  "Bhutani" , 
	 'el'  =>  "Grec" , 
	 'en'  =>  "Anglès" , 
	 'eo'  =>  "Esperanto" , 
	 'es'  =>  "Castellà" , 
	 'et'  =>  "Estonià" , 
	 'eu'  =>  "Eusquera" , 
	 'fa'  =>  "Persa" , 
	 'fi'  =>  "Finlandès" , 
	 'fj'  =>  "Fiji" , 
	 'fo'  =>  "Faeroese" , 
	 'fr'  =>  "Francès" , 
	 'fy'  =>  "Frisio" , 
	 'ga'  =>  "Irlandès" , 
	 'gd'  =>  "Gaèlic" , 
	 'gl'  =>  "Gallec" , 
	 'gn'  =>  "Guaraní" , 
	 'gu'  =>  "Gujarati" , 
	 'he'  =>  "Hebreu" , 
	 'ha'  =>  "Haussa" , 
	 'hi'  =>  "Hindú" , 
	 'hr'  =>  "Croat" , 
	 'hu'  =>  "Hongarès" , 
	 'hy'  =>  "Armeni" , 
	 'ia'  =>  "Interllenguatge" , 
	 'id'  =>  "Indonesi" , 
	 'ie'  =>  "Interllenguatge" , 
	 'ik'  =>  "Inupiak" , 
	 'is'  =>  "Islandès" , 
	 'it'  =>  "Italià" , 
	 'iu'  =>  "Inuktitut" , 
	 'iw'  =>  "Hebreu antic" , 
	 'ja'  =>  "Japonès" , 
	 'ji'  =>  "Yiddish" , 
	 'jw'  =>  "Javanès" , 
	 'ka'  =>  "Georgià" , 
	 'kk'  =>  "Kazakh" , 
	 'kl'  =>  "Groenlandia" , 
	 'km'  =>  "Cambotjà" , 
	 'kn'  =>  "Canadenc" , 
	 'ko'  =>  "Coreà" , 
	 'ks'  =>  "Kashmiri" , 
	 'ku'  =>  "Kurd" , 
	 'ky'  =>  "Kirghiz" , 
	 'la'  =>  "Llatí" , 
	 'ln'  =>  "Lingala" , 
	 'lo'  =>  "Laotán" , 
	 'lt'  =>  "Lituà" , 
	 'lv'  =>  "Letó" , 
	 'mg'  =>  "Magaix" , 
	 'mi'  =>  "Maorí" , 
	 'mk'  =>  "Macedoni" , 
	 'ml'  =>  "Mayalam" ,
	 'mn'  =>  "Mongol" ,
	 'mo'  =>  "Moldà" ,
	 'mr'  =>  "Marathi" ,
	 'ms'  =>  "Malay" ,
	 'mt'  =>  "Maltes" ,
	 'my'  =>  "Burmes" ,
	 'na'  =>  "Nauru" ,
	 'ne'  =>  "Nepalí" ,
	 'nl'  =>  "Holandès" ,
	 'no'  =>  "Noruec" ,
	 'oc'  =>  "Occità" ,
	 'om'  =>  "(Afan) Orom" ,
	 'or'  =>  "Oriyà" ,
	 'pa'  =>  "Punjabi" ,
	 'pl'  =>  "Polac" ,
	 'ps'  =>  "Pashto / Pushto" ,
	 'pt'  =>  "Portuguès" ,
	 'qu'  =>  "Quechua" ,
	 'rm'  =>  "Rhaeto-Romance" ,
	 'rn'  =>  "Kirundi" ,
	 'ro'  =>  "Rumà" ,
	 'ru'  =>  "Rus" ,
	 'rw'  =>  "Kinyarwanda" ,
	 'sa'  =>  "Sanskrit" ,
	 'sd'  =>  "Sindhi" ,
	 'sg'  =>  "Sangro" ,
	 'sh'  =>  "Serb-croata" ,
	 'si'  =>  "Singalès" ,
	 'sk'  =>  "Slovak" ,
	 'sl'  =>  "Sloveno" ,
	 'sm'  =>  "Samoan" ,
	 'sn'  =>  "Shona" ,
	 'so'  =>  "Somalí" ,
	 'sq'  =>  "Albanès" ,
	 'sr'  =>  "Serbi" ,
	 'ss'  =>  "Siswati" ,
	 'st'  =>  "Sesotho" ,
	 'su'  =>  "Sondanès" ,
	 'sv'  =>  "Suec" ,
	 'sw'  =>  "Swahili" ,
	 'ta'  =>  "Tamil" ,
	 'te'  =>  "Tegulu" ,
	 'tg'  =>  "Tajik" ,
	 'th'  =>  "Thai" ,
	 'ti'  =>  "Tigrinyà" ,
	 'tk'  =>  "Turc" ,
	 'tl'  =>  "Tagalog" ,
	 'tn'  =>  "Sestswana" ,
	 'to'  =>  "Tonga" ,
	 'tr'  =>  "Turc" ,
	 'ts'  =>  "Tsonga" ,
	 'tt'  =>  "Tatar" ,
	 'tw'  =>  "Twi" ,
	 'ug'  =>  "Uigur" ,
	 'uk'  =>  "Ucranià" ,
	 'ur'  =>  "Urdú" ,
	 'uz'  =>  "Uzbek" ,
	 'vi'  =>  "Vietnamita" ,
	 'vo'  =>  "Volapuk" ,
	 'wo'  =>  "Wolof" ,
	 'xh'  =>  "Xhosa" ,
	 'yi'  =>  "Yiddisha" ,
	 'yo'  =>  "Yoruba" ,
	 'za'  =>  "Zuang" ,
	 'zh'  =>  "Xinès" ,
	 'zu'  =>  "Zulu"
); 
