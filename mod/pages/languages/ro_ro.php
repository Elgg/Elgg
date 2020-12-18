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

	'item:object:page' => 'Pagină',
	'collection:object:page' => 'Pagini',
	'collection:object:page:all' => "Toate paginile site-ului",
	'collection:object:page:owner' => "Paginile utilizatorului %s",
	'collection:object:page:friends' => "Paginile prietenilor",
	'collection:object:page:group' => "Paginile grupului",
	'add:object:page' => "Adaugă o pagină",
	'edit:object:page' => "Editează această pagină",

	'groups:tool:pages' => 'Activează paginile de grup',
	
	'annotation:delete:page:success' => 'Revizuirea paginii a fost ștearsă cu succes',
	'annotation:delete:page:fail' => 'Revizuirea paginii nu a putut fi ștearsă',

	'pages:delete' => "Șterge această pagină",
	'pages:history' => "Istoric",
	'pages:view' => "Vezi pagina",
	'pages:revision' => "Revizuire",

	'pages:navigation' => "Navigare",

	'pages:notify:summary' => 'Pagină nouă numită %s',
	'pages:notify:subject' => "O pagină nouă: %s",
	'pages:notify:body' =>
'%s a adăugat o pagină nouă: %s

%s

Vezi și comentează pe pagină:
%s',

	'pages:more' => 'Mai multe pagini',
	'pages:none' => 'Încă nu s-au creat pagini',

	/**
	* River
	**/

	'river:object:page:create' => '%s a creat o pagină %s',
	'river:object:page:update' => '%s a actualizat o pagină %s',
	'river:object:page:comment' => '%s a comentat pe o pagină numită %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Titlul paginii',
	'pages:description' => 'Textul paginii',
	'pages:tags' => 'Etichete',
	'pages:parent_guid' => 'Pagina principală',
	'pages:access_id' => 'Acces citire',
	'pages:write_access_id' => 'Acces scriere',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Nu poți edita această pagină',
	'pages:saved' => 'Pagină salvată',
	'pages:notsaved' => 'Pagina nu a putut fi salvată',
	'pages:error:no_title' => 'Trebuie să specifici un titlu acestei pagini.',
	'entity:delete:object:page:success' => 'Pagina a fost ștearsă cu succes.',
	'pages:revision:delete:success' => 'Revizuirea paginii a fost ștearsă cu succes.',
	'pages:revision:delete:failure' => 'Revizuirea paginii nu a putut fi ștearsă.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revizuire creată %s de %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Numărul de pagini de afișat',
	'widgets:pages:name' => 'Pagini',
	'widgets:pages:description' => "Aceasta este o listă cu paginile tale.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Vezi pagina",
	'pages:label:edit' => "Editează pagina",
	'pages:label:history' => "Istoricul paginii",

	'pages:newchild' => "Creează o sub-pagină",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrează page_top la entitățile paginii",
	'pages:upgrade:2017110700:description' => "Schimbă subtipul al tuturor paginilor de top în 'pagină' și setează datele meta pentru a asigura o listare corectă.",
	
	'pages:upgrade:2017110701:title' => "Migrează entitățile de flux page_top",
	'pages:upgrade:2017110701:description' => "Schimbă subtipul tuturor elementelor de flux pentru paginile de top în 'pagină'.",
);
