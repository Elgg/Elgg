<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Bitácoras',
	'collection:object:blog' => 'Bitácoras',
	'collection:object:blog:all' => 'Todas as bitácoras',
	'collection:object:blog:owner' => 'Bitácoras de %s',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Bitácoras dos contactos',
	'add:object:blog' => 'Engadir o artigo',
	'edit:object:blog' => 'Editar o artigo',

	'blog:revisions' => 'Revisións',
	'blog:archives' => 'Arquivos',

	'groups:tool:blog' => 'Activar a bitácora do grupo',
	'blog:write' => 'Escribir un artigo',

	// Editing
	'blog:excerpt' => 'Fragmento',
	'blog:body' => 'Corp',
	'blog:save_status' => 'Gardado:',

	'blog:revision' => 'Revisión',
	'blog:auto_saved_revision' => 'Revisión gardada automaticamente',

	// messages
	'blog:message:saved' => 'Gardouse o artigo',
	'blog:error:cannot_save' => 'Non foi posíbel gardar o artigo.',
	'blog:error:cannot_auto_save' => 'Non pode gardarse automaticamente o artigo.',
	'blog:error:cannot_write_to_container' => 'Non ten acceso dabondo para gardar a bitácora no grupo.',
	'blog:messages:warning:draft' => 'Hai un borrador sen gardar deste artig!',
	'blog:edit_revision_notice' => '(versión vella)',
	'blog:message:deleted_post' => 'Eliminouse o artigo',
	'blog:error:cannot_delete_post' => 'Non foi posíbel eliminar o artigo.',
	'blog:none' => 'Non hai artigos.',
	'blog:error:missing:title' => 'Escriba o nome da bitácora',
	'blog:error:missing:description' => 'Escriba o corpo da bitácora.',
	'blog:error:cannot_edit_post' => 'Pode que o artigo non exista ou que vostede non teña os permisos necesarios para acceder a el.',
	'blog:error:post_not_found' => 'Non foi posíbel atopar o artigo indicado',
	'blog:error:revision_not_found' => 'Non é posíbel atopar esta revisión.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Novo artigo: «%s»',
	'blog:notify:subject' => 'Novo artigo: «%s»',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Mostrar os seus últimos artigos',
	'blog:moreblogs' => 'Máis artigos',
	'blog:numbertodisplay' => 'Número de artigos para mostrar.',
);
