<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Sujets de discussion",
	
	'add:object:discussion' => 'Ajouter un sujet de discussion',
	'edit:object:discussion' => 'Modifier le sujet',
	'collection:object:discussion' => 'Sujets de discussion',
	'collection:object:discussion:group' => 'Discussions du groupe',
	'collection:object:discussion:my_groups' => 'Discussions dans mes groupes',
	
	'discussion:settings:enable_global_discussions' => 'Activer les discussions globales',
	'discussion:settings:enable_global_discussions:help' => 'Permettre de créer des discussions hors des groupes',

	'discussion:latest' => 'Dernières discussions',
	'discussion:none' => 'Pas de discussion',
	'discussion:updated' => "Derniere réponse de %s %s",

	'discussion:topic:created' => 'Le sujet de discussion a été créé.',
	'discussion:topic:updated' => 'Le sujet de discussion a été mis à jour.',
	'entity:delete:object:discussion:success' => 'Le sujet de discussion a été supprimé.',

	'discussion:topic:notfound' => 'Le sujet de discussion est introuvable',
	'discussion:error:notsaved' => 'Impossible d\'enregistrer ce sujet',
	'discussion:error:missing' => 'Les deux champs "titre" et "message" sont obligatoires',
	'discussion:error:permissions' => 'Vous n\'avez pas les droits pour effectuer cette action',
	'discussion:error:no_groups' => "Vous n'êtes membre d'aucun groupe.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s a ajouté un nouveau sujet de discussion %s',
	'river:object:discussion:comment' => '%s a commenté le sujet de discussion %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nouveau sujet de discussion intitulé %s',
	'discussion:topic:notify:subject' => 'Nouveau sujet de discussion : %s',
	'discussion:topic:notify:body' =>
'%s a ajouté un nouveau sujet de discussion "%s":

%s

Voir le sujet de discussion et y répondre :
%s
',

	'discussion:comment:notify:summary' => 'Nouvelle réponse dans la discussion : %s',
	'discussion:comment:notify:subject' => 'Nouvelle réponse dans la discussion : %s',
	'discussion:comment:notify:body' =>
'%s a répondu au sujet de discussion "%s":

%s

Voir la discussion et y répondre :
%s
',

	'groups:tool:forum' => 'Activer les discussions du groupe',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Statut du sujet',
	'discussion:topic:closed:title' => 'Cette discussion est fermée.',
	'discussion:topic:closed:desc' => 'Cette discussion a été fermée et n\'accepte plus de nouveaux commentaires.',

	'discussion:topic:description' => 'Message du sujet',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Migrer les réponses aux discussions vers les commentaires",
	'discussions:upgrade:2017112800:description' => "Les réponses aux sujets de discussion avaient leur propre sous-type, ceci a été unifié dans les commentaires.",
	'discussions:upgrade:2017112801:title' => "Migrer l'activité de la rivière relative aux réponses aux sujets de discussions",
	'discussions:upgrade:2017112801:description' => "Les réponses aux sujets de discussion avaient leur propre sous-type, ceci a été unifié dans les commentaires.",
);
