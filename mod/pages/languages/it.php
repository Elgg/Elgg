<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Pagine',
	'collection:object:page' => 'Pagine',
	'collection:object:page:all' => "Tutte le pagine",
	'collection:object:page:owner' => "Pagine di %s",
	'collection:object:page:friends' => "Pagine degli amici",
	'collection:object:page:group' => "Pagine del gruppo",
	'add:object:page' => "Aggiungi pagina",
	'edit:object:page' => "Modifica questa pagina",

	'groups:tool:pages' => 'Abilita pagine del gruppo',
	
	'annotation:delete:page:success' => 'The page revision was successfully deleted',
	'annotation:delete:page:fail' => 'The page revision could not be deleted',

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
	'entity:delete:object:page:success' => 'La pagina è stata eliminata con successo.',
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
	'widgets:pages:name' => 'Pagine',
	'widgets:pages:description' => "Questo è un elenco delle tue pagine.",

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
