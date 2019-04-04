<?php
return array(
	'install:title' => 'Instalación de Elgg',
	'install:welcome' => 'Benvida',
	'install:requirements' => 'Comprobación de requisitos',
	'install:database' => 'Instalación da base de datos',
	'install:settings' => 'Configurar o siti',
	'install:admin' => 'Crear unha conta de administrador',
	'install:complete' => 'List',

	'install:next' => 'Seguinte',
	'install:refresh' => 'Actualizar',

	'install:welcome:instructions' => "Installing Elgg has 6 simple steps and reading this welcome is the first one!

If you haven't already, read through the installation instructions included with Elgg (or click the instructions link at the bottom of the page).

If you are ready to proceed, click the Next button.",
	'install:requirements:instructions:success' => "O servidor cumpre cos requisitos.",
	'install:requirements:instructions:failure' => "O servidor non cumpre cos requisitos. Solucione os problemas listados e actualice esta páxina. Se necesita máis axuda, bótelle un ollo ás ligazóns de solución de problemas ao final desta páxina.",
	'install:requirements:instructions:warning' => "O servidor cumpre cos requisitos, pero durante a comprobación apareceron avisos. Recomendámoslle que lle bote unha ollada á páxina de solución de problemas para máis información.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Servidor we',
	'install:require:settings' => 'Ficheiro de configuración',
	'install:require:database' => 'Base de datos',

	'install:check:root' => 'Your web server does not have permission to create an .htaccess file in the root directory of Elgg. You have two choices:

1. Change the permissions on the root directory

2. Copy the file install/config/htaccess.dist to .htaccess',

	'install:check:php:version' => 'Elgg necesita PHP %s ou unha versión superior. O servidor usa PHP %s.',
	'install:check:php:extension' => 'Elgg necesita a extensión de PHP «%s».',
	'install:check:php:extension:recommend' => 'Recoméndase instalar a extensión de PHP «%s».',
	'install:check:php:open_basedir' => 'É posíbel que a directiva «open_basedir» de PHP lle impida a Elgg gardar ficheiros no seu cartafol de datos.',
	'install:check:php:safe_mode' => 'Non se recomenda executar PHP en modo seguro, dado que pode ocasionar problemas con Elgg.',
	'install:check:php:arg_separator' => 'O valor de «arg_separator.output» debe ser «&» para que Elgg funcione. O valor actual do servidor é «%s».',
	'install:check:php:register_globals' => 'O rexistro de variábeis globais (opción «register_globals») debe estar desactivado.
',
	'install:check:php:session.auto_start' => "A opción «session.auto_start» debe estar desactivada para que Elgg funcione. Cambie a configuración do servidor ou engada a directiva ao ficheiro «.htaccess» de Elgg.",

	'install:check:installdir' => 'Your web server does not have permission to create the settings.php file in your installation directory. You have two choices:

1. Change the permissions on the elgg-config directory of your Elgg installation

2. Copy the file %s/settings.example.php to elgg-config/settings.php and follow the instructions in it for setting your database parameters.',
	'install:check:readsettings' => 'Existe un ficheiro de configuración no cartafol do motor, pero o servidor web non ten permisos de lectura nel. Pode eliminar o ficheiro ou darlle ao servidor permisos de lectura sobre el.',

	'install:check:php:success' => "O PHP do servidor cumpre cos requisitos de Elgg.",
	'install:check:rewrite:success' => 'O servidor pasou a proba das regras de reescritura.',
	'install:check:database' => 'Elgg non pode comprobar que a base de datos cumpre cos requisitos ata que non a carga.',

	'install:database:instructions' => "Se aínda non creou unha base de datos para Elgg, fágao agora. A continuación complete os seguintes campos para preparar a base de datos para Elgg.",
	'install:database:error' => 'Non foi posíbel crear a base de datos de Elgg por mor dun erro, e a instalación non pode continuar. Revise a mensaxe da parte superior e corrixa calquera problema. Se necesita máis axuda, siga a ligazón de solución de problemas de instalación na parte inferior desta páxina, ou publique unha mensaxe nos foros da comunidade de Elgg.',

	'install:database:label:dbuser' =>  'Usuario',
	'install:database:label:dbpassword' => 'Contrasinal',
	'install:database:label:dbname' => 'Base de datos',
	'install:database:label:dbhost' => 'Servidor',
	'install:database:label:dbprefix' => 'Prefixo das táboas',
	'install:database:label:timezone' => "Timezone",

	'install:database:help:dbuser' => 'Usuario que ten todos os permisos posíbeis sobre a base de datos MySQL que creou para Elgg.',
	'install:database:help:dbpassword' => 'Contrasinal da conta de usuario da base de datos introducida no campo anterior.',
	'install:database:help:dbname' => 'Nome da base de datos para Elgg.',
	'install:database:help:dbhost' => 'Enderezo do servidor de MySQL (normalmente é «localhost»).',
	'install:database:help:dbprefix' => "O prefixo que se lles engade a todas as táboas de Elgg (normalmente é «elgg_»).",
	'install:database:help:timezone' => "The default timezone in which the site will operate",

	'install:settings:instructions' => 'We need some information about the site as we configure Elgg. If you haven\'t <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">created a data directory</a> for Elgg, you need to do so now.',

	'install:settings:label:sitename' => 'Nome',
	'install:settings:label:siteemail' => 'Enderezo de correo',
	'install:database:label:wwwroot' => 'URL',
	'install:settings:label:path' => 'Cartafol de instalación',
	'install:database:label:dataroot' => 'Cartafol de datos',
	'install:settings:label:language' => 'Idioma',
	'install:settings:label:siteaccess' => 'Acceso predeterminado',
	'install:label:combo:dataroot' => 'Elgg crea o cartafol de datos',

	'install:settings:help:sitename' => 'Nome do novo sitio Elgg.',
	'install:settings:help:siteemail' => 'Enderezo de correo electrónico que Elgg empregará para contactar con usuarios.',
	'install:database:help:wwwroot' => 'O enderezo do sitio (Elgg adoita determinar o valor correcto automaticamente)',
	'install:settings:help:path' => 'O cartafol onde estará o código de Elgg (Elgg adoita atopar o cartafol automaticamente).',
	'install:database:help:dataroot' => 'O cartafol que creou para que Elgg garde os ficheiros (cando prema «Seguinte» comprobaranse os permisos do cartafol). Debe ser unha ruta absoluta.',
	'install:settings:help:dataroot:apache' => 'Pode permitir que Elgg cree o cartafol de datos ou pode indicar o cartafol que vostede xa creou para gardar os ficheiros dos usuarios (ao premer «Seguinte» comprobaranse os permisos do cartafol).',
	'install:settings:help:language' => 'O idioma predeterminado do sitio.',
	'install:settings:help:siteaccess' => 'O nivel de acceso predeterminado para o novo contido que creen os usuarios.',

	'install:admin:instructions' => "É hora de crear unha conta de administrador",

	'install:admin:label:displayname' => 'Nome para mostrar',
	'install:admin:label:email' => 'Enderezo de correo',
	'install:admin:label:username' => 'Nome de usuario',
	'install:admin:label:password1' => 'Contrasinal',
	'install:admin:label:password2' => 'Contrasinal (repítaa)',

	'install:admin:help:displayname' => 'Nome que se mostra no sitio para esta conta.',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Nome de usuario da conta que pode empregar para acceder ao sitio identificándose coa conta.',
	'install:admin:help:password1' => "Contrasinal da conta. Debe ter polo menos %u caracteres.",
	'install:admin:help:password2' => 'Volva escribir o contrasinal para confirmalo.',

	'install:admin:password:mismatch' => 'Os contrasinais deben coincidir.',
	'install:admin:password:empty' => 'O campo do contrasinal non pode quedar baleiro.',
	'install:admin:password:tooshort' => 'O contrasinal era curto de máis',
	'install:admin:cannot_create' => 'Non foi posíbel crear unha conta de adminsitrador',

	'install:complete:instructions' => 'O novo sitio Elgg está listo. Prema o seguinte botón para acceder a el.',
	'install:complete:gotosite' => 'Ir ao siti',
	'install:complete:admin_notice' => 'Welcome to your Elgg site! For more options, see the %s.',
	'install:complete:admin_notice:link_text' => 'settings pages',

	'InstallationException:UnknownStep' => 'Descoñécese o paso de instalación «%s».',
	'InstallationException:MissingLibrary' => 'Non foi posíbel cargar «%s»',
	'InstallationException:CannotLoadSettings' => 'Elgg non puido cargar o ficheiro de configuración. Ou ben o ficheiro non existe, ou ben hai un problema de permisos.',

	'install:success:database' => 'Instalouse a base de datos',
	'install:success:settings' => 'Gardouse a configuración do sitio.',
	'install:success:admin' => 'Creouse a conta de administrador.',

	'install:error:htaccess' => 'Non foi posíbel crear «.htaccess».',
	'install:error:settings' => 'Non foi posíbel crear o ficheiro de configuración.',
	'install:error:settings_mismatch' => 'The settings file value for "%s" does not match the given $params.',
	'install:error:databasesettings' => 'Non foi posíbel conectarse á base de datos coa información de conexión indicada.',
	'install:error:database_prefix' => 'O prefixo da base de datos contén caracteres que non son válidos.',
	'install:error:oldmysql2' => 'O servidor de bases de datos debe ser un MySQL 5.5.3 ou unha versión superior. O servidor actual usa MySQL %s.',
	'install:error:nodatabase' => 'Non foi posíbel usar a base de datos «%s». Pode que non exista.',
	'install:error:cannotloadtables' => 'Non foi posíbel cargar as táboas da base de datos.',
	'install:error:tables_exist' => 'A base de datos xa contén táboas de Elgg. Ten que eliminar esas táboas ou reiniciar o instalador e intentar facer uso delas. Para reiniciar o instalador, elimine a parte de «?step=database» do URL na barra do URL do navegador, e prema Intro.',
	'install:error:readsettingsphp' => 'Unable to read /elgg-config/settings.example.php',
	'install:error:writesettingphp' => 'Unable to write /elgg-config/settings.php',
	'install:error:requiredfield' => 'Necesítase «%s».',
	'install:error:relative_path' => 'A ruta «%s» para o cartafol de datos non parece unha ruta absoluta.',
	'install:error:datadirectoryexists' => 'O cartafol de datos, «%s», non existe.',
	'install:error:writedatadirectory' => 'O servidor web non ten permisos de escritura no cartafol de datos, «%s».',
	'install:error:locationdatadirectory' => 'Por motivos de seguranza, o cartafol de datos («%s») non pode estar dentro do cartafol de instalación.',
	'install:error:emailaddress' => '%s non é un enderezo de correo válido.',
	'install:error:createsite' => 'Non foi posíbel crear o sitio.',
	'install:error:savesitesettings' => 'Non foi posíbel gardar a configuración do sitio.',
	'install:error:loadadmin' => 'Non foi posíbel cargar o administrador.',
	'install:error:adminaccess' => 'Non foi posíbel darlle privilexios de administrador á nova conta de usuario.',
	'install:error:adminlogin' => 'Non foi posíbel acceder automaticamente ao sitio coa nova conta de administrador',
	'install:error:rewrite:apache' => 'Parece que o servidor web que está a usar é Apache.',
	'install:error:rewrite:nginx' => 'Parece que o servidor web que está a usar é Nginx.',
	'install:error:rewrite:lighttpd' => 'Parece que o servidor web que está a usar é Lighttpd.',
	'install:error:rewrite:iis' => 'Parece que o servidor web que está a usar é IIS.',
	'install:error:rewrite:allowoverride' => "The rewrite test failed and the most likely cause is that AllowOverride is not set to All for Elgg's directory. This prevents Apache from processing the .htaccess file which contains the rewrite rules.
\n\nA less likely cause is Apache is configured with an alias for your Elgg directory and you need to set the RewriteBase in your .htaccess. There are further instructions in the .htaccess file in your Elgg directory.",
	'install:error:rewrite:htaccess:write_permission' => 'O seu servidor web carece de permisos para crear un ficheiro «.htaccess» no cartafol de Elgg. Ten que copialo manualmente de «install/config/htaccess.dist» a «.htaccess» ou cambiar os permisos do cartafol.',
	'install:error:rewrite:htaccess:read_permission' => 'Hai un ficheiro «.htaccess» no cartafol de Elgg, pero o servidor web non ten permisos para lelo.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Hai un ficheiro «.htaccess» no cartafol de Elgg que non creou Elgg. Elimíneo.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Atopouse o que parece un vello ficheiro «.htaccess» de Elgg no cartafol de Elgg. Fáltalle a regra de reescritura para probar o servidor web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Produciuse un erro descoñecido durante a creación do ficheiro «.htaccess». Ten que copiar manualmente «install/config/htaccess.dist» a «.htaccess» no cartafol de Elgg.',
	'install:error:rewrite:altserver' => 'Non se pasou a proba das regras de reescritura. Ten que configurar o servidor web coas regras de reescritura de Elgg e intentalo de novo',
	'install:error:rewrite:unknown' => 'Uf. Non foi posíbel determinar o tipo de servidor web que está a usar, e non pasou a proba das regras de reescritura. Non podemos aconsellalo sobre como solucionar o seu problema específico. Bótelle unha ollada á ligazón sobre solución de problemas.',
	'install:warning:rewrite:unknown' => 'O servidor non permite probar automaticamente as regras de reescritura, e o navegador non permite probalas mediante JavaScript. Pode continuar a instalación, pero pode que ao rematar o sitio lle dea problemas. Para probar manualmente as regras de reescritura, siga esta ligazón: <a href="%s" target="_blank">probar</a>. Se as regras funcionan, aparecerá a palabra «success».',
	'install:error:wwwroot' => '%s is not a valid URL',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Produciuse un erro do que non é posíbel recuperarse, e quedou rexistrado. Se vostede é o administrador do sistema, comprobe que a información do ficheiro de configuración é correcta. En caso contrario, póñase en contacto co administrador e facilítelle a seguinte información:',
	'DatabaseException:WrongCredentials' => "Elgg non puido conectar coa base de datos mediante o nome de usuario e contrasinal facilitados. Revise o ficheiro de configuración.",
);
