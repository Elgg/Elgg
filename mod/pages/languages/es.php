<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'P&aacute;ginas',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Borrar esta p&aacute;gina",
	'pages:history' => "Historial",
	'pages:view' => "Ver p&aacute;gina",
	'pages:revision' => "Revisi&oacute;n",

	'pages:navigation' => "Navegaci&oacute;n",

	'pages:notify:summary' => 'Nueva página llamada %s',
	'pages:notify:subject' => "Una nueva página: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'M&aacute;s p&aacute;ginas',
	'pages:none' => 'No se han creado p&aacute;ginas a&uacute;n',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
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
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
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
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

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
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
