<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'install:title' => 'Instalación de Elgg',
	'install:welcome' => 'Bienvenido!',
	'install:requirements' => 'Verificación de requerimientos',
	'install:database' => 'Instalación de base de datos',
	'install:settings' => 'Configuración del sitio',
	'install:admin' => 'Crear cuenta admin',
	'install:complete' => 'Finalizado',

	'install:next' => 'Siguiente',
	'install:refresh' => 'Actualizar',
	'install:change_language' => 'Cambiar de idioma',

	'install:welcome:instructions' => "¡Instalar Elgg tiene 6 simples pasos y leer esta bienvenida es el primero!

Si aún no lo has hecho, lee las instrucciones de instalación incluidas con Elgg (o haz click en el link de instrucciones al final de la página).

Si está listo para proceder, haga click en el botón Siguiente.",
	
	'install:footer:instructions' => "Instrucciones de instalación",
	'install:footer:troubleshooting' => "Solución de problemas de instalación",
	'install:footer:community' => "Foros de la comunidad Elgg",
	
	'install:requirements:instructions:success' => "Su servidor pasó la verificación de requerimientos.",
	'install:requirements:instructions:failure' => "Su servidor falló la verificación de requerimientos. Luego de solucionar los items enumerados debajo refresque esta página. Si necesita mas ayuda verifique los enlaces de solución de problemas al final de esta página.",
	'install:requirements:instructions:warning' => "Su servidor pasó la verificación de requerimientos, pero hay al menos una advertencia. Le recomendamos que verifique la página de solución de problemas para mas información.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Servidor web',
	'install:require:settings' => 'Archivo de configuración',
	'install:require:database' => 'Base de datos',

	'install:check:root' => 'Su servidor web no tiene permiso para crear un archivo .htaccess en el directorio raíz de Elgg. Tiene dos opciones:

1. Cambiar los permisos en el directorio raíz

2. Copie el archivo install/config/htaccess.dist a .htaccess',

	'install:check:php:version' => 'Elgg requiere la versión %s de PHP o superior. Este servidor se encuentra ejecutando la versión %s.',
	'install:check:php:extension' => 'Elgg requiere la extensión %s de PHP.',
	'install:check:php:extension:recommend' => 'Se recomienda que la estensión %s de PHP se encuentre instalada.',
	'install:check:php:open_basedir' => 'La directiva de PHP open_basedir puede evitar que Elgg guarde archivos en su directorio de datos.',
	'install:check:php:safe_mode' => 'Ejecutar PHP en modo seguro no es recomendado y puede ocasionar inconvenientes con Elgg.',
	'install:check:php:arg_separator' => 'arg_separator.output debe ser & para que Elgg funcione, el valor actual del servidor es %s',
	'install:check:php:register_globals' => 'Debe desactivarse el registro de globales.',
	'install:check:php:session.auto_start' => "session.auto_start debe desactivarse para que Elgg funcione. Modifique la configuración de su servidor o agregue la directiva al archivo .htaccess de Elgg.",

	'install:check:installdir' => 'Su servidor web no tiene permiso para crear el archivo settings.php en su directorio de instalación. Tiene dos opciones:

1. Cambiar los permisos en el directorio elgg-config de tu instalación de Elgg

2. Copie el archivo %s/settings.example.php a elgg-config/settings.php y siga las instrucciones para configurar los parámetros de su base de datos.',
	'install:check:readsettings' => 'Existe un archivo settings en el directorio engine pero el servidor no puede leerlo. Puede eliminar el archivo o modificar los permisos sobre el mismo.',

	'install:check:php:success' => "El PHP de su servidor satisface todos los requerimientos de Elgg.",
	'install:check:rewrite:success' => 'El test de reescritura de reglas ha sido exitoso.',
	'install:check:database' => 'Los requerimientos de bases de datos se verificarán cuando Elgg cargue la base de datos.',

	'install:database:instructions' => "Si aún no creó una base de datos para Elgg debe hacerlo ahora. Luego complete los datos debajo para inicializar la base de datos.",
	'install:database:error' => 'Ocurrió un error al crear la base de datos de Elgg y la instalación no puede continuar. Revise los mensajes de error y corrija los problemas. Si necesita mas ayuda, visite el enlace para la solución de problemas de instalación debajo o utilice los foros de la comunidad Elgg.',

	'install:database:label:dbuser' =>  'Usuario de Base de Datos',
	'install:database:label:dbpassword' => 'Contraseña',
	'install:database:label:dbname' => 'Nombre de la Base de Datos',
	'install:database:label:dbhost' => 'Host de Base de Datos',
	'install:database:label:dbport' => 'Número de puerto de la base de datos',
	'install:database:label:dbprefix' => 'Prefijo de Tablas de la Base de Datos',
	'install:database:label:timezone' => "Zona horaria",

	'install:database:help:dbuser' => 'Usuario que posee todos los privilegios sobre la base de datos MySql que creó para Elgg',
	'install:database:help:dbpassword' => 'Contraseña para la cuenta del usuario anterior',
	'install:database:help:dbname' => 'Nombre de la base de datos Elgg',
	'install:database:help:dbhost' => 'Nombre del Host para el servidor MySQL (normalmente localhost)',
	'install:database:help:dbport' => 'Número de puerto del servidor MySQL (normalmente 3306)',
	'install:database:help:dbprefix' => "Prefijo para todas las tablas de Elgg (normalmente elgg_)",
	'install:database:help:timezone' => "La zona horaria predeterminada en la que el sitio funcionará",

	'install:settings:instructions' => 'Necesitamos alguna información sobre el sitio mientras configuramos Elgg. Si no has <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">creado un directorio</a> de datos para Elgg, necesitas hacerlo ahora.',

	'install:settings:label:sitename' => 'Nombre del Sitio',
	'install:settings:label:siteemail' => 'Dirección de Email del Sitio',
	'install:database:label:wwwroot' => 'URL del Sitio',
	'install:settings:label:path' => 'Directorio de Instalación de Elgg',
	'install:database:label:dataroot' => 'Directorio Data',
	'install:settings:label:language' => 'Idioma del Sitio',
	'install:settings:label:siteaccess' => 'Acceso por Defecto',
	'install:label:combo:dataroot' => 'Elgg crea el directorio data',

	'install:settings:help:sitename' => 'El nombre de su nuevo sitio Elgg',
	'install:settings:help:siteemail' => 'Dirección de Email utilizada por Elgg para comunicaciones a los usuarios',
	'install:database:help:wwwroot' => 'La dirección de esde sitio (normalmente Elgg la selecciona correctamnete)',
	'install:settings:help:path' => 'El directorio en donde se almacena el código de Elgg (normalmente Elgg lo selecciona correctamente)',
	'install:database:help:dataroot' => 'El directorio que ha creado para que Elgg guarde archivos (se validarán los permisos sobre este directorio cuando presione el botón de siguiente)',
	'install:settings:help:dataroot:apache' => 'Tiene la opción de que Elgg cree el directorio o de seleccionar uno que ya haya creado (se validarán los permisos sobre este directorio cuando presione el botón de siguiente)',
	'install:settings:help:language' => 'El idioma por defecto para el sitio',
	'install:settings:help:siteaccess' => 'El nivel de acceso por defecto al crear nuevo contenido los usuarios',

	'install:admin:instructions' => "Es tiempo de crear la cuenta del Administrador.",

	'install:admin:label:displayname' => 'Nombre para Mostrar',
	'install:admin:label:email' => 'Dirección de Email',
	'install:admin:label:username' => 'Nombre de Usuario',
	'install:admin:label:password1' => 'Contraseña',
	'install:admin:label:password2' => 'Contraseña Nuevamente',

	'install:admin:help:displayname' => 'El nombre que se muestra en el sitio para esta cuenta',
	'install:admin:help:username' => 'Nombre de usuario utilizado para acceder al sitio',
	'install:admin:help:password1' => "La contraseña de la cuenta debe tener al menos %u caracteres",
	'install:admin:help:password2' => 'Escriba nuevamente la contraseña para confirmar',

	'install:admin:password:mismatch' => 'Las contraseñas deben coincidir.',
	'install:admin:password:empty' => 'La contraseña no puede estar vacía.',
	'install:admin:password:tooshort' => 'La contraseña es demasiado corta',
	'install:admin:cannot_create' => 'No se pudo crear la cuenta del administrador.',

	'install:complete:instructions' => 'Su sitio Elgg ahora está listo para su uso. Haga click en el botón de abajo para ir a visitar el sitio.',
	'install:complete:gotosite' => 'Ir al sitio',
	'install:complete:admin_notice' => '¡Bienvenido a tu sitio Elgg! Para más opciones, consulte la sección %s.',
	'install:complete:admin_notice:link_text' => 'páginas de configuración',
	'install:complete:admin_notice:custom_index' => 'Hemos habilitado el plugin Front Page Demo para que puedas gestionar tu página de inicio. Configúralo aquí: %s',

	'InstallationException:CannotLoadSettings' => 'Elgg no pudo cargar el archivo de configuración. No existe o hay un problema de permisos.',

	'install:success:database' => 'Se ha instalado la base de datos.',
	'install:success:settings' => 'Se ha guardado la configuración del sitio.',
	'install:success:admin' => 'Se ha creado la cuenta Admin.',

	'install:error:htaccess' => 'No se pudo crear el archivo .htaccess',
	'install:error:settings' => 'No se pudo crear el archivo de configuración',
	'install:error:settings_mismatch' => 'El valor del archivo de configuración para "%s" no coincide con $params. Esperado: "%s" Actual: "%s"',
	'install:error:databasesettings' => 'No se pudo conectar a la base de datos con la información provista',
	'install:error:database_prefix' => 'Caracteres no válidos en el prefijo de la base de datos',
	'install:error:mysql_version' => 'MySQL debe ser la versión %s o superior. Su servidor utiliza %s.',
	'install:error:database_version' => 'La base de datos debe ser versión %s o superior. Su servidor utiliza %s.',
	'install:error:nodatabase' => 'No se pudo acceder a la base de datos %s. Puede que no exista.',
	'install:error:cannotloadtables' => 'No se pueden cargar las tablas de la base de datos',
	'install:error:tables_exist' => 'Se encontraron tablas de Elgg preexistentes en la base de datos. Debe eliminarlas o reiniciar el instalador para intentar utilizarlas. Para reiniciar el instalador, quite \'?step=database\' de la URL en la barra de direcciones de su explorador y presione ENTER.',
	'install:error:readsettingsphp' => 'No se puede leer /elgg-config/settings.example.php',
	'install:error:writesettingphp' => 'No se puede escribir /elgg-config/settings.php',
	'install:error:requiredfield' => '%s es requerido',
	'install:error:relative_path' => 'No creemos que "%s" sea una ruta absoluta para tu directorio de datos',
	'install:error:datadirectoryexists' => 'El directorio de datos (data) %s no existe.',
	'install:error:writedatadirectory' => 'El servidor no puede escribir en el directorio de datos (data) %s.',
	'install:error:locationdatadirectory' => 'El directorio de datos (data) %s debe encontrarse fuera de la carpeta de instalación por motivos de seguridad.',
	'install:error:emailaddress' => '%s no es una dirección de Email válida',
	'install:error:createsite' => 'No se pudo crear el sitio.',
	'install:error:savesitesettings' => 'No se pudo guardar la configuración del sitio',
	'install:error:loadadmin' => 'No se pudo cargar el usuario administrador.',
	'install:error:adminaccess' => 'No se le pueden otorgar privilegios de administrador al usuario.',
	'install:error:adminlogin' => 'No se puede autenticar automáticamente al usuario administrador.',
	'install:error:rewrite:apache' => 'Creemos que su servidor se encuentra ejecutando el web server Apache.',
	'install:error:rewrite:nginx' => 'Creemos que su servidor se encuentra ejecutando el web server Nginx.',
	'install:error:rewrite:lighttpd' => 'Creemos que su servidor se encuentra ejecutando el web server Lighttpd.',
	'install:error:rewrite:iis' => 'Creemos que su servidor se encuentra ejecutando el web server Microsoft IIS.',
	'install:error:rewrite:allowoverride' => "La prueba de reescritura falló y la causa más probable es que PermitirSobreescribir (AllowOverride) no está en Todos (All) para el directorio de Elgg. Esto previene que Apache procese el archivo .htaccess que contiene las reglas de reescritura.
\n\nA Una causa menos probable es que Apache esté configurado con un alias para el directorio de Elgg y necesite establecer el ReescribirBase (RewriteBase) en su .htaccess. Hay más instrucciones en el archivo .htaccess en tu directorio Elgg.",
	'install:error:rewrite:htaccess:write_permission' => 'Su servidor web no tiene permisos para escribir el archivo .htaccess en la carpeta de Elgg. Debe copiar manualmente htaccess_dist a .htaccess o modificar los permisos en el directorio.',
	'install:error:rewrite:htaccess:read_permission' => 'Hay un archivo .htaccess en el directorio de Elgg, pero su servidor web no tiene los permisos necesarios para leerlo.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Hay un archivo .htaccess en el directorio de Elgg que no ha sido creado por Elgg. Por favor elimínelo.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Al parecer hay un archivo .htaccess de Elgg antiguo en el directorio de Elgg. El mismo no contiene la regla de reescritura para realizar la prueba del servidor web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Ha ocurrido un error desconocido al crear el archivo .htaccess. Deberá copiar manualmente htaccess_dist a .htaccess en el directorio de Elgg.',
	'install:error:rewrite:altserver' => 'La prueba de la reescritura de reglas ha fallado. Debe configurar su servidor web con reescritura de reglas e intentar nuevamente.',
	'install:error:rewrite:unknown' => 'Oof. No podemos saber qué tipo de servidor web se encuentra ejecutando y falló la reescritura de reglas. No podemos ofrecer ninguna ayuda específica. Por favor verifique el enlace de solución de problemas.',
	'install:warning:rewrite:unknown' => 'Su servidor no soporta la prueba automática de reescritura de reglas. Puede continuar con la instalación, pero puede experimentar problemas con el sitio. Puede probar manualmente las reescritura de reglas accediento a este enlace: <a href="%s" target="_blank">pruebas</a>. Observará la palabra success si la ejecución ha sido exitosa.',
	'install:error:wwwroot' => '%s no es una URL válida',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Se ha producido un error irrecuperable y se ha registrado. Si usted es el administrador del sitio, compruebe su archivo de configuración; de lo contrario, póngase en contacto con el administrador del sitio con la siguiente información:',
	'DatabaseException:WrongCredentials' => "Elgg no puede conectar con la base de datos, usando los credenciales. Consulte en el archivo 'settings'.",
);
