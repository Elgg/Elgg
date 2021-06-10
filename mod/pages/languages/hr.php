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

	'item:object:page' => 'Stranica',
	'collection:object:page' => 'Stranice',
	'collection:object:page:all' => "Sve stranice",
	'collection:object:page:owner' => "%s stranice",
	'collection:object:page:friends' => "Stranica prijatelja",
	'collection:object:page:group' => "Grupna stranica",
	'add:object:page' => "Dodaj stranicu",
	'edit:object:page' => "Uredi ovu stranicu",

	'groups:tool:pages' => 'Omogući stranice grupe',

	'pages:delete' => "Izbriši ovu stranicu",
	'pages:history' => "Povijest",
	'pages:view' => "Pregledaj stranicu",
	'pages:revision' => "Revizija",

	'pages:navigation' => "Navigacija",

	'pages:notify:summary' => 'Nova stranica pod nazivom %s',
	'pages:notify:subject' => "Nova stranica: %s",
	'pages:notify:body' =>
'%s je dodao novu stranicu: %s

%s

Pregledaj i komentiraj na stanicu:
%s
',

	'pages:more' => 'Više stranica',
	'pages:none' => 'Još nije izrađene niti jedna stranica',

	/**
	* River
	**/

	'river:object:page:create' => '%s je izradio stranicu %s',
	'river:object:page:update' => '%s je uredio stranicu %s',
	'river:object:page:comment' => '%s je komentirao stranicu pod nazivom %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Naziv stranice',
	'pages:description' => 'Sadržaj stranice',
	'pages:tags' => 'Oznake',
	'pages:access_id' => 'Ovlasti čitanja',
	'pages:write_access_id' => 'Ovlasti pisanja',

	/**
	 * Status and error messages
	 */
	'pages:saved' => 'Stranica je sačuvana',
	'pages:notsaved' => 'Stranicu nije moguće sačuvati',
	'pages:error:no_title' => 'Potrebno je odrediti naziv stranice. ',
	'entity:delete:object:page:success' => 'Stranica je uspješno izbrisana.',
	'pages:revision:delete:success' => 'Revizija stranice je uspješno izbrisana. ',
	'pages:revision:delete:failure' => 'Nije moguće izbrisati reviziju stranice. ',

	/**
	 * History
	 */

	/**
	 * Widget
	 **/

	'pages:num' => 'Broj stranica za prikaz',
	'widgets:pages:name' => 'Stranice',
	'widgets:pages:description' => "Ovo je popis Vaših stranica. ",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Pregled stranice",
	'pages:label:edit' => "Uredi stranicu",
	'pages:label:history' => "Povijest stranice",

	'pages:newchild' => "Izradi podstranicu",
);
