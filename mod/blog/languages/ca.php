<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Entrades del bloc',
	'collection:object:blog' => 'Blocs',
	'collection:object:blog:all' => 'Tots els blocs',
	'collection:object:blog:owner' => 'Bloc de %s',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Blocs d\'amics',
	'add:object:blog' => 'Afegir una entrada al bloc',
	'edit:object:blog' => 'Editar entrada del bloc',

	'blog:revisions' => 'Revisions',
	'blog:archives' => 'Arxius',

	'groups:tool:blog' => 'Activar el bloc del grup',
	'blog:write' => 'Afegir una entrada al bloc',

	// Editing
	'blog:excerpt' => 'Extracte',
	'blog:body' => 'Cos',
	'blog:save_status' => 'Desat: ',

	'blog:revision' => 'Revisió',
	'blog:auto_saved_revision' => 'Revisió desada automàticament',

	// messages
	'blog:message:saved' => 'Entrada del bloc desada.',
	'blog:error:cannot_save' => 'No s\'ha pogut desar l\'entrada del bloc.',
	'blog:error:cannot_auto_save' => 'No es pot desar el post de bloc automàticament.',
	'blog:error:cannot_write_to_container' => 'No tens els permisos necessaris per afegir el bloc al grup.',
	'blog:messages:warning:draft' => 'Hi ha un esborrany sense desar per aquesta entrada!',
	'blog:edit_revision_notice' => '(Versió anterior)',
	'blog:message:deleted_post' => 'Entrada del bloc eliminada.',
	'blog:error:cannot_delete_post' => 'No s\'ha pogut eliminar l\'entrada del bloc.',
	'blog:none' => 'No hi ha cap entrada al bloc',
	'blog:error:missing:title' => 'siusplau, entra un títol per al bloc!',
	'blog:error:missing:description' => 'Siusplau, afegeix el cos del teu bloc!',
	'blog:error:cannot_edit_post' => 'La publicació no existeix o no tens els permisos necessaris per modificar-la.',
	'blog:error:post_not_found' => 'No ha estat possible trobar l\'entrade de bloc especificada.',
	'blog:error:revision_not_found' => 'No s\'ha pogut trobar la revisió.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Nou missatge de bloc anomenat %s',
	'blog:notify:subject' => 'Nou post de bloc: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Aquest giny mostra les darreres entrades al bloc.',
	'blog:moreblogs' => 'Més entrades',
	'blog:numbertodisplay' => 'Nombre d\'entrades del bloc a mostrar',
);
