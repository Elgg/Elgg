<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Article de blog',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'Tous les articles de blog du site',
	'collection:object:blog:owner' => 'Articles de blog de %s',
	'collection:object:blog:group' => 'Articles de blog du groupe',
	'collection:object:blog:friends' => 'Articles de blog des contacts',
	'add:object:blog' => 'Ajouter un article de blog',
	'edit:object:blog' => 'Modifier l\'article de blog',
	'notification:object:blog:publish' => "Envoyer une notification quand un article est créé",
	'notifications:mute:object:blog' => "à propos de l'article '%s'",

	'blog:revisions' => 'Révisions',
	'blog:archives' => 'Archives',

	'groups:tool:blog' => 'Activer le blog du groupe',

	// Editing
	'blog:excerpt' => 'Extrait',
	'blog:body' => 'Corps de l\'article',
	'blog:save_status' => 'Dernier enregistrement :',

	'blog:revision' => 'Révision',
	'blog:auto_saved_revision' => 'Révision automatiquement enregistrée',

	// messages
	'blog:message:saved' => 'Article de blog enregistré.',
	'blog:error:cannot_save' => 'Impossible d\'enregistrer l\'article de blog.',
	'blog:error:cannot_auto_save' => 'Impossible de sauvegarder automatiquement l\'article de blog. ',
	'blog:error:cannot_write_to_container' => 'Droits d\'accès insuffisants pour enregistrer l\'article dans ce groupe.',
	'blog:messages:warning:draft' => 'Il y a un brouillon non enregistré de cet article !',
	'blog:edit_revision_notice' => '(Ancienne version)',
	'blog:none' => 'Aucun article de blog',
	'blog:error:missing:title' => 'Vous devez donner un titre à votre article !',
	'blog:error:missing:description' => 'Le corps de votre article est vide !',
	'blog:error:post_not_found' => 'Impossible de trouver l\'article de blog spécifié.',
	'blog:error:revision_not_found' => 'Impossible de trouver cette révision.',

	// river
	'river:object:blog:create' => '%s a publié un article de blog %s',
	'river:object:blog:comment' => '%s a commenté l\'article de blog %s',

	// notifications
	'blog:notify:summary' => 'Nouvel article de blog intitulé %s',
	'blog:notify:subject' => 'Nouvel article de blog: %s',
	'blog:notify:body' => '%s a publié un nouvel article de blog : %s

%s

Afficher et commenter l\'article :
%s',

	// widget
	'widgets:blog:name' => 'Articles de blog',
	'widgets:blog:description' => 'Affiche vos derniers articles de blog',
	'blog:moreblogs' => 'Plus d\'articles de blog',
	'blog:numbertodisplay' => 'Nombre d\'articles de blog à afficher',
);
