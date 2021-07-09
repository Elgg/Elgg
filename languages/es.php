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

	'item:site:site' => 'Sitio',
	'collection:site:site' => 'Sitios',
	'index:content' => '<p>Bienvenido a tu sitio Elgg. </p><p><strong>Consejo:</strong> Muchos sitios usan el plugin de <code>actividad</code> para poner un flujo de actividad en esta pagina. </p>',

/**
 * Sessions
 */

	'login' => "Iniciar sesi&oacute;n",
	'loginok' => "Ha iniciado sesi&oacute;n",
	'loginerror' => "Inicio de sesi&oacute;n incorrecto. Verifique sus credenciales e intente nuevamente",
	'login:empty' => "El nombre de usuario y contrase&ntilde;a son requeridos",
	'login:baduser' => "No se pudo cargar su cuenta de usuario",
	'auth:nopams' => "Error interno. No se encuentra un m&eacute;todo de autenticaci&oacute;n instalado",

	'logout' => "Cerrar sesi&oacute;n",
	'logoutok' => "Se ha cerrado la sesi&oacute;n",
	'logouterror' => "No se pudo cerrar la sesi&oacute;n, por favor intente nuevamente",
	'session_expired' => "Su sesión ha expirado. Por favor <a href='javascript:location.reload(true)'>refresca</a> la página para ingresar nuevamente.",
	'session_changed_user' => "Has ingresado como otro usuario. Deberías <a href='javascript:location.reload(true)'>recargar</a> la página.",

	'loggedinrequired' => "Debe estar autenticado para poder visualizar esta p&aacute;gina",
	'loggedoutrequired' => "Debes de cerrar sesión para ver la pagina solicitada",
	'adminrequired' => "Debe ser un administrador para poder visualizar esta p&aacute;gina",
	'membershiprequired' => "Debe ser miembro del grupo para poder visualizar esta p&aacute;gina",
	'limited_access' => "No tienes permiso para ver la página solicitada",
	'invalid_request_signature' => "La URL de la página a la que intenta acceder no es válida o ha caducado",

/**
 * Errors
 */

	'exception:title' => "Error Fatal",
	'exception:contact_admin' => 'Se ha encontrado un error fatal al iniciar sesi&oacute;n. Contacta al administrador con la siguiente informaci&oacute;n:',

	'actionundefined' => "La acci&oacute;n (%s) solicitada no se encuentra definida en el sistema",
	'actionnotfound' => "El log de acciones para %s no se ha encontrado",
	'actionloggedout' => "Lo sentimos, no puede realizar esta acci&oacute;n sin identificarse",
	'actionunauthorized' => 'Usted no posee los permisos necesarios para realizar esta acci&oacute;n',

	'ajax:error' => 'Ha habido un error inesperado en la llamada AJAX. Puede que la conexión con el servidor se haya perdido.',
	'ajax:not_is_xhr' => 'No puedes acceder a las vista AJAX directamente',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) plugin mal configurado. Se ha desactivado. Por favor, consulta la wiki de Elgg para ver las posibles causas (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) no puede iniciarse. Motivo: %s',
	'PluginException:InvalidID' => "%s no es un ID de plugin v&aacute;lido",
	'PluginException:InvalidPath' => "%s es un path de plugin inv&aacute;lido",
	'PluginException:InvalidManifest' => 'Archivo de manifesto inv&aacute;lido para el plugin %s',
	'PluginException:InvalidPlugin' => '%s no es un plugin v&aacute;lido',
	'PluginException:InvalidPlugin:Details' => '%s no es un plugin v&aacute;lido: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',
	'ElggPlugin:MissingID' => 'No se encuentra el ID del plugin (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'ElggPluginPackage faltante para el plugin con ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Archivo %s faltante en el package',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'El directorio del plugin debe ser renombrado a "%s" para que se igual al ID de su manifiesto.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Tipo de dependencia "%s" inv&aacute;lida',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Tipo "%s" provisto inv&aacute;lido',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Dependencia %s inv&aacute;lida "%s" en plugin %s. Los plugins no pueden entrar en conlicto con otros requeridos!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicto con el plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'El archivo  "elgg-plugin.php" del complemento está presente, pero ilegible.',
	'ElggPlugin:Error' => 'Error del plugin',
	'ElggPlugin:Error:ID' => 'Error en el plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error en el directorio del plugin "%s"',
	'ElggPlugin:Error:Unknown' => 'Error de plugin indefinido',
	'ElggPlugin:Exception:CannotIncludeFile' => 'No puede incluirse %s para el plugin %s (guid: %s) en %s. Verifique los permisos!',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Se lanzó la excepción incluyendo %s para el complemento %s (guid: %s) en %s. ',
	'ElggPlugin:Exception:CannotRegisterViews' => 'No puede cargarse el directorio "views" para el plugin %s (guid: %s) en %s. Verifique los permisos!',
	'ElggPlugin:Exception:NoID' => 'No se encontr&oacute; el ID para el plugin con guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'El paquete no pudo ser cargado',
	'ElggPlugin:Exception:InvalidManifest' => 'El manifest del plugin es inexistente o invalido',
	'PluginException:NoPluginName' => "No se pudo encontrar el nombre del plugin",
	'PluginException:ParserError' => 'Error procesando el manifiesto con versi&oacute;n de API %s en plugin %s',
	'PluginException:NoAvailableParser' => 'No se encuentra un procesador para el manifiesto de la versi&oacute;n de la API %s en plugin %s',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Atributo '%s' faltante en manifiesto del el plugin %s",
	'ElggPlugin:InvalidAndDeactivated' => '%s no es un plugin v&aacute;lido y se ha deshabilitado',
	'ElggPlugin:activate:BadConfigFormat' => 'El archivo "elgg-plugin.php" del complemento no devolvió una matriz serializable. ',
	'ElggPlugin:activate:ConfigSentOutput' => 'El archivo "elgg-plugin.php" del complemento envió una salida.',

	'ElggPlugin:Dependencies:Requires' => 'Requiere',
	'ElggPlugin:Dependencies:Suggests' => 'Sugiere',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflictos',
	'ElggPlugin:Dependencies:Conflicted' => 'En conflicto',
	'ElggPlugin:Dependencies:Provides' => 'Provee',
	'ElggPlugin:Dependencies:Priority' => 'Prioridad',

	'ElggPlugin:Dependencies:Elgg' => 'Versi&oacute;n Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Versi&oacute;n de PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Extensi&oacute;n PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Configuraci&oacute;n PHP ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Luego %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Antes %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s no instalado',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Faltante',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Hay otros complementos que tienen a «%s» como dependencia. Debes desactivar los siguientes complementos primero para poder desactivar este: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Hay elementos de menú que no están enlazados a ningún elemento padre',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'El elemento de menú [%s] no tiene elemento padre[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'El elemento de menú [%s] está registrado por duplicado',

	'RegistrationException:EmptyPassword' => 'Los campos de contrase&ntilde;as son obligatorios',
	'RegistrationException:PasswordMismatch' => 'Las contrase&ntilde;as deben coincidir',
	'LoginException:BannedUser' => 'Su ingreso ha sido bloqueado moment&aacute;neamente',
	'LoginException:UsernameFailure' => 'No pudo iniciarse la sesi&oacute;n. Por favor verifique su nombre de usuario y contrase&ntilde;a',
	'LoginException:PasswordFailure' => 'No pudo iniciarse la sesi&oacute;n. Por favor verifique su nombre de usuario y contrase&ntilde;a',
	'LoginException:AccountLocked' => 'Su cuenta ha sido bloqueada por la cantidad de intentos fallidos de inicio de sesion',
	'LoginException:ChangePasswordFailure' => 'Fall&oacute; el cambio de contrase&ntilde;a. revisa la antigua y nueva contrase&ntilde;a.',
	'LoginException:Unknown' => 'We could not log you in due to an unknown error.',
	'LoginException:AdminValidationPending' => "Your account needs to be validated by a site administrator before you can use it. You'll be notified when your account is validated.",
	'LoginException:DisabledUser' => "Your account has been disabled. You're not allowed to login.",

	'UserFetchFailureException' => 'No se pueden revisar los permisos para el user_guid [%s] porque el usuario no existe.',

	'PageNotFoundException' => 'La pagina que estas intentando ver no existe o no tienes permisos para verla',
	'EntityNotFoundException' => 'El contenido al que intentas acceder ha sido eliminado o no tienes permiso para verlo.',
	'EntityPermissionsException' => 'No tienes permisos suficientes para esta acción.',
	'GatekeeperException' => 'No tienes permisos para ver la pagina a la que intentas acceder',
	'BadRequestException' => 'Bad request',
	'ValidationException' => 'Los datos enviados no cumplen los requerimientos, por favor comprueba los datos.',
	'LogicException:InterfaceNotImplemented' => '%s debe de ser implementado %s',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "The provided password is doesn't meet the character requirements",
	'Security:InvalidPasswordLengthException' => "The provided password doesn't meet the minimal length requirement of %s characters",

	'deprecatedfunction' => 'Precauci&oacute;n: Este c&oacute;digo utiliza la funci&oacute;n obsoleta \'%s\' que no es compatible con esta versi&oacute;n de Elgg',

	'pageownerunavailable' => 'Precauci&oacute;n: El administrador de p&aacute;gina %d no se encuentra accesible!',
	'viewfailure' => 'Ocurri&oacute; un error interno en la vista %s',
	'view:missing_param' => "Falta el parámetro obligatorio «%s» en la vista «%s».",
	'changebookmark' => 'Por favor modifique su &iacute;ndice para esta vista',
	'noaccess' => 'You need to login to view this content or the content has been removed or you do not have permission to view it.',
	'error:missing_data' => 'Faltan datos en tu solicitud',
	'save:fail' => 'Hubo un error guardando tus datos',
	'save:success' => 'Tus datos fueron guardados',

	'forward:error' => 'Lo sentimos. Un error ha ocurrido mientras te redirigíamos a otro sitio.',

	'error:default:title' => 'Error...',
	'error:default:content' => 'Oops... Algo salió mal',
	'error:400:title' => 'Petición incorrecta',
	'error:400:content' => 'Lo sentimos. La petición no es válida o está incompleta.',
	'error:403:title' => 'Prohibido',
	'error:403:content' => 'Lo sentimos. No tienes permiso para acceder a la página solicitada.',
	'error:404:title' => 'Página no encontrada',
	'error:404:content' => 'Lo sentimos. No pudimos encontrar la página solicitada',

	'upload:error:ini_size' => 'El archivo que intentó subir es muy grande.',
	'upload:error:form_size' => 'El archivo que intentó subir es muy grande.',
	'upload:error:partial' => 'La subida no pudo completarse',
	'upload:error:no_file' => 'Ningún archivo ha sido seleccionado',
	'upload:error:no_tmp_dir' => 'No se puede guardar el archivo subido',
	'upload:error:cant_write' => 'No se puede guardar el archivo subido',
	'upload:error:extension' => 'No se puede guardar el archivo subido',
	'upload:error:unknown' => 'La subida ha fallado',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Administrador',
	'table_columns:fromView:banned' => 'Bloqueado',
	'table_columns:fromView:container' => 'Contenedor',
	'table_columns:fromView:excerpt' => 'Descripción',
	'table_columns:fromView:link' => 'Nombre/Título',
	'table_columns:fromView:icon' => 'Icono',
	'table_columns:fromView:item' => 'Elemento',
	'table_columns:fromView:language' => 'Idioma',
	'table_columns:fromView:owner' => 'Propietario',
	'table_columns:fromView:time_created' => 'Tiempo de creación',
	'table_columns:fromView:time_updated' => 'Tiempo de actualización',
	'table_columns:fromView:user' => 'Usuario',

	'table_columns:fromProperty:description' => 'Descripción',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Nombre',
	'table_columns:fromProperty:type' => 'Tipo',
	'table_columns:fromProperty:username' => 'Nombre de usuario',

	'table_columns:fromMethod:getSubtype' => 'Subtipo',
	'table_columns:fromMethod:getDisplayName' => 'Nombre/Título',
	'table_columns:fromMethod:getMimeType' => 'Tipo MIME',
	'table_columns:fromMethod:getSimpleType' => 'Tipo',

/**
 * User details
 */

	'name' => "Nombre",
	'email' => "Direcci&oacute;n de Email",
	'username' => "Nombre de usuario",
	'loginusername' => "Nombre de usuario o Email",
	'password' => "Contrase&ntilde;a",
	'passwordagain' => "Contrase&ntilde;a (nuevamente, para verificaci&oacute;n)",
	'admin_option' => "Hacer administrador a este usuario?",
	'autogen_password_option' => "¿Generar automáticamente una contraseña segura? ",

/**
 * Access
 */

	'access:label:private' => "Privado",
	'access:label:logged_in' => "Usuarios conectados",
	'access:label:public' => "Publico",
	'access:label:logged_out' => "Usuarios desconectados",
	'access:label:friends' => "Amigos",
	'access' => "Acceso",
	'access:overridenotice' => "Aviso: Debido a la política del grupo, este contenido solo es accesible para los miembros del grupo",
	'access:limited:label' => "Limitado",
	'access:help' => "El nivel de acceso",
	'access:read' => "S&oacute;lo lectura",
	'access:write' => "Acceso de escritura",
	'access:admin_only' => "Solo Administradores",
	'access:missing_name' => "Falta el nombre del nivel de acceso",
	'access:comments:change' => "Esta discusión está solo visible para un conjunto limitado de usuarios. Piénsate bien con quién la compartes.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Panel de control",
	'dashboard:nowidgets' => "Su panel de control le permite seguir la actividad y el contenido que le interesan de este sitio",

	'widgets:add' => 'Agregar widget',
	'widgets:add:description' => "Haga click en el bot&oacute;n de alg&uacute;n widget para agregarlo a la p&aacute;gina",
	'widgets:position:fixed' => '(Posici&oacute;n fija en la p&aacute;gina)',
	'widget:unavailable' => 'Ya agreg&oacute; este widget',
	'widget:numbertodisplay' => 'Cantidad de elementos para mostrar',

	'widget:delete' => 'Quitar %s',
	'widget:edit' => 'Personalizar este widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "El widget se guard&oacute; correctamente",
	'widgets:save:failure' => "No se pudo guardar el widget, por favor intente nuevamente",
	'widgets:add:success' => "Se agreg&oacute; correctamente el widget",
	'widgets:add:failure' => "No se pudo a&ntilde;adir el widget",
	'widgets:move:failure' => "No se pudo guardar la nueva posici&oacute;n del widget",
	'widgets:remove:failure' => "No se pudo quitar el widget",
	'widgets:not_configured' => "Este widget no esta configurado todavía",
	
/**
 * Groups
 */

	'group' => "Grupo",
	'item:group' => "Grupos",
	'collection:group' => 'Grupos',
	'item:group:group' => "Grupo",
	'collection:group:group' => 'Grupos',
	'groups:tool_gatekeeper' => "La funcionalidad solicitada no esta activada en este grupo",

/**
 * Users
 */

	'user' => "Usuario",
	'item:user' => "Usuarios",
	'collection:user' => 'Usuarios',
	'item:user:user' => 'Usuario',
	'collection:user:user' => 'Usuarios',

	'friends' => "Amigos",
	'collection:friends' => 'Friends\' %s',

	'avatar' => 'Imagen de perfil',
	'avatar:noaccess' => "No tienes permitido editar el avatar de este usuario",
	'avatar:create' => 'Cree su imagen de perfil',
	'avatar:edit' => 'Editar imagen de perfil',
	'avatar:upload' => 'Subir nueva imagen de perfil',
	'avatar:current' => 'Imagen de perfil actual',
	'avatar:remove' => 'Remove your avatar and set the default icon',
	'avatar:crop:title' => 'Herramienta de recorte de imagen de perfil',
	'avatar:upload:instructions' => "Su imagen de perfil se mostrar&aacute; en la red. Podr&aacute; modificarla siempre que lo desee (Formatos de archivo aceptados: GIF, JPG o PNG)",
	'avatar:create:instructions' => 'Haga click y arrastre un cuadrado debajo para seleccionar el recorte de la imagen. Aparecer&aacute; una previsualizaci&oacute;n en la caja de la deracha. Cuando est&eacute; conforme con la previsualizaci&oacute;n, haga click en \'Crear imagen de perfil\'. La versi&oacute;n recortada ser&aacute; la que se utilice para mostrar en la red',
	'avatar:upload:success' => 'Imagen de perfil subida correctamente',
	'avatar:upload:fail' => 'Fall&oacute; la subida de la imagen de perfil',
	'avatar:resize:fail' => 'Error al modificar el tama&ntilde;o de la imagen de perfil',
	'avatar:crop:success' => 'Recorte de la imagen de perfil finalizado correctamente',
	'avatar:crop:fail' => 'El recortado del avatar ha fallado',
	'avatar:remove:success' => 'Se ha eliminado el avatar',
	'avatar:remove:fail' => 'fall&oacute; al remover el avatar',
	
	'action:user:validate:already' => "%s ya ha sido validado",
	'action:user:validate:success' => "%s ha sido validado",
	'action:user:validate:error' => "Ocurrió un error validando %s",

/**
 * Feeds
 */
	'feed:rss' => 'Canal RSS para esta p&aacute;gina',
	'feed:rss:title' => 'Fuente RSS para esta pagina',
/**
 * Links
 */
	'link:view' => 'Ver enlace',
	'link:view:all' => 'Ver todos',


/**
 * River
 */
	'river' => "River",
	'river:user:friend' => "%s ahora es amigo con %s",
	'river:update:user:avatar' => '%s tiene una nueva imagen de perfil',
	'river:noaccess' => 'No posee permisos para visualizar este elemento',
	'river:posted:generic' => '%s publicado',
	'riveritem:single:user' => 'un usuario',
	'riveritem:plural:user' => 'algunos usuarios',
	'river:ingroup' => 'en el grupo %s',
	'river:none' => 'Sin actividad',
	'river:update' => 'Actualizaciones de %s',
	'river:delete' => 'Eliminar este elemento de la actividad',
	'river:delete:success' => 'El item en el River ha sido borrado',
	'river:delete:fail' => 'El item en el River no pudo ser borrado',
	'river:delete:lack_permission' => 'No tienes permiso para eliminar este elemento de la actividad.',
	'river:can_delete:invaliduser' => 'No se puede chequear canDelete para el usuario [%s] porque el usuario no existe.',
	'river:subject:invalid_subject' => 'Usuario inválido',
	'activity:owner' => 'Ver Actividad',

/**
 * Relationships
 */
	
	'relationship:default' => "%s relates to %s",

/**
 * Notifications
 */
	'notifications:usersettings' => "Configuraci&oacute;n de notifiaciones",
	'notification:method:email' => 'Correo electr&oacute;nico',

	'notifications:usersettings:save:ok' => "Su configuraci&oacute;n de notificaciones se guard&oacute; correctamente",
	'notifications:usersettings:save:fail' => "Ocurri&oacute; un error al guardar la configuraci&oacute;n de notificaciones",

	'notification:subject' => 'Notificaciones de %s',
	'notification:body' => 'Ver la nueva actividad en %s',

/**
 * Search
 */

	'search' => "Buscar",
	'searchtitle' => "Buscar: %s",
	'users:searchtitle' => "Buscar para usuarios: %s",
	'groups:searchtitle' => "Buscar para grupos: %s",
	'advancedsearchtitle' => "%s con coincidencias en resultados %s",
	'notfound' => "No se encontraron resultados",
	'next' => "Siguiente",
	'previous' => "Anterior",

	'viewtype:change' => "Modificar tipo de lista",
	'viewtype:list' => "Vista de lista",
	'viewtype:gallery' => "Galer&iacute;a",

	'tag:search:startblurb' => "Items con tags que coincidan con '%s':",

	'user:search:startblurb' => "Usuarios que coincidan con '%s':",
	'user:search:finishblurb' => "Click aqu&iacute; para ver mas",

	'group:search:startblurb' => "Grupos que coinciden con '%s':",
	'group:search:finishblurb' => "Click aqu&iacute; para ver mas",
	'search:go' => 'Ir',
	'userpicker:only_friends' => 'S&oacute;lo amigos',

/**
 * Account
 */

	'account' => "Cuenta",
	'settings' => "Configuraci&oacute;n",
	'tools' => "Herramientas",
	'settings:edit' => 'Editar configuraci&oacute;n',

	'register' => "Registrarse",
	'registerok' => "Se registr&oacute; correctamente para %s",
	'registerbad' => "No se pudo registrar debido a un error desconocido",
	'registerdisabled' => "La registraci&oacute;n se deshabilit&oacute; por el administrador del sistema",
	'register:fields' => 'Todos los campos son obligatorios',

	'registration:noname' => 'El nombre para mostrar es requerido.',
	'registration:notemail' => 'No ha ingresado una direcci&oacute;n de Email v&aacute;lida',
	'registration:userexists' => 'El nombre de usuario ya existe',
	'registration:usernametooshort' => 'El nombre de usuario debe tener un m&iacute;nimo de %u caracteres',
	'registration:usernametoolong' => 'Tu nombre de usuario es demasiado largo. El m&aacute;ximo permitido es of %u caracteres.',
	'registration:passwordtooshort' => 'La contrase&ntilde;a debe tener un m&iacute;nimo de %u caracteres',
	'registration:dupeemail' => 'Ya se encuentra registrada la direcci&oacute;n de Email',
	'registration:invalidchars' => 'Lo sentimos, su nombre de usuario posee los caracteres inv&aacute;lidos: %s. Estos son todos los caracteres que se encuentran invalidados: %s',
	'registration:emailnotvalid' => 'Lo sentimos, la direcci&oacute;n de email que ha ingresado es inv&aacute;lida en el sistema',
	'registration:passwordnotvalid' => 'Lo sentimos, la contrase&ntilde;a que ha ingresado es inv&aacute;lida en el sistema',
	'registration:usernamenotvalid' => 'Lo sentimos, el nombre de usuario que ha ingresado es inv&aacute;lida en el sistema',

	'adduser' => "Nuevo usuario",
	'adduser:ok' => "Se agreg&oacute; correctamente un nuevo usuario",
	'adduser:bad' => "No se pudo agregar el nuevo usuario",

	'user:set:name' => "Configuraci&oacute;n del nombre de cuenta",
	'user:name:label' => "Mi nombre para mostrar",
	'user:name:success' => "Se modific&oacute; correctamente su nombre en la red",
	'user:name:fail' => "No se pudo modificar su nombre en la red. Por favor, aseg&uacute;rese de que no es demasiado largo e intente nuevamente",
	'user:username:success' => "Nombre cambiado satisfactoriamente en el sistema",
	'user:username:fail' => "No se pudo cambiar el nombre de usuario en el sistema.",

	'user:set:password' => "Contrase&ntilde;a de la cuenta",
	'user:current_password:label' => 'Contrase&ntilde;a actual',
	'user:password:label' => "Nueva contrase&ntilde;a",
	'user:password2:label' => "Confirmar nueva contrase&ntilde;a",
	'user:password:success' => "Contrase&ntilde;a modificada",
	'user:password:fail' => "No se pudo modificar la contrase&ntilde;a en  la red",
	'user:password:fail:notsame' => "Las dos contrase&ntilde;as no coinciden!",
	'user:password:fail:tooshort' => "La contrase&ntilde;a es demasiado corta!",
	'user:password:fail:incorrect_current_password' => 'La contrase&ntilde;a actual ingresada es incorrecta',
	'user:changepassword:unknown_user' => 'Usuario inv&aacute;lido',
	'user:changepassword:change_password_confirm' => 'Esto cambiará su contraseña.',

	'user:set:language' => "Configuraci&oacute;n de lenguaje",
	'user:language:label' => "Su lenguaje",
	'user:language:success' => "Se actualiz&oacute; su configuraci&oacute;n de lenguaje",
	'user:language:fail' => "No se pudo actualizar su configuraci&oacute;n de lenguaje",

	'user:username:notfound' => 'No se encuentra el usuario %s',
	'user:username:help' => 'Tenga en cuenta que cambiar un nombre de usuario cambiará todos los enlaces dinámicos relacionados con el usuario',

	'user:password:lost' => 'Olvid&eacute; mi contrase&ntilde;a',
	'user:password:hash_missing' => 'Lamentablemente, debemos pedirle que restablezca su contraseña. Hemos mejorado la seguridad de las contraseñas en el sitio, pero no hemos podido migrar todas las cuentas en el proceso.',
	'user:password:changereq:success' => 'Solicitud de nueva contrase&ntilde;a confirmada, se le ha enviado un Email',
	'user:password:changereq:fail' => 'No se pudo solicitar una nueva contrase&ntilde;a',

	'user:password:text' => 'Para solicitar una nueva contrase&ntilde;a ingrese su nombre de usuario y presione el bot&oacute;n debajo',

	'user:persistent' => 'Recordarme',

	'walled_garden:home' => 'Inicio',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "The password needs to be at least %s characters.",
	'password:requirements:lower' => "The password needs to have at least %s lower case characters.",
	'password:requirements:no_lower' => "The password shouldn't contain any lower case characters.",
	'password:requirements:upper' => "The password needs to have at least %s upper case characters.",
	'password:requirements:no_upper' => "The password shouldn't contain any upper case characters.",
	'password:requirements:number' => "The password needs to have at least %s number characters.",
	'password:requirements:no_number' => "The password shouldn't contain any number characters.",
	'password:requirements:special' => "The password needs to have at least %s special characters.",
	'password:requirements:no_special' => "The password shouldn't contain any special characters.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrar',
	'menu:page:header:configure' => 'Configurar',
	'menu:page:header:develop' => 'Desarrollar',
	'menu:page:header:information' => 'Información',
	'menu:page:header:default' => 'Otro',

	'admin:view_site' => 'Ver sitio',
	'admin:loggedin' => 'Sesi&oacute;n iniciada como %s',
	'admin:menu' => 'Men&uacute;',

	'admin:configuration:success' => "Su configuraci&oacute;n ha sido guardada",
	'admin:configuration:fail' => "No se pudo guardar su configuraci&oacute;n",
	'admin:configuration:dataroot:relative_path' => 'No se puede configurar "%s" como el directorio de datos raiz ya que la ruta no es absoluta.',
	'admin:configuration:default_limit' => 'El n&uacute;mero de elementos debe ser de al menos 1.',

	'admin:unknown_section' => 'Secci&oacute;n de administraci&oacute;n inv&aacute;lida',

	'admin' => "Administraci&oacute;n",
	'admin:header:release' => "Elgg release: %s",
	'admin:description' => "El panel de administraci&oacute;n le permite organizar todos los aspectos del sistema, desde la gesti&oacute;n de usuarios hasta el comportamiento de los plugins. Seleccione una opci&oacute;n debajo para comenzar",

	'admin:performance' => 'Rendimiento',
	'admin:performance:label:generic' => 'Generic',
	'admin:performance:generic:description' => 'Below is a list of performance suggestions / values which could help in tuning your website',
	'admin:performance:simplecache' => 'Caché simple',
	'admin:performance:simplecache:settings:warning' => "It's recommended you configure the simplecache setting in the settings.php.
Configuring simplecache in the settings.php file improves caching performance.
It allows Elgg to skip connecting to the database when serving cached JavaScript and CSS files",
	'admin:performance:systemcache' => 'Systemcache',
	'admin:performance:apache:mod_cache' => 'Apache mod_cache',
	'admin:performance:apache:mod_cache:warning' => 'The mod_cache module provides HTTP-aware caching schemes. This means that the files will be cached according
to an instruction specifying how long a page can be considered "fresh".',
	'admin:performance:php:open_basedir' => 'PHP open_basedir',
	'admin:performance:php:open_basedir:not_configured' => 'No limitations have been set',
	'admin:performance:php:open_basedir:warning' => 'A small amount of open_basedir limitations are in effect, this could impact performance.',
	'admin:performance:php:open_basedir:error' => 'A large amount of open_basedir limitations are in effect, this will probably impact performance.',
	'admin:performance:php:open_basedir:generic' => 'With open_basedir every file access will be checked against the list of limitations. Since Elgg has a lot of
file access this will negatively impact performance. Also PHPs opcache can no longer cache file paths in memory and has to resolve this upon every access.',
	
	'admin:statistics' => 'Estad&iacute;sticas',
	'admin:server' => 'Servidor',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Ultimos trabajos del Cron',
	'admin:cron:period' => 'Periodo Cron',
	'admin:cron:friendly' => 'Ultimo completado',
	'admin:cron:date' => 'Fecha y hora',
	'admin:cron:msg' => 'Mensaje',
	'admin:cron:started' => 'Los trabajos de cron para "%s" han empezado a las %s',
	'admin:cron:started:actual' => 'Intervalo de Cron "%s" empezó a procesarse en %s',
	'admin:cron:complete' => 'Los trabajos de cron para "%s" se han completado a las %s',

	'admin:appearance' => 'Apariencia',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilidades',
	'admin:configure_utilities' => 'Utilidades',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Usuarios",
	'admin:users:online' => 'Conectados actualmente',
	'admin:users:newest' => 'Los mas nuevos',
	'admin:users:admins' => 'Administrators',
	'admin:users:searchuser' => 'Search user to make them admin',
	'admin:users:existingadmins' => 'List of existing admins',
	'admin:users:add' => 'Agregar Nuevo Usuario',
	'admin:users:description' => "Este panel de administraci&oacute;n le permite gestionar la configuraci&oacute;n de usuarios de la red. Seleccione una opci&oacute;n debajo para comenzar",
	'admin:users:adduser:label' => "Click aqu&iacute; para agregar un nuevo usuario..",
	'admin:users:opt:linktext' => "Configurar usuarios..",
	'admin:users:opt:description' => "Configurar usuarios e informaci&oacute;n de cuentas",
	'admin:users:find' => 'Buscar',
	'admin:users:unvalidated' => 'No validado',
	'admin:users:unvalidated:no_results' => 'No hay usuarios sin validar.',
	'admin:users:unvalidated:registered' => 'Registrado: %s',
	'admin:users:unvalidated:change_email' => 'Change e-mail address',
	'admin:users:unvalidated:change_email:user' => 'Change e-mail address for: %s',
	
	'admin:configure_utilities:maintenance' => 'Modo mantenimiento',
	'admin:upgrades' => 'Actualizaciones',
	'admin:upgrades:finished' => 'Completado',
	'admin:upgrades:db' => 'Actualizaciones de base de datos',
	'admin:upgrades:db:name' => 'Actualizar nombre',
	'admin:upgrades:db:start_time' => 'Hora de inicio',
	'admin:upgrades:db:end_time' => 'Hora de finalización',
	'admin:upgrades:db:duration' => 'Duración',
	'admin:upgrades:menu:pending' => 'Actualizaciones pendientes',
	'admin:upgrades:menu:completed' => 'Actualizaciones completadas',
	'admin:upgrades:menu:db' => 'Actualizaciones de base de datos',
	'admin:upgrades:menu:run_single' => 'Ejecutar esta actualización',
	'admin:upgrades:run' => 'Ejecutar actualizaciones ahora',
	'admin:upgrades:error:invalid_upgrade' => 'La entidad %s no existe o no es una instancia valida de ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Corredor de lotes para la actualización %s (%s) no pudo ser instanciado',
	'admin:upgrades:completed' => 'Actualización "%s" completada el %s',
	'admin:upgrades:completed:errors' => 'Actualización "%s" completada el %s pero encontró %s errores',
	'admin:upgrades:failed' => 'Actualización "%s" ha fallado',
	'admin:action:upgrade:reset:success' => 'Actualización "%s" fue reiniciada',

	'admin:settings' => 'Configuraci&oacute;n',
	'admin:settings:basic' => 'Configuraci&oacute;n B&aacute;sica',
	'admin:settings:i18n' => 'Internacionalización',
	'admin:settings:advanced' => 'Configuraci&oacute;n Avanzada',
	'admin:settings:users' => 'Usuarios',
	'admin:site_settings' => "Site Settings",
	'admin:site:description' => "Este panel de administraci&oacute;n le permite gestionar la configuraci&oacute;n global de la red. Selecciona una opci&oacute;n debajo para comenzar",
	'admin:site:opt:linktext' => "Configurar sitio..",
	'admin:settings:in_settings_file' => 'Esta opción se configura en settings.php',

	'site_secret:current_strength' => 'Seguridad de la clave',
	'site_secret:strength:weak' => "Débil",
	'site_secret:strength_msg:weak' => "Nosotros recomendamos que regeneres tu clave secreta",
	'site_secret:strength:moderate' => "Moderado",
	'site_secret:strength_msg:moderate' => "Le recomendamos que regenere el secreto del sitio para una mayor seguridad.",
	'site_secret:strength:strong' => "Fuerte",
	'site_secret:strength_msg:strong' => "Tu clave secreta es suficientemente segura. No hay necesidad de regenerarla.",

	'admin:dashboard' => 'Panel de control',
	'admin:widget:online_users' => 'Usuarios conectados',
	'admin:widget:online_users:help' => 'Lista los usuarios conectados actualmente en la red',
	'admin:widget:new_users' => 'Usuarios Nuevos',
	'admin:widget:new_users:help' => 'Lista los usuarios m&aacute;s nuevos',
	'admin:widget:banned_users' => 'Usuarios prohibidos',
	'admin:widget:banned_users:help' => 'Lista de usuarios prohibidos',
	'admin:widget:content_stats' => 'Estad&iacute;sticas de contenido',
	'admin:widget:content_stats:help' => 'Seguimiento del contenido creado por los usuarios de la red',
	'admin:widget:cron_status' => 'Estado de Cron',
	'admin:widget:cron_status:help' => 'Muestra el estado de la última ejecución de los trabajos de Cron',
	'admin:statistics:numentities' => 'Estadísticas del contenido',
	'admin:statistics:numentities:type' => 'Tipo de contenido',
	'admin:statistics:numentities:number' => 'Numero',
	'admin:statistics:numentities:searchable' => 'Entidades de búsqueda',
	'admin:statistics:numentities:other' => 'Otras entidades',

	'admin:widget:admin_welcome' => 'Bienvenido',
	'admin:widget:admin_welcome:help' => "Esta es el &aacute;rea de administraci&oacute;n",
	'admin:widget:admin_welcome:intro' =>
'Bienvenido! Se encuentra viendo el panel de control de la administraci&oacute;n. Es &uacute;til para visualizar las novedades en la red',

	'admin:widget:admin_welcome:registration' => "Registration for new users is currently disabled! You can enabled this on the %s page.",
	'admin:widget:admin_welcome:admin_overview' =>
"El menú a la derecha proporciona la navegación para el área de administración. Se organiza en
tres secciones:
	<dl>
		<dt>Administrar</dt><dd>Tareas básicas como administrar usuarios, monitorear contenido reportado y activar complementos.</dd>
		<dt>Configurar</dt><dd>Tareas ocasionales como configurar el nombre del sitio o configurar los ajustes de un complemento.</dd>
		<dt>Información</dt><dd>Información sobre su sitio como estadísticas</dd>
		<dt>Desarrollar</dt><dd>Para desarrolladores que están construyendo complementos o diseñando temas. (Requiere un complemento de desarrollador.)</dd>
	</dl>
",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Aseg&uacute;rese de verificar los recursos disponibles en los enlaces del pi&eacute; de p&aacute;gina y gracias por utilizar Elgg!',

	'admin:widget:control_panel' => 'Panel de control',
	'admin:widget:control_panel:help' => "Provee un acceso f&aacute;cil a los controles",

	'admin:cache:flush' => 'Limpiar la cache',
	'admin:cache:flushed' => "La cache del sitio ha sido limpiada",
	'admin:cache:invalidate' => 'Invalidate the caches',
	'admin:cache:invalidated' => "The site's caches have been invalidated",
	'admin:cache:clear' => 'Clear the caches',
	'admin:cache:cleared' => "The site's caches have been cleared",
	'admin:cache:purge' => 'Purge the caches',
	'admin:cache:purged' => "The site's caches have been purged",

	'admin:footer:faq' => 'FAQs de Administraci&oacute;n',
	'admin:footer:manual' => 'Manual de Administraci&oacute;n',
	'admin:footer:community_forums' => 'Foros de la Comunidad Elgg',
	'admin:footer:blog' => 'Blog Elgg',

	'admin:plugins:category:all' => 'Todos los plugins',
	'admin:plugins:category:active' => 'Plugins activos',
	'admin:plugins:category:inactive' => 'Plugins inactivos',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Inclu&iacute;do',
	'admin:plugins:category:nonbundled' => 'No integrado',
	'admin:plugins:category:content' => 'Contenido',
	'admin:plugins:category:development' => 'Desarrollo',
	'admin:plugins:category:enhancement' => 'Mejoras',
	'admin:plugins:category:api' => 'Servicio/API',
	'admin:plugins:category:communication' => 'Comunicaci&oacute;n',
	'admin:plugins:category:security' => 'Seguridad and Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Temas',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Utilidades',

	'admin:plugins:markdown:unknown_plugin' => 'Plugin desconocido',
	'admin:plugins:markdown:unknown_file' => 'Archivo desconocido',

	'admin:notices:delete_all' => 'Descartar todos los %s avisos',
	'admin:notices:could_not_delete' => 'Notificaci&oacute;n de no se pudo eliminar',
	'item:object:admin_notice' => 'Admin notice',
	'collection:object:admin_notice' => 'Avisos del administrador',

	'admin:options' => 'Opciones de Admin',

	'admin:security' => 'Seguridad',
	'admin:security:information' => 'Información',
	'admin:security:information:description' => 'On this page you can find a list of security recommendations.',
	'admin:security:information:https' => 'Is the website protected by HTTPS',
	'admin:security:information:https:warning' => "It's recommended to protect your website using HTTPS, this helps protect data
(eg. passwords) from being sniffed over the internet connection.",
	'admin:security:information:wwwroot' => 'Website main folder is writable',
	'admin:security:information:wwwroot:error' => "It's recommended that you install Elgg in a folder which isn't writable by your webserver.
Malicious visitors could place unwanted code in your website.",
	'admin:security:information:validate_input' => 'Input validation',
	'admin:security:information:validate_input:error' => "Some plugin has disabled the input validation on your website, this will allow users to
submit potentially harmfull content (eg. cross-site-scripting, etc)",
	'admin:security:information:password_length' => 'Minimal password length',
	'admin:security:information:password_length:warning' => "It's recommended to have a minimal password length of at least 6 characters.",
	'admin:security:information:username_length' => 'Minimal username length',
	'admin:security:information:username_length:warning' => "It's recommended to have a minimal username length of at least 4 characters.",
	'admin:security:information:php:session_gc' => "PHP session cleanup",
	'admin:security:information:php:session_gc:chance' => "Cleanup chance: %s%%",
	'admin:security:information:php:session_gc:lifetime' => "Session lifetime %s seconds",
	'admin:security:information:php:session_gc:error' => "It's recommended to set 'session.gc_probability' and 'session.gc_divisor' in your PHP settings, this will cleanup
expired sessions from your database and not allow users to reuse old sessions.",
	'admin:security:information:htaccess:hardening' => ".htaccess file access hardening",
	'admin:security:information:htaccess:hardening:help' => "In the .htaccess file access to certain files can be blocked to increase security on your site. For more information look in your .htaccess file.",
	
	'admin:security:settings' => 'Ajustes',
	'admin:security:settings:description' => 'En esta página puede configurar algunas características de seguridad. Por favor, lea la configuración cuidadosamente.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:account' => 'Cuenta',
	'admin:security:settings:label:notifications' => 'Notificaciones',
	'admin:security:settings:label:site_secret' => 'Secreto del sitio',
	
	'admin:security:settings:notify_admins' => 'Notificar a todos los administradores del sitio cuando se agrega o elimina un administrador',
	'admin:security:settings:notify_admins:help' => 'Esto enviará una notificación a todos los administradores del sitio de que uno de los administradores agregó o eliminó un administrador del sitio.',
	
	'admin:security:settings:notify_user_admin' => 'Notificar al usuario cuando se agrega o elimina el rol de administrador',
	'admin:security:settings:notify_user_admin:help' => 'Esto enviará una notificación al usuario de que el rol de administrador se agregó o se eliminó de su cuenta.',
	
	'admin:security:settings:notify_user_ban' => 'Notificar al usuario cuando su cuenta sea suspendida/habilitada',
	'admin:security:settings:notify_user_ban:help' => 'Esto enviara una notificación al usuario cuya cuenta ha sido suspendida/habilitada',
	
	'admin:security:settings:notify_user_password' => 'Notify the user when they change their password',
	'admin:security:settings:notify_user_password:help' => 'This will send a notification to the user when they change their password.',
	
	'admin:security:settings:protect_upgrade' => 'Proteger upgrade.php',
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
	
	'admin:security:settings:email_require_confirmation' => 'Require confirmation on email address change',
	'admin:security:settings:email_require_confirmation:help' => 'The new e-mail address needs to be confirmed before the change is in effect. After a successfull change a notification is send to the old e-mail address.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:security:settings:site_secret:regenerate' => "Regenerate site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	
	'admin:security:settings:minusername' => "Minimal username length",
	'admin:security:settings:minusername:help' => "Minimal number of characters required in a username",
	
	'admin:security:settings:min_password_length' => "Minimal password length",
	'admin:security:settings:min_password_length:help' => "Minimal number of characters required in a password",
	
	'admin:security:settings:min_password_lower' => "Minimal number of lower case characters in a password",
	'admin:security:settings:min_password_lower:help' => "Configure the minimal number of lower case (a-z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_upper' => "Minimal number of upper case characters in a password",
	'admin:security:settings:min_password_upper:help' => "Configure the minimal number of upper case (A-Z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_number' => "Minimal number of number characters in a password",
	'admin:security:settings:min_password_number:help' => "Configure the minimal number of number (0-9) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_special' => "Minimal number of special characters in a password",
	'admin:security:settings:min_password_special:help' => "Configure the minimal number of special (!@$%^&*()<>,.?/[]{}-=_+) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
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
	
	'user:notification:unban:subject' => 'Tu cuenta en %s ya no esta suspendida',
	'user:notification:unban:body' => 'Hi %s,

Your account on %s is no longer banned. You can use the site again.

To go to the site, click here:
%s',
	
	'user:notification:password_change:subject' => 'Your password has been changed!',
	'user:notification:password_change:body' => "Hi %s,

Your password on '%s' has been changed! If you made this change than you're all set.

If you didn't make this change, please reset your password here:
%s

Or contact a site administrator:
%s",
	
	'admin:notification:unvalidated_users:subject' => "Users awaiting approval on %s",
	'admin:notification:unvalidated_users:body' => "Hi %s,

%d users of '%s' are awaiting approval by an administrator.

See the full list of users here:
%s",

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Configuraci&oacute;n para el plugin %s guardada correctamente",
	'plugins:settings:save:fail' => "Ocurri&oacute; un error al intentar guardar la configuraci&oacute;n para el plugin %s",
	'plugins:settings:remove:ok' => "All settings for the %s plugin have been removed",
	'plugins:settings:remove:fail' => "An error occured while removing all settings for the plugin %s",
	'plugins:usersettings:save:ok' => "Configuraci&oacute;n del usuario para el plugin %s guardada",
	'plugins:usersettings:save:fail' => "Ocurri&oacute; un error al intentar guardar la configuraci&oacute;n del usuario para el plugin %s",
	
	'item:object:plugin' => 'Plugins',
	'collection:object:plugin' => 'Plugins',
	
	'plugins:settings:remove:menu:text' => "Remove all settings",
	'plugins:settings:remove:menu:confirm' => "Are you sure you wish to remove all settings, including user settings from this plugin?",

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activar todos',
	'admin:plugins:deactivate_all' => 'Desactivar todos',
	'admin:plugins:activate' => 'Activar',
	'admin:plugins:deactivate' => 'Desactivar',
	'admin:plugins:description' => "Este panel le permite controlar y configurar las herramientas instaladas en su sitio",
	'admin:plugins:opt:linktext' => "Configurar herramientas..",
	'admin:plugins:opt:description' => "Configurar las herramientas instaladas en el sitio. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Nombre",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categor&iacute;as',
	'admin:plugins:label:licence' => "Licencia",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Información",
	'admin:plugins:label:files' => "Archivos",
	'admin:plugins:label:resources' => "Recursos",
	'admin:plugins:label:screenshots' => "Capturas de pantalla",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Report issue",
	'admin:plugins:label:donate' => "Donate",
	'admin:plugins:label:moreinfo' => 'mas informaci&oacute;n',
	'admin:plugins:label:version' => 'Versi&oacute;n',
	'admin:plugins:label:location' => 'Ubicacion',
	'admin:plugins:label:priority' => 'Prioridad',
	'admin:plugins:label:contributors' => 'Colaboradores',
	'admin:plugins:label:contributors:name' => 'Nombre',
	'admin:plugins:label:contributors:email' => 'Correo Electrónico',
	'admin:plugins:label:contributors:website' => 'Sitio Web',
	'admin:plugins:label:contributors:username' => 'Nombre de Usuario de la Comunidad',
	'admin:plugins:label:contributors:description' => 'Descripci&oacute;n completa',
	'admin:plugins:label:dependencies' => 'Dependencias',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'Este plugin tiene dependencias desconocidas y no se activar&aacute;. Consulte las dependencias debajo de mas informaci&oacute;n',
	'admin:plugins:warning:invalid' => '%s no es un plugin Elgg v&aacute;lido. Visite <a href="http://docs.elgg.org/Invalid_Plugin">la Documentaci&oacute;n Elgg</a> para consejos de soluci&oacute;n de problemas',
	'admin:plugins:warning:invalid:check_docs' => 'Mira <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">la documentación de Elgg</a> donde encontrarás consejos de resolución de problemas.',
	'admin:plugins:cannot_activate' => 'no se puede activar',
	'admin:plugins:cannot_deactivate' => 'no se puede desactivar',
	'admin:plugins:already:active' => 'Los plugins seleccionados ya están activos.',
	'admin:plugins:already:inactive' => 'Los plugins seleccionados ya no están activos',

	'admin:plugins:set_priority:yes' => "Reordenar %s",
	'admin:plugins:set_priority:no' => "No se puede reordenar %s",
	'admin:plugins:set_priority:no_with_msg' => "No se pudo reordenar %s. Error: %s",
	'admin:plugins:deactivate:yes' => "Desactivar %s",
	'admin:plugins:deactivate:no' => "No se puede desactivar %s",
	'admin:plugins:deactivate:no_with_msg' => "Mo se pudo desactivar %s. Error: %s",
	'admin:plugins:activate:yes' => "Activado %s",
	'admin:plugins:activate:no' => "No se puede activar %s",
	'admin:plugins:activate:no_with_msg' => "No se pudo activar %s. Error: %s",
	'admin:plugins:categories:all' => 'Todas las categor&iacute;as',
	'admin:plugins:plugin_website' => 'Sitio del plugin',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versi&oacute;n %s',
	'admin:plugin_settings' => 'Configuraci&oacute;n del plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'El plugin se ecnuentra activo pero posee dependencias desconocidas. Puede que se encuentren problemas en su funcionamiento. Vea "mas informaci&oacute;n" debajo para detalles',

	'admin:plugins:dependencies:type' => 'Tipo',
	'admin:plugins:dependencies:name' => 'Nombre',
	'admin:plugins:dependencies:expected_value' => 'Valor de Test',
	'admin:plugins:dependencies:local_value' => 'Valor Actual',
	'admin:plugins:dependencies:comment' => 'Comentario',

	'admin:statistics:description' => "Este es un resumen de las estad&iacute;sticas del sitio. Si necesita estad&iacute;sticas mas avanzadas, hay dispoinble una funcionalidad de administraci&oacute;n profesional",
	'admin:statistics:opt:description' => "Ver informaci&oacute;n estad&iacute;stica sobre usuarios y objetos en el sitio",
	'admin:statistics:opt:linktext' => "Ver estad&iacute;sticas..",
	'admin:statistics:label:user' => "Estadísticas de usuario",
	'admin:statistics:label:numentities' => "Entidades del sitio",
	'admin:statistics:label:numusers' => "Cantidad de usuarios",
	'admin:statistics:label:numonline' => "Cantidad de usuarios conectados",
	'admin:statistics:label:onlineusers' => "Usuarios conectados en este momento",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Versi&oacute;n de Elgg",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versi&oacute;n",
	'admin:statistics:label:version:code' => "Versión de Código",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Requisitos',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Mostrar PHPInfo',
	'admin:server:label:web_server' => 'Servidor Web',
	'admin:server:label:server' => 'Servidor',
	'admin:server:label:log_location' => 'Localizaci&oacute;n de los registros',
	'admin:server:label:php_version' => 'Versi&oacute;n de PHP',
	'admin:server:label:php_version:required' => 'Elgg requires a minimal PHP version of 7.1',
	'admin:server:label:php_ini' => 'Ubicaci&oacute;n del archivo PHP ini',
	'admin:server:label:php_log' => 'Registros de PHP',
	'admin:server:label:mem_avail' => 'Memoria disponible',
	'admin:server:label:mem_used' => 'Memoria usada',
	'admin:server:error_log' => "Registro de errores del servidor Web",
	'admin:server:label:post_max_size' => 'Tama&ntilde;o m&aacute;ximo de las peticiones POST',
	'admin:server:label:upload_max_filesize' => 'Tama&ntilde; m&aacute;ximo de las subidas',
	'admin:server:warning:post_max_too_small' => '(Nota: post_max_size debe ser mayor que el tama&ntilde; indicado aqu&iacute; para habilitar las subidas)',
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
	
	'admin:server:requirements:php_extension' => "Extensi&oacute;n PHP: %s",
	'admin:server:requirements:php_extension:required' => "This PHP extension is required for the correct operation of Elgg",
	'admin:server:requirements:php_extension:recommended' => "This PHP extension is recommended for the optimal operation of Elgg",
	'admin:server:requirements:rewrite' => ".htaccess rewrite rules",
	'admin:server:requirements:rewrite:fail' => "Check your .htaccess for the correct rewrite rules",
	
	'admin:server:requirements:database:server' => "Database server",
	'admin:server:requirements:database:server:required' => "Elgg requires MySQL v5.5.3 or higher for its database",
	'admin:server:requirements:database:client' => "Database client",
	'admin:server:requirements:database:client:required' => "Elgg requires pdo_mysql to connect to the database server",
	
	'admin:user:label:search' => "Encontrar usuarios:",
	'admin:user:label:searchbutton' => "Buscar",

	'admin:user:ban:no' => "No puede bloquear al usuario",
	'admin:user:ban:yes' => "Usuario bloqueado",
	'admin:user:self:ban:no' => "No puede bloquearse a usted mismo",
	'admin:user:unban:no' => "No puede desbloquear al usuario",
	'admin:user:unban:yes' => "Usuario desbloqueado",
	'admin:user:delete:no' => "No puede eliminar al usuario",
	'admin:user:delete:yes' => "El usuario %s ha sido eliminado",
	'admin:user:self:delete:no' => "No puede eliminarse a usted mismo",

	'admin:user:resetpassword:yes' => "Contrase&ntilde;a restablecida, se notifica al usuario",
	'admin:user:resetpassword:no' => "No se puede restablecer la contrase&ntilde;a",

	'admin:user:makeadmin:yes' => "El usuario ahora es un administrador",
	'admin:user:makeadmin:no' => "No se pudo establecer al usuario como administrador",

	'admin:user:removeadmin:yes' => "El usuario ya no es administrador",
	'admin:user:removeadmin:no' => "No se pueden quitar los privilegios de administrador de este usuario",
	'admin:user:self:removeadmin:no' => "No puede quitar sus privilegios de administrador",

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configurar los elementos del men&uacute; principal',
	'admin:menu_items:description' => 'Select the order of site menu items. Unconfigured items will be added to the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Quitar enlaces del men&uacute; de la barra de herramientas?',
	'admin:menu_items:saved' => 'Elementos del men&uacute; guardados',
	'admin:add_menu_item' => 'Agregar un elemento del men&uacute; personalizado',
	'admin:add_menu_item:description' => 'Complete el nombre para mostrar y la direcci&oacute;n url para agregar un elemento de men&uacute; personalizado',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Tipo de widget desconocido',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Editar el robots.txt de este sitio a continuación",
	'admin:robots.txt:plugins' => "Plugins estan agregando lo siguiente al archivo robots.txt",
	'admin:robots.txt:subdir' => "La herramienta de robots.txt no funcionara por que Elgg esta instalado en un sub-directorio",
	'admin:robots.txt:physical' => "La herramienta robots.txt no funcionará porque hay una archivo físico robots.txt",

	'admin:maintenance_mode:default_message' => 'El sitio no está disponible por mantenimiento',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Modo de Mantenimiento',
	'admin:maintenance_mode:message_label' => 'Mensaje que se mostrará a los usuarios cuando el modo de mantenimiento este activado',
	'admin:maintenance_mode:saved' => 'Las configuraciones del modo de mantenimiento fueron guardadas',
	'admin:maintenance_mode:indicator_menu_item' => 'El sitio esta en modo de mantenimiento',
	'admin:login' => 'Entrada de Administradores',

/**
 * User settings
 */

	'usersettings:description' => "El panel de configuraci&oacute;n permite parametrizar sus preferencias personales, desde la administraci&oacute;n de usuarios al comportamiento de los plugins. Seleccione una opci&oacute;n debajo para comenzar",

	'usersettings:statistics' => "Sus estad&iacute;sticas",
	'usersettings:statistics:opt:description' => "Ver informaci&oacute;n estad&iacute;stica de usuarios y objectos en la red",
	'usersettings:statistics:opt:linktext' => "Estad&iacute;sticas de la cuenta",

	'usersettings:statistics:login_history' => "Historial de inicio de sesión",
	'usersettings:statistics:login_history:date' => "Fecha",
	'usersettings:statistics:login_history:ip' => "Dirección IP",

	'usersettings:user' => "Sus preferencias",
	'usersettings:user:opt:description' => "Esto le permite establecer sus preferencias",
	'usersettings:user:opt:linktext' => "Modificar sus preferencias",

	'usersettings:plugins' => "Herramientas",
	'usersettings:plugins:opt:description' => "Preferencias de Configuraci&oacute;n para sus herramientas activas",
	'usersettings:plugins:opt:linktext' => "Configure sus herramientas",

	'usersettings:plugins:description' => "Este panel le permite establecer sus preferencias personales para las herramientas habilitadas por el administrador del sistema",
	'usersettings:statistics:label:numentities' => "Su contenido",

	'usersettings:statistics:yourdetails' => "Sus detalles",
	'usersettings:statistics:label:name' => "Nombre completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membro desde",
	'usersettings:statistics:label:lastlogin' => "&uacute;ltimo acceso",

/**
 * Activity river
 */

	'river:all' => 'Actividad de toda la red',
	'river:mine' => 'Mi Actividad',
	'river:owner' => 'Actividad de %s',
	'river:friends' => 'Actividad de Amigos',
	'river:select' => 'Mostrar %s',
	'river:comments:more' => '%u m&aacute;s',
	'river:comments:all' => 'Ver todos los comentarios de %u',
	'river:generic_comment' => 'comentado en %s %s',

/**
 * Icons
 */

	'icon:size' => "Tama&ntilde;o del &iacute;cono",
	'icon:size:topbar' => "Barra principal",
	'icon:size:tiny' => "Chiquito",
	'icon:size:small' => "Pequeño",
	'icon:size:medium' => "Mediano",
	'icon:size:large' => "Grande",
	'icon:size:master' => "Extra Grande",
	
	'entity:edit:icon:crop_messages:generic' => "The selected image doesn't meet the recommended image dimensions. This could result in low quality icons.",
	'entity:edit:icon:crop_messages:width' => "It's recommended to use an image with a minimal width of at least %dpx.",
	'entity:edit:icon:crop_messages:height' => "It's recommended to use an image with a minimal height of at least %dpx.",
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Guardar",
	'save_go' => "Save, and go to %s",
	'reset' => 'Reiniciar',
	'publish' => "Publicar",
	'cancel' => "Cancelar",
	'saving' => "Guardando..",
	'update' => "Actualizar",
	'preview' => "Previsualizar",
	'edit' => "Editar",
	'delete' => "Eliminar",
	'accept' => "Aceptar",
	'reject' => "Rechazar",
	'decline' => "Declinar",
	'approve' => "Aprobar",
	'activate' => "Activar",
	'deactivate' => "Desactivar",
	'disapprove' => "Desaprobar",
	'revoke' => "Revocar",
	'load' => "Cargar",
	'upload' => "Subir",
	'download' => "Descargar",
	'ban' => "Bloquear",
	'unban' => "Desbloquar",
	'banned' => "Bloqueado",
	'enable' => "Habilitar",
	'disable' => "Deshabilitar",
	'request' => "Solicitud",
	'complete' => "Completa",
	'open' => 'Abrir',
	'close' => 'Cerrar',
	'hide' => 'Ocuiltar',
	'show' => 'Mostrar',
	'reply' => "Responder",
	'more' => 'M&aacute;s',
	'more_info' => 'M&aacute;s informaci&oacute;n',
	'comments' => 'Comentarios',
	'import' => 'Importar',
	'export' => 'Exportar',
	'untitled' => 'Sin T&iacute;tulo',
	'help' => 'Ayuda',
	'send' => 'Enviar',
	'post' => 'Publicar',
	'submit' => 'Enviar',
	'comment' => 'Comentar',
	'upgrade' => 'Actualizar',
	'sort' => 'Ordenar',
	'filter' => 'Filtrar',
	'new' => 'Nuevo',
	'add' => 'Añadir',
	'create' => 'Crear',
	'remove' => 'Remover',
	'revert' => 'Revertir',
	'validate' => 'Validar',
	'read_more' => 'Leer mas',

	'site' => 'Sitio',
	'activity' => 'Actividad',
	'members' => 'Miembros',
	'menu' => 'Men&uacute;',

	'up' => 'Arriba',
	'down' => 'Abajo',
	'top' => 'Primero',
	'bottom' => 'Ultimo',
	'right' => 'Derecha',
	'left' => 'Izquierda',
	'back' => 'Atrás',

	'invite' => "Invitar",

	'resetpassword' => "Restablecer contrase&ntilde;a",
	'changepassword' => "Cambiar contraseña",
	'makeadmin' => "Hacer administrador",
	'removeadmin' => "Quitar administrador",

	'option:yes' => "S&iacute;",
	'option:no' => "No",

	'unknown' => 'Desconocido',
	'never' => 'Nunca',

	'active' => 'Activo',
	'total' => 'Total',
	'unvalidated' => 'No validado',
	
	'ok' => 'OK',
	'any' => 'Cualquiera',
	'error' => 'Error',

	'other' => 'Otro',
	'options' => 'Opciones',
	'advanced' => 'Avanzado',

	'learnmore' => "Click aqu&iacute; para ver m&aacute;s",
	'unknown_error' => 'Error desconocido',

	'content' => "contenido",
	'content:latest' => '&uacute;ltima actividad',
	'content:latest:blurb' => 'Alternativamente, click aqu&iacute; para ver el &uacute;ltimo contenido en toda la red',
	
	'list:out_of_bounds' => "You have reached a part of the list without any content, however there is content available.",
	'list:out_of_bounds:link' => "Go back to the first page of this listing.",

	'link:text' => 'ver link',

/**
 * Generic questions
 */

	'question:areyousure' => '&iquest;Est&aacute; seguro?',

/**
 * Status
 */

	'status' => 'Estado',
	'status:unsaved_draft' => 'Borrador sin guardar',
	'status:draft' => 'Borrador',
	'status:unpublished' => 'Sin Publicar',
	'status:published' => 'Publicado',
	'status:featured' => 'Destacados',
	'status:open' => 'Abierto',
	'status:closed' => 'Cerrado',
	'status:enabled' => 'Enabled',
	'status:disabled' => 'Disabled',
	'status:unavailable' => 'Unavailable',
	'status:active' => 'Activo',
	'status:inactive' => 'Inactive',

/**
 * Generic sorts
 */

	'sort:newest' => 'M&aacute;s nuevo',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alfab&eacute;tico',
	'sort:priority' => 'Prioridad',

/**
 * Generic data words
 */

	'title' => "T&iacute;tulo",
	'description' => "Descripci&oacute;n",
	'tags' => "Tags",
	'all' => "Todo",
	'mine' => "M&iacute;o",

	'by' => 'por',
	'none' => 'nada',

	'annotations' => "Anotaciones",
	'relationships' => "Relaciones",
	'metadata' => "Metadata",
	'tagcloud' => "Nube de tags",

	'on' => 'Habilitado',
	'off' => 'Deshabilitado',

	'number_counter:decimal_separator' => ".",
	'number_counter:thousands_separator' => ",",
	'number_counter:view:thousand' => "%sK",
	'number_counter:view:million' => "%sM",
	'number_counter:view:billion' => "%sB",
	'number_counter:view:trillion' => "%sT",

/**
 * Entity actions
 */

	'edit:this' => 'Editar',
	'delete:this' => 'Eliminar',
	'comment:this' => 'Comentar',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Est&aacute; seguro de eliminar este item?",
	'deleteconfirm:plural' => "&ntilde;Seguro que deseas borrar estos elementos?",
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'Cuenta de usuario creada',
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

	'systemmessages:dismiss' => "click para cerrar",


/**
 * Messages
 */
	'messages:title:success' => 'Éxito',
	'messages:title:error' => 'Error',
	'messages:title:warning' => 'Advertencia',
	'messages:title:help' => 'Ayuda',
	'messages:title:notice' => 'Aviso',
	'messages:title:info' => 'Información',

/**
 * Import / export
 */

	'importsuccess' => "Importaci&oacute;n exitosa",
	'importfail' => "Error al importar datos de OpenDD",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "ahora",
	'friendlytime:minutes' => "hace %s minutos",
	'friendlytime:minutes:singular' => "hace un minuto",
	'friendlytime:hours' => "hace %s horas",
	'friendlytime:hours:singular' => "hace una hora",
	'friendlytime:days' => "hace %s d&iacute;as",
	'friendlytime:days:singular' => "ayer",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "en %s minutos",
	'friendlytime:future:minutes:singular' => "en un minuto",
	'friendlytime:future:hours' => "en %s horas",
	'friendlytime:future:hours:singular' => "en una hora",
	'friendlytime:future:days' => "en %s días",
	'friendlytime:future:days:singular' => "mañana",

	'date:month:01' => 'Enero %s',
	'date:month:02' => 'Febrero %s',
	'date:month:03' => 'Marzo %s',
	'date:month:04' => 'Abril %s',
	'date:month:05' => 'Mayo %s',
	'date:month:06' => 'Junio %s',
	'date:month:07' => 'Julio %s',
	'date:month:08' => 'Agosto %s',
	'date:month:09' => 'Septiembre %s',
	'date:month:10' => 'Octubre %s',
	'date:month:11' => 'Noviembre %s',
	'date:month:12' => 'Diciembre %s',

	'date:month:short:01' => 'Ene %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Abr %s',
	'date:month:short:05' => 'May %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Ago %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dic %s',

	'date:weekday:0' => 'Domingo',
	'date:weekday:1' => 'Lunes',
	'date:weekday:2' => 'Martes',
	'date:weekday:3' => 'Miércoles',
	'date:weekday:4' => 'Jueves',
	'date:weekday:5' => 'Viernes',
	'date:weekday:6' => 'Sábado',

	'date:weekday:short:0' => 'Dom',
	'date:weekday:short:1' => 'Lun',
	'date:weekday:short:2' => 'Mar',
	'date:weekday:short:3' => 'Mie',
	'date:weekday:short:4' => 'Jue',
	'date:weekday:short:5' => 'Vie',
	'date:weekday:short:6' => 'Sab',

	'interval:minute' => 'Cada minuto',
	'interval:fiveminute' => 'Cada cinco minutos',
	'interval:fifteenmin' => 'Cada quince minutos',
	'interval:halfhour' => 'Cada media hora',
	'interval:hourly' => 'Cada hora',
	'interval:daily' => 'Diario',
	'interval:weekly' => 'Semanal',
	'interval:monthly' => 'Mensual',
	'interval:yearly' => 'Anual',

/**
 * System settings
 */

	'installation:sitename' => "El nombre del sitio:",
	'installation:sitedescription' => "Breve descripci&oacute;n del sitio (opcional):",
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
	'installation:wwwroot' => "URL del sitio:",
	'installation:path' => "El path completo a la instalaci&oacute;n de Elgg:",
	'installation:dataroot' => "El path completo al directorio de datos:",
	'installation:dataroot:warning' => "Debe crear este directorio manualmente. Debe encontrarse en un directorio diferente al de la instalaci&oacute;n de Elgg",
	'installation:sitepermissions' => "Permisos de acceso por defecto:",
	'installation:language' => "Lenguaje por defecto para el sitio:",
	'installation:debug' => "El modo Debug provee informaci&oacute;n extra que puede utilizarse para evaluar eventualidades. Puede enlentecer el funcionamiento del sistema y debe utilizarse s&oacute;lo cuando se detectan problemas:",
	'installation:debug:label' => "Nivel del registro:",
	'installation:debug:none' => 'Desactivar modo Debug (reomendado)',
	'installation:debug:error' => 'Mostrar s&oacute;lo errores cr&iacute;ticos',
	'installation:debug:warning' => 'Mostrar s&oacute;lo alertas cr&iacute;ticas',
	'installation:debug:notice' => 'Mostrar todos los errores, alertas e informaciones de eventos',
	'installation:debug:info' => 'Registrar todo',

	// Walled Garden support
	'installation:registration:description' => 'La registraci&oacute;n de usuarios se encuentra habilitada por defecto. Puede deshabilitarla para impedir que nuevos usuarios se registren por s&iacute; mismos',
	'installation:registration:label' => 'Permitir el registro de nuevos usuarios',
	'installation:adminvalidation:description' => 'If enabled, newly registered users require manual validation by an administrator before they can use the site.',
	'installation:adminvalidation:label' => 'New users require manual validation by an administrator',
	'installation:adminvalidation:notification:description' => 'When enabled, site administrators will get a notification that there are pending user validations. An administrator can disable the notification on their personal settings page.',
	'installation:adminvalidation:notification:label' => 'Notify administrators of pending user validations',
	'installation:adminvalidation:notification:direct' => 'Direct',
	'installation:walled_garden:description' => 'Habilitar al sitio para ejecutarse como una red privada. Esto impedir&aacute; a usuarios no registrados visualizar cualquier p&aacute;gina del sitio, exceptuando las establecidas como p&uacute;blicas',
	'installation:walled_garden:label' => 'Restringir p&aacute;ginas a usuarios registrados',

	'installation:view' => "Ingrese la vista que se visualizar&aacute; por defecto en el sitio o deje esto en blanco para la vista por defecto (si tiene dudas, d&eacute;jelo por defecto):",

	'installation:siteemail' => "Direcci&oacute;n de Email del sitio (utilizada para enviar mails desde el sistema):",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "N&uacute;mero por defecto de elementos por p&aacute;gina",

	'admin:site:access:warning' => "Las modificaciones en el control de accesos s&oacute;lo tendr&aacute; impacto en los accesos futuros",
	'installation:allow_user_default_access:description' => "Si se selecciona, se les permitir&aacute; a los usuarios establecer su propio nivel de acceso por defecto que puede sobreescribir los niveles de acceso del sistema",
	'installation:allow_user_default_access:label' => "Permitir el acceso por defecto de los usuarios",

	'installation:simplecache:description' => "La cache simple aumenta el rentimiento almacenando contenido est&aacute;tico, como hojas CSS y archivos JavaScript. Normalmente se desea esto activado",
	'installation:simplecache:label' => "Utilizar cache simple (recomendado)",

	'installation:cache_symlink:description' => "El enlace simbólico al directorio de caché simple permite que el servidor sirva vistas estáticas sin pasar por el motor, lo que mejora considerablemente el rendimiento y reduce la carga del servidor",
	'installation:cache_symlink:label' => "Usar un enlace simbólico para el directorio de caché simple (recomendado)",
	'installation:cache_symlink:warning' => "Se ha creado el enlace simbólico. Si, por alguna razón, quieres quitar el enlace, borra el enlace simbólico al directorio de tu servidor",
	'installation:cache_symlink:paths' => 'El enlace simbólico correctamente configurado debe vincularse desde <i>%s</i> a <i>%s</i>',
	'installation:cache_symlink:error' => "No se puede generar el enlace simbólico por la configuración de tu servidor. Por favor consulta el manual para crear manualmente el enlace simbólico",

	'installation:minify:description' => "El simple cache puede también mejorar el rendimiento por que comprime los archivos JavaScript y CSS. (Requiere que simple cache este habilitado.)",
	'installation:minify_js:label' => "Comprimir JavaScript (recomendado)",
	'installation:minify_css:label' => "Comprimir CSS (recomendado)",

	'installation:htaccess:needs_upgrade' => "Debe actualizar el archivo .htaccess para que la ruta se inyecte en el parámetro GET __elgg_uri (puede usar install/config/htaccess.dist como guía)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg no puede conectarse a sí mismo para poner a prueba las reglas de substitución. Asegúrese de que «curl» está funcionando, y de que no existen restricciones por dirección IP que impidan las conexiones locales al propio servidor (localhost).",

	'installation:systemcache:description' => "The system cache decreases the loading time of the Elgg engine by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caché',
	'admin:legend:content' => 'Contenido',
	'admin:legend:content_access' => 'Acceso del Contenido',
	'admin:legend:site_access' => 'Acceso del Sitio',
	'admin:legend:debug' => 'Depuración y registro',
	
	'config:i18n:allowed_languages' => "Allowed languages",
	'config:i18n:allowed_languages:help' => "Only allowed languages can be used by users. English and the site language are always allowed.",
	'config:users:can_change_username' => "Allow users to change their username",
	'config:users:can_change_username:help' => "If not allowed only admins can change a users username",
	'config:remove_branding:label' => "Eliminar marca de Elgg",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	'config:content:comment_box_collapses' => "The comment box collapses after the first comment on content",
	'config:content:comment_box_collapses:help' => "This only applies if the comments list is sorted latest first",
	'config:content:comments_latest_first' => "The comments should be listed with the latest comment first",
	'config:content:comments_latest_first:help' => "This controls the default behaviour of the listing of comments on a content detail page. If disabled this will also move the comment box to the end of the comments list",
	
	'upgrading' => 'Actualizando..',
	'upgrade:core' => 'La instalaci&oacute;n de Elgg ha sido actualizada',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Desbloqueo de Actualización exitoso.",
	'upgrade:unable_to_upgrade' => 'No se puede actualizar',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'La API OAuth (anteriormente OAuth Lib) se ha desactivado durante la actualizaci&oacute;n. Por favor act&iacute;vela manualmente si se requiere',
	'upgrade:site_secret_warning:moderate' => "Te recomendamos que regeneres la clave de tu sitio para mejorar la seguridad del sistema. Ver Configuración &gt; Preferencias &gt; Avanzado",
	'upgrade:site_secret_warning:weak' => "Te recomendamos fuertemente que regeneres la clave de tu sitio para mejorar la seguridad del sistema. Ver Configuración &gt; Preferencias &gt; Avanzado",

	'deprecated:function' => '%s() ha quedado obsoleta por %s()',

	'admin:pending_upgrades' => 'Este sitio tiene actualizaciones pendientes que requieren tu atención inmediata',
	'admin:view_upgrades' => 'Ver actualizaciones pendientes',
	'item:object:elgg_upgrade' => 'Actualizaciones del sitio',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Tu instalación esta al día!',

	'upgrade:item_count' => 'Hay <b>%s</b> elementos que es necesario actualizar.',
	'upgrade:warning' => '<b>Aviso:</b> ¡En un sitio grande esta actualización puede llevar un tiempo significativo!',
	'upgrade:success_count' => 'Actualizado:',
	'upgrade:error_count' => 'Errores:',
	'upgrade:finished' => 'Se completó la actualización.',
	'upgrade:finished_with_errors' => '<p>Ocurrieron errores durante la actualización. Actualice la página y pruebe a ejecutar la actualización de nuevo.</p></p><br />
Si el error se repite, busque la causa en el registro de errores del servidor. Puede buscar ayuda para solucionar el problema en el <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupo de asistencia técnica</a> de la comunidad de Elgg.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Alinear columnas GUID de la base de datos',
	
/**
 * Welcome
 */

	'welcome' => "Bienvenido",
	'welcome:user' => 'Bienvenido %s',

/**
 * Emails
 */

	'email:from' => 'De',
	'email:to' => 'Para',
	'email:subject' => 'Asunto',
	'email:body' => 'Cuerpo',

	'email:settings' => "Configuraci&oacute;n de Email",
	'email:address:label' => "Direcci&oacute;n de Email",
	'email:address:help:confirm' => "Pending e-mail address change to '%s', please check the inbox for instructions.",
	'email:address:password' => "Contraseña",
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "New email address saved.",
	'email:save:fail' => "No se pudo guardar la nueva direcci&oacute;n de Email",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s te ha puesto como amigo suyo!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "Contraseña cambiada!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Contrase&ntilde;a restablecida!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "Solicitud de cambio de contraseña.",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

%s",
	
	'account:email:request:success' => "Your new e-mail address will be saved after confirmation, please check the inbox of '%s' for more instructions.",
	'email:request:email:subject' => "Please confirm your e-mail address",
	'email:request:email:body' => "Hi %s,

You requested to change your e-mail address on '%s'.
If you didn't request this change, you can ignore this email.

In order to confirm the e-mail address change, please click this link:
%s

Please note this link is only valid for 1 hour.",
	
	'account:email:request:error:no_new_email' => "No e-mail address change pending",
	
	'email:confirm:email:old:subject' => "You're e-mail address was changed",
	'email:confirm:email:old:body' => "Hi %s,

Your e-mail address on '%s' was changed.
From now on you'll receive notifications on '%s'.

If you didn't request this change, please contact a site administrator.
%s",
	
	'email:confirm:email:new:subject' => "You're e-mail address was changed",
	'email:confirm:email:new:body' => "Hi %s,

Your e-mail address on '%s' was changed.
From now on you'll receive notifications on this e-mail address.

If you didn't request this change, please contact a site administrator.
%s",

	'account:email:admin:validation_notification' => "Notify me when there are users requiring validation by an administrator",
	'account:email:admin:validation_notification:help' => "Because of the site settings, newly registered users require manual validation by an administrator. With this setting you can disable notifications about pending validation requests.",
	
	'account:validation:pending:title' => "Account validation pending",
	'account:validation:pending:content' => "Your account has been registered successfully! However before you can use you account a site administrator needs to validate you account. You'll receive an e-mail when you account is validated.",
	
	'account:notification:validation:subject' => "Your account on %s has been validated!",
	'account:notification:validation:body' => "Hi %s,

Your account on '%s' has been validated. You can now use your account.

To go the the website, click here:
%s",

/**
 * user default access
 */

	'default_access:settings' => "Tu nivel de acceso por defecto",
	'default_access:label' => "Acceso por defecto",
	'user:default_access:success' => "El nivel de acceso por defecto ha sido guardado",
	'user:default_access:failure' => "El nivel de acceso por defecto no ha podido ser guardado",

/**
 * Comments
 */

	'comments:count' => "%s comentarios",
	'item:object:comment' => 'Comments',
	'collection:object:comment' => 'Comentarios',

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "Comentar",
	'generic_comments:edit' => "Editar comentario",
	'generic_comments:post' => "Publicar un comentario",
	'generic_comments:text' => "Comentar",
	'generic_comments:latest' => "&uacute;ltimos comentarios",
	'generic_comment:posted' => "Se ha publicado su comentario",
	'generic_comment:updated' => "El comentario fué cambiado éxitosamente.",
	'entity:delete:object:comment:success' => "Se ha quitado su comentario",
	'generic_comment:blank' => "Lo sentimos, debe ingresar alg&uacute;n comentario antes de poder guardarlo",
	'generic_comment:notfound' => "Lo sentimos. No hemos encontrado el comentario especificado.",
	'generic_comment:notfound_fallback' => "Lo sentimos. No hemos encontrado el comentario especificado, pero te hemos redirigido a la página donde se comentó.",
	'generic_comment:failure' => "Un error no especificado ocurrió al guardar el comentario.",
	'generic_comment:none' => 'Sin comentarios',
	'generic_comment:title' => 'Comentario de %s',
	'generic_comment:on' => '%s on %s',
	'generic_comments:latest:posted' => 'Publicó un',

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

	'byline' => 'Por %s',
	'byline:ingroup' => 'en el grupo %s',
	'entity:default:missingsupport:popup' => 'Esta entidad no puede mostrarse correctamente. Esto puede deberse a que el soporte provisto por un plugin ya no se encuentra instalado',

	'entity:delete:item' => 'Elemento',
	'entity:delete:item_not_found' => 'No se ha encontrado el elemento',
	'entity:delete:permission_denied' => 'No tiene permisos para borrar este elemento',
	'entity:delete:success' => '%s se ha borrado.',
	'entity:delete:fail' => '%s no se ha podido borrar.',

	'entity:can_delete:invaliduser' => 'No se puede comprobar canDelete() para el user_guid [%s] porque el usuario no existe.',

/**
 * Annotations
 */
	
	'annotation:delete:fail' => "An error occured while removing the annotation",
	'annotation:delete:success' => "The annotation was removed successfully",
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'En el formulario faltan __token o campos __ts',
	'actiongatekeeper:tokeninvalid' => "The page you were using had expired. Please try again.",
	'actiongatekeeper:timeerror' => 'La p&aacute;gina que se encontraba utilizando ha expirado. Por favor refresque la p&aacute;gina e intente nuevamente',
	'actiongatekeeper:pluginprevents' => 'Lo sentimos. No se ha podido enviar el formulario por motivos desconocidos.',
	'actiongatekeeper:uploadexceeded' => 'El tama&ntilde;o del(los) archivo(s) supera el m&iacute;mite establecido',
	'actiongatekeeper:crosssitelogin' => "Sorry, logging in from a different domain is not permitted. Please try again.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Failed to contact %s. You may experience problems saving content. Please refresh this page.',
	'js:security:token_refreshed' => 'La conexi&oacute;n a %s ha sido restaurada!',
	'js:lightbox:current' => "imagen %s de %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Creado con Elgg",
	
/**
 * Cli commands
 */
	'cli:login:error:unknown' => "Unable to login as %s",
	'cli:login:success:log' => "Logged in as %s [guid: %s]",
	'cli:response:output' => "Response:",
	'cli:option:as' => "Execute the command on behalf of a user with the given username",
	'cli:option:language' => "Execute the command in the given language (eg. en, nl or de)",
	
	'cli:cache:clear:description' => "Clear Elgg caches",
	'cli:cache:invalidate:description' => "Invalidate Elgg caches",
	'cli:cache:purge:description' => "Purge Elgg caches",
	
	'cli:cron:description' => "Execute cron handlers for all or specified interval",
	'cli:cron:option:interval' => "Name of the interval (e.g. hourly)",
	'cli:cron:option:force' => "Force cron commands to run even if they are not yet due",
	'cli:cron:option:time' => "Time of the cron initialization",
	
	'cli:database:seed:description' => "Seeds the database with fake entities",
	'cli:database:seed:option:limit' => "Number of entities to seed",
	'cli:database:seed:option:image_folder' => "Path to a local folder containing images for seeding",
	'cli:database:seed:log:error:faker' => "This is a developer tool currently intended for testing purposes only. Please refrain from using it.",
	'cli:database:seed:log:error:logged_in' => "Database seeding should not be run with a logged in user",
	
	'cli:database:unseed:description' => "Removes seeded fake entities from the database",
	
	'cli:plugins:activate:description' => "Activate plugin(s)",
	'cli:plugins:activate:option:force' => "Resolve conflicts by deactivating conflicting plugins and enabling required ones",
	'cli:plugins:activate:argument:plugins' => "Plugin IDs to be activated",
	'cli:plugins:activate:progress:start' => "Activating plugins",
	
	'cli:plugins:deactivate:description' => "Deactivate plugin(s)",
	'cli:plugins:deactivate:option:force' => "Force deactivation of all dependent plugins",
	'cli:plugins:deactivate:argument:plugins' => "Plugin IDs to be deactivated",
	'cli:plugins:deactivate:progress:start' => "Deactivating plugins",
	
	'cli:plugins:list:description' => "List all plugins installed on the site",
	'cli:plugins:list:option:status' => "Plugin status ( %s )",
	'cli:plugins:list:option:refresh' => "Refresh plugin list with recently installed plugins",
	'cli:plugins:list:error:status' => "%s is not a valid status. Allowed options are: %s",
	
	'cli:simpletest:description' => "Run simpletest test suite (deprecated)",
	'cli:simpletest:option:config' => "Path to settings file that the Elgg Application should be bootstrapped with",
	'cli:simpletest:option:plugins' => "A list of plugins to enable for testing or 'all' to enable all plugins",
	'cli:simpletest:option:filter' => "Only run tests that match filter pattern",
	'cli:simpletest:error:class' => "You must install your Elgg application using '%s'",
	'cli:simpletest:error:file' => "%s is not a valid simpletest class",
	'cli:simpletest:output:summary' => "Time: %.2f seconds, Memory: %.2fMb",
	
	'cli:upgrade:batch:description' => "Executes one or more upgrades",
	'cli:upgrade:batch:argument:upgrades' => "One or more upgrades (class names) to be executed",
	'cli:upgrade:batch:option:force' => "Run upgrade even if it has been completed before",
	'cli:upgrade:batch:finished' => "Running upgrades finished",
	'cli:upgrade:batch:notfound' => "No upgrade class found for %s",

	'cli:upgrade:list:description' => "Lists all upgrades in the system",
	'cli:upgrade:list:completed' => "Actualizaciones completadas",
	'cli:upgrade:list:pending' => "Actualizaciones pendientes",
	'cli:upgrade:list:notfound' => "No upgrades found",
	
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
	"cmn" => "Chino Mandarín", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Espa&ntilde;ol",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Euskera (España)",
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
	//"in" => "Indonesian",
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
	"pt_br" => "Portugués (Brasil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Rumano (Rumanía)",
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
	"sr_latin" => "Servio (Latino)",
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
	"zh_hans" => "Chino Simplificado",
	"zu" => "Zulu",

	"field:required" => 'Requerido',

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
	
	"core:upgrade:2019071901:title" => "Update default security parameter: Email change confirmation",
	"core:upgrade:2019071901:description" => "Installed Elgg version introduces additional security parameters. It is recommended that your run this upgrade to configure the default. You can later update this parameter in the site security settings.",
);
