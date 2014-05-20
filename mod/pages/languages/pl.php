<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Strony",
	'pages:owner' => "Strony użytkownika %s",
	'pages:friends' => "Strony znajomych",
	'pages:all' => "Wszystkie strony",
	'pages:add' => "Dodaj stronę",

	'pages:group' => "Strony grupy",
	'groups:enablepages' => 'Włącz strony grupy',

	'pages:new' => "Nowa strona",
	'pages:edit' => "Edytuj tą stronę",
	'pages:delete' => "Usuń tą stronę",
	'pages:history' => "Historia strony",
	'pages:view' => "Wyświetl stronę",
	'pages:revision' => "Wersja",
	'pages:current_revision' => "Obecna wersja",
	'pages:revert' => "Przywróć",

	'pages:navigation' => "Nawigacja strony",

	'pages:notify:summary' => 'Nowa strona o nazwie %s',
	'pages:notify:subject' => "Nowa strona: %s",
	'pages:notify:body' =>
'%s dodał nową stronę: %s

%s

Wyświetl i skomentuj nową stronę:
%s
',
	'item:object:page_top' => 'Strony najwyższego poziomu',
	'item:object:page' => 'Strony',
	'pages:nogroup' => 'Ta grupa nie ma jeszcze żadnych stron.',
	'pages:more' => 'Więcej stron',
	'pages:none' => 'Nie utworzono jeszcze stron',

	/**
	* River
	**/

	'river:create:object:page' => '%s utworzył stronę %s',
	'river:create:object:page_top' => '%s utworzył stronę %s',
	'river:update:object:page' => '%s zaktualizował stronę %s',
	'river:update:object:page_top' => '%s zaktualizował stronę %s',
	'river:comment:object:page' => '%s skomentował stronę %s',
	'river:comment:object:page_top' => '%s skomentował stronę %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Tytuł strony',
	'pages:description' => 'Treść strony',
	'pages:tags' => 'Tagi',
	'pages:parent_guid' => 'Strona nadrzędna',
	'pages:access_id' => 'Uprawnienia odczytu',
	'pages:write_access_id' => 'Uprawnienia zapisu',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'Brak dostępu do strony',
	'pages:cantedit' => 'Nie można edytować tej strony',
	'pages:saved' => 'Strona zapisana',
	'pages:notsaved' => 'Strona nie mogła zostać zapisana',
	'pages:error:no_title' => 'Musisz podać tytuł dla tej strony.',
	'pages:delete:success' => 'Twoja strona została pomyślnie usunięta.',
	'pages:delete:failure' => 'Strona nie może zostać usunięta.',
	'pages:revision:delete:success' => 'Pomyślnie usunięto wersję strony.',
	'pages:revision:delete:failure' => 'Usunięcie wersji strony nie powiodło się.',
	'pages:revision:not_found' => 'Nie znaleziono wskazanej wersji.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Ostatnia aktualizacja %s przez %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Wersja utworzona %s przez %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Ilość stron do wyświetlenia',
	'pages:widget:description' => "Oto lista twoich stron",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Zobacz stronę",
	'pages:label:edit' => "Edytuj stronę",
	'pages:label:history' => "Historia strony",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Ta strona",
	'pages:sidebar:children' => "Podstrony",
	'pages:sidebar:parent' => "Strona nadrzędna",

	'pages:newchild' => "Utwórz podstronę",
	'pages:backtoparent' => "Wróć do '%s'",
);
