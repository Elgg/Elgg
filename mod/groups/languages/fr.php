<?php
	/**
	 * translation by RONNEL Jérémy
	 * jeremy.ronnel@elbee.fr
	 */

	$french = array(
	
		/**
		 * Menu items and titles
		 */
			
			'groups' => "Groupes",
			'groups:owned' => "Vos groupes",
			'groups:yours' => "Vos inscriptions",
			'groups:user' => "Les groupes de %s",
			'groups:all' => "Tous les groupes",
			'groups:new' => "Créer un groupe",
			'groups:edit' => "Editer le groupe",
	
			'groups:icon' => 'Icône du groupe (laissez vide par défaut)',
			'groups:name' => 'Nom du groupe',
			'groups:username' => "Diminutif du groupe (afficher dans l'URL, uniquement des caractères alphanumériques)",
			'groups:description' => 'Description',
			'groups:briefdescription' => 'Brève description',
			'groups:interests' => 'Intérêts',
			'groups:website' => 'Site internet',
			'groups:members' => 'Membre du groupe',
			'groups:membership' => "Inscription",
			'groups:access' => "Permissions d'accès",
			'groups:owner' => "Propriétaire",
	        'groups:widget:num_display' => 'Nombre de groupes à afficher',
	        'groups:widget:membership' => 'Inscription au groupe',
	        'groups:widgets:description' => 'Afficher vos affiliation aux groupes dans votre profil',
			'groups:noaccess' => "Vous n'avez pas accès au groupe",
			'groups:cantedit' => "Vous ne pouvez pas éditer ce groupe",
			'groups:saved' => 'Le groupe a été sauvegardé',
	
			'groups:joinrequest' => 'Devenir membre',
			'groups:join' => 'Rejoindre ce groupe',
			'groups:leave' => 'Quitter ce groupe',
			'groups:invite' => 'Inviter des amis',
			'groups:inviteto' => "Inviter des amis à '%s'",
			'groups:nofriends' => "Tous vos amis ont été invités à rejoindre ce groupe.",
	
			'groups:group' => "Groupe",
			
			'item:object:groupforumtopic' => "Sujet du forum",
	
			/*
			  Group forum strings
			*/
			
			'groups:forum' => 'Forum du groupe',
			'groups:addtopic' => 'Ajouter un sujet',
			'groups:forumlatest' => 'Derniers forums',
			'groups:latestdiscussion' => 'Dernières discussions',
			'groupspost:success' => 'Votre commentaire a bien été sauvegardé',
			'groups:alldiscussion' => 'Dernières discussions',
			'groups:edittopic' => 'Editer le sujet',
			'groups:topicmessage' => 'Corps du sujet',
			'groups:topicstatus' => 'Statut du sujet',
			'groups:reply' => 'Poster un commentaire',
			'groups:topic' => 'Sujet',
			'groups:posts' => 'Commentaires',
			'groups:lastperson' => 'Dernière personne',
			'groups:when' => 'Quand',
			'grouptopic:notcreated' => 'Aucun sujet de discussion.',
			'groups:topicopen' => 'Ouvrir',
			'groups:topicclosed' => 'Fermer',
			'groups:topicresolved' => 'Résoudre',
			'grouptopic:created' => 'Le sujet de discussion a été créé.',
			'groupstopic:deleted' => 'Le sujet de discussion a été supprimé.',
			'groups:topicsticky' => 'Post-it',
			'groups:topicisclosed' => 'Ce sujet de discussion a été fermé.',
			'groups:topiccloseddesc' => "Ce sujet de discussion a été fermé et n'accepte plus de commentaire.",
			
	
			'groups:privategroup' => 'Ce groupe est privé, demander une invitation à le joindre.',
			'groups:notitle' => 'Les groupes doivent avoir un titre',
			'groups:cantjoin' => 'Impossible de rejoindre ce groupe',
			'groups:cantleave' => 'Vous ne pouvez pas quitter ce groupe',
			'groups:addedtogroup' => 'Vous faites désormais parti du groupe',
			'groups:joinrequestnotmade' => "Demande d'invitation impossible",
			'groups:joinrequestmade' => "Votre demande d'invitation a bien été envoyé",
			'groups:joined' => 'Vous faites désormais parti du groupe !',
			'groups:left' => 'Vous ne faites plus parti du groupe',
			'groups:notowner' => "Désolé, vous n'êtes pas le propriétaire de ce groupe.",
			'groups:alreadymember' => 'Vous faites déjà parti de ce groupe !',
			'groups:userinvited' => "L'utilisateur a été invité à rejoindre ce groupe.",
			'groups:usernotinvited' => "L'utilisateur ne peut pas être invité",
	
			'groups:invite:subject' => "%s, vous êtes inviter à rejoindre le groupe %s!",
			'groups:invite:body' => "Salut %s,

Vous venez d'être invité à rejoindre le groupe '%s' group, cliquez ci-dessous pour accepter :

%s",

			'groups:welcome:subject' => "Bienvenue dans le groupe %s!",
			'groups:welcome:body' => "Salut %s!
		
Vous faites désormais parti du groupe '%s' ! Cliquez ci-dessous pour commencer une discussion !

%s",
	
			'groups:request:subject' => "%s a demandé une invitation pour '%s'",
			'groups:request:body' => "Salut %s,

%s a demandé à rejoindre le groupe '%s', cliquez ci-dessous pour voir son profile :

%s

ou cliquez ici pour confirmer son inscription :

%s",
	
			'groups:river:member' => 'est désormais menbre de',
	
			'groups:nowidgets' => "Il n'existe pas de widget pour ce groupe.",
	
	
			'groups:widgets:members:title' => 'Membres du groupe',
			'groups:widgets:members:description' => 'Liste des membres du groupe.',
			'groups:widgets:members:label:displaynum' => "Lister les membres d'un groupe.",
			'groups:widgets:members:label:pleaseedit' => 'Veuillez configurer le widget.',
	
			'groups:widgets:entities:title' => "Objets dans le group",
			'groups:widgets:entities:description' => "Lister les objets du groupe",
			'groups:widgets:entities:label:displaynum' => "Lister les objets d'un groupe.",
			'groups:widgets:entities:label:pleaseedit' => "Veuillez configurer ce widget.",
		
	);
					
	add_translation("fr",$french);
?>