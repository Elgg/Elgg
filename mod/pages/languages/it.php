<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Pagine",
	'pages:owner' => "Pagine di %s",
	'pages:friends' => "Pagine degli amici",
	'pages:all' => "Tutte le pagine del sito",
	'pages:add' => "Aggiungi una pagina",

	'pages:group' => "Pagine del gruppo",
	'groups:enablepages' => 'Abilita pagine del gruppo',

	'pages:new' => "Una nuova pagina",
	'pages:edit' => "Modifica questa pagina",
	'pages:delete' => "Elimina questa pagina",
	'pages:history' => "Cronologia",
	'pages:view' => "Mostra pagina",
	'pages:revision' => "Revisione",
	'pages:current_revision' => "Revisione attuale",
	'pages:revert' => "Ripristina",

	'pages:navigation' => "Navigazione",

	'pages:notify:summary' => 'Nuova pagina intitolata %s',
	'pages:notify:subject' => "Una nuova pagina: %s",
	'pages:notify:body' =>
'%s ha aggiunto una nuova pagina: %s

%s

Visualizza e commenta questa pagina:
%s',
	'item:object:page_top' => 'Pagine di primo livello',
	'item:object:page' => 'Pagine',
	'pages:nogroup' => 'Questo gruppo non ha ancora alcuna pagina',
	'pages:more' => 'Più pagine',
	'pages:none' => 'Ancora nessuna pagina creata',

	/**
	* River
	**/

	'river:create:object:page' => '%s ha creato la pagina %s',
	'river:create:object:page_top' => '%s ha creato la pagina %s',
	'river:update:object:page' => '%s ha aggiornato la pagina %s',
	'river:update:object:page_top' => '%s ha aggiornato la pagina %s',
	'river:comment:object:page' => '%s ha commentato la pagina %s',
	'river:comment:object:page_top' => '%s ha commentato la pagina %s',

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
	'pages:noaccess' => 'Non hai accesso a questa pagina',
	'pages:cantedit' => 'Non puoi modificare questa pagina',
	'pages:saved' => 'Pagina salvata',
	'pages:notsaved' => 'La pagina non può essare salvata',
	'pages:error:no_title' => 'Devi specificare un titolo per questa pagina.',
	'pages:delete:success' => 'La pagina è stata eliminata con successo.',
	'pages:delete:failure' => 'Impossibile rimuovere la pagina.',
	'pages:revision:delete:success' => 'La revisione della pagina è stata eliminata.',
	'pages:revision:delete:failure' => 'La revisione della pagina non può essere eliminata.',
	'pages:revision:not_found' => 'Impossibile trovare questa revisione.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Ultimo aggiornamento %s di %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisione creata %s da %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Numero di pagine da visualizzare',
	'pages:widget:description' => "Questo è un elenco delle tue pagine.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Visualizza pagina",
	'pages:label:edit' => "Modifica pagina",
	'pages:label:history' => "Cronologia della pagina",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Questa pagina",
	'pages:sidebar:children' => "Sotto-pagine",
	'pages:sidebar:parent' => "Su di un livello",

	'pages:newchild' => "Crea una sotto-pagina",
	'pages:backtoparent' => "Torna a '%s'",
);
