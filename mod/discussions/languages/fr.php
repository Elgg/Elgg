<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Sujets de discussion",
	
	'add:object:discussion' => 'Ajouter un sujet de discussion',
	'edit:object:discussion' => 'Modifier le sujet de discussion',
	'collection:object:discussion' => 'Sujets de discussion',
	'collection:object:discussion:group' => 'Discussions du groupe',
	'collection:object:discussion:my_groups' => 'Discussions dans mes groupes',
	'notification:object:discussion:create' => "Envoyer une notification quand une discussion est créée",
	'notifications:mute:object:discussion' => "à propos de la discussion '%s'",
	
	'discussion:settings:enable_global_discussions' => 'Activer les discussions globales',
	'discussion:settings:enable_global_discussions:help' => 'Permet de créer des discussions hors des groupes',

	'discussion:latest' => 'Discussions récentes',
	'discussion:none' => 'Pas de discussion',
	'discussion:updated' => "Dernière réponse de %s %s",

	'discussion:topic:created' => 'Le sujet de discussion a été créé.',
	'discussion:topic:updated' => 'Le sujet de discussion a été mis à jour.',
	'entity:delete:object:discussion:success' => 'Le sujet de discussion a été supprimé.',

	'discussion:topic:notfound' => 'Le sujet de discussion n\'a pas été trouvé',
	'discussion:error:notsaved' => 'Impossible d\'enregistrer ce sujet de discussion',
	'discussion:error:missing' => 'Les deux champs "titre" et "message" sont obligatoires',
	'discussion:error:permissions' => 'Vous n\'avez pas les droits pour effectuer cette action',
	'discussion:error:no_groups' => "Vous n'êtes membre d'aucun groupe.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s a ajouté un nouveau sujet de discussion %s',
	'river:object:discussion:comment' => '%s a répondu dans le sujet de discussion %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nouveau sujet de discussion intitulé %s',
	'discussion:topic:notify:subject' => 'Nouveau sujet de discussion : %s',
	'discussion:topic:notify:body' => '%s a ajouté un nouveau sujet de discussion "%s":

%s

Voir le sujet de discussion et y répondre :
%s',

	'discussion:comment:notify:summary' => 'Nouvelle réponse dans la discussion : %s',
	'discussion:comment:notify:subject' => 'Nouvelle réponse dans la discussion : %s',
	'discussion:comment:notify:body' => '%s a répondu dans le sujet de discussion "%s" :

%s

Voir la discussion et y répondre :
%s',

	'groups:tool:forum' => 'Activer les discussions du groupe',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Statut de la discussion',
	'discussion:topic:closed:title' => 'Cette discussion est fermée.',
	'discussion:topic:closed:desc' => 'Cette discussion a été fermée et n\'accepte plus de nouveaux commentaires.',

	'discussion:topic:description' => 'Message du sujet de discussion',
	'discussion:topic:toggle_status:open' => 'Le sujet de discussion a bien été rouvert.',
	'discussion:topic:toggle_status:open:confirm' => 'Confirmez-vous vouloir rouvrir cette discussion ?',
	'discussion:topic:toggle_status:closed' => 'Le sujet de discussion a bien été fermé.',
	'discussion:topic:toggle_status:closed:confirm' => 'Confirmez-vous vouloir fermer cette discussion ?',
);
