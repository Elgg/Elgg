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
	'notification:object:page:create' => "Trimite o notificare atunci când o pagină este creată",
	'notifications:mute:object:page' => "despre pagina '%s'",

	'groups:tool:pages' => 'Activează paginile de grup',
	
	'annotation:delete:page:success' => 'Revizuirea paginii a fost ștearsă cu succes',
	'annotation:delete:page:fail' => 'Revizuirea paginii nu a putut fi ștearsă',

	'pages:history' => "Istoric",
	'pages:revision' => "Revizuire",

	'pages:navigation' => "Navigare",

	'pages:notify:summary' => 'Pagină nouă numită %s',
	'pages:notify:subject' => "O pagină nouă: %s",
	'pages:notify:body' => '%s a adăugat o pagină nouă: %s

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

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Nu poți edita această pagină',
	'pages:saved' => 'Pagină salvată',
	'pages:notsaved' => 'Pagina nu a putut fi salvată',
	'pages:error:no_title' => 'Trebuie să specifici un titlu acestei pagini.',
	'entity:delete:object:page:success' => 'Pagina a fost ștearsă cu succes.',

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

	'pages:newchild' => "Creează o sub-pagină",
);
