<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Páxinas',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Eliminar a páxina",
	'pages:history' => "Historia",
	'pages:view' => "Ver a páxina",
	'pages:revision' => "Revisión",

	'pages:navigation' => "Navegación",

	'pages:notify:summary' => 'Nova páxina: «%s»',
	'pages:notify:subject' => "Nova páxina: «%s»",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Máis páxinas',
	'pages:none' => 'Aínda non hai ningunha páxina creada.',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Títul',
	'pages:description' => 'Text',
	'pages:tags' => 'Etiquetas',
	'pages:parent_guid' => 'Páxina superior',
	'pages:access_id' => 'Lectura',
	'pages:write_access_id' => 'Escritura',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Non pode editar a páxina.',
	'pages:saved' => 'Gardouse a páxina.',
	'pages:notsaved' => 'Non foi posíbel gardar a páxina.',
	'pages:error:no_title' => 'Debe poñerlle título á páxina.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => 'Eliminouse a revisión da páxina.',
	'pages:revision:delete:failure' => 'Non foi posíbel eliminar a revisión da páxina',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisión creada %s por %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Número de páxinas para mostrar',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Ver a páxina",
	'pages:label:edit' => "Editar a páxina",
	'pages:label:history' => "Historial da páxina",

	'pages:newchild' => "Crear unha subpáxina",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
