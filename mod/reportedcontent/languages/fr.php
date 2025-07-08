<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:reported_content' => 'Elément signalé',
	'collection:object:reported_content' => 'Éléments signalés',
	
	'admin:administer_utilities:reportedcontent' => 'Contenu signalé',
	'admin:administer_utilities:reportedcontent:archive' => 'Contenu signalé - archive',
	
	'reportedcontent' => 'Contenu signalé',
	'reportedcontent:this' => 'Signaler ceci',
	'reportedcontent:this:tooltip' => 'Signaler cette page à un administrateur',
	'reportedcontent:none' => 'Aucun signalement', // @todo remove in Elgg 7.0
	'reportedcontent:report' => 'Signaler à l\'administrateur',
	'reportedcontent:archive' => 'Archiver le signalement',
	'reportedcontent:archived' => 'Le signalement a bien été archivé',
	'reportedcontent:description' => 'Pourquoi souhaitez-vous signaler ceci ?',
	'reportedcontent:address' => 'Adresse de l\'élément',
	'reportedcontent:success' => 'Votre signalement a bien été envoyé à l\'administrateur du site',
	'reportedcontent:numbertodisplay' => 'Nombre de signalements à afficher',
	'reportedcontent:user' => 'Signaler cet utilisateur',
	'reportedcontent:new' => 'Nouveaux signalements',
	'reportedcontent:archived_reports' => 'Archivé',
	'reportedcontent:related_reports' => 'Signalements associés',
	'reportedcontent:comments:message' => 'Les commentaires ne sont disponibles que pour l\'administrateur. La personne qui signale le contenu ne sera pas informée des nouveaux commentaires.',
	
	'reportedcontent:failed' => 'Désolé, la tentative de signaler ce contenu a échoué.',
	'reportedcontent:notarchived' => 'Il a été impossible d\'archiver ce signalement',
	
	'widgets:reportedcontent:name' => 'Contenu signalé',
	'widgets:reportedcontent:description' => 'Afficher le contenu signalé',
	
	'reportedcontent:usersettings:notifications:reportedcontent:description' => 'Recevoir une notification quand un contenu est signalé',
	
	'reportedcontent:notifications:create:admin:subject' => 'Nouveau contenu signalé : %s',
	'reportedcontent:notifications:create:admin:summary' => 'Nouveau contenu signalé : %s',
	'reportedcontent:notifications:create:admin:body' => '%s a signalé ceci :

%s

Vérifiez la page concernée :
%s

Pour consulter tous les signalements, visitez :
%s',
);
