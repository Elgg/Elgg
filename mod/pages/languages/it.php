<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Pagine',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => "Elimina questa pagina",
	'pages:history' => "Cronologia",
	'pages:view' => "Mostra pagina",
	'pages:revision' => "Revisione",

	'pages:navigation' => "Navigazione",

	'pages:notify:summary' => 'Nuova pagina intitolata %s',
	'pages:notify:subject' => "Una nuova pagina: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Più pagine',
	'pages:none' => 'Ancora nessuna pagina creata',

	/**
	* River
	**/

	'river:object:page:create' => '%s ha creato la pagina %s',
	'river:object:page:update' => '%s ha aggiornato la pagina %s',
	'river:object:page:comment' => '%s ha commentato la pagina dal titolo %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Titolo pagina',
	'pages:description' => 'Contenuto della pagina',
	'pages:tags' => 'Tag',
	'pages:parent_guid' => 'Pagina madre',
	'pages:access_id' => 'Accesso in lettura',
	'pages:write_access_id' => 'Accesso in scrittura',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Non puoi modificare questa pagina',
	'pages:saved' => 'Pagina salvata',
	'pages:notsaved' => 'La pagina non può essare salvata',
	'pages:error:no_title' => 'Devi specificare un titolo per questa pagina.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => 'La revisione della pagina è stata eliminata.',
	'pages:revision:delete:failure' => 'La revisione della pagina non può essere eliminata.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisione creata %s da %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Numero di pagine da visualizzare',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Visualizza pagina",
	'pages:label:edit' => "Modifica pagina",
	'pages:label:history' => "Cronologia della pagina",

	'pages:newchild' => "Crea una sotto-pagina",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migra page_top a page entities",
	'pages:upgrade:2017110700:description' => "Cambia il sottotipo di tutte le 'pagine top' in 'pagina' e imposta i metadati per assicurare un'elencazione corretta.",
	
	'pages:upgrade:2017110701:title' => "Migra gli inserimenti sul river delle page_top",
	'pages:upgrade:2017110701:description' => "Cambia il sottotipo di tutti gli elementi del river per le 'Pagine top' in 'pagina'",
);
