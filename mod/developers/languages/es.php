<?php
return array(
	// menu
	'admin:develop_tools' => 'Herramientas',
	'admin:develop_tools:sandbox' => 'Sanbox del Tema',
	'admin:develop_tools:inspect' => 'Inspecciona',
	'admin:develop_tools:unit_tests' => 'Tests unitarios',
	'admin:developers' => 'Desarrolladorxs',
	'admin:developers:settings' => 'Configuración de Desarrolladorxs',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Controle su configuración de desarrollo y depuración. Algunas de estas opciones también se encuentran disponibles en otras secciones de la administración.',
	'developers:label:simple_cache' => 'Utilizar cache simple',
	'developers:help:simple_cache' => 'Deshabilitar la cache mientras se desarrolla. De otro modo, las modificaciones a las views (incluyendo los css) serán ignoradas.',
	'developers:label:system_cache' => 'Utiliza el caché del sistema',
	'developers:help:system_cache' => 'Desactívalo mientras estés desarrollando. Si no, los cambios en los plugins no se verán.',
	'developers:label:debug_level' => "Nivel de monitoreo",
	'developers:help:debug_level' => "Esto controla la cantidad de información que se registra. Vea elgg_log() para más información.",
	'developers:label:display_errors' => 'Mostrar errores fatales de PHP',
	'developers:help:display_errors' => "Por defecto, el archivo .htaccess de Elgg deshabilita la visualización de errores fatales.",
	'developers:label:screen_log' => "Registrar en pantalla",
	'developers:help:screen_log' => "Esto muestra las salidas de elgg_log() y elgg_dump() en la página.",
	'developers:label:show_strings' => "Mostrar strings de traducciones extrañas",
	'developers:help:show_strings' => "Esto muestra las traducciones utilizadas por elgg_echo().",
	'developers:label:wrap_views' => "Wrap de vistas",
	'developers:help:wrap_views' => "Esto envuelve casi todas las vistas con comentarios HTML. Muy útil para encontrar la vista creando HTML particular
         Esto puede dañar vistas no HTML en viewtype por defecto. Ver developers_wrap_views() para más detalles",
	'developers:label:log_events' => "Eventos de Logs y Hooks de plugins",
	'developers:help:log_events' => "Escribir eventos y hooks de plugins en el log. Precaución: hay varios de estos por página.",

	'developers:debug:off' => 'Apagado',
	'developers:debug:error' => 'Error',
	'developers:debug:warning' => 'Precaución',
	'developers:debug:notice' => 'Información',
	'developers:debug:info' => 'Información',
	
	// inspection
	'developers:inspect:help' => 'Inspección de configuration del framework Elgg.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' en %s",

	// theme sandbox
	'theme_sandbox:intro' => 'Introducción',
	'theme_sandbox:breakout' => 'Fuera de iframe',
	'theme_sandbox:buttons' => 'Botones',
	'theme_sandbox:components' => 'Componentes',
	'theme_sandbox:forms' => 'Formularios',
	'theme_sandbox:grid' => 'Grilla',
	'theme_sandbox:icons' => 'Iconos',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Estructuras',
	'theme_sandbox:modules' => 'Módulos',
	'theme_sandbox:navigation' => 'Navegación',
	'theme_sandbox:typography' => 'Tipografías',

	'theme_sandbox:icons:blurb' => 'Use <em>elgg_view_icon($name)</em> ola clase elgg-icon-$name para mostrar iconos.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg tiene tests unitarios y de integración para detectar bugs en las clases del core y sus funciones.',
	'developers:unit_tests:warning' => 'Atención: No ejecutes estos tests en sitios en producción. Pueden corromper la base de datos.',
	'developers:unit_tests:run' => 'Ejecuta',

	// status messages
	'developers:settings:success' => 'Configuraciones almacenadas',
);
