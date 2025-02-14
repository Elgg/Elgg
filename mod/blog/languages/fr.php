<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Article de blog',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'Tous les blogs du site',
	'collection:object:blog:owner' => 'Blogs de %s',
	'collection:object:blog:group' => 'Blogs du groupe',
	'collection:object:blog:friends' => 'Blogs des contacts',
	'add:object:blog' => 'Ajouter un article de blog',
	'edit:object:blog' => 'Modifier l\'article de blog',
	'notification:object:blog:publish' => "Envoyer une notification quand un article est publié",
	'notifications:mute:object:blog' => "à propos de l'article '%s'",
	'menu:blog_archive:header' => "Archives des blogs",
	
	'entity:edit:object:blog:success' => 'Le blog a bien été enregistré',

	'blog:revisions' => 'Révisions',
	'blog:archives' => 'Archives',

	'groups:tool:blog' => 'Activer le blog du groupe',
	'groups:tool:blog:description' => 'Autoriser les membres du groupe à écrire des articles dans le blog de ce groupe.',

	// Editing
	'blog:excerpt' => 'Extrait',
	'blog:body' => 'Corps de l\'article',
	'blog:save_status' => 'Dernier enregistrement :',

	'blog:revision' => 'Révision',
	
	// messages
	'blog:message:saved' => 'Article de blog enregistré.',
	'blog:error:cannot_save' => 'Impossible d\'enregistrer l\'article de blog.',
	'blog:error:cannot_write_to_container' => 'Droits d\'accès insuffisants pour enregistrer l\'article dans ce groupe.',
	'blog:edit_revision_notice' => '(Ancienne version)',
	'blog:none' => 'Aucun article de blog',
	'blog:error:missing:title' => 'Vous devez donner un titre à votre article !',
	'blog:error:missing:description' => 'Vous devez écrire le corps de votre article !',
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
	
	'notification:mentions:object:blog:subject' => '%s vous a mentionné dans un article de blog',

	// widget
	'widgets:blog:name' => 'Articles de blog',
	'widgets:blog:description' => 'Affiche vos derniers articles de blog',
	'blog:moreblogs' => 'Plus d\'articles de blog',
	'blog:numbertodisplay' => 'Nombre d\'articles de blog à afficher',
);
