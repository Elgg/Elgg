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
	'login:continue' => "Iniciar sesión para continuar",
	'login:empty' => "El nombre de usuario y contrase&ntilde;a son requeridos",
	'login:baduser' => "No se pudo cargar su cuenta de usuario",

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

	'actionnotfound' => "El log de acciones para %s no se ha encontrado",
	'actionunauthorized' => 'Usted no posee los permisos necesarios para realizar esta acci&oacute;n',

	'ajax:error' => 'Ha habido un error inesperado en la llamada AJAX. Puede que la conexión con el servidor se haya perdido.',
	'ajax:not_is_xhr' => 'No puedes acceder a las vista AJAX directamente',
	'ajax:pagination:no_data' => 'No se han encontrado nuevos datos de página',
	'ajax:pagination:load_more' => 'Más información',

	'ElggEntity:Error:SetSubtype' => 'Utilizar %s en lugar del colocador mágico para "subtipo"',
	'ElggEntity:Error:SetEnabled' => 'Utilizar %s en lugar del colocador mágico para "habilitado"',
	'ElggEntity:Error:SetDeleted' => 'Utilizar %s en lugar del colocador mágico para "borrado"',
	'ElggUser:Error:SetAdmin' => 'Utilizar %s en lugar del colocador mágico para "admin"',
	'ElggUser:Error:SetBanned' => 'Utilizar %s en lugar del setter mágico para "prohibido"',

	'PluginException:CannotStart' => '%s (guid: %s) no puede iniciarse. Motivo: %s',
	'PluginException:InvalidID' => "%s no es un ID de plugin v&aacute;lido",
	'ElggPlugin:MissingID' => 'No se encuentra el ID del plugin (guid %s)',
	'ElggPlugin:Error' => 'Error del plugin',
	'ElggPlugin:Exception:CannotIncludeFile' => 'No puede incluirse %s para el plugin %s (guid: %s) en %s. Verifique los permisos!',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Se lanzó la excepción incluyendo %s para el complemento %s (guid: %s) en %s. ',
	'ElggPlugin:Exception:CannotRegisterViews' => 'No puede cargarse el directorio "views" para el plugin %s (guid: %s) en %s. Verifique los permisos!',
	'ElggPlugin:InvalidAndDeactivated' => '%s no es un plugin v&aacute;lido y se ha deshabilitado',
	'ElggPlugin:activate:BadConfigFormat' => 'El archivo "elgg-plugin.php" del complemento no devolvió una matriz serializable. ',
	'ElggPlugin:activate:ConfigSentOutput' => 'El archivo "elgg-plugin.php" del complemento envió una salida.',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Hay otros complementos que tienen a «%s» como dependencia. Debes desactivar los siguientes complementos primero para poder desactivar este: %s',
	'ElggPlugin:Dependencies:MustBeActive' => 'Debe estar activo',
	'ElggPlugin:Dependencies:Position' => 'Posición',

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

	'UserFetchFailureException' => 'No se pueden revisar los permisos para el user_guid [%s] porque el usuario no existe.',

	'PageNotFoundException' => 'La pagina que estas intentando ver no existe o no tienes permisos para verla',
	'EntityNotFoundException' => 'El contenido al que intentas acceder ha sido eliminado o no tienes permiso para verlo.',
	'EntityPermissionsException' => 'No tienes permisos suficientes para esta acción.',
	'GatekeeperException' => 'No tienes permisos para ver la pagina a la que intentas acceder',
	'RegistrationAllowedGatekeeperException:invalid_invitecode' => "El código de invitación proporcionado no es válido",
	'BadRequestException' => 'Petición incorrecta',
	'ValidationException' => 'Los datos enviados no cumplen los requerimientos, por favor comprueba los datos.',
	'LogicException:InterfaceNotImplemented' => '%s debe de ser implementado %s',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "La contraseña proporcionada no cumple los requisitos de caracteres.",

	'changebookmark' => 'Por favor modifique su &iacute;ndice para esta vista',
	'error:missing_data' => 'Faltan datos en tu solicitud',
	'save:fail' => 'Hubo un error guardando tus datos',
	'save:success' => 'Tus datos fueron guardados',

	'error:default:title' => 'Error...',
	'error:default:content' => 'Oops... Algo salió mal',
	'error:400:title' => 'Petición incorrecta',
	'error:400:content' => 'Lo sentimos. La petición no es válida o está incompleta.',
	'error:401:title' => 'Sin autorización',
	'error:403:title' => 'Prohibido',
	'error:403:content' => 'Lo sentimos. No tienes permiso para acceder a la página solicitada.',
	'error:404:title' => 'Página no encontrada',
	'error:404:content' => 'Lo sentimos. No pudimos encontrar la página solicitada',
	'error:500:title' => 'Error interno del servidor',
	'error:503:title' => 'Servicio no disponible',

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
	'table_columns:fromView:checkbox' => 'Seleccione',
	'table_columns:fromView:container' => 'Contenedor',
	'table_columns:fromView:entity_menu' => 'Men&uacute;',
	'table_columns:fromView:excerpt' => 'Descripción',
	'table_columns:fromView:link' => 'Nombre/Título',
	'table_columns:fromView:icon' => 'Icono',
	'table_columns:fromView:item' => 'Elemento',
	'table_columns:fromView:language' => 'Idioma',
	'table_columns:fromView:last_action' => 'Última acción',
	'table_columns:fromView:last_login' => 'Último acceso',
	'table_columns:fromView:owner' => 'Propietario',
	'table_columns:fromView:prev_last_login' => 'Último acceso anterior',
	'table_columns:fromView:time_created' => 'Tiempo de creación',
	'table_columns:fromView:time_updated' => 'Tiempo de actualización',
	'table_columns:fromView:unvalidated_menu' => 'Men&uacute;',
	'table_columns:fromView:user' => 'Usuario',

	'table_columns:fromProperty:description' => 'Descripción',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Nombre',
	'table_columns:fromProperty:type' => 'Tipo',
	'table_columns:fromProperty:username' => 'Nombre de usuario',
	'table_columns:fromProperty:validated' => 'Validado',

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
	'access:limited:label' => "Limitado",
	'access:help' => "El nivel de acceso",
	'access:read' => "S&oacute;lo lectura",
	'access:write' => "Acceso de escritura",
	'access:admin_only' => "Solo Administradores",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Panel de control",
	'dashboard:nowidgets' => "Su panel de control le permite seguir la actividad y el contenido que le interesan de este sitio",

	'widgets:add' => 'Agregar widget',
	'widgets:add:description' => "Haga click en el bot&oacute;n de alg&uacute;n widget para agregarlo a la p&aacute;gina",
	'widget:unavailable' => 'Ya agreg&oacute; este widget',
	'widget:numbertodisplay' => 'Cantidad de elementos para mostrar',

	'widget:delete' => 'Quitar %s',
	'widget:edit' => 'Personalizar este widget',

	'item:object:widget' => "Widgets",
	'collection:object:widget' => 'Widgets',
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
	'notification:user:user:make_admin' => "Enviar una notificación cuando un usuario recibe derechos de administrador",
	'notification:user:user:remove_admin' => "Enviar una notificación cuando se revocan los derechos de administrador de un usuario",
	'notification:user:user:unban' => "Enviar una notificación cuando un usuario es desbloqueado",

	'friends' => "Amigos",

	'avatar' => 'Imagen de perfil',
	'avatar:edit' => 'Editar imagen de perfil',
	'avatar:upload:instructions' => "Su imagen de perfil se mostrar&aacute; en la red. Podr&aacute; modificarla siempre que lo desee (Formatos de archivo aceptados: GIF, JPG o PNG)",
	'avatar:upload:success' => 'Imagen de perfil subida correctamente',
	'avatar:upload:fail' => 'Fall&oacute; la subida de la imagen de perfil',
	'avatar:resize:fail' => 'Error al modificar el tama&ntilde;o de la imagen de perfil',
	'avatar:remove:success' => 'Se ha eliminado el avatar',
	'avatar:remove:fail' => 'fall&oacute; al remover el avatar',
	
	'header:remove:success' => 'La eliminación de la cabecera ha tenido éxito',
	'header:remove:fail' => 'Error en la eliminación del encabezado',
	'header:upload:success' => 'La carga de la cabecera se ha realizado correctamente',
	'header:upload:fail' => 'Error en la carga del encabezado',
	
	'action:user:validate:already' => "%s ya ha sido validado",
	'action:user:validate:success' => "%s ha sido validado",
	'action:user:validate:error' => "Ocurrió un error validando %s",
	
	'action:user:login_as' => "Iniciar sesión como",
	'action:user:logout_as' => "Volver a la página %s",
	
	'action:user:login_as:success' => "Ha iniciado sesión como%s",
	'action:user:login_as:unknown' => "Usuario desconocido. No se ha podido iniciar sesión.",
	'action:user:login_as:error' => "No se ha podido iniciar sesión como %s",
	
	'action:admin:user:bulk:ban' => "Usuarios %s baneados con éxito",
	'action:admin:user:bulk:unban' => "Usuarios %s desbaneados con éxito",

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
	'river:site:site:join' => "%s  se unió al sitio",
	'river:update:user:avatar' => '%s tiene una nueva imagen de perfil',
	'river:posted:generic' => '%s publicado',
	'river:ingroup' => 'en el grupo %s',
	'river:none' => 'Sin actividad',
	'river:update' => 'Actualizaciones de %s',
	'river:delete' => 'Eliminar este elemento de la actividad',
	'river:delete:success' => 'El item en el River ha sido borrado',
	'river:delete:fail' => 'El item en el River no pudo ser borrado',
	'river:delete:lack_permission' => 'No tienes permiso para eliminar este elemento de la actividad.',
	'river:subject:invalid_subject' => 'Usuario inválido',
	'activity:owner' => 'Ver Actividad',

/**
 * Relationships
 */
	
	'relationship:default' => "%s  se refiere a %s",

/**
 * Notifications
 */
	'notification:method:email' => 'Correo electr&oacute;nico',
	'notification:method:email:from' => '%s  (vía %s)',
	'notification:method:delayed_email' => 'Correo electrónico retrasado',
	
	'usersettings:notifications:title' => "Ajustes de notificación",
	'usersettings:notifications:users:title' => 'Notificaciones por usuario',
	'usersettings:notifications:users:description' => 'Para recibir notificaciones de tus amigos (de forma individual) cuando creen nuevos contenidos, búscalos a continuación y selecciona el método de notificación que deseas utilizar.',
	
	'usersettings:notifications:menu:page' => "Ajustes de notificación",
	'usersettings:notifications:menu:filter:settings' => "Configuraci&oacute;n",
	
	'usersettings:notifications:default:description' => 'Ajustes de notificación por defecto para eventos del sistema',
	'usersettings:notifications:content_create:description' => 'Configuración de notificaciones por defecto para nuevos contenidos creados por ti, esto puede causar notificaciones cuando otros realicen acciones en tu contenido como dejar un comentario.',
	'usersettings:notifications:create_comment:description' => "Configuración de notificaciones por defecto al comentar un contenido para seguir el resto de la conversación.",
	'usersettings:notifications:mentions:description' => "Recibe una notificación cuando te @mencionen",
	'usersettings:notifications:admin_validation_notification:description' => "Recibir una notificación cuando haya que validar a un usuario recién registrado.",

	'usersettings:notifications:timed_muting' => "Desactivar temporalmente las notificaciones",
	'usersettings:notifications:timed_muting:help' => "Si no desea recibir ninguna notificación durante un periodo determinado (por ejemplo, unas vacaciones), puede fijar una fecha de inicio y de fin para desactivar temporalmente todas las notificaciones.",
	'usersettings:notifications:timed_muting:start' => "Primer día",
	'usersettings:notifications:timed_muting:end' => "Último día",
	'usersettings:notifications:timed_muting:warning' => "Actualmente sus notificaciones están temporalmente desactivadas",
	'usersettings:notifications:save:fail' => "Se ha producido un problema al guardar la configuración de las notificaciones.",
	
	'usersettings:notifications:subscriptions:save:ok' => "Las suscripciones a notificaciones se han guardado correctamente.",
	'usersettings:notifications:subscriptions:save:fail' => "Hubo un problema al guardar las suscripciones de notificaciones.",

	'notification:default:salutation' => 'Estimado %s',
	'notification:default:sign-off' => 'Saludos,

%s',
	'notification:subject' => 'Notificaciones de %s',
	'notification:body' => 'Ver la nueva actividad en %s',
	
	'notification:mentions:subject' => '%s te mencionó',
	'notification:mentions:body' => "%s  te menciona en '%s'.

Para ver el post completo, haz clic en el siguiente enlace:
%s",
	
	'notifications:delayed_email:subject:daily' => "Notificaciones diarias",
	'notifications:delayed_email:subject:weekly' => "Notificaciones semanales",
	'notifications:delayed_email:body:intro' => "A continuación encontrará una lista de sus notificaciones retrasadas.",
	
	'notifications:subscriptions:record:settings' => 'Mostrar selección detallada',
	'notifications:subscriptions:no_results' => 'Aún no hay registros de suscripción',
	'notifications:subscriptions:details:no_results' => 'No hay suscripciones detalladas que configurar.',
	'notifications:subscriptions:details:reset' => 'Deshacer selección',

	'notifications:mute:title' => "Silenciar notificaciones",
	'notifications:mute:description' => "Si ya no desea recibir notificaciones como la que ha recibido configure una o varias de las siguientes razones para bloquear todas las notificaciones:",
	'notifications:mute:error:content' => "No se ha podido determinar ninguna configuración de notificación",
	'notifications:mute:entity' => "sobre '%s'",
	'notifications:mute:container' => "de '%s'",
	'notifications:mute:owner' => "por '%s'",
	'notifications:mute:actor' => "iniciado por '%s'",
	'notifications:mute:group' => "escrito en el grupo '%s'",
	'notifications:mute:user' => "escrito por el usuario '%s'",
	
	'notifications:mute:save:success' => "Tus ajustes de notificación se han guardado",
	
	'notifications:mute:email:footer' => "Silenciar estos correos",

/**
 * Search
 */

	'search' => "Buscar",
	'notfound' => "No se encontraron resultados",

	'viewtype:change' => "Modificar tipo de lista",
	'viewtype:list' => "Vista de lista",
	'viewtype:gallery' => "Galer&iacute;a",
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
	'registration:dupeemail' => 'Ya se encuentra registrada la direcci&oacute;n de Email',
	'registration:invalidchars' => 'Lo sentimos, su nombre de usuario posee los caracteres inv&aacute;lidos: %s. Estos son todos los caracteres que se encuentran invalidados: %s',
	'registration:invalidchars:route' => 'Lo sentimos, su nombre de usuario contiene un carácter %s que no es válido.',
	'registration:emailnotvalid' => 'Lo sentimos, la direcci&oacute;n de email que ha ingresado es inv&aacute;lida en el sistema',
	'registration:passwordnotvalid' => 'Lo sentimos, la contrase&ntilde;a que ha ingresado es inv&aacute;lida en el sistema',
	'registration:usernamenotvalid' => 'Lo sentimos, el nombre de usuario que ha ingresado es inv&aacute;lida en el sistema',

	'adduser:ok' => "Se agreg&oacute; correctamente un nuevo usuario",
	
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
	'user:changepassword:unknown_user' => 'Usuario inv&aacute;lido',
	'user:changepassword:change_password_confirm' => 'Esto cambiará su contraseña.',

	'user:delete:title' => 'Confirmar la eliminación de la cuenta',
	'user:delete:description' => 'Confirma que deseas eliminar la cuenta de %s. Al eliminar la cuenta también se eliminará todo el contenido (incluidos los grupos) que pertenezca a este usuario. Esto también podría incluir contenido relacionado, como contenido de grupo, subpáginas o comentarios sobre el contenido. A continuación puedes ver una lista de los contenidos que pertenecen al usuario.',
	'user:delete:confirm' => "Confirmo que deseo eliminar este usuario",

	'user:set:language' => "Configuraci&oacute;n de lenguaje",
	'user:language:label' => "Su lenguaje",
	'user:language:success' => "Se actualiz&oacute; su configuraci&oacute;n de lenguaje",

	'user:username:notfound' => 'No se encuentra el usuario %s',
	'user:username:help' => 'Tenga en cuenta que cambiar un nombre de usuario cambiará todos los enlaces dinámicos relacionados con el usuario',

	'user:password:lost' => 'Olvid&eacute; mi contrase&ntilde;a',
	'user:password:hash_missing' => 'Lamentablemente, debemos pedirle que restablezca su contraseña. Hemos mejorado la seguridad de las contraseñas en el sitio, pero no hemos podido migrar todas las cuentas en el proceso.',
	'user:password:changereq:success' => 'Solicitud de nueva contrase&ntilde;a confirmada, se le ha enviado un Email',

	'user:password:text' => 'Para solicitar una nueva contrase&ntilde;a ingrese su nombre de usuario y presione el bot&oacute;n debajo',

	'user:persistent' => 'Recordarme',

	'walled_garden:home' => 'Inicio',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "La contraseña debe tener al menos %s caracteres.",
	'password:requirements:lower' => "La contraseña debe tener al menos %s caracteres en minúsculas.",
	'password:requirements:no_lower' => "La contraseña no debe contener ningún carácter en minúsculas.",
	'password:requirements:upper' => "La contraseña debe tener al menos %s caracteres en mayúsculas.",
	'password:requirements:no_upper' => "La contraseña no debe contener ningún carácter en mayúsculas.",
	'password:requirements:number' => "La contraseña debe tener al menos %s caracteres numéricos.",
	'password:requirements:no_number' => "La contraseña no debe contener caracteres numéricos.",
	'password:requirements:special' => "La contraseña debe tener al menos %s caracteres especiales.",
	'password:requirements:no_special' => "La contraseña no debe contener caracteres especiales.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrar',
	'menu:page:header:configure' => 'Configurar',
	'menu:page:header:utilities' => 'Utilidades',
	'menu:page:header:develop' => 'Desarrollar',
	'menu:page:header:information' => 'Información',
	'menu:page:header:default' => 'Otro',
	'menu:page:header:plugin_settings' => 'Configuraci&oacute;n del plugin',

	'admin:view_site' => 'Ver sitio',
	'admin:loggedin' => 'Sesi&oacute;n iniciada como %s',
	'admin:menu' => 'Men&uacute;',

	'admin:configuration:success' => "Su configuraci&oacute;n ha sido guardada",
	'admin:configuration:fail' => "No se pudo guardar su configuraci&oacute;n",
	'admin:configuration:dataroot:relative_path' => 'No se puede configurar "%s" como el directorio de datos raiz ya que la ruta no es absoluta.',
	'admin:configuration:default_limit' => 'El n&uacute;mero de elementos debe ser de al menos 1.',

	'admin:unknown_section' => 'Secci&oacute;n de administraci&oacute;n inv&aacute;lida',

	'admin' => "Administraci&oacute;n",
	'admin:header:release' => "Liberación de Elgg: %s",
	'admin:description' => "El panel de administraci&oacute;n le permite organizar todos los aspectos del sistema, desde la gesti&oacute;n de usuarios hasta el comportamiento de los plugins. Seleccione una opci&oacute;n debajo para comenzar",

	'admin:performance' => 'Rendimiento',
	'admin:performance:label:generic' => 'Genérico',
	'admin:performance:generic:description' => 'A continuación encontrará una lista de sugerencias / valores de rendimiento que podrían ayudarle a afinar su sitio web',
	'admin:performance:simplecache' => 'Caché simple',
	'admin:performance:simplecache:settings:warning' => "Se recomienda configurar simplecache en el archivo settings.php.
Configurar simplecache en el archivo settings.php mejora el rendimiento del caché.
Permite a Elgg omitir la conexión a la base de datos cuando sirve archivos JavaScript y CSS en caché.",
	'admin:performance:systemcache' => 'Caché del sistema',
	'admin:performance:apache:mod_cache' => 'Apache mod_cache',
	'admin:performance:apache:mod_cache:warning' => 'El módulo mod_cache proporciona esquemas de caché HTTP-aware. Esto significa que los archivos se almacenarán en caché de acuerdo con una instrucción que especifica cuánto tiempo una página puede considerarse "fresca".',
	'admin:performance:php:open_basedir' => 'PHP open_basedir',
	'admin:performance:php:open_basedir:not_configured' => 'No se han establecido limitaciones',
	'admin:performance:php:open_basedir:warning' => 'Una pequeña cantidad de limitaciones open_basedir están vigentes, esto podría afectar el rendimiento.',
	'admin:performance:php:open_basedir:error' => 'Una gran cantidad de limitaciones de open_basedir están vigentes, esto probablemente impactará en el rendimiento.',
	'admin:performance:php:open_basedir:generic' => 'Con open_basedir cada acceso a archivo será chequeado contra la lista de limitaciones.
Dado que Elgg tiene muchos accesos a archivos, esto impactará negativamente en el rendimiento. Además PHPs opcache ya no puede cachear rutas de archivos en memoria y tiene que resolver esto en cada acceso.',
	
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
	'admin:users:banned' => 'Bloqueado',
	'admin:users:existingadmins' => 'Lista de administradores existentes',
	'admin:users:add' => 'Agregar Nuevo Usuario',
	'admin:users:description' => "Este panel de administraci&oacute;n le permite gestionar la configuraci&oacute;n de usuarios de la red. Seleccione una opci&oacute;n debajo para comenzar",
	'admin:users:adduser:label' => "Click aqu&iacute; para agregar un nuevo usuario..",
	'admin:users:opt:linktext' => "Configurar usuarios..",
	'admin:users:opt:description' => "Configurar usuarios e informaci&oacute;n de cuentas",
	'admin:users:find' => 'Buscar',
	'admin:users:unvalidated' => 'No validado',
	'admin:users:unvalidated:no_results' => 'No hay usuarios sin validar.',
	'admin:users:unvalidated:registered' => 'Registrado: %s',
	'admin:users:unvalidated:change_email' => 'Cambiar la dirección de correo electrónico',
	'admin:users:unvalidated:change_email:user' => 'Cambiar la dirección de correo electrónico de:  %s',
	'admin:users:inactive' => 'Inactivo',
	'admin:users:inactive:last_login_before' => "Mostrar usuarios no conectados después de",
	'admin:users:inactive:last_login_before:help' => "Esto también mostrará los usuarios que nunca han iniciado sesión.",
	'admin:users:details:attributes' => 'Atributos del usuario',
	'admin:users:details:profile' => 'Información sobre el perfil',
	'admin:users:details:profile:no_fields' => 'No hay campos de perfil configurados',
	'admin:users:details:profile:no_information' => 'No se dispone de información sobre el perfil',
	'admin:users:details:statistics' => 'Estad&iacute;sticas de contenido',
	
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
	'admin:site_icons' => "Iconos del sitio",
	'admin:site_icons:site_icon' => "Icono del sitio",
	'admin:site_icons:info' => "Sube un icono relacionado con tu sitio. Este icono se utilizará como favicon y al mostrar el sitio por ejemplo como remitente en las notificaciones del sitio.",
	'admin:site_icons:font_awesome' => "Fuente impresionante",
	'admin:site_icons:font_awesome:zip' => "Cargar archivo ZIP",
	'admin:site_icons:font_awesome:zip:help' => "Aquí puedes subir una descarga de Font Awesome desde https://fontawesome.com/download. Este webfont se servirá localmente.",
	'admin:site_icons:font_awesome:zip:error' => "No se puede extraer el archivo ZIP cargado",
	'admin:site_icons:font_awesome:remove_zip' => "Eliminar fuente cargada",
	'admin:theme' => "Theme",
	'admin:theme:info' => "En este formulario se pueden configurar diversas variables temáticas. Esta configuración anulará la configuración existente.",
	'admin:theme:warning' => "Tenga en cuenta que estos cambios podrían romper su estilo.",
	'admin:theme:css_variable:name' => "Variable CSS",
	'admin:theme:css_variable:value' => "Valor",
	'admin:site_settings' => "Configuración del sitio",
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
	'admin:widget:elgg_blog' => 'Blog de Elgg',
	'admin:widget:elgg_blog:help' => 'Muestra las últimas entradas del blog de Elgg',
	'admin:widget:elgg_blog:no_results' => 'No se pueden buscar las últimas noticias de Elgg',
	'admin:statistics:numentities' => 'Estadísticas del contenido',
	'admin:statistics:numentities:type' => 'Tipo de contenido',
	'admin:statistics:numentities:number' => 'Numero',
	'admin:statistics:numentities:searchable' => 'Entidades de búsqueda',
	'admin:statistics:numentities:other' => 'Otras entidades',

	'admin:statistics:database' => 'Información de la base de datos',
	'admin:statistics:database:table' => 'Tabla',
	'admin:statistics:database:row_count' => 'Recuento de filas',

	'admin:statistics:queue' => 'Información de cola',
	'admin:statistics:queue:name' => 'Nombre',
	'admin:statistics:queue:row_count' => 'Recuento de filas',
	'admin:statistics:queue:oldest' => 'Récord más antiguo',
	'admin:statistics:queue:newest' => 'Registro más reciente',

	'admin:widget:admin_welcome' => 'Bienvenido',
	'admin:widget:admin_welcome:help' => "Esta es el &aacute;rea de administraci&oacute;n",
	'admin:widget:admin_welcome:intro' => 'Bienvenido! Se encuentra viendo el panel de control de la administraci&oacute;n. Es &uacute;til para visualizar las novedades en la red',

	'admin:widget:admin_welcome:registration' => "El registro de nuevos usuarios está deshabilitado. Puede activarlo en la página %s.",
	'admin:widget:admin_welcome:admin_overview' => "El menú de la derecha permite navegar por el área de administración. Está organizada en
tres secciones:
	<dl>
		<dt>Administrar</dt> <dd>Tareas básicas como la gestión de usuarios, la monitorización de contenidos y la activación de plugins.</dd>
		<dt>Configurar</dt> <dd>Tareas ocasionales como establecer el nombre del sitio o configurar las preferencias de seguridad.</dd>
		<dt>Utilidades</dt> <dd>Varias herramientas para apoyar el mantenimiento del sitio.</dd>
		<dt>Información</dt> <dd>Información sobre su sitio, como estadísticas.</dd>
		<dt>Desarrollar</dt> <dd>Para desarrolladores que están creando plugins o depurando el sitio. (Requiere un plugin de desarrollador).</dd>
	</dl>",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Aseg&uacute;rese de verificar los recursos disponibles en los enlaces del pi&eacute; de p&aacute;gina y gracias por utilizar Elgg!',

	'admin:widget:control_panel' => 'Panel de control',
	'admin:widget:control_panel:help' => "Provee un acceso f&aacute;cil a los controles",

	'admin:cache:flush' => 'Limpiar la cache',
	'admin:cache:flushed' => "La cache del sitio ha sido limpiada",
	'admin:cache:invalidate' => 'Invalidar las cachés',
	'admin:cache:invalidated' => "Las cachés del sitio han sido invalidadas",
	'admin:cache:clear' => 'Borrar las cachés',
	'admin:cache:cleared' => "Los cachés del sitio han sido borrados",
	'admin:cache:purge' => 'Purgar las cachés',
	'admin:cache:purged' => "Las cachés del sitio han sido purgadas",

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
	'admin:security:information:description' => 'En esta página encontrará una lista de recomendaciones de seguridad.',
	'admin:security:information:https' => '¿Está el sitio web protegido por HTTPS?',
	'admin:security:information:https:warning' => "Se recomienda proteger su sitio web utilizando HTTPS, esto ayuda a proteger los datos (por ejemplo, contraseñas) de ser husmeados a través de la conexión a Internet.",
	'admin:security:information:wwwroot' => 'Se puede escribir en la carpeta principal del sitio web',
	'admin:security:information:wwwroot:error' => "Es recomendable que instales Elgg en una carpeta en la que no pueda escribir tu servidor web. Visitantes malintencionados podrían colocar código no deseado en tu sitio web.",
	'admin:security:information:validate_input' => 'Validación de entrada',
	'admin:security:information:validate_input:error' => "Algún plugin ha desactivado la validación de entrada en su sitio web, lo que permitirá a los usuarios enviar contenido potencialmente dañino (por ejemplo, cross-site-scripting, etc.)",
	
	'admin:security:settings' => 'Ajustes',
	'admin:security:settings:description' => 'En esta página puede configurar algunas características de seguridad. Por favor, lea la configuración cuidadosamente.',
	'admin:security:settings:label:account' => 'Cuenta',
	'admin:security:settings:label:notifications' => 'Notificaciones',
	'admin:security:settings:label:site_secret' => 'Secreto del sitio',
	
	'admin:security:settings:notify_admins' => 'Notificar a todos los administradores del sitio cuando se agrega o elimina un administrador',
	'admin:security:settings:notify_admins:help' => 'Esto enviará una notificación a todos los administradores del sitio de que uno de los administradores agregó o eliminó un administrador del sitio.',
	
	'admin:security:settings:notify_user_admin' => 'Notificar al usuario cuando se agrega o elimina el rol de administrador',
	'admin:security:settings:notify_user_admin:help' => 'Esto enviará una notificación al usuario de que el rol de administrador se agregó o se eliminó de su cuenta.',
	
	'admin:security:settings:notify_user_ban' => 'Notificar al usuario cuando su cuenta sea suspendida/habilitada',
	'admin:security:settings:notify_user_ban:help' => 'Esto enviara una notificación al usuario cuya cuenta ha sido suspendida/habilitada',
	
	'admin:security:settings:protect_upgrade' => 'Proteger upgrade.php',
	'admin:security:security_txt:contact' => "Contacto",
	'admin:security:security_txt:language' => "Su lenguaje",
	
	'user:notification:unban:subject' => 'Tu cuenta en %s ya no esta suspendida',

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Configuraci&oacute;n para el plugin %s guardada correctamente",
	'plugins:settings:save:fail' => "Ocurri&oacute; un error al intentar guardar la configuraci&oacute;n para el plugin %s",
	'plugins:usersettings:save:ok' => "Configuraci&oacute;n del usuario para el plugin %s guardada",
	'plugins:usersettings:save:fail' => "Ocurri&oacute; un error al intentar guardar la configuraci&oacute;n del usuario para el plugin %s",
	
	'item:object:plugin' => 'Plugins',
	'collection:object:plugin' => 'Plugins',

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
	'admin:plugins:label:dependencies' => 'Dependencias',

	'admin:plugins:warning:unmet_dependencies' => 'Este plugin tiene dependencias desconocidas y no se activar&aacute;. Consulte las dependencias debajo de mas informaci&oacute;n',
	'admin:plugins:warning:invalid' => '%s no es un plugin Elgg v&aacute;lido. Visite <a href="http://docs.elgg.org/Invalid_Plugin">la Documentaci&oacute;n Elgg</a> para consejos de soluci&oacute;n de problemas',
	'admin:plugins:warning:invalid:check_docs' => 'Mira <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">la documentación de Elgg</a> donde encontrarás consejos de resolución de problemas.',
	'admin:plugins:cannot_activate' => 'no se puede activar',
	'admin:plugins:cannot_deactivate' => 'no se puede desactivar',
	'admin:plugins:already:active' => 'Los plugins seleccionados ya están activos.',
	'admin:plugins:already:inactive' => 'Los plugins seleccionados ya no están activos',

	'admin:plugins:set_priority:yes' => "Reordenar %s",
	'admin:plugins:set_priority:no' => "No se puede reordenar %s",
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

	'admin:statistics:description' => "Este es un resumen de las estad&iacute;sticas del sitio. Si necesita estad&iacute;sticas mas avanzadas, hay dispoinble una funcionalidad de administraci&oacute;n profesional",
	'admin:statistics:opt:description' => "Ver informaci&oacute;n estad&iacute;stica sobre usuarios y objetos en el sitio",
	'admin:statistics:opt:linktext' => "Ver estad&iacute;sticas..",
	'admin:statistics:label:user' => "Estadísticas de usuario",
	'admin:statistics:label:numentities' => "Entidades del sitio",
	'admin:statistics:label:numusers' => "Cantidad de usuarios",
	'admin:statistics:label:numonline' => "Cantidad de usuarios conectados",
	'admin:statistics:label:onlineusers' => "Usuarios conectados en este momento",
	'admin:statistics:label:admins' => "Admins",
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
	'admin:server:label:php_ini' => 'Ubicaci&oacute;n del archivo PHP ini',
	'admin:server:label:php_log' => 'Registros de PHP',
	'admin:server:label:mem_avail' => 'Memoria disponible',
	'admin:server:label:mem_used' => 'Memoria usada',
	'admin:server:error_log' => "Registro de errores del servidor Web",
	'admin:server:label:post_max_size' => 'Tama&ntilde;o m&aacute;ximo de las peticiones POST',
	'admin:server:label:upload_max_filesize' => 'Tama&ntilde; m&aacute;ximo de las subidas',
	'admin:server:warning:post_max_too_small' => '(Nota: post_max_size debe ser mayor que el tama&ntilde; indicado aqu&iacute; para habilitar las subidas)',
	'admin:server:label:memcache' => 'Memcache',

	'admin:server:label:redis' => 'Redis',

	'admin:server:label:opcache' => 'OPcache',
	
	'admin:server:requirements:php_extension' => "Extensi&oacute;n PHP: %s",
	
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
	'admin:menu_items:configure' => 'Configurar los elementos del men&uacute; principal',
	'admin:menu_items:hide_toolbar_entries' => 'Quitar enlaces del men&uacute; de la barra de herramientas?',
	'admin:menu_items:saved' => 'Elementos del men&uacute; guardados',
	'admin:add_menu_item' => 'Agregar un elemento del men&uacute; personalizado',
	'admin:add_menu_item:description' => 'Complete el nombre para mostrar y la direcci&oacute;n url para agregar un elemento de men&uacute; personalizado',
	'admin:default_widgets:unknown_type' => 'Tipo de widget desconocido',

	'admin:robots.txt:instructions' => "Editar el robots.txt de este sitio a continuación",
	'admin:robots.txt:plugins' => "Plugins estan agregando lo siguiente al archivo robots.txt",
	'admin:robots.txt:subdir' => "La herramienta de robots.txt no funcionara por que Elgg esta instalado en un sub-directorio",
	'admin:robots.txt:physical' => "La herramienta robots.txt no funcionará porque hay una archivo físico robots.txt",

	'admin:maintenance_mode:default_message' => 'El sitio no está disponible por mantenimiento',
	'admin:maintenance_mode:mode_label' => 'Modo de Mantenimiento',
	'admin:maintenance_mode:message_label' => 'Mensaje que se mostrará a los usuarios cuando el modo de mantenimiento este activado',
	'admin:maintenance_mode:saved' => 'Las configuraciones del modo de mantenimiento fueron guardadas',
	'admin:maintenance_mode:indicator_menu_item' => 'El sitio esta en modo de mantenimiento',
	'admin:login' => 'Entrada de Administradores',

/**
 * User settings
 */

	'usersettings:statistics' => "Sus estad&iacute;sticas",
	'usersettings:statistics:opt:linktext' => "Estad&iacute;sticas de la cuenta",

	'usersettings:statistics:login_history' => "Historial de inicio de sesión",
	'usersettings:statistics:login_history:date' => "Fecha",
	'usersettings:statistics:login_history:ip' => "Dirección IP",
	'usersettings:user:opt:linktext' => "Modificar sus preferencias",

	'usersettings:plugins:opt:linktext' => "Configure sus herramientas",
	
	'usersettings:statistics:yourdetails' => "Sus detalles",
	'usersettings:statistics:label:name' => "Nombre completo",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:lastlogin' => "&uacute;ltimo acceso",
	'usersettings:statistics:label:membersince' => "Membro desde",
	'usersettings:statistics:label:numentities' => "Su contenido",

/**
 * Activity river
 */

	'river:all' => 'Actividad de toda la red',
	'river:mine' => 'Mi Actividad',
	'river:owner' => 'Actividad de %s',
	'river:friends' => 'Actividad de Amigos',
	'river:select' => 'Mostrar %s',
	'river:comments:all' => 'Ver todos los comentarios de %u',

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

/**
 * Generic action words
 */

	'save' => "Guardar",
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
	'next' => 'Siguiente',
	'previous' => 'Anterior',
	
	'site' => 'Sitio',
	'activity' => 'Actividad',
	'members' => 'Miembros',
	'menu' => 'Men&uacute;',
	'item' => 'Elemento',

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
	'status:active' => 'Activo',
	'status:inactive' => 'Inactivo',

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

/**
 * User add
 */

	'useradd:subject' => 'Cuenta de usuario creada',

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
	'installation:walled_garden:description' => 'Habilitar al sitio para ejecutarse como una red privada. Esto impedir&aacute; a usuarios no registrados visualizar cualquier p&aacute;gina del sitio, exceptuando las establecidas como p&uacute;blicas',
	'installation:walled_garden:label' => 'Restringir p&aacute;ginas a usuarios registrados',

	'installation:siteemail' => "Direcci&oacute;n de Email del sitio (utilizada para enviar mails desde el sistema):",
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

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caché',
	'admin:legend:content' => 'Contenido',
	'admin:legend:comments' => 'Comentarios',
	'admin:legend:content_access' => 'Acceso del Contenido',
	'admin:legend:site_access' => 'Acceso del Sitio',
	'admin:legend:debug' => 'Depuración y registro',
	'config:i18n:who_can_change_language:admin_only' => "Solo Administradores",
	'config:remove_branding:label' => "Eliminar marca de Elgg",
	'config:content:mentions_display_format:username' => "Nombre de Usuario",
	'config:content:mentions_display_format:display_name' => "Mi nombre para mostrar",
	'config:email' => "Correo electr&oacute;nico",

	'upgrading' => 'Actualizando..',
	'upgrade:core' => 'La instalaci&oacute;n de Elgg ha sido actualizada',
	'upgrade:unlock:success' => "Desbloqueo de Actualización exitoso.",

	'admin:pending_upgrades' => 'Este sitio tiene actualizaciones pendientes que requieren tu atención inmediata',
	'admin:view_upgrades' => 'Ver actualizaciones pendientes',
	'item:object:elgg_upgrade' => 'Actualizaciones del sitio',
	'admin:upgrades:none' => 'Tu instalación esta al día!',

	'upgrade:success_count' => 'Actualizado:',
	'upgrade:finished' => 'Se completó la actualización.',
	'upgrade:finished_with_errors' => '<p>Ocurrieron errores durante la actualización. Actualice la página y pruebe a ejecutar la actualización de nuevo.</p></p><br />
Si el error se repite, busque la causa en el registro de errores del servidor. Puede buscar ayuda para solucionar el problema en el <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupo de asistencia técnica</a> de la comunidad de Elgg.</p>',
	
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
	'email:address:password' => "Contraseña",
	'email:save:fail' => "No se pudo guardar la nueva direcci&oacute;n de Email",

	'friend:newfriend:subject' => "%s te ha puesto como amigo suyo!",

	'email:changepassword:subject' => "Contraseña cambiada!",

	'email:resetpassword:subject' => "Contrase&ntilde;a restablecida!",

	'email:changereq:subject' => "Solicitud de cambio de contraseña.",

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

	'generic_comments:add' => "Comentar",
	'generic_comments:edit' => "Editar comentario",
	'generic_comments:latest' => "&uacute;ltimos comentarios",
	'generic_comment:posted' => "Se ha publicado su comentario",
	'generic_comment:updated' => "El comentario fué cambiado éxitosamente.",
	'entity:delete:object:comment:success' => "Se ha quitado su comentario",
	'generic_comment:blank' => "Lo sentimos, debe ingresar alg&uacute;n comentario antes de poder guardarlo",
	'generic_comment:notfound' => "Lo sentimos. No hemos encontrado el comentario especificado.",
	'generic_comment:failure' => "Un error no especificado ocurrió al guardar el comentario.",
	'generic_comment:none' => 'Sin comentarios',
	'generic_comment:on' => '%s on %s',

/**
 * Entities
 */

	'byline' => 'Por %s',
	'byline:ingroup' => 'en el grupo %s',
	
	'entity:delete:item' => 'Elemento',
	'entity:delete:item_not_found' => 'No se ha encontrado el elemento',
	'entity:delete:permission_denied' => 'No tiene permisos para borrar este elemento',
	'entity:delete:success' => '%s se ha borrado.',
	'entity:delete:fail' => '%s no se ha podido borrar.',

	'entity:restore:item' => 'Elemento',
	
	'entity:mute' => "Silenciar notificaciones",

/**
 * Annotations
 */
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'En el formulario faltan __token o campos __ts',
	'actiongatekeeper:timeerror' => 'La p&aacute;gina que se encontraba utilizando ha expirado. Por favor refresque la p&aacute;gina e intente nuevamente',
	'actiongatekeeper:pluginprevents' => 'Lo sentimos. No se ha podido enviar el formulario por motivos desconocidos.',
	'actiongatekeeper:uploadexceeded' => 'El tama&ntilde;o del(los) archivo(s) supera el m&iacute;mite establecido',

/**
 * Javascript
 */
	'js:lightbox:current' => "imagen %s de %s",

/**
 * Diagnostics
 */
	
/**
 * Trash
 */

/**
 * Miscellaneous
 */
	'elgg:powered' => "Creado con Elgg",
	'field:required' => "Requerido",

/**
 * Accessibility
 */
	'menu:comments:header' => "Comentarios",
	'menu:filter:header' => "Filtrar",
	'menu:page:header' => "P&aacute;ginas",
	'menu:river:header' => "River",
	'menu:site:header' => "Sitio",
	'menu:social:header' => "Social",
	'menu:title:header' => "T&iacute;tulo",
	'menu:topbar:header' => "Barra principal",

/**
 * Cli commands
 */
	'cli:upgrade:list:completed' => "Actualizaciones completadas",
	'cli:upgrade:list:pending' => "Actualizaciones pendientes",
	
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

/**
 * Upgrades
 */
);
