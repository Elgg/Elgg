<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sitios',

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
	'session_expired' => "Su sesión ha expirado. Por favor refresca la página para ingresar nuevamente.",

	'loggedinrequired' => "Debe estar autenticado para poder visualizar esta p&aacute;gina",
	'adminrequired' => "Debe ser un administrador para poder visualizar esta p&aacute;gina",
	'membershiprequired' => "Debe ser miembro del grupo para poder visualizar esta p&aacute;gina",
	'limited_access' => "No tienes permiso para ver la página solicitada",


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
	'ElggPlugin:Exception:CannotIncludeFile' => 'No puede incluirse %s para el plugin %s (guid: %s) en %s. Verifique los permisos!',
	'ElggPlugin:Exception:CannotRegisterViews' => 'No puede cargarse el directorio "views" para el plugin %s (guid: %s) en %s. Verifique los permisos!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'No pueden registrarse lenguajes para el plugin %s (guid: %s) en %s.  Verifique los permisos!',
	'ElggPlugin:Exception:NoID' => 'No se encontr&oacute; el ID para el plugin con guid %s!',
	'PluginException:NoPluginName' => "No se pudo encontrar el nombre del plugin",
	'PluginException:ParserError' => 'Error procesando el manifiesto con versi&oacute;n de API %s en plugin %s',
	'PluginException:NoAvailableParser' => 'No se encuentra un procesador para el manifiesto de la versi&oacute;n de la API %s en plugin %s',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Atributo '%s' faltante en manifiesto del el plugin %s",
	'ElggPlugin:InvalidAndDeactivated' => '%s no es un plugin v&aacute;lido y se ha deshabilitado',

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

	'deprecatedfunction' => 'Precauci&oacute;n: Este c&oacute;digo utiliza la funci&oacute;n obsoleta \'%s\' que no es compatible con esta versi&oacute;n de Elgg',

	'pageownerunavailable' => 'Precauci&oacute;n: El administrador de p&aacute;gina %d no se encuentra accesible!',
	'viewfailure' => 'Ocurri&oacute; un error interno en la vista %s',
	'view:missing_param' => "Falta el parámetro obligatorio «%s» en la vista «%s».",
	'changebookmark' => 'Por favor modifique su &iacute;ndice para esta vista',
	'noaccess' => 'You need to login to view this content or the content has been removed or you do not have permission to view it.',
	'error:missing_data' => 'Faltan datos en tu solicitud',
	'save:fail' => 'Hubo un error guardando tus datos',
	'save:success' => 'Tus datos fueron guardados',

	'error:default:title' => 'Error...',
	'error:default:content' => 'Oops... Algo salió mal',
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
 * User details
 */

	'name' => "Nombre",
	'email' => "Direcci&oacute;n de Email",
	'username' => "Nombre de usuario",
	'loginusername' => "Nombre de usuario o Email",
	'password' => "Contrase&ntilde;a",
	'passwordagain' => "Contrase&ntilde;a (nuevamente, para verificaci&oacute;n)",
	'admin_option' => "Hacer administrador a este usuario?",

/**
 * Access
 */

	'PRIVATE' => "Privado",
	'LOGGED_IN' => "Usuarios logueados",
	'PUBLIC' => "Todos",
	'LOGGED_OUT' => "Usuarios que han cerrado sesión",
	'access:friends:label' => "Amigos",
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
	'widgets:panel:close' => "Cerrar el panel de widgets",
	'widgets:position:fixed' => '(Posici&oacute;n fija en la p&aacute;gina)',
	'widget:unavailable' => 'Ya agreg&oacute; este widget',
	'widget:numbertodisplay' => 'Cantidad de elementos para mostrar',

	'widget:delete' => 'Quitar %s',
	'widget:edit' => 'Personalizar este widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "El widget se guard&oacute; correctamente",
	'widgets:save:failure' => "No se pudo guardar el widget, por favor intente nuevamente",
	'widgets:add:success' => "Se agreg&oacute; correctamente el widget",
	'widgets:add:failure' => "No se pudo a&ntilde;adir el widget",
	'widgets:move:failure' => "No se pudo guardar la nueva posici&oacute;n del widget",
	'widgets:remove:failure' => "No se pudo quitar el widget",

/**
 * Groups
 */

	'group' => "Grupo",
	'item:group' => "Grupos",

/**
 * Users
 */

	'user' => "Usuario",
	'item:user' => "Usuarios",

/**
 * Friends
 */

	'friends' => "Amigos",
	'friends:yours' => "Tus Amigos",
	'friends:owned' => "Amigos de %s",
	'friend:add' => "Añadir a amigos",
	'friend:remove' => "Quitar amigo",

	'friends:add:successful' => "Se ha a&ntilde;adido a %s como amigo",
	'friends:add:failure' => "No se pudo a&ntilde;adir a %s como amigo. Por favor intente nuevamente",

	'friends:remove:successful' => "Se quit&oacute; a %s de sus amigos",
	'friends:remove:failure' => "No se pudo quitar a %s de sus amigos. Por favor intente nuevamente",

	'friends:none' => "Este usuario no tiene amigos a&uacute;n",
	'friends:none:you' => "No tienes amigos a&uacute;n",

	'friends:none:found' => "No se encontraron amigos",

	'friends:of:none' => "Nadie ha agragado a este usuario como amigo a&uacute;n",
	'friends:of:none:you' => "Nadie te ha agragado como amigo a&uacute;n. Comienza a a&ntilde;adir contenido y completar tu perfil para que la gente te encuentre!",

	'friends:of:owned' => "Amigos de %s",

	'friends:of' => "Amigos de",
	'friends:collections' => "Colecciones de amigos",
	'collections:add' => "Nueva colecci&oacute;n",
	'friends:collections:add' => "Nueva colecci&oacute;n de amigos",
	'friends:addfriends' => "Seleccionar amigos",
	'friends:collectionname' => "Nombre de la colecci&oacute;n",
	'friends:collectionfriends' => "Amigos en la colecci&oacute;n",
	'friends:collectionedit' => "Editar esta colecci&oacute;n",
	'friends:nocollections' => "No tienes colecciones a&uacute;n",
	'friends:collectiondeleted' => "La colecci&oacute;n ha sido eliminada",
	'friends:collectiondeletefailed' => "No se puede eliminar la colecci&oacute;n",
	'friends:collectionadded' => "La colecci&oacute;n se ha creado correctamente",
	'friends:nocollectionname' => "Debes ponerle un nombre a la colecci&oacute;n antes de crearla",
	'friends:collections:members' => "Miembros de esta colecci&oacute;n",
	'friends:collections:edit' => "Editar colecci&oacute;n",
	'friends:collections:edited' => "Colecci&oacute;n guardada",
	'friends:collection:edit_failed' => 'No se pudo guardar la colecci&oacute;n',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Imagen de perfil',
	'avatar:noaccess' => "No tienes permitido editar el avatar de este usuario",
	'avatar:create' => 'Cree su imagen de perfil',
	'avatar:edit' => 'Editar imagen de perfil',
	'avatar:preview' => 'Previsualizar',
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

	'profile:edit' => 'Editar perfil',
	'profile:aboutme' => "Sobre mi",
	'profile:description' => "Sobre mi",
	'profile:briefdescription' => "Descripci&oacute;n corta",
	'profile:location' => "Ubicaci&oacute;n",
	'profile:skills' => "Habilidades",
	'profile:interests' => "Intereses",
	'profile:contactemail' => "Email de contacto",
	'profile:phone' => "Tel&eacute;fono",
	'profile:mobile' => "M&oacute;vil",
	'profile:website' => "Sitio Web",
	'profile:twitter' => "Usuario de Twitter",
	'profile:saved' => "Su perfil ha sido guardado correctamente",

	'profile:field:text' => 'Texto corto',
	'profile:field:longtext' => 'Area de texto largo',
	'profile:field:tags' => 'Etiquetas',
	'profile:field:url' => 'Direcci&oacute;n Web',
	'profile:field:email' => 'Direcci&oacute;n de email',
	'profile:field:location' => 'Ubicaci&oacute;n',
	'profile:field:date' => 'Fecha',

	'admin:appearance:profile_fields' => 'Editar campos de perfil',
	'profile:edit:default' => 'Editar campos de perfil',
	'profile:label' => "Etiqueta de perfil",
	'profile:type' => "Tipo de perfil",
	'profile:editdefault:delete:fail' => 'Se ha producido un error al eliminar el campo del perfil',
	'profile:editdefault:delete:success' => 'Item de perfil por defecto eliminado!',
	'profile:defaultprofile:reset' => 'Reinicio de perfil de sistema por defecto',
	'profile:resetdefault' => 'Reiniciar perfil de sistema por defecto',
	'profile:resetdefault:confirm' => 'Are you sure you want to delete your custom profile fields?',
	'profile:explainchangefields' => "Puede reemplazar los campos de perfil existentes con sus propios utilizando el formulario de abajo. \n\n Ingrese un nuevo nombre de campo de perfil, por ejemplo, 'Equipo favorito', luego seleccione el tipo de campo (eg. texto, url, tags), y haga click en el bot&oacute;n de 'Agregar'. Para re ordenar los campos arrastre el control al lado de la etiqueta del campo. Para editar la etiqueta del campo haga click en el texto de la etiqueta para volverlo editable. \n\n Puede volver a la disposici&oacute;n original del perfil en cualquier momento, pero perder&aacute; la informaci&oacute;n creada en los campos personalizados del perfil hasta el momento",
	'profile:editdefault:success' => 'Elemento agregado al perfil por defecto correctamente',
	'profile:editdefault:fail' => 'No se pudo guardar el perfil por defecto',
	'profile:field_too_long' => 'No se pudo guardar la informaci&oacute;n del perfil debido a que la secci&oacute;n "%s" es demasiado larga.',
	'profile:noaccess' => "No tienes permiso para editar este perfil.",
	'profile:invalid_email' => '«%s» debe ser una dirección de correo electrónico válida.',


/**
 * Feeds
 */
	'feed:rss' => 'Canal RSS para esta p&aacute;gina',
/**
 * Links
 */
	'link:view' => 'Ver enlace',
	'link:view:all' => 'Ver todos',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s ahora es amigo de %s",
	'river:update:user:avatar' => '%s tiene una nueva imagen de perfil',
	'river:update:user:profile' => '%s ha actualizado su perfil',
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
	'river:subject:invalid_subject' => 'Usuario inválido',
	'activity:owner' => 'Ver Actividad',

	'river:widget:title' => "Actividad",
	'river:widget:description' => "Mostrar la &uacute;ltima actividad",
	'river:widget:type' => "Tipo de actividad",
	'river:widgets:friends' => 'Actividad de amigos',
	'river:widgets:all' => 'Toda la actividad del sitio',

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

	'user:password:lost' => 'Olvid&eacute; mi contrase&ntilde;a',
	'user:password:changereq:success' => 'Solicitud de nueva contrase&ntilde;a confirmada, se le ha enviado un Email',
	'user:password:changereq:fail' => 'No se pudo solicitar una nueva contrase&ntilde;a',

	'user:password:text' => 'Para solicitar una nueva contrase&ntilde;a ingrese su nombre de usuario y presione el bot&oacute;n debajo',

	'user:persistent' => 'Recordarme',

	'walled_garden:welcome' => 'Bienvenido a',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrar',
	'menu:page:header:configure' => 'Configurar',
	'menu:page:header:develop' => 'Desarrollar',
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
	'admin:description' => "El panel de administraci&oacute;n le permite organizar todos los aspectos del sistema, desde la gesti&oacute;n de usuarios hasta el comportamiento de los plugins. Seleccione una opci&oacute;n debajo para comenzar",

	'admin:statistics' => "Estad&iacute;sticas",
	'admin:statistics:overview' => 'Resumen',
	'admin:statistics:server' => 'Informaci&oacute;n del servidor',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Ultimos trabajos del Cron',
	'admin:cron:period' => 'Periodo Cron',
	'admin:cron:friendly' => 'Ultimo completado',
	'admin:cron:date' => 'Fecha y hora',

	'admin:appearance' => 'Apariencia',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilidades',
	'admin:configure_utilities' => 'Utilidades',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Usuarios",
	'admin:users:online' => 'Conectados actualmente',
	'admin:users:newest' => 'Los mas nuevos',
	'admin:users:admins' => 'Administrators',
	'admin:users:add' => 'Agregar Nuevo Usuario',
	'admin:users:description' => "Este panel de administraci&oacute;n le permite gestionar la configuraci&oacute;n de usuarios de la red. Seleccione una opci&oacute;n debajo para comenzar",
	'admin:users:adduser:label' => "Click aqu&iacute; para agregar un nuevo usuario..",
	'admin:users:opt:linktext' => "Configurar usuarios..",
	'admin:users:opt:description' => "Configurar usuarios e informaci&oacute;n de cuentas",
	'admin:users:find' => 'Buscar',

	'admin:administer_utilities:maintenance' => 'Modo de Mantenimiento',
	'admin:upgrades' => 'Actualizaciones',

	'admin:settings' => 'Configuraci&oacute;n',
	'admin:settings:basic' => 'Configuraci&oacute;n B&aacute;sica',
	'admin:settings:advanced' => 'Configuraci&oacute;n Avanzada',
	'admin:site:description' => "Este panel de administraci&oacute;n le permite gestionar la configuraci&oacute;n global de la red. Selecciona una opci&oacute;n debajo para comenzar",
	'admin:site:opt:linktext' => "Configurar sitio..",
	'admin:settings:in_settings_file' => 'Esta opción se configura en settings.php',

	'admin:legend:security' => 'Seguridad',
	'admin:site:secret:intro' => 'Elgg usa una clave para crear tokens de seguridad para varios propósitos.',
	'admin:site:secret_regenerated' => "La clave secreta ha sido regenerada.",
	'admin:site:secret:regenerate' => "Regenerar clave secreta",
	'admin:site:secret:regenerate:help' => "Nota: Puede que regenerar el secreto del sitio suponga un inconveniente para algunos usuarios, ya que invalidará códigos usados para las cookies de la función de «Recuérdame», para solicitudes de validación de la dirección de correo electrónico, para códigos de invitación, etc.",
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
	'widget:content_stats:type' => 'Tipo de contenido',
	'widget:content_stats:number' => 'N&uacute;mero',

	'admin:widget:admin_welcome' => 'Bienvenido',
	'admin:widget:admin_welcome:help' => "Esta es el &aacute;rea de administraci&oacute;n",
	'admin:widget:admin_welcome:intro' =>
'Bienvenido! Se encuentra viendo el panel de control de la administraci&oacute;n. Es &uacute;til para visualizar las novedades en la red',

	'admin:widget:admin_welcome:admin_overview' =>
"La navegaci&oacute;n para el &aacute;rea de administraci&oacute;n se encuentra en el men&uacute; de la derecha. El mismo se organiza en",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Aseg&uacute;rese de verificar los recursos disponibles en los enlaces del pi&eacute; de p&aacute;gina y gracias por utilizar Elgg!',

	'admin:widget:control_panel' => 'Panel de control',
	'admin:widget:control_panel:help' => "Provee un acceso f&aacute;cil a los controles",

	'admin:cache:flush' => 'Limpiar la cache',
	'admin:cache:flushed' => "La cache del sitio ha sido limpiada",

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

	'admin:notices:could_not_delete' => 'Notificaci&oacute;n de no se pudo eliminar',
	'item:object:admin_notice' => 'Admin notice',

	'admin:options' => 'Opciones de Admin',

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Configuraci&oacute;n para el plugin %s guardada correctamente",
	'plugins:settings:save:fail' => "Ocurri&oacute; un error al intentar guardar la configuraci&oacute;n para el plugin %s",
	'plugins:usersettings:save:ok' => "Configuraci&oacute;n del usuario para el plugin %s guardada",
	'plugins:usersettings:save:fail' => "Ocurri&oacute; un error al intentar guardar la configuraci&oacute;n del usuario para el plugin %s",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activar todos',
	'admin:plugins:deactivate_all' => 'Desactivar todos',
	'admin:plugins:activate' => 'Activar',
	'admin:plugins:deactivate' => 'Desactivar',
	'admin:plugins:description' => "Este panel le permite controlar y configurar las herramientas instaladas en su sitio",
	'admin:plugins:opt:linktext' => "Configurar herramientas..",
	'admin:plugins:opt:description' => "Configurar las herramientas instaladas en el sitio. ",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categor&iacute;as',
	'admin:plugins:label:licence' => "Licencia",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Report issue",
	'admin:plugins:label:donate' => "Donate",
	'admin:plugins:label:moreinfo' => 'mas informaci&oacute;n',
	'admin:plugins:label:version' => 'Versi&oacute;n',
	'admin:plugins:label:location' => 'Ubicacion',
	'admin:plugins:label:contributors' => 'Colaboradores',
	'admin:plugins:label:contributors:name' => 'Nombre',
	'admin:plugins:label:contributors:email' => 'Correo Electrónico',
	'admin:plugins:label:contributors:website' => 'Sitio Web',
	'admin:plugins:label:contributors:username' => 'Nombre de Usuario de la Comunidad',
	'admin:plugins:label:contributors:description' => 'Descripci&oacute;n completa',
	'admin:plugins:label:dependencies' => 'Dependencias',

	'admin:plugins:warning:elgg_version_unknown' => 'Este plugin utiliza un archivo de manifiesto obsoleto y no especifica una versi&oacute;n de Elgg compatibla. Es muy probable que no funcione!',
	'admin:plugins:warning:unmet_dependencies' => 'Este plugin tiene dependencias desconocidas y no se activar&aacute;. Consulte las dependencias debajo de mas informaci&oacute;n',
	'admin:plugins:warning:invalid' => '%s no es un plugin Elgg v&aacute;lido. Visite <a href="http://docs.elgg.org/Invalid_Plugin">la Documentaci&oacute;n Elgg</a> para consejos de soluci&oacute;n de problemas',
	'admin:plugins:warning:invalid:check_docs' => 'Mira <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">la documentación de Elgg</a> donde encontrarás consejos de resolución de problemas.',
	'admin:plugins:cannot_activate' => 'no se puede activar',

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
	'admin:statistics:label:basic' => "Estad&iacute;sticas b&aacute;sicas del sitio",
	'admin:statistics:label:numentities' => "Entidades del sitio",
	'admin:statistics:label:numusers' => "Cantidad de usuarios",
	'admin:statistics:label:numonline' => "Cantidad de usuarios conectados",
	'admin:statistics:label:onlineusers' => "Usuarios conectados en este momento",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Versi&oacute;n de Elgg",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versi&oacute;n",

	'admin:server:label:php' => 'PHP',
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

	'admin:appearance:menu_items' => 'Elementos del Men&uacute;',
	'admin:menu_items:configure' => 'Configurar los elementos del men&uacute; principal',
	'admin:menu_items:description' => 'Seleccione qu&eacute; elementos del men&uacute; desea mostrar como enlaces favoritos. Los items no utilizados se encontrar&aacute;n en el item "Mas" al final de la lista',
	'admin:menu_items:hide_toolbar_entries' => 'Quitar enlaces del men&uacute; de la barra de herramientas?',
	'admin:menu_items:saved' => 'Elementos del men&uacute; guardados',
	'admin:add_menu_item' => 'Agregar un elemento del men&uacute; personalizado',
	'admin:add_menu_item:description' => 'Complete el nombre para mostrar y la direcci&oacute;n url para agregar un elemento de men&uacute; personalizado',

	'admin:appearance:default_widgets' => 'Widgets por defecto',
	'admin:default_widgets:unknown_type' => 'Tipo de widget desconocido',
	'admin:default_widgets:instructions' => 'Agregar, quitar, mover y configurar los widgets por defecto en la p&aacute;gina de widget seleccionada',

	'admin:robots.txt:instructions' => "Editar el robots.txt de este sitio a continuación",
	'admin:robots.txt:plugins' => "Plugins estan agregando lo siguiente al archivo robots.txt",
	'admin:robots.txt:subdir' => "La herramienta de robots.txt no funcionara por que Elgg esta instalado en un sub-directorio",

	'admin:maintenance_mode:default_message' => 'El sitio no está disponible por mantenimiento',
	'admin:maintenance_mode:instructions' => 'El Modo de Mantenimiento solo debe ser usado para actualizaciones y otros cambios de importancia en el sitio.
Cuando este modo esta activado, solo los administradores pueden ingresar y ver el sitio',
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

	'friends:widget:description' => "Muestra algunos de tus amigos",
	'friends:num_display' => "Cantidad de amigos a mostrar",
	'friends:icon_size' => "Tama&ntilde;o del &iacute;cono",
	'friends:tiny' => "peque&ntilde;o",
	'friends:small' => "chico",

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
	'back' => 'Back',

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
	'fileexists' => "El archivo ya se ha subido. Para reemplazarlo, seleccione:",

/**
 * User add
 */

	'useradd:subject' => 'Cuenta de usuario creada',
	'useradd:body' => '
 %s,
 
Su cuenta de usuario ha sido creada en %s. Para iniciar sesi&oacute;n visite:
 
 %s
 
E inicie sesi&oacute;n con las siguientes credenciales:
 
 Username: %s
 Password: %s
 
Una vez autenticado, le recomendamos que modifique su contrase&ntilde;a.
 ',

/**
 * System messages
 */

	'systemmessages:dismiss' => "click para cerrar",


/**
 * Import / export
 */
		
	'importsuccess' => "Importaci&oacute;n exitosa",
	'importfail' => "Error al importar datos de OpenDD",

/**
 * Time
 */

	'friendlytime:justnow' => "ahora",
	'friendlytime:minutes' => "hace %s minutos",
	'friendlytime:minutes:singular' => "hace un minuto",
	'friendlytime:hours' => "hace %s horas",
	'friendlytime:hours:singular' => "hace una hora",
	'friendlytime:days' => "hace %s d&iacute;as",
	'friendlytime:days:singular' => "ayer",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	
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

	'date:weekday:0' => 'Domingo',
	'date:weekday:1' => 'Lunes',
	'date:weekday:2' => 'Martes',
	'date:weekday:3' => 'Miércoles',
	'date:weekday:4' => 'Jueves',
	'date:weekday:5' => 'Viernes',
	'date:weekday:6' => 'Sábado',
	
	'interval:minute' => 'Cada minuto',
	'interval:fiveminute' => 'Cada cinco minutos',
	'interval:fifteenmin' => 'Cada quince minutos',
	'interval:halfhour' => 'Cada media hora',
	'interval:hourly' => 'Cada hora',
	'interval:daily' => 'Diario',
	'interval:weekly' => 'Semanal',
	'interval:monthly' => 'Mensual',
	'interval:yearly' => 'Anual',
	'interval:reboot' => 'Cada reinicio',

/**
 * System settings
 */

	'installation:sitename' => "El nombre del sitio:",
	'installation:sitedescription' => "Breve descripci&oacute;n del sitio (opcional):",
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
	'installation:walled_garden:description' => 'Habilitar al sitio para ejecutarse como una red privada. Esto impedir&aacute; a usuarios no registrados visualizar cualquier p&aacute;gina del sitio, exceptuando las establecidas como p&uacute;blicas',
	'installation:walled_garden:label' => 'Restringir p&aacute;ginas a usuarios registrados',

	'installation:httpslogin' => "Habilitar esta opci&oacute;n para que los usuarios se autentiquen mediante HTTPS. Necesitar&aacute; habilitar HTTPS en el server tambi&eacute;n para que esto funcione",
	'installation:httpslogin:label' => "Habilitar autenticaci&oacute;n HTTPS",
	'installation:view' => "Ingrese la vista que se visualizar&aacute; por defecto en el sitio o deje esto en blanco para la vista por defecto (si tiene dudas, d&eacute;jelo por defecto):",

	'installation:siteemail' => "Direcci&oacute;n de Email del sitio (utilizada para enviar mails desde el sistema):",
	'installation:default_limit' => "N&uacute;mero por defecto de elementos por p&aacute;gina",

	'admin:site:access:warning' => "Las modificaciones en el control de accesos s&oacute;lo tendr&aacute; impacto en los accesos futuros",
	'installation:allow_user_default_access:description' => "Si se selecciona, se les permitir&aacute; a los usuarios establecer su propio nivel de acceso por defecto que puede sobreescribir los niveles de acceso del sistema",
	'installation:allow_user_default_access:label' => "Permitir el acceso por defecto de los usuarios",

	'installation:simplecache:description' => "La cache simple aumenta el rentimiento almacenando contenido est&aacute;tico, como hojas CSS y archivos JavaScript. Normalmente se desea esto activado",
	'installation:simplecache:label' => "Utilizar cache simple (recomendado)",

	'installation:minify:description' => "El simple cache puede también mejorar el rendimiento por que comprime los archivos JavaScript y CSS. (Requiere que simple cache este habilitado.)",
	'installation:minify_js:label' => "Comprimir JavaScript (recomendado)",
	'installation:minify_css:label' => "Comprimir CSS (recomendado)",

	'installation:htaccess:needs_upgrade' => "Debe actualizar el archivo .htaccess para que la ruta se inyecte en el parámetro GET __elgg_uri (puede usar install/config/htaccess.dist como guía)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg no puede conectarse a sí mismo para poner a prueba las reglas de substitución. Asegúrese de que «curl» está funcionando, y de que no existen restricciones por dirección IP que impidan las conexiones locales al propio servidor (localhost).",
	
	'installation:systemcache:description' => "The system cache decreases the loading time of the Elgg engine by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caché',
	'admin:legend:content_access' => 'Acceso del Contenido',
	'admin:legend:site_access' => 'Acceso del Sitio',
	'admin:legend:debug' => 'Depuración y registro',

	'upgrading' => 'Actualizando..',
	'upgrade:db' => 'La base de datos ha sido actualizada',
	'upgrade:core' => 'La instalaci&oacute;n de Elgg ha sido actualizada',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Desbloqueo de Actualización exitoso.",
	'upgrade:unable_to_upgrade' => 'No se puede actualizar',
	'upgrade:unable_to_upgrade_info' =>
		'Esta versión no se puede actualizar debido a que se detectaron vistas legadas de otras versiones en el directorio de las vistas del core de Elgg. Estas vistas son obsoletas y tienen que ser eliminadas para el correcto funcionamiento de Elgg. Si no has hecho cambios en el core de Elgg, puedes simplemente eliminar el directorio de vistas y reemplazarlo con dicho directorio de uno de los paquetes más recientes de Elgg, descargándolo de <a href="http://elgg.org">elgg.org</a>.<br /><br />

		Si necesitas instrucciones detalladas, por favor visita la  <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
		documentación de Actualización de Elgg</a>.  Si necesitas soporte, escribe describiendo tu problema en los
		<a href="http://community.elgg.org/pg/groups/discussion/">Foros de Soporte de la Comunidad</a>.',

	'update:twitter_api:deactivated' => 'La API de Twitter (anteriormente Twitter Service) se ha desactivado durante la actualizaci&oacute;n. Por favor act&iacute;vela manualmente si se requiere',
	'update:oauth_api:deactivated' => 'La API OAuth (anteriormente OAuth Lib) se ha desactivado durante la actualizaci&oacute;n. Por favor act&iacute;vela manualmente si se requiere',
	'upgrade:site_secret_warning:moderate' => "Te recomendamos que regeneres la clave de tu sitio para mejorar la seguridad del sistema. Ver Configuración &gt; Preferencias &gt; Avanzado",
	'upgrade:site_secret_warning:weak' => "Te recomendamos fuertemente que regeneres la clave de tu sitio para mejorar la seguridad del sistema. Ver Configuración &gt; Preferencias &gt; Avanzado",

	'deprecated:function' => '%s() ha quedado obsoleta por %s()',

	'admin:pending_upgrades' => 'Este sitio tiene actualizaciones pendientes que requieren tu atención inmediata',
	'admin:view_upgrades' => 'Ver actualizaciones pendientes',
 	'admin:upgrades' => 'Actualizaciones',
	'item:object:elgg_upgrade' => 'Actualizaciones del sitio',
	'admin:upgrades:none' => 'Tu instalación esta al día!',

	'upgrade:item_count' => 'Hay <b>%s</b> elementos que es necesario actualizar.',
	'upgrade:warning' => '<b>Aviso:</b> ¡En un sitio grande esta actualización puede llevar un tiempo significativo!',
	'upgrade:success_count' => 'Actualizado:',
	'upgrade:error_count' => 'Errores:',
	'upgrade:river_update_failed' => 'No fue posible actualizar la entrada del River para el elemento con identificador «%s».',
	'upgrade:timestamp_update_failed' => 'No fue posible actualizar los tiempos del elemento con identificador «%s».',
	'upgrade:finished' => 'Se completó la actualización.',
	'upgrade:finished_with_errors' => '<p>Ocurrieron errores durante la actualización. Actualice la página y pruebe a ejecutar la actualización de nuevo.</p></p><br />
Si el error se repite, busque la causa en el registro de errores del servidor. Puede buscar ayuda para solucionar el problema en el <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupo de asistencia técnica</a> de la comunidad de Elgg.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Actualización de comentarios',
	'upgrade:comment:create_failed' => 'No fue posible convertir el comentario con identificador «%s» en una entidad.',
	'admin:upgrades:commentaccess' => 'Actualizar el acceso a comentarios',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Actualización de la carpeta de datos',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Actualización de las respuestas de discusiones',
	'discussion:upgrade:replies:create_failed' => 'No fue posible convertir la respuesta de discusión con identificador «%s» en una entidad.',

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

	'email:save:success' => "New email address saved.",
	'email:save:fail' => "No se pudo guardar la nueva direcci&oacute;n de Email",

	'friend:newfriend:subject' => "%s te ha puesto como amigo suyo!",
	'friend:newfriend:body' => "%s te ha puesto como amigo suyo!
 
Para visualizar su perfil haz click aqu&iacute;:
 
 %s
 
Por favor no responda a este mail",

	'email:changepassword:subject' => "Contraseña cambiada!",
	'email:changepassword:body' => "Hola %s,

Tu contraseña ha sido cambiada.",

	'email:resetpassword:subject' => "Contrase&ntilde;a restablecida!",
	'email:resetpassword:body' => "Hola %s,
 
Tu contrase&ntilde;a ha sido restablecida a: %s",

	'email:changereq:subject' => "Solicitud de cambio de contraseña.",
	'email:changereq:body' => "Hola %s,

Alguien (desde la dirección IP %s) ha solicitado un cambio de contraseña para tu cuenta.

Si has sido tu, utiliza el enlace inferior. Si no es así, puedes ignorar este mensaje.

%s
",

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

	'river:comment:object:default' => '%s commented on %s',

	'generic_comments:add' => "Comentar",
	'generic_comments:edit' => "Editar comentario",
	'generic_comments:post' => "Publicar un comentario",
	'generic_comments:text' => "Comentar",
	'generic_comments:latest' => "&uacute;ltimos comentarios",
	'generic_comment:posted' => "Se ha publicado su comentario",
	'generic_comment:updated' => "El comentario fué cambiado éxitosamente.",
	'generic_comment:deleted' => "Se ha quitado su comentario",
	'generic_comment:blank' => "Lo sentimos, debe ingresar alg&uacute;n comentario antes de poder guardarlo",
	'generic_comment:notfound' => "Lo sentimos. No hemos encontrado el comentario especificado.",
	'generic_comment:notfound_fallback' => "Lo sentimos. No hemos encontrado el comentario especificado, pero te hemos redirigido a la página donde se comentó.",
	'generic_comment:notdeleted' => "Lo sentimos, no se pudo eliminar el comentario",
	'generic_comment:failure' => "Un error no especificado ocurrió al guardar el comentario.",
	'generic_comment:none' => 'Sin comentarios',
	'generic_comment:title' => 'Comentario de %s',
	'generic_comment:on' => '%s on %s',
	'generic_comments:latest:posted' => 'Publicó un',

	'generic_comment:email:subject' => 'Tienes un nuevo comentario!',
	'generic_comment:email:body' => "Tiene un nuevo comentario en el item \"%s\" de %s. Dice:
 
 
 %s
 
 
Para responder o ver el item original, haga click aqu&iacute;:
 
 %s
 
Para ver el prfil de %s, haga click aqu&iacute;:
 
 %s
 
Por favor no responda a este correo",

/**
 * Entities
 */
	
	'byline' => 'Por %s',
	'entity:default:strapline' => 'Creado %s por %s',
	'entity:default:missingsupport:popup' => 'Esta entidad no puede mostrarse correctamente. Esto puede deberse a que el soporte provisto por un plugin ya no se encuentra instalado',

	'entity:delete:success' => 'La entidad %s ha sido eliminada',
	'entity:delete:fail' => 'La entidad %s no pudo ser eliminada',

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
	"pt_br" => 'Portugués Brasileño',
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
