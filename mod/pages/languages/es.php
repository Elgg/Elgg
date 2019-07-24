<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'P&aacute;ginas',
	'collection:object:page' => 'P&aacute;ginas',
	'collection:object:page:all' => "Todas las p&aacute;ginas",
	'collection:object:page:owner' => "P&aacute;ginas de %s",
	'collection:object:page:friends' => "P&aacute;ginas de Amigos",
	'collection:object:page:group' => "P&aacute;ginas de Grupo",
	'add:object:page' => "Añadir una página",
	'edit:object:page' => "Edita esta página",

	'groups:tool:pages' => 'Habilitar páginas de grupo',

	'pages:delete' => "Borrar esta p&aacute;gina",
	'pages:history' => "Historial",
	'pages:view' => "Ver p&aacute;gina",
	'pages:revision' => "Revisi&oacute;n",

	'pages:navigation' => "Navegaci&oacute;n",

	'pages:notify:summary' => 'Nueva página llamada %s',
	'pages:notify:subject' => "Una nueva página: %s",
	'pages:notify:body' =>
'%s ha añadido la nueva página: %s

%s

Ver y comentar sobre esta página:
%s',

	'pages:more' => 'M&aacute;s p&aacute;ginas',
	'pages:none' => 'No se han creado p&aacute;ginas a&uacute;n',

	/**
	* River
	**/

	'river:object:page:create' => '%s ha creado la página %s',
	'river:object:page:update' => '%s ha actualizado la página %s',
	'river:object:page:comment' => '%s ha comentado sobre una página titulada %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'T&iacute;tulo de la p&aacute;gina',
	'pages:description' => 'Contenido',
	'pages:tags' => 'Etiquetas',
	'pages:parent_guid' => 'Página antecesora',
	'pages:access_id' => 'S&oacute;lo lectura',
	'pages:write_access_id' => 'Acceso de escritura',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'No puedes editar esta p&aacute;gina',
	'pages:saved' => 'P&aacute;gina guardada',
	'pages:notsaved' => 'La p&aacute;gina no pudo ser guardada',
	'pages:error:no_title' => 'Debes especificar un t&iacute;tulo para esta p&aacute;gina.',
	'entity:delete:object:page:success' => 'La página fue eliminada con éxito.',
	'pages:revision:delete:success' => 'La revisión de la página se eliminó correctamente.',
	'pages:revision:delete:failure' => 'No fue posible eliminar la revisión de la página.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisi&oacute;n de %s creada por %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'N&uacute;mero de p&aacute;ginas a mostrar',
	'widgets:pages:name' => 'Páginas',
	'widgets:pages:description' => "Esta es una lista de tus páginas.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Ver p&aacute;gina",
	'pages:label:edit' => "Editar p&aacute;gina",
	'pages:label:history' => "Historial de la p&aacute;gina",

	'pages:newchild' => "Crear una subp&aacute;gina",

	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migra page_top a las entidades de la página",
	'pages:upgrade:2017110700:description' => "Cambia el subtipo de todas las páginas principales a 'página' y configura los metadatos para garantizar una lista correcta.",

	'pages:upgrade:2017110701:title' => "Migrar las entradas del río page_top",
	'pages:upgrade:2017110701:description' => "Cambia el subtipo de todos los elementos de río para las páginas superiores a 'página'.",
);
