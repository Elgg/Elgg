<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Llocs',

/**
 * Sessions
 */

	'login' => "Iniciar sessió",
	'loginok' => "Ha iniciat sessió",
	'loginerror' => "Inici de sessió incorrecte. Verifiqueu les credencials i intenteu-ho novament",
	'login:empty' => "El nom d'usuari i la contrasenya són requerits",
	'login:baduser' => "No s'ha pogut carregar el seu compte d'usuari",
	'auth:nopams' => "Error intern. No s'ha trobat un mètode d'autentificació instal·lat",

	'logout' => "Tancar sessió",
	'logoutok' => "S'ha tancat la sessió",
	'logouterror' => "No s'ha pogut tancar la sessió, si us plau intenteu-ho de nou",
	'session_expired' => "La seua sessió ha expirat. Si us plau <a href='javascript:location.reload(true)'>recarregueu</a> la pàgina per a accedir novament.",
	'session_changed_user' => "Has accedit com a un altre usuari. Deuríes <a href='javascript:location.reload(true)'>recarregar</a> la pàgina.",

	'loggedinrequired' => "Deus de estar autentificat per a poder visualitzar aquesta pàgina",
	'adminrequired' => "Deus ser un administrador per a poder visualitzar aquesta pàgina",
	'membershiprequired' => "Deus ser membre del grup per a poder visualitzar aquesta pàgina",
	'limited_access' => "No tens permís per a veure la pàgina sol·licitada",
	'invalid_request_signature' => "La URL de la pàgina a la que intentes accedir no es vàlida o ha caducat",

/**
 * Errors
 */

	'exception:title' => "Error Fatal",
	'exception:contact_admin' => 'S\'ha trobat un error fatal al iniciar sessió. Contacteu amb l\'administrador amb la següent informació',

	'actionundefined' => "L'acció (%s) sol·licitada no es troba definida en el sistema",
	'actionnotfound' => "El log d'accions per a %s no s'ha trobat",
	'actionloggedout' => "Ho sentim, no es pot realitzar aquesta acció sense identificar-se",
	'actionunauthorized' => 'vostè no posseeix els permisos necessaris per a realitzar aquesta acció',

	'ajax:error' => 'Ha ocorregut un error inesperat en la crida AJAX. Es posible que la conexió amb el servidor s\'haja perdut.',
	'ajax:not_is_xhr' => 'No pots accedir a la vista AJAX directament',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) plugin mal configurat. S'ha desactivat. Si us plau, consulteu la wiki d'Elgg per a veure les posibles causes (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) no pot iniciar-se. Motiu: %s',
	'PluginException:InvalidID' => "%s no es un ID d'un plugin vàlid",
	'PluginException:InvalidPath' => "%s es un path de plugin invàlid",
	'PluginException:InvalidManifest' => 'Arxiu de manifest invàlid per al plugin %s',
	'PluginException:InvalidPlugin' => '%s no és un plugin vàlid',
	'PluginException:InvalidPlugin:Details' => '%s no és un plugin vàlid: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin no pot ser instanciat a null. Deus pasar-li un GUID, un ID de plugin, o un path complet.',
	'ElggPlugin:MissingID' => 'No es troba el ID del plugin (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'ElggPluginPackage faltant per al plugin amb ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Arxiu %s faltant en el package',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'El directori del plugin deu ser renomenat a \'%s\' perquè sigui igual al ID del seu manifest.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Tipus de dependència \'%s\' invàlida',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Tipus \'%s\' previst invàlid',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Dependència %s invàlida \'%s\' en plugin %s. Els plugins no poden entrar en conflicte amb altres requerits!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicte amb el plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'El arxiu  \'elgg-plugin.php\' del complement està present, però il·legible.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'No pot incloure\'s %s per al plugin %s (guid: %s) en %s. Verifiqueu els permisos!',
	'ElggPlugin:Exception:IncludeFileThrew' => 'S\'ha llançat l\'excepció incloent %s per al complement %s (guid: %s) en %s. ',
	'ElggPlugin:Exception:CannotRegisterViews' => 'No es pot carregar el directori \'views\' per al plugin %s (guid: %s) en %s. Verifiqueu els permisos!',
	'ElggPlugin:Exception:NoID' => 'No s\'ha trobat el ID per al plugin amb guid %s!',
	'PluginException:NoPluginName' => "No s'ha pogut trobar el nom del plugin",
	'PluginException:ParserError' => 'Error processant el manifest amb versió de API %s en el plugin %s',
	'PluginException:NoAvailableParser' => 'No s\'ha trobat un processador per al manifest de la versió de la API %s en plugin %s',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Atribut '%s' faltant en el manifest del plugin %s",
	'ElggPlugin:InvalidAndDeactivated' => '%s no és un plugin vàlid i s\'ha deshabilitat',
	'ElggPlugin:activate:BadConfigFormat' => 'El arxiu \'elgg-plugin.php\' del complement no ha retornat una matriu serialitzable. ',
	'ElggPlugin:activate:ConfigSentOutput' => 'El arxiu \'elgg-plugin.php\' del complement ha enviat una eixida.',

	'ElggPlugin:Dependencies:Requires' => 'Requereix',
	'ElggPlugin:Dependencies:Suggests' => 'Sugereix',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflictes',
	'ElggPlugin:Dependencies:Conflicted' => 'En conflicte',
	'ElggPlugin:Dependencies:Provides' => 'Proveeix',
	'ElggPlugin:Dependencies:Priority' => 'Prioritat',

	'ElggPlugin:Dependencies:Elgg' => 'Versió Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Versió de PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Extensió PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Configuració PHP ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Després %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Abans %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s no instal·lat',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Faltant',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Hi ha altres complements que tenen a «%s» com a dependència. Deus desactivar els següents complements primer per a poder desactivar aquest: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Hi ha elements de menú que no estàn enllaçats a ningún element pare',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'El element de menú [%s] no té element pare[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'El element de menú [%s] està registrat per duplicat',

	'RegistrationException:EmptyPassword' => 'Els camps de contrasenya són obligatoris',
	'RegistrationException:PasswordMismatch' => 'Les contrasenyes deuen coincidir',
	'LoginException:BannedUser' => 'El seu accés ha estat bloquejat momentàniament',
	'LoginException:UsernameFailure' => 'No ha pogut iniciar-se la sessió. Si us plau, verifiqueu el vostre nom d\'usuari i contrasenya',
	'LoginException:PasswordFailure' => 'No ha pogut iniciar-se la sessió. Si us plau, verifiqueu el vostre nom d\'usuari i contrasenya',
	'LoginException:AccountLocked' => 'El seu compte d\'usuari ha estat bloquejat per la cantitat d\'intents de sessió fallits',
	'LoginException:ChangePasswordFailure' => 'Ha fallat el canvi de contrasenya. Revisa l\'antiga i nova contrasenya.',
	'LoginException:Unknown' => 'No s\'ha pogut iniciar sessió degut a un error desconegut.',

	'UserFetchFailureException' => 'No es poden revisar els permisos per al user_guid [%s] perquè l\'usuari no existeix.',

	'deprecatedfunction' => 'Precaució: Aquest codi utilitza la funció obsoleta \'%s\' que no és compatible amb aquesta versió d\'Elgg',

	'pageownerunavailable' => 'Precaució: L\'administrador de la pàgina %d no es troba accesible!',
	'viewfailure' => 'Ha ocorregut un error intern en la vista %s',
	'view:missing_param' => "Falta el parámetre obligatori «%s» en la vista «%s».",
	'changebookmark' => 'Si us plau, modifiqueu el vostre índex per a aquesta vista',
	'noaccess' => 'Necesites iniciar sessió per a veure aquest contingut o el contingut ha estat remogut o no tens permís per a veure-ho.',
	'error:missing_data' => 'Falten dades en la seus sol·licitud',
	'save:fail' => 'Ha ocorregut un error desant les seues dades',
	'save:success' => 'Les seues dades han estat desades',

	'error:default:title' => 'Error...',
	'error:default:content' => 'Oops... Alguna cosa ha eixit malament',
	'error:400:title' => 'Petició incorrecta',
	'error:400:content' => 'Ho sentim. La seva petició no és vàlida o està incompleta.',
	'error:403:title' => 'Prohibit',
	'error:403:content' => 'Ho sentim. No tens permís per a accedir a la pàgina sol·licitada.',
	'error:404:title' => 'Pàgina no trobada',
	'error:404:content' => 'Ho sentim. No hem pogut trobar la pàgina sol·licitada',

	'upload:error:ini_size' => 'L\'arxiu que ha intentat pujar és massa gran.',
	'upload:error:form_size' => 'L\'arxiu que ha intentat pujar és molt gran.',
	'upload:error:partial' => 'La pujada no ha pogut completar-se',
	'upload:error:no_file' => 'Ningún arxiu ha estat seleccionat',
	'upload:error:no_tmp_dir' => 'No es pot desar l\'arxiu pujat',
	'upload:error:cant_write' => 'No es pot desar l\'arxiu pujat',
	'upload:error:extension' => 'No es pot desar l\'arxiu pujat',
	'upload:error:unknown' => 'La pujada ha fallat',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Administrador',
	'table_columns:fromView:banned' => 'Bloquejat',
	'table_columns:fromView:container' => 'Contenedor',
	'table_columns:fromView:excerpt' => 'Descripció',
	'table_columns:fromView:link' => 'Nom/Títol',
	'table_columns:fromView:icon' => 'Icona',
	'table_columns:fromView:item' => 'Element',
	'table_columns:fromView:language' => 'Idioma',
	'table_columns:fromView:owner' => 'Propietari',
	'table_columns:fromView:time_created' => 'Temps de creació',
	'table_columns:fromView:time_updated' => 'Temps d\'actualització',
	'table_columns:fromView:user' => 'Usuari',

	'table_columns:fromProperty:description' => 'Descripció',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Nom',
	'table_columns:fromProperty:type' => 'Tipus',
	'table_columns:fromProperty:username' => 'Nom d\'usuari',

	'table_columns:fromMethod:getSubtype' => 'Subtipus',
	'table_columns:fromMethod:getDisplayName' => 'Nom/Títol',
	'table_columns:fromMethod:getMimeType' => 'Tipus MIME',
	'table_columns:fromMethod:getSimpleType' => 'Tipus',

/**
 * User details
 */

	'name' => "Nom",
	'email' => "Direcció d'Email",
	'username' => "Nom d'usuari",
	'loginusername' => "Nom d'usuari o Email",
	'password' => "Contrasenya",
	'passwordagain' => "Contrasenya (novament, per a verificar)",
	'admin_option' => "Fer administrador a aquest usuari?",
	'autogen_password_option' => "¿Generar automàticament una contrasenya segura? ",

/**
 * Access
 */

	'PRIVATE' => "Privat",
	'LOGGED_IN' => "Usuaris que han iniciat sessió",
	'PUBLIC' => "Tots",
	'LOGGED_OUT' => "Usuaris que han tancat sessió",
	'access:friends:label' => "Amics",
	'access' => "Accés",
	'access:overridenotice' => "Avís: Degut a la política del grup, aquest contingut solament és accesible per als membres del grup",
	'access:limited:label' => "Limitat",
	'access:help' => "El nivell d'accés",
	'access:read' => "Soles lectura",
	'access:write' => "Accés d'escriptura",
	'access:admin_only' => "Solament Administradors",
	'access:missing_name' => "Falta el nom del nivell d'accés",
	'access:comments:change' => "Aquesta discussió està solament visible per a un conjunt limitat d'usuaris. Pensa bé amb qui la comparteixes.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Panel de control",
	'dashboard:nowidgets' => "El seu panel de control li permet seguir l'activitat i el contingut que li interessa d'aquest lloc",

	'widgets:add' => 'Agregar widget',
	'widgets:add:description' => "Faça click en el botó d'algun widget per a agregar-lo a la pàgina",
	'widgets:panel:close' => "Tancar el panel de widgets",
	'widgets:position:fixed' => '(Posició fixa en la pàgina)',
	'widget:unavailable' => 'Ja va agregar aquest widget',
	'widget:numbertodisplay' => 'Cantitat d\'elements per a mostrar',

	'widget:delete' => 'Llevar %s',
	'widget:edit' => 'Personalitzar aquest widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "El widget s'ha desat correctament",
	'widgets:save:failure' => "No s'ha pogut desar el widget, si us plau, intenteu-ho de nou",
	'widgets:add:success' => "S'ha agregat correctament el widget",
	'widgets:add:failure' => "No s'ha pogut afegir el widget",
	'widgets:move:failure' => "No s'ha pogut desar la nova posició del widget",
	'widgets:remove:failure' => "No s'ha pogut llevar el widget",

/**
 * Groups
 */

	'group' => "Grup",
	'item:group' => "Grups",

/**
 * Users
 */

	'user' => "Usuari",
	'item:user' => "Usuaris",

/**
 * Friends
 */

	'friends' => "Amics",
	'friends:yours' => "Els teus Amics",
	'friends:owned' => "Amics de %s",
	'friend:add' => "Afegir amics",
	'friend:remove' => "Llevar amic",

	'friends:add:successful' => "S'ha afegit a %s com a amic",
	'friends:add:failure' => "No s'ha pogut afegir a %s com amic. Si us plau intenteu-ho novament",

	'friends:remove:successful' => "S'ha llevat a %s dels seus amics",
	'friends:remove:failure' => "No s'ha pogut llevar a %s dels seus amics. Si us plau intenteu-ho novament",

	'friends:none' => "Aquest usuari no té amics encara",
	'friends:none:you' => "No tens amics encara",

	'friends:none:found' => "No s'han trobat amics",

	'friends:of:none' => "Ningú ha agregat a aquest usuari com a amic encara",
	'friends:of:none:you' => "Ningú t'ha agregat com a amic encara. Comença a afegir contingut i completar el teu perfil per a que la gent et trobe!",

	'friends:of:owned' => "Amics de %s",

	'friends:of' => "Amics de",
	'friends:collections' => "Col·leccions d'amics",
	'collections:add' => "Nova col·lecció",
	'friends:collections:add' => "Nova col·lecció d'amics",
	'friends:addfriends' => "Seleccionar amics",
	'friends:collectionname' => "Nom de la col·lecció",
	'friends:collectionfriends' => "Amics en la col·lecció",
	'friends:collectionedit' => "Editar aquesta col·lecció",
	'friends:nocollections' => "No tens col·leccions encara",
	'friends:collectiondeleted' => "La col·lecció ha sigut eliminada",
	'friends:collectiondeletefailed' => "No es pot eliminar la col·lecció",
	'friends:collectionadded' => "La col·lecció s'ha creat correctament",
	'friends:nocollectionname' => "Deus posar-li un nom a la col·lecció abans de crear-la",
	'friends:collections:members' => "Membres d'aquesta col·lecció",
	'friends:collections:edit' => "Editar col·lecció",
	'friends:collections:edited' => "Col·lecció desada",
	'friends:collection:edit_failed' => 'No s\'ha pogut desar la col·lecció',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Imatge de perfil',
	'avatar:noaccess' => "No tens permès editar el avatar d'aquest usuari",
	'avatar:create' => 'Cree la seua imatge de perfil',
	'avatar:edit' => 'Editar imatge de perfil',
	'avatar:preview' => 'Previsualitzar',
	'avatar:upload' => 'Pujar nova imatge de perfil',
	'avatar:current' => 'Imatge de perfil actual',
	'avatar:remove' => 'Remou el teu avatar i estableix la icona per defecte',
	'avatar:crop:title' => 'Ferramenta de retall d\'imatge de perfil',
	'avatar:upload:instructions' => "La seua imatge de perfil es mostrarà en la xarxa. Podràs modificar-la sempre que ho desitges (Formats d'arxiu acceptats: GIF, JPG o PNG)",
	'avatar:create:instructions' => 'Faça click i arrastre un cuadrat avall per a seleccionar el retall de la imatge. Apareixerà una previsualització en la caixa de la dreta. Quan estigues conforme amb la previsualització, faça click en \'Crear imatge de perfil\'. La versió retallada serà la que s\'utilitze per a ser mostrada en la xarxa',
	'avatar:upload:success' => 'Imatge de perfil pujada correctament',
	'avatar:upload:fail' => 'Ha fallat la pujada de la imatge de perfil',
	'avatar:resize:fail' => 'Error al modificar el tamany de la imatge de perfil',
	'avatar:crop:success' => 'Retall de la imatge de perfil finalitzat correctament',
	'avatar:crop:fail' => 'El retall del avatar ha fallat',
	'avatar:remove:success' => 'S\'ha eliminat el avatar',
	'avatar:remove:fail' => 'Remoure el avatar ha fallat',

	'profile:edit' => 'Editar perfil',
	'profile:aboutme' => "Sobre mi",
	'profile:description' => "Sobre mi",
	'profile:briefdescription' => "Descripció curta",
	'profile:location' => "Ubicació",
	'profile:skills' => "Habilitats",
	'profile:interests' => "Interessos",
	'profile:contactemail' => "Email de contacte",
	'profile:phone' => "Telèfon",
	'profile:mobile' => "Mòbil",
	'profile:website' => "Lloc Web",
	'profile:twitter' => "Usuari de Twitter",
	'profile:saved' => "El seu perfil s'ha desat correctament",

	'profile:field:text' => 'Text curt',
	'profile:field:longtext' => 'Àrea de text llarg',
	'profile:field:tags' => 'Etiquetes',
	'profile:field:url' => 'Direcció Web',
	'profile:field:email' => 'Direcció d\'email',
	'profile:field:location' => 'Ubicació',
	'profile:field:date' => 'Data',

	'admin:appearance:profile_fields' => 'Editar camps de perfil',
	'profile:edit:default' => 'Editar camps de perfil',
	'profile:label' => "Etiqueta de perfil",
	'profile:type' => "Tipus de perfil",
	'profile:editdefault:delete:fail' => 'S\'ha produït un error al eliminar el camp de perfil',
	'profile:editdefault:delete:success' => 'Item de perfil per defecte eliminat!',
	'profile:defaultprofile:reset' => 'Reinici de perfil de sistema per defecte',
	'profile:resetdefault' => 'Reiniciar perfil de sistema per defecte',
	'profile:resetdefault:confirm' => 'Estàs segur que vols esborrar els teus camps de perfil personalitzats?',
	'profile:explainchangefields' => "Pots reemplaçar els camps de perfil existents amb els propis utilitzant el formulari d'avall. \n\n Introduïu un nou nom de camp de perfil, per exemple, 'Equip favorit', després seleccioneu el tipus de camp (eg. text, url, tags), i feu click en el botó d''Agregar'.Per a re ordenar els camps arrossegueu el control al costat de l'etiqueta del camp. Per a editar l'etiqueta del camp faça click en el text de l'etiqueta per a tornar-lo editable. \n\n Pot tornar a la disposició original del perfil en qualsevol moment, però perdrà l'informació creada en els camps personalitzats del perfil fins al moment",
	'profile:editdefault:success' => 'Element afegit al perfil per defecte correctament',
	'profile:editdefault:fail' => 'No s\'ha pogut desar el perfil per defecte',
	'profile:field_too_long' => 'No s\'ha pogut desar l\'informació del perfil degut a que la secció \'%s\' és massa llarga.',
	'profile:noaccess' => "No tens permís per a editar aquest perfil.",
	'profile:invalid_email' => '«%s» deu ser una direcció de correu electrònic vàlida.',


/**
 * Feeds
 */
	'feed:rss' => 'Canal RSS per a aquesta pàgina',
/**
 * Links
 */
	'link:view' => 'Veure enllaç',
	'link:view:all' => 'Veure tots',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s ara és amic de %s",
	'river:update:user:avatar' => '%s té una nova imatge de perfil',
	'river:update:user:profile' => '%s ha actualitzat el seu perfil',
	'river:noaccess' => 'No posseeix permisos per a visualitzar aquest element',
	'river:posted:generic' => '%s publicat',
	'riveritem:single:user' => 'un usuari',
	'riveritem:plural:user' => 'alguns usuaris',
	'river:ingroup' => 'en el grup %s',
	'river:none' => 'Sense activitat',
	'river:update' => 'Actualitzacions de %s',
	'river:delete' => 'Eliminar aquest element de l\'activitat',
	'river:delete:success' => 'L\'item en el River ha estat esborrat',
	'river:delete:fail' => 'L\'item en el River no ha pogut ser esborrat',
	'river:delete:lack_permission' => 'No tens permís per a eliminar aquest element de l\'activitat.',
	'river:can_delete:invaliduser' => 'No es pot revisar canDelete per al usuari [%s] perquè l\'usuari no existeix.',
	'river:subject:invalid_subject' => 'Usuari invàlid',
	'activity:owner' => 'Veure Activitat',

	'river:widget:title' => "Activitat",
	'river:widget:description' => "Mostrar l'última activitat",
	'river:widget:type' => "Tipus d'activitat",
	'river:widgets:friends' => 'Activitat d\'amics',
	'river:widgets:all' => 'Tota l\'activitat del lloc',

/**
 * Notifications
 */
	'notifications:usersettings' => "Configuració de notificacions",
	'notification:method:email' => 'Correu electrònic',

	'notifications:usersettings:save:ok' => "La seva configuració de notificacions s'ha desat correctament",
	'notifications:usersettings:save:fail' => "Ha ocorregut un error al desar la configuració de notificacions",

	'notification:subject' => 'Notificacions de %s',
	'notification:body' => 'Veure la nova activitat en %s',

/**
 * Search
 */

	'search' => "Cercar",
	'searchtitle' => "Cercar: %s",
	'users:searchtitle' => "Cercar per a usuaris: %s",
	'groups:searchtitle' => "Cercar per a grups: %s",
	'advancedsearchtitle' => "%s amb coincidències en resultats %s",
	'notfound' => "No s'han trobat resultats",
	'next' => "Següent",
	'previous' => "Anterior",

	'viewtype:change' => "Modificar tipus de llista",
	'viewtype:list' => "Vista de llista",
	'viewtype:gallery' => "Galería",

	'tag:search:startblurb' => "Items amb tags que coincideixin amb '%s':",

	'user:search:startblurb' => "Usuaris que coincideixin amb '%s':",
	'user:search:finishblurb' => "Click ací per a veure més",

	'group:search:startblurb' => "Grups que coincideixin amb '%s':",
	'group:search:finishblurb' => "Click ací per a veure més",
	'search:go' => 'Cerca',
	'userpicker:only_friends' => 'Solament amics',

/**
 * Account
 */

	'account' => "Compte",
	'settings' => "Configuració",
	'tools' => "Ferramentes",
	'settings:edit' => 'Editar configuració',

	'register' => "Registrar-se",
	'registerok' => "S'ha registrat correctament per a %s",
	'registerbad' => "No s'ha pogut registrar degut a un error desconegut",
	'registerdisabled' => "El registre s'ha deshabilitat per l'administrador del sistema",
	'register:fields' => 'Tots els camps són obligatoris',

	'registration:notemail' => 'No ha ingresat una direcció de correu vàlida',
	'registration:userexists' => 'El nom d\'usuari ja existeix',
	'registration:usernametooshort' => 'El nom d\'usuari deu tenir un mínim de %u caràcters',
	'registration:usernametoolong' => 'El teu nom d\'usuari és massa llarg. El màxim permés és %u caràcters.',
	'registration:passwordtooshort' => 'La contrasenya deu tenir un mínim de %u caràcters',
	'registration:dupeemail' => 'Ja es troba registrada la direcció de correu electrònic',
	'registration:invalidchars' => 'Ho sentim, el seu nom d\'usuari posseeix els caràcters invàlids: %s. Aquests són tots els caràcters que es troben invalidats: %s',
	'registration:emailnotvalid' => 'Ho sentim, la direcció de correu electrònic que has ingressat és invàlida en el sistema',
	'registration:passwordnotvalid' => 'Ho sentim, la contrasenya que ha ingressat és invàlida en el sistema',
	'registration:usernamenotvalid' => 'Ho sentim, el nom d\'usuari que ha ingressat és invàlid en el sistema',

	'adduser' => "Nou usuari",
	'adduser:ok' => "S'ha agregat correctament un nou usuari",
	'adduser:bad' => "No s'ha pogut agregar el nou usuari",

	'user:set:name' => "Configuració del nom de compte",
	'user:name:label' => "El meu nom per a mostrar",
	'user:name:success' => "S'ha modificat correctament el seu nom en la xarxa",
	'user:name:fail' => "No s'ha pogut modificar el seu nom en la xarxa. Si us plau, asegureu-vos de que no és massa larg i intenteu-ho novament",

	'user:set:password' => "Contrasenya del compte",
	'user:current_password:label' => 'Constrasenya actual',
	'user:password:label' => "Noca contrasenya",
	'user:password2:label' => "Confirmar nova contrasenya",
	'user:password:success' => "Contrasenya modificada",
	'user:password:fail' => "No s'ha pogut modificar la contrasenya en la xarxa",
	'user:password:fail:notsame' => "Les dues contrasenyes no coincideixen!",
	'user:password:fail:tooshort' => "La contrasenya es massa curta!",
	'user:password:fail:incorrect_current_password' => 'La contrasenya actual ingressada és incorrecta',
	'user:changepassword:unknown_user' => 'Usuari invàlid',
	'user:changepassword:change_password_confirm' => 'Açò cambiará la seua contrasenya.',

	'user:set:language' => "Configuració de llenguatge",
	'user:language:label' => "El seu llenguatge",
	'user:language:success' => "S'ha actualitzat la seua configuració de llenguatge",
	'user:language:fail' => "No s'ha pogut actualitzar la seua configuració de llenguatge",

	'user:username:notfound' => 'No s\'ha pogut trobar l\'usuari %s',

	'user:password:lost' => 'He oblidat la meua contrasenya',
	'user:password:changereq:success' => 'Sol·licitud de nova contrasenya confirmada, se li ha enviat un correu electrònica',
	'user:password:changereq:fail' => 'No s\'ha pogut sol·licitar una nova contrasenya',

	'user:password:text' => 'Per a sol·licitar una nova contrasenya ingresse el seu nom d\'usuari i premi el botó situat avall',

	'user:persistent' => 'Recordar-me',

	'walled_garden:welcome' => 'Benvingut a',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrar',
	'menu:page:header:configure' => 'Configurar',
	'menu:page:header:develop' => 'Desenvolupar',
	'menu:page:header:default' => 'Altre',

	'admin:view_site' => 'Veure lloc',
	'admin:loggedin' => 'Sessió iniciada com a %s',
	'admin:menu' => 'Menú',

	'admin:configuration:success' => "La seua configuració ha sigut desada",
	'admin:configuration:fail' => "No s'ha pogut desar la seua configuració",
	'admin:configuration:dataroot:relative_path' => 'No es pot configurar \'%s\' com a directori de dades arrel, ja que la ruta no és absoluta.',
	'admin:configuration:default_limit' => 'El nombre d\'elements deu ser al menys 1.',

	'admin:unknown_section' => 'Secció d\'administració invàlida',

	'admin' => "Administració",
	'admin:description' => "El panel d'administració li permet organitzar tots els aspectes del sistema, des de la gestió d'usuaris fins al comportament dels plugins. Seleccione una opció avall per començar",

	'admin:statistics' => "Estadístiques",
	'admin:statistics:overview' => 'Resum',
	'admin:statistics:server' => 'Informació del servidor',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Últims treballs del Cron',
	'admin:cron:period' => 'període Cron',
	'admin:cron:friendly' => 'Últim completat',
	'admin:cron:date' => 'Data i hora',
	'admin:cron:msg' => 'Missatge',
	'admin:cron:started' => 'Els treballs de cron per a \'%s\' han començat a les %s',
	'admin:cron:complete' => 'Els treballs de cron per a \'%s\' s\'han completat a les %s',

	'admin:appearance' => 'Apariència',
	'admin:administer_utilities' => 'Utilitats',
	'admin:develop_utilities' => 'Utilitats',
	'admin:configure_utilities' => 'Utilitats',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Usuaris",
	'admin:users:online' => 'Connectats actualment',
	'admin:users:newest' => 'Els més nous',
	'admin:users:admins' => 'Administradors',
	'admin:users:add' => 'Agregar Nou Usuari',
	'admin:users:description' => "Aquest panell d'administració li permet gestionar la configuració d'usuaris de la xarxa. Seleccione una opció avall per començar",
	'admin:users:adduser:label' => "Click ací per a agregar un nou usuari..",
	'admin:users:opt:linktext' => "Configurar usuaris..",
	'admin:users:opt:description' => "Configurar usuaris e informació de comptes",
	'admin:users:find' => 'Cercar',

	'admin:administer_utilities:maintenance' => 'Mode de Manteniment',
	'admin:upgrades' => 'Actualitzacions',

	'admin:settings' => 'Configuració',
	'admin:settings:basic' => 'Configuració Básica',
	'admin:settings:advanced' => 'Configuració Avançada',
	'admin:site:description' => "Aquest panell d'administració li permet gestionar la configuració global de la xarxa. Selecciona una opció avall per començar",
	'admin:site:opt:linktext' => "Configurar lloc..",
	'admin:settings:in_settings_file' => 'Aquesta opció es configura en settings.php',

	'admin:legend:security' => 'Seguretat',
	'admin:site:secret:intro' => 'Elgg utilitza una clau per a crear tokens de seguretat per varios propòsits.',
	'admin:site:secret_regenerated' => "La clau secreta ha estat regenerada.",
	'admin:site:secret:regenerate' => "Regenerar clau secreta",
	'admin:site:secret:regenerate:help' => "Nota: pot ser que regenerar el secret del lloc supose un inconvenient per a alguns usuaris, ja que invalidará codis utilitzats per les cookies de la funció de «Recordeu-me», per a sol·licituds de validació de la direcció de correu electrònic, per a codis d'invitació, etc.",
	'site_secret:current_strength' => 'Seguretat de la clau',
	'site_secret:strength:weak' => "Feble",
	'site_secret:strength_msg:weak' => "Nosaltres recomanem que regeneres la teua clau secreta",
	'site_secret:strength:moderate' => "Moderada",
	'site_secret:strength_msg:moderate' => "Li recomanem que regenere el secret del lloc per a una major seguretat.",
	'site_secret:strength:strong' => "Forta",
	'site_secret:strength_msg:strong' => "La teua clau secreta és suficientment segura. No hi ha necessitat de regenerar-la.",

	'admin:dashboard' => 'Panell de control',
	'admin:widget:online_users' => 'Usuaris connectats',
	'admin:widget:online_users:help' => 'Llista els usuaris connectats actualment en la xarxa',
	'admin:widget:new_users' => 'Usuaris Nous',
	'admin:widget:new_users:help' => 'Llista els usuaris més nous',
	'admin:widget:banned_users' => 'Usuaris prohibits',
	'admin:widget:banned_users:help' => 'Llista d\'usuaris prohibits',
	'admin:widget:content_stats' => 'Estadístiques de contingut',
	'admin:widget:content_stats:help' => 'Seguiment del contingut creat pels usuaris de la xarxa',
	'admin:widget:cron_status' => 'Estat de Cron',
	'admin:widget:cron_status:help' => 'Mostra l\'estat de l\'ultima execució dels treballs de Cron',
	'widget:content_stats:type' => 'Tipus de contingut',
	'widget:content_stats:number' => 'Nombre',

	'admin:widget:admin_welcome' => 'Benvingut',
	'admin:widget:admin_welcome:help' => "Aquesta és l'area d'administració",
	'admin:widget:admin_welcome:intro' =>
'Benvingut! Es troba veient el panell de control de l\'administració. és útil per a visualitzar les novetats en la xarxa',

	'admin:widget:admin_welcome:admin_overview' =>
"La navegació per el área d'administració es troba en el menú de la dreta. El mateix s'organitza en",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Asegureu-vos de verificar els recursos disponibles en els enllaços del peu de pàgina i gràcies per utilitzar Elgg!',

	'admin:widget:control_panel' => 'Panell de control',
	'admin:widget:control_panel:help' => "Proveeix d'un accés fàcil als controls",

	'admin:cache:flush' => 'Netejar la cache',
	'admin:cache:flushed' => "La cache del lloc ha estat netejada",

	'admin:footer:faq' => 'FAQs d\'Administració',
	'admin:footer:manual' => 'Manual d\'Administració',
	'admin:footer:community_forums' => 'Foros de la Comunitat d\'Elgg',
	'admin:footer:blog' => 'Blog Elgg',

	'admin:plugins:category:all' => 'Tots els plugins',
	'admin:plugins:category:active' => 'Plugins actius',
	'admin:plugins:category:inactive' => 'Plugins inactius',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Inclòs',
	'admin:plugins:category:nonbundled' => 'No integrat',
	'admin:plugins:category:content' => 'Contingut',
	'admin:plugins:category:development' => 'Desenvolupament',
	'admin:plugins:category:enhancement' => 'Millores',
	'admin:plugins:category:api' => 'Servei/API',
	'admin:plugins:category:communication' => 'Comunicació',
	'admin:plugins:category:security' => 'Seguretat i Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimèdia',
	'admin:plugins:category:theme' => 'Temes',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Utilitats',

	'admin:plugins:markdown:unknown_plugin' => 'Plugin desconegut',
	'admin:plugins:markdown:unknown_file' => 'Arxiu desconegut',

	'admin:notices:could_not_delete' => 'Notificació de no s\'ha pogut eliminar',
	'item:object:admin_notice' => 'notificació d\'Admin',

	'admin:options' => 'Opcions d\'Admin',

/**
 * Plugins
 */

	'plugins:disabled' => 'Els plugins no s\'estàn carregant perquè un fitcher de nom \'disabled\' està en el directori de mods.',
	'plugins:settings:save:ok' => "Configuració per al pluhin %s desada correctament",
	'plugins:settings:save:fail' => "Ha ocorregut un error al intentar desar la configuració per al plugin %s",
	'plugins:usersettings:save:ok' => "Configuració del usuari per al plugin %s desada",
	'plugins:usersettings:save:fail' => "Ha ocorregut un error al intentar desar la configuració de l'usuari per al plugin %s",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activar tots',
	'admin:plugins:deactivate_all' => 'Desactivar tots',
	'admin:plugins:activate' => 'Activar',
	'admin:plugins:deactivate' => 'Desactivar',
	'admin:plugins:description' => "Aquest panell li permet controlar i configurar les ferramentes instal·lades en el seu lloc",
	'admin:plugins:opt:linktext' => "Configurar ferramentes..",
	'admin:plugins:opt:description' => "Configurar les ferramentes instal·lades en el lloc. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Nom",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categoríes',
	'admin:plugins:label:licence' => "Licència",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Informació",
	'admin:plugins:label:files' => "Arxius",
	'admin:plugins:label:resources' => "Recursos",
	'admin:plugins:label:screenshots' => "Captures de pantalla",
	'admin:plugins:label:repository' => "Codi",
	'admin:plugins:label:bugtracker' => "reportar problema",
	'admin:plugins:label:donate' => "Donar",
	'admin:plugins:label:moreinfo' => 'més informació',
	'admin:plugins:label:version' => 'Versió',
	'admin:plugins:label:location' => 'Ubicació',
	'admin:plugins:label:contributors' => 'Col·laboradors',
	'admin:plugins:label:contributors:name' => 'Nom',
	'admin:plugins:label:contributors:email' => 'Correu Electrònico',
	'admin:plugins:label:contributors:website' => 'Lloc Web',
	'admin:plugins:label:contributors:username' => 'Nom d\'Usuari de la Comunitat',
	'admin:plugins:label:contributors:description' => 'Descripció completa',
	'admin:plugins:label:dependencies' => 'Dependències',

	'admin:plugins:warning:unmet_dependencies' => 'Aquest plugin té dependències desconegudes i no s\'activarà. Consulteu les dependències avall de més informació',
	'admin:plugins:warning:invalid' => '%s no es un plugin Elgg vàlid. Visiteu <a href=\'http://docs.elgg.org/Invalid_Plugin\'>la Documentació Elgg</a> per a consells de solució de problemes',
	'admin:plugins:warning:invalid:check_docs' => 'Mira <a href=\'http://learn.elgg.org/en/stable/appendix/faqs.html\'>la documentació d\'Elgg</a> on trobaràs consells de resolució de problemes.',
	'admin:plugins:cannot_activate' => 'no es pot activar',
	'admin:plugins:cannot_deactivate' => 'no es pot desactivar',
	'admin:plugins:already:active' => 'Els plugins seleccionats ja estàn actius.',
	'admin:plugins:already:inactive' => 'Els plugins seleccionats ja no estàn actius',

	'admin:plugins:set_priority:yes' => "Reordenar %s",
	'admin:plugins:set_priority:no' => "No es pot reordenar %s",
	'admin:plugins:set_priority:no_with_msg' => "No s'ha pogut reordenar %s. Erro: %s",
	'admin:plugins:deactivate:yes' => "Desactivar %s",
	'admin:plugins:deactivate:no' => "No es pot desactivar %s",
	'admin:plugins:deactivate:no_with_msg' => "No es pot desactivar %s. Error: %s",
	'admin:plugins:activate:yes' => "Activat %s",
	'admin:plugins:activate:no' => "No es pot activar %s",
	'admin:plugins:activate:no_with_msg' => "No es pot activar %s. Error: %s",
	'admin:plugins:categories:all' => 'Totes les categoríes',
	'admin:plugins:plugin_website' => 'Lloc del plugin',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versió %s',
	'admin:plugin_settings' => 'Configuració del plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'El plugin es troba actiu, pero posseeix dependències desconegudes. Pot ser que es troben problemes amb el seu funcionament. Visita el link \'més informació\' avall per a detalls',

	'admin:plugins:dependencies:type' => 'Tipus',
	'admin:plugins:dependencies:name' => 'Nom',
	'admin:plugins:dependencies:expected_value' => 'Valor de Test',
	'admin:plugins:dependencies:local_value' => 'Valor Actual',
	'admin:plugins:dependencies:comment' => 'Comentari',

	'admin:statistics:description' => "Aquest és un resum de les estadístiques del lloc. Si necesites estadístiques més avançades, hi ha disponible una funcionalitat d'administració profesional",
	'admin:statistics:opt:description' => "Veure informació estadística sobre usuaris i objectes en el lloc",
	'admin:statistics:opt:linktext' => "Veure estadístiques..",
	'admin:statistics:label:basic' => "Estadístiques bàsiques del lloc",
	'admin:statistics:label:numentities' => "Entitats del lloc",
	'admin:statistics:label:numusers' => "Cantitat d'usuaris",
	'admin:statistics:label:numonline' => "Cantitat d'usuaris connectats",
	'admin:statistics:label:onlineusers' => "Usuaris connectats en aquest moment",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Versió d'Elgg",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versió",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Servidor Web',
	'admin:server:label:server' => 'Servidor',
	'admin:server:label:log_location' => 'Localització dels registres',
	'admin:server:label:php_version' => 'Versió de PHP',
	'admin:server:label:php_ini' => 'Ubicació del arxiu PHP ini',
	'admin:server:label:php_log' => 'Registres de PHP',
	'admin:server:label:mem_avail' => 'Memòria disponible',
	'admin:server:label:mem_used' => 'Memòria gastada',
	'admin:server:error_log' => "Registre d'errors del servidor Web",
	'admin:server:label:post_max_size' => 'Tamany màxim de les peticions POST',
	'admin:server:label:upload_max_filesize' => 'Tamany màxim de les pujades',
	'admin:server:warning:post_max_too_small' => '(Nota: post_max_size deu ser major que el tamany indicat aquí per habilitar les pujades)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache no està configurat en aquest servidor o encara no ha estat configurat en Elgg config.
		Per a millorar el rendiment, es recomana activar i configurar memcache.
	',

	'admin:user:label:search' => "Trobar usuaris:",
	'admin:user:label:searchbutton' => "Cercar",

	'admin:user:ban:no' => "No pots bloquejar a l'usuari",
	'admin:user:ban:yes' => "Usuari bloquejat",
	'admin:user:self:ban:no' => "No pots bloquejar-te a tú mateix",
	'admin:user:unban:no' => "No pots desbloquejar al usuari",
	'admin:user:unban:yes' => "Usuari desbloquejat",
	'admin:user:delete:no' => "No pots eliminar al usuari",
	'admin:user:delete:yes' => "L'usuari %s ha estat eliminat",
	'admin:user:self:delete:no' => "No pots eliminar-te a tú mateix",

	'admin:user:resetpassword:yes' => "Contrasenya restablescuda, es notificará al usuari",
	'admin:user:resetpassword:no' => "No es pot restablir la contrasenya",

	'admin:user:makeadmin:yes' => "L'usuari és ara un administrador",
	'admin:user:makeadmin:no' => "No s'ha pogut establir a l'usuari com a administrador",

	'admin:user:removeadmin:yes' => "L'usuari ja no és administrador",
	'admin:user:removeadmin:no' => "No es poden llevar els privilegis d'administrador d'aquest usuari",
	'admin:user:self:removeadmin:no' => "No pots llevar els teus propis privilegis d'administrador",

	'admin:appearance:menu_items' => 'Elements del Menú',
	'admin:menu_items:configure' => 'Configurar els elements del menú principal',
	'admin:menu_items:description' => 'Selecciona quins elements del menú desitja mostrar com enllaços favorits. Els items no utilitzats es trobarán en el item \'Més\' al final de la llista',
	'admin:menu_items:hide_toolbar_entries' => 'Llevar enllaços del menú de la barra de ferramentes?',
	'admin:menu_items:saved' => 'Elements del menú desats',
	'admin:add_menu_item' => 'Agregar un element del menú personalitzat',
	'admin:add_menu_item:description' => 'Completa el nom per a mostrar i la direció url per agregar un element de menú personalitzat',

	'admin:appearance:default_widgets' => 'Widgets per defecte',
	'admin:default_widgets:unknown_type' => 'Tipus de widget desconegut',
	'admin:default_widgets:instructions' => 'Agregar, llevar, moure i configurar els widgets per defecte en la página de widget seleccionada',

	'admin:robots.txt:instructions' => "Editar el robots.txt d'aquest lloc a continuació",
	'admin:robots.txt:plugins' => "Plugins estàn agregant el següent a l'arxiu robots.txt",
	'admin:robots.txt:subdir' => "La ferramenta de robots.txt no funcionará perquè Elgg està instal·lat en un sub-directori",
	'admin:robots.txt:physical' => "La ferramenta robots.txt no funcionarà perquè hi ha un arxiu físic robots.txt",

	'admin:maintenance_mode:default_message' => 'El lloc no està disponible per manteniment',
	'admin:maintenance_mode:instructions' => 'El Mode de Manteniment solament deu ser utilitzat per a actualitzacions i altres canvis d\'importància en el lloc.
Quan aquest mode està activat, solament els administradors poden ingressar i vore el lloc',
	'admin:maintenance_mode:mode_label' => 'Mode de Manteniment',
	'admin:maintenance_mode:message_label' => 'Missatge que es mostrarà als usuaris quan el mode de manteniment estiga activat',
	'admin:maintenance_mode:saved' => 'Les configuracions del mode de manteniment han estat desades',
	'admin:maintenance_mode:indicator_menu_item' => 'El lloc està en mode de manteniment',
	'admin:login' => 'Entrada d\'Administradors',

/**
 * User settings
 */

	'usersettings:description' => "El panell de configuració permet parametritzar les seves preferències personals, desde l'administració d'usuaris al comportament dels plugins. Selecciona una opció avall per començar",

	'usersettings:statistics' => "Les seues estadístiques",
	'usersettings:statistics:opt:description' => "Veure informació estadística d'usuaris i objectes en la xarxa",
	'usersettings:statistics:opt:linktext' => "Estadístiques del compte",
	
	'usersettings:statistics:login_history' => "Historial d'inici de sessió",
	'usersettings:statistics:login_history:date' => "Data",
	'usersettings:statistics:login_history:ip' => "Direcció IP",

	'usersettings:user' => "Les seues preferències",
	'usersettings:user:opt:description' => "Açò li permet establir les seues preferències",
	'usersettings:user:opt:linktext' => "Modificar les seues preferències",

	'usersettings:plugins' => "Ferramentes",
	'usersettings:plugins:opt:description' => "Preferències de Configuració per a les seues ferramentes actives",
	'usersettings:plugins:opt:linktext' => "Configure les seues ferramentes",

	'usersettings:plugins:description' => "Aquest panell li permet establir les seues preferènciespersonals per a les ferramentes habilitades per l'administrador del sistema",
	'usersettings:statistics:label:numentities' => "El seu contingut",

	'usersettings:statistics:yourdetails' => "Els seus detalls",
	'usersettings:statistics:label:name' => "Nom complet",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membre desde",
	'usersettings:statistics:label:lastlogin' => "Últim accés",

/**
 * Activity river
 */

	'river:all' => 'Activitat de tota la xarxa',
	'river:mine' => 'La meua Activitat',
	'river:owner' => 'Activitat de %s',
	'river:friends' => 'Activitat d\'Amics',
	'river:select' => 'Mostrar %s',
	'river:comments:more' => '%u més',
	'river:comments:all' => 'Veure tots els comentaris de %u',
	'river:generic_comment' => 'comentat en %s %s',

	'friends:widget:description' => "Mostra alguns dels teus amics",
	'friends:num_display' => "Cantitat d'amics a mostrar",
	'friends:icon_size' => "tamany de la icona",
	'friends:tiny' => "molt petit",
	'friends:small' => "petit",

/**
 * Icons
 */

	'icon:size' => "Tamany de la icona",
	'icon:size:topbar' => "Barra principal",
	'icon:size:tiny' => "Molt petit",
	'icon:size:small' => "Petit",
	'icon:size:medium' => "Mitjà",
	'icon:size:large' => "Gran",
	'icon:size:master' => "Original",

/**
 * Generic action words
 */

	'save' => "Desar",
	'reset' => 'Reiniciar',
	'publish' => "Publicar",
	'cancel' => "Cancel·lar",
	'saving' => "Desant..",
	'update' => "Actualitzar",
	'preview' => "Previsualitzar",
	'edit' => "Editar",
	'delete' => "Eliminar",
	'accept' => "Acceptar",
	'reject' => "Rebutjar",
	'decline' => "Declinar",
	'approve' => "Aprovar",
	'activate' => "Activar",
	'deactivate' => "Desactivar",
	'disapprove' => "Desaprovar",
	'revoke' => "Revocar",
	'load' => "Carregar",
	'upload' => "Pujar",
	'download' => "Descarregar",
	'ban' => "Bloquejar",
	'unban' => "Desbloquejar",
	'banned' => "Bloquejat",
	'enable' => "Habilitar",
	'disable' => "Deshabilitar",
	'request' => "Sol·licitud",
	'complete' => "Completa",
	'open' => 'Obrir',
	'close' => 'Tancar',
	'hide' => 'Amagar',
	'show' => 'Mostrar',
	'reply' => "Respondre",
	'more' => 'Més',
	'more_info' => 'Més informació',
	'comments' => 'Comentaris',
	'import' => 'Importar',
	'export' => 'Exportar',
	'untitled' => 'Sense títol',
	'help' => 'Ajuda',
	'send' => 'Enviar',
	'post' => 'Publicar',
	'submit' => 'Enviar',
	'comment' => 'Comentar',
	'upgrade' => 'Actualitzar',
	'sort' => 'Ordenar',
	'filter' => 'Filtrar',
	'new' => 'Nou',
	'add' => 'Afegir',
	'create' => 'Crear',
	'remove' => 'Eliminar',
	'revert' => 'Revertir',

	'site' => 'Lloc Web',
	'activity' => 'Activitat',
	'members' => 'Membres',
	'menu' => 'Menú;',

	'up' => 'Dalt',
	'down' => 'Baix',
	'top' => 'Cap de pàgina',
	'bottom' => 'Peu de pàgina',
	'right' => 'Dreta',
	'left' => 'Esquerra',
	'back' => 'Endarrere',

	'invite' => "Convidar",

	'resetpassword' => "Restablir contrasenya",
	'changepassword' => "Cambiar contrasenya",
	'makeadmin' => "Fer administrador",
	'removeadmin' => "Llevar administrador",

	'option:yes' => "Sí",
	'option:no' => "No",

	'unknown' => 'Desconegut',
	'never' => 'Mai',

	'active' => 'Actiu',
	'total' => 'Total',

	'ok' => 'OK',
	'any' => 'Qualsevol',
	'error' => 'Error',

	'other' => 'Altre',
	'options' => 'Opcions',
	'advanced' => 'Avançat',

	'learnmore' => "Click aquí per a veure més",
	'unknown_error' => 'Error desconegut',

	'content' => "contingut",
	'content:latest' => 'última activitat',
	'content:latest:blurb' => 'Alternativament, click aquí per a veure l\'últim contingut en tota la xarxa',

	'link:text' => 'veure link',

/**
 * Generic questions
 */

	'question:areyousure' => '¿Estàs segur?',

/**
 * Status
 */

	'status' => 'Estat',
	'status:unsaved_draft' => 'Borrador sense desar',
	'status:draft' => 'Borrador',
	'status:unpublished' => 'Sense Publicar',
	'status:published' => 'Publicat',
	'status:featured' => 'Destacat',
	'status:open' => 'Obert',
	'status:closed' => 'Tancat',

/**
 * Generic sorts
 */

	'sort:newest' => 'Més nou',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alfabètic',
	'sort:priority' => 'Prioridad',

/**
 * Generic data words
 */

	'title' => "Títol",
	'description' => "Descripció",
	'tags' => "Etiquetes",
	'all' => "Tot",
	'mine' => "Meu",

	'by' => 'per',
	'none' => 'res',

	'annotations' => "Anotacions",
	'relationships' => "Relacions",
	'metadata' => "Metadades",
	'tagcloud' => "Núbol de etiquetes",

	'on' => 'Habilitat',
	'off' => 'Deshabilitat',

/**
 * Entity actions
 */

	'edit:this' => 'Editar',
	'delete:this' => 'Eliminar',
	'comment:this' => 'Comentar',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Estàs segur de que vols eliminar aquest item?",
	'deleteconfirm:plural' => "Estàs segur de que desitjes eliminar aquestos items?",
	'fileexists' => "El fitxer ja s'ha pujat. Per reemplaçar-ho, selecciona:",

/**
 * User add
 */

	'useradd:subject' => 'Compte d\'usuari creat',
	'useradd:body' => '
 %s,
 
El seu compte d\'usuari ha estat creat en %s. Per a iniciar sessió visita:
 
 %s
 
E inicia sessió amb les següents credencials:
 
 Usuari: %s
 Contrasenya: %s
 
Una vegada autentificat, li recomanem que modifique la seua contrasenya.
 ',

/**
 * System messages
 */

	'systemmessages:dismiss' => "click per a tancar",


/**
 * Import / export
 */

	'importsuccess' => "Importació amb èxit",
	'importfail' => "Error al importar dades",

/**
 * Time
 */

	'friendlytime:justnow' => "ara",
	'friendlytime:minutes' => "fa %s minuts",
	'friendlytime:minutes:singular' => "fa un minut",
	'friendlytime:hours' => "fa %s hores",
	'friendlytime:hours:singular' => "fa una hora",
	'friendlytime:days' => "fa %s díes",
	'friendlytime:days:singular' => "ahir",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'friendlytime:future:minutes' => "en %s minuts",
	'friendlytime:future:minutes:singular' => "en un minut",
	'friendlytime:future:hours' => "en %s hores",
	'friendlytime:future:hours:singular' => "en una hora",
	'friendlytime:future:days' => "en %s díes",
	'friendlytime:future:days:singular' => "demà",

	'date:month:01' => 'Gener %s',
	'date:month:02' => 'Febrer %s',
	'date:month:03' => 'Març %s',
	'date:month:04' => 'Abril %s',
	'date:month:05' => 'Maig %s',
	'date:month:06' => 'Juny %s',
	'date:month:07' => 'Juliol %s',
	'date:month:08' => 'Agost %s',
	'date:month:09' => 'Septembre %s',
	'date:month:10' => 'Octubre %s',
	'date:month:11' => 'Novembre %s',
	'date:month:12' => 'Decembre %s',
	
	'date:month:short:01' => 'Gen %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Abr %s',
	'date:month:short:05' => 'Mai %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Ago %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dic %s',

	'date:weekday:0' => 'Diumenge',
	'date:weekday:1' => 'Dilluns',
	'date:weekday:2' => 'Dimarts',
	'date:weekday:3' => 'Dimecres',
	'date:weekday:4' => 'Dijous',
	'date:weekday:5' => 'Divendres',
	'date:weekday:6' => 'Dissabte',

	'date:weekday:short:0' => 'Diu',
	'date:weekday:short:1' => 'Dil',
	'date:weekday:short:2' => 'Dim',
	'date:weekday:short:3' => 'Dic',
	'date:weekday:short:4' => 'Dij',
	'date:weekday:short:5' => 'Div',
	'date:weekday:short:6' => 'Dis',

	'interval:minute' => 'Cada minut',
	'interval:fiveminute' => 'Cada cinc minuts',
	'interval:fifteenmin' => 'Cada quinze minuts',
	'interval:halfhour' => 'Cada mitja hora',
	'interval:hourly' => 'Cada hora',
	'interval:daily' => 'Diari',
	'interval:weekly' => 'Semanal',
	'interval:monthly' => 'Mensual',
	'interval:yearly' => 'Anual',
	'interval:reboot' => 'Cada reinici',

/**
 * System settings
 */

	'installation:sitename' => "El nom del lloc web:",
	'installation:sitedescription' => "Breu descripció del lloc web (opcional):",
	'installation:wwwroot' => "URL del lloc web:",
	'installation:path' => "El path complet a la instal·lació d'Elgg:",
	'installation:dataroot' => "El path complet al directori de dades:",
	'installation:dataroot:warning' => "Deus crear aquest directori manualment. Deus trobar-te en un directori diferent al de la instal·lació d'Elgg",
	'installation:sitepermissions' => "Permissos d'accés per defecte:",
	'installation:language' => "Llenguatge per defecte per al lloc web:",
	'installation:debug' => "El mode Debug proveeix informació extra que pot utilitzar-se per a evaluar eventualitats. Pot alentir el funcionament del sistema i deu de utilitzar-se solament quan es detecten problemes:",
	'installation:debug:label' => "Nivell del registre:",
	'installation:debug:none' => 'Desactivar mode Debug (recomanat)',
	'installation:debug:error' => 'Mostrar solament errors crítics',
	'installation:debug:warning' => 'Mostrar solament alertes crítiques',
	'installation:debug:notice' => 'Mostrar tots els errors, alertes e informacions de events',
	'installation:debug:info' => 'Registrar tot',

	// Walled Garden support
	'installation:registration:description' => 'El registre d\'usuaris es troba habilitat per defecte. Pot deshabilitar-lo per a impedir que nous usuaris es registren per si mateixos',
	'installation:registration:label' => 'Permetre el registre de nous usuaris',
	'installation:walled_garden:description' => 'Habilitar al lloc web per a executar-se com una xarxa privada. Açò impedirà a usuaris no registrats visualitzar qualsevol pàgina del lloc web, exceptuant les establescudes com a públiques',
	'installation:walled_garden:label' => 'Restringir pàgines a usuaris registrats',

	'installation:view' => "Ingresse la vista que es visualitzará per defecte en el lloc web o deixe açò en blanc per a la vista per defecte (si tens dubtes, deixeu-ho com per defecte):",

	'installation:siteemail' => "Direcció d'Email del lloc web (utilitzada per a enviar correus electrònics desde el sistema):",
	'installation:default_limit' => "Nombre per defecte d'elements per pàgina",

	'admin:site:access:warning' => "Les modificacions en el control d'accessos solament tindrá impacte en els accessos futurs",
	'installation:allow_user_default_access:description' => "Si es selecciona, se'ls permetrà als usuaris establir el seu propi nivell d'accès per defecte que pot sobreescriure els nivells d'accès del sistema",
	'installation:allow_user_default_access:label' => "Permetre l'accès per defecte dels usuaris",

	'installation:simplecache:description' => "La caché simple aumenta el rendiment emmagatzemant contingut estàtic, com fulles CSS i arxius JavaScript. Normalment es desitja tindre-la activada",
	'installation:simplecache:label' => "Utilitzar caché simple (recomanat)",

	'installation:cache_symlink:description' => "L'enllaç simbòlic al directori de caché simple permet que el servidor serveixi vistes estàtiques sense pasar pel motor, lo qual millora consideràblement el rendiment i redueix la càrrega del servidor",
	'installation:cache_symlink:label' => "Utilitzar un enllaç simbòlic per al directori de caché simple (recomanat)",
	'installation:cache_symlink:warning' => "S'ha creat el enllaç simbòlic. Si, per algun motiu, vols llevar l'enllaç, esborra l'enllaç simbòlic al directori del teu servidor",
	'installation:cache_symlink:paths' => 'L\'enllaç simbòlic correctament configurat deu vincular-se desde <i>%s</i> a <i>%s</i>',
	'installation:cache_symlink:error' => "No es pot generar l'enllaç simbòlic per la configuració del vostre servidor. Si us plau, consulteu el manual per a crear manualment l'enllaç simbòlic",

	'installation:minify:description' => "El simple caché pot també millorar el rendiment perquè comprimeix els arxius JavaScript i CSS. (Requereix que simple caché estiga habilitat.)",
	'installation:minify_js:label' => "Comprimir JavaScript (recomanat)",
	'installation:minify_css:label' => "Comprimir CSS (recomanat)",

	'installation:htaccess:needs_upgrade' => "Deu actualitzar l'arxiu .htaccess per a que la ruta s'injecte en el paràmetre GET __elgg_uri (pots utilitzar install/config/htaccess.dist com a guía)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg no pot connectar-se a sí mateix per a posar a prova les regles de substitució. Assegureu-vos de que «curl» està funcionant, i de que no existeixen restriccions per direcció IP que impedeixin les connexions locals al propi servidor (localhost).",

	'installation:systemcache:description' => "La caché del sistema decrementa el temps de càrrega del motor d'Elgg cacheant dades a fitchers.",
	'installation:systemcache:label' => "Gastar la caché del sistema (recomanat)",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caché',
	'admin:legend:content_access' => 'Accés del Contingut',
	'admin:legend:site_access' => 'Accés del Lloc Web',
	'admin:legend:debug' => 'Depuració i registre',

	'upgrading' => 'Actualitzant..',
	'upgrade:core' => 'La instal·lació d\'Elgg ha estat actualitzada',
	'upgrade:unlock' => 'Desbloquejar actualització',
	'upgrade:unlock:confirm' => "La base de dades està bloquejada per una altra actualització. Executar actualitzacions concurrents és perillós. Solament hauries de continuar si saps que no hi ha una altra actualització executant-se. Desbloquejar?",
	'upgrade:locked' => "No es pot actualitzar. Una altra actualització està executant-se. Per a lliberar el bloqueig d'actualitzacions, visita la secció d'Administració.",
	'upgrade:unlock:success' => "Desbloqueig d'Actualització amb èxit.",
	'upgrade:unable_to_upgrade' => 'No es pot actualitzar',
	'upgrade:unable_to_upgrade_info' =>
		'Aquesta versió no es pot actualitzar degut a què s\'han detectat vistes legades d\'altres versions en el directori de les vistes del core d\'Elgg. Aquestes vistes son obsoletes i tenen que ser eliminades per al correcte funcionament d\'Elgg. Si no has fet canvis en el core d\'Elgg, pots simplement eliminar el directori de vistes i reemplaçar-lo amb el citat directori de un dels paquets més recents d\'Elgg, descarregant-lo de <a href="http://elgg.org">elgg.org</a>.<br /><br />

		Si necessites instruccions detallades, si us plau visita la   <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
		documentació d\'Actualització d\'Elgg</a>.  Si necessites suport, escriu describint el teu problema en els
		<a href="http://community.elgg.org/pg/groups/discussion/">Foros de Suport de la Comunitat</a>.',

	'update:twitter_api:deactivated' => 'La API de Twitter (anteriorment Twitter Service) s\'ha desactivat durant l\'actualització. Si us plau activeu-la manualment si es requereix',
	'update:oauth_api:deactivated' => 'La API de OAuth (anteriorment OAuth Lib) s\'ha desactivat durant l\'actualització. Si us plau activeu-la manualment si es requereix',
	'upgrade:site_secret_warning:moderate' => "Te recomanem que regeneres la clau del teu lloc web per a millorar la seguretat del sistema. Veure Configuració > Preferències > Avançat",
	'upgrade:site_secret_warning:weak' => "Te recomanem fortament que regeneres la clau del teu lloc web per a millorar la seguretat del sistema. Veure Configuració > Preferències > Avançat",

	'deprecated:function' => '%s() ha quedat obsoleta per %s()',

	'admin:pending_upgrades' => 'Aquest lloc té actualitzacions pendents que requereixen la teua atenció immediata',
	'admin:view_upgrades' => 'Veure actualitzacions pendents',
	'item:object:elgg_upgrade' => 'Actualitzacions del lloc',
	'admin:upgrades:none' => 'La teua instal·lació està al día!',

	'upgrade:item_count' => 'Hi ha <b>%s</b> elements que és necessari actualitzar.',
	'upgrade:warning' => '<b>Avís:</b> ¡En un lloc web gran aquesta actualització pot comportar un temps significatiu!',
	'upgrade:success_count' => 'Actualitzat:',
	'upgrade:error_count' => 'Errors:',
	'upgrade:river_update_failed' => 'No ha sigut possible actualitzar l\'entrada del River per al element amb identificador «%s».',
	'upgrade:timestamp_update_failed' => 'No ha sigut possible actualitzar els temps del element amb identificador «%s».',
	'upgrade:finished' => 'S\'ha completat l\'actualització.',
	'upgrade:finished_with_errors' => '<p>Han ocorregut errors durant l\'actualització. Actualitze la pàgina i probe a executar l\'actualització de nou.</p></p><br />
Si el error es repiteix, cerca la causa en el registre d\'errors del servidor. Pots cercar ajuda per a solventar el problema en el <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grup d\'assistència tècnica</a> de la comunitat d\'Elgg.</p>',

	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Alinear columnes GUID de la base de dades',
	
/**
 * Welcome
 */

	'welcome' => "Benvingut",
	'welcome:user' => 'Benvingut %s',

/**
 * Emails
 */

	'email:from' => 'De',
	'email:to' => 'Per a',
	'email:subject' => 'Assumpte',
	'email:body' => 'Cos',

	'email:settings' => "Configuració d'Email",
	'email:address:label' => "Direcció d'Email",

	'email:save:success' => "Noves direccions de correu desades.",
	'email:save:fail' => "No s'ha pogut desar la nova direcció d'Email",

	'friend:newfriend:subject' => "%s t'ha afegit com a amic!",
	'friend:newfriend:body' => "%s t'ha afegit com a amic!
 
Per a visualitzar el seu perfil fes click aquí:
 
 %s
 
Si us plau, no contestes a aquest correu electrònic",

	'email:changepassword:subject' => "Contrasenya canviada!",
	'email:changepassword:body' => "Hola %s,

La teua contrasenya ha estat canviada.",

	'email:resetpassword:subject' => "Contrasenya reestablerta!",
	'email:resetpassword:body' => "Hola %s,
 
La teva contrasenya ha estat reestablerta a: %s",

	'email:changereq:subject' => "Sol·licitud de canvi de contrasenya.",
	'email:changereq:body' => "Hola %s,

Algú (desde la direcció IP %s) ha sol·licitat un canvi de contrasenya per al teu compte.

Si has sigut tu, utilitza l'enllaç inferior. Si no és així, pots ignorar aquest missatge.

%s
",

/**
 * user default access
 */

	'default_access:settings' => "El teu nivell d'accés per defecte",
	'default_access:label' => "Accés per defecte",
	'user:default_access:success' => "El nivell d'accés per defecte ha estat desat",
	'user:default_access:failure' => "El nivell d'accés per defecte no ha pogut ser desat",

/**
 * Comments
 */

	'comments:count' => "%s comentaris",
	'item:object:comment' => 'Comentaris',

	'river:comment:object:default' => '%s ha comentat en %s',

	'generic_comments:add' => "Comentar",
	'generic_comments:edit' => "Editar comentari",
	'generic_comments:post' => "Publicar un comentari",
	'generic_comments:text' => "Comentar",
	'generic_comments:latest' => "Últims comentaris",
	'generic_comment:posted' => "S'ha publicat el seu comentari",
	'generic_comment:updated' => "El comentari ha estat canviat exitosament.",
	'generic_comment:deleted' => "S'ha llevat el seu comentari",
	'generic_comment:blank' => "Ho sentim, deus ingressar algun comentari abans de poder desar-lo",
	'generic_comment:notfound' => "Ho sentim. No hem trobat el comentari especificat.",
	'generic_comment:notfound_fallback' => "Ho sentim. No hem trobat el comentari especificat, però te hem redirigit a la pàgina on es va comentar.",
	'generic_comment:notdeleted' => "Ho sentim, no s'ha pogut eliminar el comentari",
	'generic_comment:failure' => "Un error no especificat ha ocorregut al desar el comentari.",
	'generic_comment:none' => 'Sense comentaris',
	'generic_comment:title' => 'Comentari de %s',
	'generic_comment:on' => '%s en %s',
	'generic_comments:latest:posted' => 'Ha publicat un',

	'generic_comment:email:subject' => 'Tens un nou comentari!',
	'generic_comment:email:body' => "Tens un nou comentari en el item '%s' de %s. Diu:
 
 
 %s
 
 
Per a contestar o veure el item original, fes click aquí:
 
 %s
 
Per a veure el perfil de %s, fes click aquí:
 
 %s
 
Si us plau, no contesteu a aquest correu",

/**
 * Entities
 */

	'byline' => 'Per %s',
	'byline:ingroup' => 'en el grup %s',
	'entity:default:strapline' => 'Creat %s per %s',
	'entity:default:missingsupport:popup' => 'Aquesta entitat no pot mostrar-se correctament. Açò pot ser degut a que el suport provist per un plugin ja no es troba instal·lat',

	'entity:delete:item' => 'Element',
	'entity:delete:item_not_found' => 'No s\'ha trobat l\'element',
	'entity:delete:permission_denied' => 'No tens permisos per a esborrar aquest element',
	'entity:delete:success' => '%s s\'ha esborrat.',
	'entity:delete:fail' => '%s no s\'ha pogut esborrar.',

	'entity:can_delete:invaliduser' => 'No es pot comprovar canDelete() per al user_guid [%s] perquè l\'usuari no existeix.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'En el formulari falten __token o camps __ts',
	'actiongatekeeper:tokeninvalid' => "La pàgina que estaves navegant ha expirat. Si us plau, proveu de nou.",
	'actiongatekeeper:timeerror' => 'La pàgina que estaves navegant ha expirat. Si us plau, refresqueu la pàgina e intenteu-ho novament',
	'actiongatekeeper:pluginprevents' => 'Ho sentim. No s\'ha pogut enviar el formulari per motius desconeguts.',
	'actiongatekeeper:uploadexceeded' => 'El tamany del(els) arxiu(s) supera el màxim establescut',
	'actiongatekeeper:crosssitelogin' => "Ho sentim, accedir desde un domini diferent no està permès. Si us plau, intenteu-ho de nou.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'i, el, aleshores, però, de ell, de ella, ell, u, no, a més, alrededor, ara, per tant, malgrat això, encara, igualment, d\'una altra manera, per tant, invèrsament, més aviat, consequentment, a més, no obstant, en canvi, mentrestant, d\'acord amb, açò, pareix, què, qui, quins, qui sigui, qui sigui qui',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Fallada al contactar %s. Pot ser que experimentes problemes al desar contingut. Si us plau refresca la pàgina.',
	'js:security:token_refreshed' => 'La connexió a %s ha estat restaurada!',
	'js:lightbox:current' => "imatge %s de %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Creat amb Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Àfar",
	"ab" => "Abkhaz",
	"af" => "Afrikaans",
	"am" => "Amhàric",
	"ar" => "Àrab",
	"as" => "Assamès",
	"ay" => "Aimara",
	"az" => "Àzeri",
	"ba" => "Baixkir",
	"be" => "Bielorús",
	"bg" => "Búlgar",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengalí; Bangla",
	"bo" => "Tibetà",
	"br" => "Bretó",
	"ca" => "Català",
	"cmn" => "Xinès Mandarí", // ISO 639-3
	"co" => "Cors",
	"cs" => "Txec",
	"cy" => "Gal·lès",
	"da" => "Danès",
	"de" => "Alemà",
	"dz" => "Dzongka",
	"el" => "Grec",
	"en" => "Anglès",
	"eo" => "Esperanto",
	"es" => "Castellá",
	"et" => "Estonià",
	"eu" => "Basc; Èuscar",
	"eu_es" => "Euskera (Espanya)",
	"fa" => "Persa",
	"fi" => "Finès; Finlandès",
	"fj" => "Fijià",
	"fo" => "Feroès",
	"fr" => "Francès",
	"fy" => "Frisó",
	"ga" => "Irlandès; Gaèlic irlandès",
	"gd" => "Gaèlic; Gaèlic escocès",
	"gl" => "Gallec",
	"gn" => "Guaraní",
	"gu" => "Gujarati",
	"he" => "Hebreu",
	"ha" => "Haussa",
	"hi" => "Hindi",
	"hr" => "Serbocroat",
	"hu" => "Hongarès",
	"hy" => "Armeni",
	"ia" => "Interlingua",
	"id" => "Indonesi; Malai",
	"ie" => "Interllengua",
	"ik" => "Esquimal; Inupiaq",
	//"in" => "Indonesian",
	"is" => "Islandès",
	"it" => "Italià",
	"iu" => "Esquimal; Inuktitut",
	"iw" => "Hebreu (obsolet)",
	"ja" => "Japonès",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanès",
	"ka" => "Georgià",
	"kk" => "Kazakh",
	"kl" => "Esquimal; kalaallisut",
	"km" => "khmer",
	"kn" => "Kannada",
	"ko" => "Coreà",
	"ks" => "caixmiri",
	"ku" => "kurd",
	"ky" => "kirguís",
	"la" => "Llatí",
	"ln" => "Lingala",
	"lo" => "Lao; Laosià",
	"lt" => "Lituà",
	"lv" => "Letó",
	"mg" => "Malgaix",
	"mi" => "Maori",
	"mk" => "Macedònic",
	"ml" => "Malaialam",
	"mn" => "Mongol; Khalkha",
	"mo" => "Moldau",
	"mr" => "Marathi",
	"ms" => "Malai",
	"mt" => "Maltès",
	"my" => "Birmà",
	"na" => "Nauruà",
	"ne" => "Nepalès",
	"nl" => "Holandès; Neerlandès",
	"no" => "Noruec",
	"oc" => "Occità",
	"om" => "Oromo",
	"or" => "Oriya",
	"pa" => "Panjabi",
	"pl" => "Polonès",
	"ps" => "Paixtu",
	"pt" => "Portuguès",
	"pt_br" => "Portuguès (Brasil)",
	"qu" => "Quítxua",
	"rm" => "Retoromànic",
	"rn" => "Rundi",
	"ro" => "Romanès",
	"ro_ro" => "Romanès (Romanía)",
	"ru" => "Rus",
	"rw" => "Ruanda",
	"sa" => "Sànscrit",
	"sd" => "Sindhi",
	"sg" => "Sango",
	"sh" => "Serbocroat",
	"si" => "Singalès",
	"sk" => "Eslovac",
	"sl" => "Eslovè; Eslovènic",
	"sm" => "Samoà",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanès",
	"sr" => "Serbi",
	"sr_latin" => "Serbi (Llatí)",
	"ss" => "Swazi",
	"st" => "Sotho del sud",
	"su" => "Sondanès",
	"sv" => "Suec",
	"sw" => "Suahili",
	"ta" => "Tàmil",
	"te" => "Telugu",
	"tg" => "Tadjik",
	"th" => "Tailandès",
	"ti" => "Tigrinya",
	"tk" => "Turcman",
	"tl" => "Tagàlog",
	"tn" => "Tswana",
	"to" => "Tongalès",
	"tr" => "Turc",
	"ts" => "Tsonga",
	"tt" => "Tàrtar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "ucraïnès",
	"ur" => "Urdú",
	"uz" => "Uzbek",
	"vi" => "Vietnamita",
	"vo" => "volapük",
	"wo" => "Wòlof",
	"xh" => "Xosa",
	//"y" => "Yiddish",
	"yi" => "judeoalemany; jiddisch",
	"yo" => "Ioruba",
	"za" => "Zhuang",
	"zh" => "Xinès",
	"zh_hans" => "Xinès Simplificat",
	"zu" => "Zulu",

	"field:required" => 'Requerit',

);
