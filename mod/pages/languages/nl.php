<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Pagina\'s',
	'collection:object:page' => 'Pagina\'s',
	'collection:object:page:all' => "Alle site pagina's",
	'collection:object:page:owner' => "%s's pagina's",
	'collection:object:page:friends' => "Pagina's van vrienden",
	'collection:object:page:group' => "Groepspagina's",
	'add:object:page' => "Nieuwe pagina",
	'edit:object:page' => "Bewerk deze pagina",

	'groups:tool:pages' => 'Schakel groepspagina\'s in',

	'pages:delete' => "Verwijder deze pagina",
	'pages:history' => "Paginageschiedenis",
	'pages:view' => "Bekijk pagina",
	'pages:revision' => "Revisie",

	'pages:navigation' => "Paginanavigatie",

	'pages:notify:summary' => 'Nieuwe pagina met de titel %s',
	'pages:notify:subject' => "Een nieuwe pagina: %s",
	'pages:notify:body' =>
'%s schreef een nieuwe pagina: %s

%s

Om de pagina te bekijken en te reageren, klik hier:
%s',

	'pages:more' => 'Meer pagina\'s',
	'pages:none' => 'Nog geen pagina\'s aangemaakt',

	/**
	* River
	**/

	'river:object:page:create' => '%s heeft de pagina %s toegevoegd',
	'river:object:page:update' => '%s heeft de pagina %s bewerkt',
	'river:object:page:comment' => '%s reageerde op de pagina %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Paginatitel',
	'pages:description' => 'Jouw tekst',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Hoofdpagina',
	'pages:access_id' => 'Toegang',
	'pages:write_access_id' => 'Schrijfrechten',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Je kunt deze pagina niet bewerken',
	'pages:saved' => 'Pagina opgeslagen',
	'pages:notsaved' => 'Pagina kon niet worden opgeslagen',
	'pages:error:no_title' => 'Je moet een titel opgeven voor deze pagina.',
	'entity:delete:object:page:success' => 'Pagina succesvol verwijderd',
	'pages:revision:delete:success' => 'De paginarevisie is succesvol verwijderd.',
	'pages:revision:delete:failure' => 'De paginarevisie kon niet worden verwijderd.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revisie van de pagina \'%s\' door %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Aantal pagina\'s om weer te geven',
	'widgets:pages:name' => 'Pagina\'s',
	'widgets:pages:description' => "Toon een lijst van je pagina's",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Bekijk pagina",
	'pages:label:edit' => "Bewerk pagina",
	'pages:label:history' => "Paginageschiedenis",

	'pages:newchild' => "Maak een subpagina",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migreer page_top naar page entities",
	'pages:upgrade:2017110700:description' => "Wijzig het subtype van alle hoofdpagina's naar 'page' en zet de correcte metadata voor de correcte lijstweergave.",
	
	'pages:upgrade:2017110701:title' => "Migreer page_top activiteiten op de activiteitenstroom",
	'pages:upgrade:2017110701:description' => "Wijzig het subtype van alle activiteiten mbt hoofdpagina's naar 'page'",
);
