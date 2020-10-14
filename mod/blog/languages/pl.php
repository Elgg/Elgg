<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blogi',
	'collection:object:blog' => 'Blogi',
	'collection:object:blog:all' => 'Wszystkie blogi',
	'collection:object:blog:owner' => 'Blogi użytkownika %s',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Blogi znajomych',
	'add:object:blog' => 'Dodaj wpis na blogu',
	'edit:object:blog' => 'Edytuj wpis na blogu',

	'blog:revisions' => 'Wersje',
	'blog:archives' => 'Archiwalne',

	'groups:tool:blog' => 'Włącz blog grupy',
	'blog:write' => 'Dodaj nowy wpis',

	// Editing
	'blog:excerpt' => 'Fragment',
	'blog:body' => 'Treść',
	'blog:save_status' => 'Ostatnio zapisano:',

	'blog:revision' => 'Wersja',
	'blog:auto_saved_revision' => 'Automatycznie zapisana wersja',

	// messages
	'blog:message:saved' => 'Wpis na blogu został zapisany.',
	'blog:error:cannot_save' => 'Zapis posta się nie powiódł.',
	'blog:error:cannot_auto_save' => 'Nie powiódł się automatyczny zapis wpisu na blogu.',
	'blog:error:cannot_write_to_container' => 'Masz niedostateczne uprawnienia, aby dodawać wpisy na blogu w tej grupie.',
	'blog:messages:warning:draft' => 'Szkic wpisu nie został zapisany!',
	'blog:edit_revision_notice' => '(Stara wersja)',
	'blog:message:deleted_post' => 'Wpis na blogu został usunięty.',
	'blog:error:cannot_delete_post' => 'Nie można usunąć wpisu na blogu.',
	'blog:none' => 'Brak wpisów na blogu',
	'blog:error:missing:title' => 'Proszę podać tytuł wpisu!',
	'blog:error:missing:description' => 'Proszę podać treść wpisu!',
	'blog:error:cannot_edit_post' => 'Ten wpis nie istnieje lub nie masz odpowiednich uprawnień do jego edycji.',
	'blog:error:post_not_found' => 'Nie można znaleźć wskazanego wpisu na blogu.',
	'blog:error:revision_not_found' => 'Nie znaleziono wskazanej wersji.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Nowy wpis na blogu o nazwie %s',
	'blog:notify:subject' => 'Nowy wpis na blogu: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Wyświetl moje najnowsze wpisy na blogu',
	'blog:moreblogs' => 'Więcej wpisów na blogu',
	'blog:numbertodisplay' => 'Ilość wyświetlanych wpisów',
);
