<?php

	$spanish = array(

	    /*
	     * This translation is still incomplete
         * Version 0.2
	     * September 2nd-2008
	     * TODO
	     * Review for orthographic mistakes
	     * Translate plug-in (in the respective files)
	     */
		/**
		 * Sites
		 */
	
			'item:site' => 'Sitios',
	
		/**
		 * Sessions
		 */
			
			'login' => "Ingresar",
			'loginok' => "Acceso Autorizado.",
			'loginerror' => "Acceso Denegado. Revisa los datos e intenta nuevamente.",
	
			'logout' => "Salir",
			'logoutok' => "Haz terminado tu sesión",
			'logouterror' => "Error en el proceso de salida.",
	
		/**
		 * Errors
		 */
			'exception:title' => "Bienvenido a Elgg.",
	
			'InstallationException:CantCreateSite' => "No puedo crear un sitio con el nombre de la credencial:%s, Url: %s",
		
			'actionundefined' => "La acción requerida (%s) no esta definida en el sistema.",
			'actionloggedout' => "Perdón, no puedes realizar estar acción mientras no est√©s fuera del servicio.",
	
			'notfound' => "El recurso que se pide no fue encontrado, o no tienes los permisos necesarios.",
			
			'SecurityException:Codeblock' => "Acceso denegado para ejecutar bloque de código privilegiado",
			'DatabaseException:WrongCredentials' => "Elgg no pudo conectarse a la base de datos usando los datos que completaste %s@%s (pw: %s).",
			'DatabaseException:NoConnect' => "Elgg no pudo seleccionar la base de datos '%s', por favor checa que la base esta creada y se puede accesar.",
			'SecurityException:FunctionDenied' => "El acceso a la función privilegiada '%s' no está permitida.",
			'DatabaseException:DBSetupIssues' => "Hay un número de cuestiones: ",
			'DatabaseException:ScriptNotFound' => "Elgg no pudo encontrar el script de la base de datos en %s.",
			
			'IOException:FailedToLoadGUID' => "Fallo al leer el nuevo %s del GUID:%d",
			'InvalidParameterException:NonElggObject' => "Error al pasar un objeto incorrecto a un constructor ElggObject!",
			'InvalidParameterException:UnrecognisedValue' => "Se paso incorrectamente un valor irreconocible a un constructor.",
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d no es un valido %s",
			
			'PluginException:MisconfiguredPlugin' => "%s es un plugin mal configurado.",
			
			'InvalidParameterException:NonElggUser' => "Error al pasar un objeto incorrecto a un constructor ElggUser!",
			
			'InvalidParameterException:NonElggSite' => "Error al pasar un objeto incorrecto a un constructor ElggSite!",
			
			'InvalidParameterException:NonElggGroup' => "Error al pasar un objeto incorrecto a un constructor ElggGroup!",
	
			'IOException:UnableToSaveNew' => "No se puede salvar %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID no ha sido especificado durante la exportación, esto nunca debería pasar.",
			'InvalidParameterException:NonArrayReturnValue' => "La funcion de la serialización de la entidad paso un parámetro que no es un arreglo",
			
			'ConfigurationException:NoCachePath' => "El path del cache no esta especificado!",
			'IOException:NotDirectory' => "%s no es un directorio.",
			
			'IOException:BaseEntitySaveFailed' => "No se puede salvar la información de entidad base del nuevo objeto!",
			'InvalidParameterException:UnexpectedODDClass' => "La función import() paso una clase ODD no esperada",
			'InvalidParameterException:EntityTypeNotSet' => "El tipo de entidad debe estar definido.",
			
			'ClassException:ClassnameNotClass' => "%s no es un %s.",
			'ClassNotFoundException:MissingClass' => "La clase '%s' no fue encontrada, plugin esta perdido?",
			'InstallationException:TypeNotSupported' => "El tipo %s no esta soportado. Esto indica un error en la instalación, muy probablemente causado por una actualización incompleta.",

			'ImportException:ImportFailed' => "No se puede importar el elemento %d",
			'ImportException:ProblemSaving' => "Hubo un problema salvando %s",
			'ImportException:NoGUID' => "Se creo una nueva entidad que no tiene GUID, esto no debería de pasar.",
			
			'ImportException:GUIDNotFound' => "La entidad '%d' no pudo ser encontrada.",
			'ImportException:ProblemUpdatingMeta' => "Hubo un problema actualizando '%s' en la entidad '%d'",
			
			'ExportException:NoSuchEntity' => "No existe la entidad GUID:%d", 
			
			'ImportException:NoODDElements' => "No hay elementos OpenDD encontrados en los datos importados, la importación falló.",
			'ImportException:NotAllImported' => "No todos los elementos fueron importados.",
			
			'InvalidParameterException:UnrecognisedFileMode' => "Modo de archivo no reconocido '%s'",
			'InvalidParameterException:MissingOwner' => "¡Todos los archivos deben de tener un dueño!",
			'IOException:CouldNotMake' => "No puedo hacer %s",
			'IOException:MissingFileName' => "Debe especificar un nombre antes de abrir un archivo.",
			'ClassNotFoundException:NotFoundNotSavedWithFile' => "No se encontró Filestore o la clase no fue salvada con el archivo!",
			'NotificationException:NoNotificationMethod' => "No se especificó un m√©todo de notificación.",
			'NotificationException:NoHandlerFound' => "No se encontró la liga para '%s' o no puede ser llamada.",
			'NotificationException:ErrorNotifyingGuid' => "Hubo un error al notificar %d",
			'NotificationException:NoEmailAddress' => "No se pudo obtener una dirección de correo electrónico para GUID:%d",
			'NotificationException:MissingParameter' => "No se encuentra el parametro requerido, '%s'",
			
			'DatabaseException:WhereSetNonQuery' => "El set Where no contiene WhereQueryComponent",
			'DatabaseException:SelectFieldsMissing' => "Los campos no se encuentran en la requisición de base de datos estilo Select",
			'DatabaseException:UnspecifiedQueryType' => "Tipo de requisición de base de datos no reconocida o no especificada.",
			'DatabaseException:NoTablesSpecified' => "No hay tablas especificadas para la requisición de base de datos.",
			'DatabaseException:NoACL' => "No se proveyo de control de acceso en la requisición de base de datos",
			
			'InvalidParameterException:NoEntityFound' => "No se encontró la entidad, no existe o no tiene acceso a ella.",
			
			'InvalidParameterException:GUIDNotFound' => "GUID:%s no pudo ser encontrada, o no tiene acceso a ella.",
			'InvalidParameterException:IdNotExistForGUID' => "Disculpa, '%s' no existe para guid:%d",
			'InvalidParameterException:CanNotExportType' => "Disculpa, no se como exportar '%s'",
			'InvalidParameterException:NoDataFound' => "No se pudo encontrar ningun dato.",
			'InvalidParameterException:DoesNotBelong' => "No pertenece a la entidad.",
			'InvalidParameterException:DoesNotBelongOrRefer' => "No pertenece a la entidad o se refiere a la entidad.",
			'InvalidParameterException:MissingParameter' => "Parámetro no encontrado, necesita proveer un GUID.",
			
			'SecurityException:APIAccessDenied' => "Disculpa, el acceso al API ha sido deshabilitado por el administrador.",
			'SecurityException:NoAuthMethods' => "No se encontraron m√©todos de autentificacion que puedan autentificar esta requisicion de API.",
			'APIException:ApiResultUnknown' => "El resultado del API es de tipo desconocido, esto nunca debe ocurrir.", 
			
			'ConfigurationException:NoSiteID' => "No ID del sitio ha sido especificado.",
			'InvalidParameterException:UnrecognisedMethod' => "M√©todo de llamada no reconocido '%s'",
			'APIException:MissingParameterInMethod' => "Parámetro no encontrado %s en el m√©todo %s",
			'APIException:ParameterNotArray' => "%s no parece ser un arreglo.",
			'APIException:UnrecognisedTypeCast' => "Tipo no reconocido en el computo %s de la variable '%s' en el m√©todo '%s'",
			'APIException:InvalidParameter' => "Se encontró un parametro invalido para '%s' en el m√©todo '%s'.",
			'APIException:FunctionParseError' => "%s(%s) tiene un error de parseo.",
			'APIException:FunctionNoReturn' => "%s(%s) no regreso un valor.",
			'SecurityException:AuthTokenExpired' => "El token de autentificación esta perdido, o es invalido o expiro.",
			'CallException:InvalidCallMethod' => "%s debe ser llamado usando '%s'",
			'APIException:MethodCallNotImplemented' => "El m√©todo de llamada '%s' no ha sido implementado.",
			'APIException:AlgorithmNotSupported' => "El algoritmo '%s' no está soportado o no se habilitó.",
			'ConfigurationException:CacheDirNotSet' => "El directorio del Cache 'cache_path' no está definido.",
			'APIException:NotGetOrPost' => "El metodo de requisición debe ser GET o POST",
			'APIException:MissingAPIKey' => "No se encuentra el encabezado de HTTP X-Elgg-apikey",
			'APIException:MissingHmac' => "No se encuentra el encabezado X-Elgg-hmac",
			'APIException:MissingHmacAlgo' => "No se encuentra el encabezado X-Elgg-hmac-algo",
			'APIException:MissingTime' => "No se encuentra el encabezado X-Elgg-time",
			'APIException:TemporalDrift' => "X-Elgg-time se encuentra muy alejado en el pasado o futuro. Hubo un fallo de Epoch.",
			'APIException:NoQueryString' => "No hubo datos en la línea de requisición de base de datos",
			'APIException:MissingPOSTHash' => "No se encuentra el encabezado X-Elgg-posthash",
			'APIException:MissingPOSTAlgo' => "No se encuentra el encabezado X-Elgg-posthash_algo",
			'APIException:MissingContentType' => "No se encuentra el tipo de contenido para enviar los datos",
			'SecurityException:InvalidPostHash' => "El Hash de POST no es valido - Se espero %s pero se obtuvo %s.",
			'SecurityException:DupePacket' => "La firma del paquete ha sido vista.",
			'SecurityException:InvalidAPIKey' => "Llave del API invalida o no se encuentra.",
			'NotImplementedException:CallMethodNotImplemented' => "El metodo de llamada '%s' no se encuentra soportado en este momento.",
	
			'NotImplementedException:XMLRPCMethodNotImplemented' => "El m√©todo de llamada XML-RPC '%s' no esta implementado.",
			'InvalidParameterException:UnexpectedReturnFormat' => "La llamada al m√©todo '%s' regreso un resultado inesperado.",
			'CallException:NotRPCCall' => "La llamada no parece ser una llamada XML-RPC valida",
	
			'PluginException:NoPluginName' => "El nombre del plugin no pudo ser encontrado",
			
		/**
		 * User details
		 */

			'name' => "Nombre",
			'email' => "Dirección de correo electrónico",
			'username' => "Nombre de usuario",
			'password' => "Contraseña",
			'passwordagain' => "Contraseña (para verificar)",
			'admin_option' => "¬øHacer de este usuario un administrador?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Privado",
			'ACCESS_LOGGED_IN' => "Registrado",
			'ACCESS_PUBLIC' => "Público",
			'PRIVATE' => "Privado",
			'LOGGED_IN' => "Registrado",
			'PUBLIC' => "Público",
			'access' => "Acceso",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "Panel de Control",
			'dashboard:nowidgets' => "Su tablero de instrumentos es su puente hacia el sitio. Siga la liga a 'Editar página' para agregar Widgets que lleven registro del contenido y su existencia dentro del sistema.",

			'widgets:add' => 'Agregar Widgets a tu página',
			'widgets:add:description' => "Escoja las opciones de funcionalidad que quiera agregar a su página arrastrandolas desde el <b>Widget gallery</b> a la derecha, a cualquiera de las tres areas Widget abajo, y posicionelas donde le gustaria que aparecieran.

Para remover un Widget arrastrelo de regreso a la <b>Galeria de Widget </b>.",
			'widgets:position:fixed' => '(Posicion fija en la página)',
	
			'widgets' => "Widgets",
			'widget' => "Widget",
			'item:object:widget' => "Widgets",
			'layout:customise' => "Adaptar el esquema",
			'widgets:gallery' => "Galeria de Widgets",
			'widgets:leftcolumn' => "Widgets de la izquierda",
			'widgets:fixed' => "Posicion fija",
			'widgets:middlecolumn' => "Widgets centrales",
			'widgets:rightcolumn' => "Widgets de la derecha",
			'widgets:profilebox' => "Caja de perfil",
			'widgets:panel:save:success' => "Sus Widgets fueron salvados exitosamente.",
			'widgets:panel:save:failure' => "Hubo un problema salvando sus Widgets. Por favor intente de nuevo.",
			'widgets:save:success' => "El Widget fue salvado exitósamente.",
			'widgets:save:failure' => "No se pudo salvar el Widget. Por favor trate nuevamente.",
			
	
		/**
		 * Groups
		 */
	
			'group' => "Grupo", 
			'item:group' => "Grupos",
	
		/**
		 * Profile
		 */
	
			'profile' => "Perfil",
			'user' => "Usuario",
			'item:user' => "Usuarios",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "Tu perfil",
			'profile:user' => "El perfil de %s",
	
			'profile:edit' => "Editar perfil",
			'profile:editicon' => "Subir una nueva imagen al perfil",
			'profile:profilepictureinstructions' => "La fotografía del perfil es la imagen que se mostrara en tu página de perfil. <br /> Puedes cambiarla cuantas veces quieras. (Tipos de archivos permitidos: GIF, JPG o PNG)",
			'profile:icon' => "Imagen del perfil",
			'profile:createicon' => "Crea tu avatar",
			'profile:currentavatar' => "Avatar actual",
			'profile:createicon:header' => "Imagen del perfil",
			'profile:profilepicturecroppingtool' => "Herramienta para recortar la foto del perfil",
			'profile:createicon:instructions' => "Seleccione con el ratón y arrastre un cuadrado hacia abajo para ajustar como quiere su foto recortada. Una imagen preliminar de la foto recortada aparecera en la caja de la derecha. Cuando este satisfecho con la imagen, presione \"Crear su Avatar\". Esta imagen recortada sera usada en el sitio como su Avatar. ",
	
			'profile:editdetails' => "Editar detalles",
			'profile:editicon' => "Icono para editar el perfil",
	
			'profile:aboutme' => "Acerca de", 
			'profile:description' => "Descripción",
			'profile:briefdescription' => "Descripción breve",
			'profile:location' => "Ubicación",
			'profile:skills' => "Habilidades",  
			'profile:interests' => "Intereses", 
			'profile:contactemail' => "Correo Electrónico",
			'profile:phone' => "Teléfono",
			'profile:mobile' => "Celular",
			'profile:website' => "página web",

			'profile:river:update' => "%s actualizo su perfil",
			'profile:river:iconupdate' => "%s actualizo su icono del perfil",
	
		/**
		 * Profile status messages
		 */
	
			'profile:saved' => "Tu perfil fue satisfactoriamente guardado.",
			'profile:icon:uploaded' => "Tu imagen del perfil fue subida.",
	
		/**
		 * Profile error messages
		 */
	
			'profile:noaccess' => "No tiene permiso para editar este perfil.",
			'profile:notfound' => "Lo siento, no pudimos encontrar el perfil especificado.",
			'profile:cantedit' => "Lo siento, no tiene los permisos requeridos para editar este perfil.",
			'profile:icon:notfound' => "Lo siento, hubo un problema para subir la foto de su perfil.",
	
		/**
		 * Friends
		 */
	
			'friends' => "Amigos",
			'friends:yours' => "Tus amigos",
			'friends:owned' => "Los amigos de %s",
			'friend:add' => "Agregar amigos",
			'friend:remove' => "Eliminar amigos",
	
			'friends:add:successful' => "Has agregado exitosamente a %s como un amigo.",
			'friends:add:failure' => "No hemos podido agregar a %s como un amigo. Por favor intente de nuevo.",
	
			'friends:remove:successful' => "Has dado de baja exitosamente a %s de tu lista de amigos.",
			'friends:remove:failure' => "No pudimos remover a %s de tu lista de amigos. Por favor intenta de nuevo.",
	
			'friends:none' => "Este usuario no ha agregado a nadie en su lista todavía.",
			'friends:none:you' => "No has agregado a nadie en tu lista de amigos! Busca personas basado en lo que te interesa.",
	
			'friends:none:found' => "No se encontraron amigos.",
	
			'friends:of:none' => "Nadie hasta este momento ha agregado a este usuario como su amigo.",
			'friends:of:none:you' => "Nadie te ha agregado como su amigo todavía. Empieza a agregar contenido y completa tu perfil para que le permitas a los demas encontrarte!",
	
			'friends:of' => "Amigos de",
			'friends:of:owned' => "Gente que ha hecho a %s su amigo",

			 'friends:num_display' => "Número de amigos para mostrar",
			 'friends:icon_size' => "Tamaño del icono",
			 'friends:tiny' => "diminuto",
			 'friends:small' => "pequeño",
			 'friends' => "Amigos",
			 'friends:of' => "Amigos de",
			 'friends:collections' => "Colecciones de amigos",
			 'friends:collections:add' => "Colección de amigos nueva",
			 'friends:addfriends' => "Agregar amigos",
			 'friends:collectionname' => "Nombre de la colección de amigos",
			 'friends:collectionfriends' => "Amigos en la colección",
			 'friends:collectionedit' => "Editar esta colección",
			 'friends:nocollections' => "No tiene ninguna colección de amigos todavía.",
			 'friends:collectiondeleted' => "Su colección de amigos ha sido borrada.",
			 'friends:collectiondeletefailed' => "No ha sido posible borrar la colección de amigos. No cuenta con el permiso requerido, o algún otro problema ha ocurrido.",
			 'friends:collectionadded' => "Su colección de amigos fue creada exitosamente",
			 'friends:nocollectionname' => "Usted necesita darle a la colección de amigos un nombre antes de que pueda ser creada.",
		
	        'friends:river:created' => "%s agregó el Widget de amigos.",
	        'friends:river:updated' => "%s actualizó su Widget de amigos.",
	        'friends:river:delete' => "%s borró su Widget de amigos.",
	        'friends:river:add' => "%s agregó a alguien como su amigo.",
	
		/**
		 * Feeds
		 */
			'feed:rss' => 'Suscríbete al RSS',
			'feed:odd' => 'Syndicate OpenDD',
	
		/**
		 * River
		 */
			'river' => "River",			
			'river:relationship:friend' => 'es ahora amigo con',

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "La configuración del Plugin %s fueron salvados exitosamente.",
			'plugins:settings:save:fail' => "Hubo un problema salvando la configuración del Plugin %s.",
			'plugins:usersettings:save:ok' => "La configuracion de usuario para el Plugin %s fueron salvados exitosamente.",
			'plugins:usersettings:save:fail' => "Hubo un problema para salvar la configuración de usuario para el Plugin %s.",
			
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "Configuración de notificaciones",
			'notifications:methods' => "Por favor especifique que métodos quiere autorizar.",
	
			'notifications:usersettings:save:ok' => "La configuración de sus notificaciones se salvo exitosamente.",
			'notifications:usersettings:save:fail' => "Hubo un problema salvando la configuración de sus notificaciones.",
		/**
		 * Search
		 */
	
			'search' => "Buscar",
			'searchtitle' => "Buscar: %s",
			'users:searchtitle' => "Buscando usuarios: %s",
			'advancedsearchtitle' => "%s con resultados concordando con %s",
			'notfound' => "No se encontraron resultados.",
			'next' => "Siguiente",
			'previous' => "Anterior",
	
			'viewtype:change' => "Cambiar tipo de listado",
			'viewtype:list' => "Ver listado",
			'viewtype:gallery' => "Ver galeria",
	
			'tag:search:startblurb' => "Objetos con etiquetas concordando '%s':",

			'user:search:startblurb' => "Usuarios concordando con '%s':",
			'user:search:finishblurb' => "Para ver mas, siga esta liga.",
	
		/**
		 * Account
		 */
	
			'account' => "Cuenta",
			'settings' => "Configuración",
	
			'register' => "Registro",
			'registerok' => "Se ha registrado exitosamente para %s. Para activar su cuenta, por favor confirme su correo electronico siguiendo la liga que le enviamos en un correo electrónico a la cuenta que nos proporciono.",
			'registerbad' => "No ha podido ser registrado. El nombre de usuario puede existir con anterioridad, o puede ser que sus password no concuerden.",
			'registerdisabled' => "Los registros han sido deshabilitados por el administrador del sistema.",
	
			'registration:notemail' => 'The email address you provided does not appear to be a valid email address.',
			'registration:userexists' => 'That username already exists',
			'registration:usernametooshort' => 'Your username must be a minimum of 4 characters long.',
			'registration:passwordtooshort' => 'The password must be a minimum of 6 characters long.',
			'registration:dupeemail' => 'This email address has already been registered.',
			
			'adduser' => "Agregar usuario",
			'adduser:ok' => "Ha agregado exitosamente a un nuevo usuario.",
			'adduser:bad' => "El nuevo usuario no pudo ser creado.",
	
			'item:object:reported_content' => "Reported items",
			
			'user:set:name' => "Configuración de nombre de cuenta",
			'user:name:label' => "Su nombre",
			'user:name:success' => "Se cambio exitosamente su nombre en el sistema.",
			'user:name:fail' => "No se pudo cambiar su nombre en el sistema.",
	
			'user:set:password' => "Contraseña de su cuenta",
			'user:password:label' => "Su nueva contraseña",
			'user:password2:label' => "Su contraseña de nuevo",
			'user:password:success' => "Contraseña cambiada",
			'user:password:fail' => "No se pudo cambiar su contraseña en el sistema.",
			'user:password:fail:notsame' => "Las dos contraseñas no son las mismas!",
			'user:password:fail:tooshort' => "La contraseña es muy corta!",
	
			'user:set:language' => "Configuración de lenguaje",
			'user:language:label' => "Su lenguaje",
			'user:language:success' => "Su configuración de lenguaje ha sido actualizada.",
			'user:language:fail' => "Su configuración de lenguaje no pudo ser salvada.",
	
			'user:username:notfound' => 'No se encontró el nombre del usuario %s.',
	
			'user:password:lost' => 'Contraseña perdida',
			'user:password:resetreq:success' => 'Petición de nueva contraseña ha sido exitoso, se ha enviado un correo electrónico.',
			'user:password:resetreq:fail' => 'No se pudo obtener una nueva contraeña.',
	
			'user:password:text' => 'Para generar una nueva contraseña, de de alta su cuenta de usuario abajo. Le enviaremos la dirección de una página de verificación única vía correo electrónico. Siga la liga en ese correo electrónico y una nueva contrasena le sera enviada.',
	
		/**
		 * Administración
		 */

			'admin:configuration:success' => "Tu configuración ha sido guardada.",
			'admin:configuration:fail' => "Tu configuraciónn no pudo guardada.",
	
			'admin' => "Administración",
			'admin:description' => "El panel de administración te permite controlar todos los aspectos del sistema, desde gestión de usuarios al comportamiento de los plugins. Elige una upción abajo para comenzar.",
			
			'admin:user' => "Administración de usuario.",
			'admin:user:description' => "Este panel de administración te permite a controlar la configuración de usuario para tu sitio. Elige una opción abajo para comenzar.",
			'admin:user:adduser:label' => "Haz click aquí para añadir un usuario nuevo...",
			'admin:user:opt:linktext' => "Configurar usuarios...",
			'admin:user:opt:description' => "Configurar usuarios e información de cuenta... ",
			
			'admin:site' => "Administración del sitio",
			'admin:site:description' => "Este panel de administración de permite controlar una configuración global para tu sitio. Elige una opción abajo para comenzar.",
			'admin:site:opt:linktext' => "Configurar el sitio...",
			'admin:site:opt:description' => "Configurar los parámetros técnicos y no técnicos. ",
			
			'admin:plugins' => "Aministrar herramientas.",
			'admin:plugins:description' => "Este panel de administración te permite controlar y configurar las herramientas instaladas en tu sistema.",
			'admin:plugins:opt:linktext' => "Configurar herramientas...",
			'admin:plugins:opt:description' => "Configurar las herramientas instaladas en el sitio. ",
			'admin:plugins:label:author' => "Autor",
			'admin:plugins:label:copyright' => "Copyright",
			'admin:plugins:label:licence' => "Licencia",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "El plugin %s se deshabilitó con éxito.",
			'admin:plugins:disable:no' => "El plugin %s no se pudo deshabilitar.",
			'admin:plugins:enable:yes' => "El plugin %s se habilito con éxito.",
			'admin:plugins:enable:no' => "El plugin %s no se pudo habilitar.",
	
			'admin:statistics' => "Estadísticas",
			'admin:statistics:description' => "Esta es una vista general de las estadísticas en tu sitio. Si necesitar estadísticas más detalladas, hay una ventana de administración profesional disponible.",
			'admin:statistics:opt:description' => "Ver información estadística sobre usuarios y objetos en tu sitio.",
			'admin:statistics:opt:linktext' => "Ver estadísticas...",
			'admin:statistics:label:basic' => "Estadísticas básicas del sitio",
			'admin:statistics:label:numentities' => "Entidades en el sitio",
			'admin:statistics:label:numusers' => "Número de usuarios",
			'admin:statistics:label:numonline' => "Número de usuarios online",
			'admin:statistics:label:onlineusers' => "Usuarios online ahora",
			'admin:statistics:label:version' => "Versión de Elgg",
			'admin:statistics:label:version:release' => "Lanzamiento",
			'admin:statistics:label:version:version' => "Versión",
	
			'admin:user:label:search' => "Encontrar usuarios:",
			'admin:user:label:seachbutton' => "Buscar", 
	
			'admin:user:ban:no' => "No se puede banear al usuario",
			'admin:user:ban:yes' => "Usuario baneado.",
			'admin:user:unban:no' => "No se puede quitar el baneo al usuario",
			'admin:user:unban:yes' => "Se le quitó el baneo al usuario.",
			'admin:user:delete:no' => "No se puede eliminar al usuario",
			'admin:user:delete:yes' => "Usuario eliminado",
	
			'admin:user:resetpassword:yes' => "Contraseña reseteada, usuario notificado.",
			'admin:user:resetpassword:no' => "La contraseña no se pudo resetear.",
	
			'admin:user:makeadmin:yes' => "El usuario es ahora administrador.",
			'admin:user:makeadmin:no' => "No pudimos hacer a este usuario administrador.",
			
				/**
		 * Configuración de usuario
		 */
			'usersettings:description' => "El panel de configuración de usuario te permtie controlar toda tu configuración personal, desde gestión de usuarios al comportamiento de los plugins. Elige una opción abajo para comenzar.",
	
			'usersettings:statistics' => "Tus estadísticas",
			'usersettings:statistics:opt:description' => "ver información estadística sobre usuarios y objetos en tu sitio.",
			'usersettings:statistics:opt:linktext' => "Estadísticas de la cuenta",
	
			'usersettings:user' => "Tu configuración",
			'usersettings:user:opt:description' => "Esto te permite controlar tu configuración de usuario.",
			'usersettings:user:opt:linktext' => "Cambia tu configuración",
	
			'usersettings:plugins' => "Herramientas",
			'usersettings:plugins:opt:description' => "Opciones de configuración para tus herramientas activas.",
			'usersettings:plugins:opt:linktext' => "Configurar tus herramientas...",
	
			'usersettings:plugins:description' => "Este panel te permite controlar y configurar tus opciones personales para las herramientas instaladas por tu administrador del sistema.",
			'usersettings:statistics:label:numentities' => "Tus entidades",
	
			'usersettings:statistics:yourdetails' => "Tus detalles",
			'usersettings:statistics:label:name' => "Nombre completo",
			'usersettings:statistics:label:email' => "Email",
			'usersettings:statistics:label:membersince' => "Miembro desde",
			'usersettings:statistics:label:lastlogin' => "Última vez conectado",
	
	
			
	
	
		/**
		 * Generic action words
		 */
	
			'save' => "Guardar",
			'cancel' => "Cancelar",
			'saving' => "Guardando ...",
			'update' => "Actualizar",
			'edit' => "Editar",
			'delete' => "Borrar",
			'load' => "Bajar",
			'upload' => "Subir",
			'ban' => "Prohibir",
			'unban' => "Unban",
			'enable' => "Activar",
			'disable' => "Desactivar",
			'request' => "Requisición",
	
			'invite' => "Invitar",
	
			'resetpassword' => "Eliminar contraseña",
			'makeadmin' => "Hacer administrador",
	
			'option:yes' => "Si",
			'option:no' => "No",
	
			'unknown' => 'Desconocida',
	
			'learnmore' => "Siga la liga para aprender mas.",
	
			'content' => "content",
			'content:latest' => 'Latest activity',
			'content:latest:blurb' => 'Alternatively, click here to view the latest content from across the site.',
			
		/**
		 * Generic data words
		 */
	
			'title' => "Título",
			'description' => "Descripción",
			'tags' => "Etiquetas",
			'spotlight' => "En la mira",
			/* This was the original translation, but I prefer the other one. Feel free to change
			'spotlight' => "Centro de atracción", */
			'all' => "Todos",
	
			'by' => 'Por',
	
			'annotations' => "Anotaciones",
			'relationships' => "Relaciones",
			'metadata' => "Metadata",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "¿Estas seguro que quieres borrar este objeto?",
			'fileexists' => "Un archivo ha sido subido con anterioridad. Para reemplazarlo, selecciónelo de la lista de abajo:",
	
		/**
		 * Import / export
		 */
			'importsuccess' => "Los datos fueron exportados satisfactóriamente",
			'importfail' => "Los datos de OpenDD no pudierson ser importados",
	
		/**
		 * Time
		 */
	
			'friendlytime:justnow' => "ahora mismo",
			'friendlytime:minutes' => "hace %s minutos",
			'friendlytime:minutes:singular' => "hace un minuto",
			'friendlytime:hours' => "hace %s horas",
			'friendlytime:hours:singular' => "hace una hora",
			'friendlytime:days' => "hace %s días",
			'friendlytime:days:singular' => "ayer",
	
		/**
		 * Instalación y configuración del sistema
		 */
	
			'installation:error:htaccess' => "Elgg requiere que se llame a un archivo .htaccess que se emplace en el directorio raíz de su instalación. Intentamos crearlo para ti, pero Elgg no tiene permiso de escritura en ese directorio. 

Crear esto es fácil. Copia el contenido del cuadro de texto inferior en un editor de texto y guárdalo como .htaccess

",
			'installation:error:settings' => "Elgg no pudo encontrar su archivo de configuración de usuario. La mayor parte de opciones de configuración de Elgg serán manejadas por tí, pero necesitamos que proporciones los detalles de tu base de datos. Para hacer esto:

1. Cambia el nombre de engine/settings.example.php a settings.php en tu instalación Elgg.

2. Ábrelo con un editor de textos e introduce los detalles de tu base de datos MySQL. Si no los conoces, pregunta a tu administrador del sistema o soporte técnico para ayuda.

De forma alternativa, puedes introducir la configuración de tu base de datos abajo y nosotros intentaremos hacerlo por tí...",
	
			'installation:error:configuration' => "Una vez hayas corregido problemas de configuración, pulsa recargar para volver a intentarlo.",
	
			'installation' => "Instalación",
			'installation:success' => "La base de datos de Elgg se instaló con éxito.",
			'installation:configuration:success' => "Tus opaciones de configuración iniciales se han guardado. Ahora registra a tu usuario inicial; este será tu primer administrador del sistema.",
	
			'installation:settings' => "Configuración del sistema",
			'installation:settings:description' => "Ahora que la base de datos de Elgg ha sido instalada con éxito, necesitas introducir un poco de información para tener tu sitio completo y funcionando. Hemos intentado adivinar y rellenar todo lo posible, pero debes revisar estos detalles.",
	
			'installation:settings:dbwizard:prompt' => "Introduce la configuración de tu base de datos abajo y pulsa en guardar:",
			'installation:settings:dbwizard:label:user' => "Usuario de la base de datos",
			'installation:settings:dbwizard:label:pass' => "Contraseña de la base de datos",
			'installation:settings:dbwizard:label:dbname' => "Base de datos de Elgg",
			'installation:settings:dbwizard:label:host' => "Nombre del host de la base de datos (normalmente 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Prefijo para la tabla de la base de datos (normalmente 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "No pudimos guardar el nuevo archivo settings.php. Por favor guarda el archivo siguiente como engine/settings.php utilizando un editor de textos.",
	
			'sitename' => "El nombre de tu sitio (ej. \"El sitio de mi red social\"):",
			'sitedescription' => "Breve descripción de tu sitio (opcional)",
			'wwwroot' => "El sitio URL, seguido de una barra invertida:",
			'path' => "La ruta completa de tu directorio raíz en tu disco, seguido de una barra invertida:",
			'dataroot' => "La ruta completa del directorio donde se guardarán los ficheros subidos, seguida de una barra invertida:",
			'language' => "El lenguaje por defecto para tu sitio:",
			'debug' => "El modo Debug proporciona información extra que puede ser utilizad para realizar un diagnóstico de fallos, pero puede realentizar tu sistema así que sólo debe utilizarse si experimentas problemas:",
			'debug:label' => "Activar el modo Debug",
			'usage' => "Esta opción permite a Elgg mandar estadñisticas anónimas de utilización a Curverider.",
			'usage:label' => "mandar estadísticas anónimas de uso",
			'view' => "Introducir la vista que se utilizará por defecto para tu sitio (ej. 'móvil'), o deja este espacio en blanco para tener la que viene por defecto:",
	
	
		/**
		 * Bienvenido
		 */
	
			'welcome' => "Bienvenido/a %s",
			'welcome_message' => "Bienvenido a esta instalación de Elgg.",
	
		/**
		 * Emails
		 */
			'email:settings' => "Configuración de email",
			'email:address:label' => "Tu dirección de email",
			
			'email:save:success' => "Nueva dirección email guardada, verificación solicitada.",
			'email:save:fail' => "Tu nueva dirección email no pudo guardarse.",
	
			'email:confirm:success' => "¡Has confirmado tu dirección email!",
			'email:confirm:fail' => "Tu dirección email no pudo ser verificada...",
	
			'friend:newfriend:subject' => "¡%s te ha hecho su amigo!",
			'friend:newfriend:body' => "¡%s te ha hecho su amigo!

Para ver su perfil, haz click aquí:

	%s

No puedes responder a este email.",
	
	
			'email:validate:subject' => "¡Por favor confirma tu dirección email %s!",
			'email:validate:body' => "Hola %s,

Por favor confirma tu dirección email haciendo click en el enlace inferior:

%s
",
			'email:validate:success:subject' => "¡Email validado %s!",
			'email:validate:success:body' => "Hola %s,
			
Enhorabuena, has validado con éxito tu dirección email.",
	
	
			'email:resetpassword:subject' => "¡Contraseña reseteada!",
			'email:resetpassword:body' => "Hola %s,
			
Tu contraseña se ha reseteado a: %s",
	
	
			'email:resetreq:subject' => "Solicitud para una nueva contraseña.",
			'email:resetreq:body' => "Hola %s,
			
Alguien (desde la dirección IP %s) ha solicitado una contraseña nueva para su cuenta.

Si solicitastes esto haz click en el enlace inferior, en otro caso por favor ignora este email.

%s
",

	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"Faltan los datos de entrada",
	

	
		/**
		 * Comments
		 */
	
			'comments:count' => "%s comentarios",
			'generic_comments:add' => "Agrega un comenario",
			'generic_comments:text' => "Comentario",
			'generic_comment:posted' => "Tu comentario fue exitósamente enviado.",
			'generic_comment:deleted' => "Tu comentario fue exitósamente borrado.",
			'generic_comment:blank' => "Lo sentimos; tienes que poner algo en tu comenario antes de que podamos guardarlo.",
			'generic_comment:notfound' => "Lo sentimos; no pudimos encontrar el objeto especificado.",
			'generic_comment:notdeleted' => "Lo sentimos; no pudimos borrar el comentario.",
			'generic_comment:failure' => "Upps, un error inesperado ocurrió al agregar tu comentario. Por favor trata de nuevo.",
	
			'generic_comment:email:subject' => '¡Tienes un nuevo comentario!',
			'generic_comment:email:body' => "Tienes un comentario nuevo en tu elemento \"%s\" de %s. Dice:

			
%s


Para responder o ver el objeto original, haz click aquí:

	%s

Para ver el perfil de %s, haz click aquí:

	%s

No puedes responder a este email.",
	
		/**
		 * Entidades
		 */
			'entity:default:strapline' => 'Creado %s por %s',
			'entity:default:missingsupport:popup' => 'Esta entidad no puede mostrarse correctamente. Esto puede ser porque se requiera un plugin que ya no está instalado.',
	
			'entity:delete:success' => 'La entidad %s se ha borrado',
			'entity:delete:fail' => 'La entidad %s no pudo eliminarse',
	
	
		/**
		 * Action gatekeeper
		 */
			'actiongatekeeper:missingfields' => 'Faltan los campos __token o __ts en el form',
			'actiongatekeeper:tokeninvalid' => 'El token proporcionado por el form no coincide con el generado por el servidor.',
			'actiongatekeeper:timeerror' => 'El form ha expirado, por favor actualiza y vuelve a intentar.',
			'actiongatekeeper:pluginprevents' => 'Una extensión ha prevenido al form de ser enviado.',

	
	
		/**
		 * Languages according to ISO 639-1
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
			"de" => "German",
			"dz" => "Bhutani",
			"el" => "Greek",
			"en" => "English",
			"eo" => "Esperanto",
			"es" => "Spanish",
			"et" => "Estonian",
			"eu" => "Basque",
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
			"in" => "Indonesian",
			"is" => "Icelandic",
			"it" => "Italian",
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
			"y" => "Yiddish",
			"yo" => "Yoruba",
			"za" => "Zuang",
			"zh" => "Chinese",
			"zu" => "Zulu",
	);
	
	add_translation("es",$spanish);

?>
