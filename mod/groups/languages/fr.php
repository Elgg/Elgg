<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'add:group:group' => "Créer un nouveau groupe",
	
	'groups' => "Groupes",
	'groups:owned' => "Groupes dont je suis responsable",
	'groups:owned:user' => 'Groupes dont %s est responsable',
	'groups:yours' => "Mes groupes",
	'groups:user' => "Groupes de %s",
	'groups:all' => "Tous les groupes",
	'groups:add' => "Créer un nouveau groupe",
	'groups:edit' => "Modifier le groupe",
	'groups:edit:profile' => "Profil",
	'groups:edit:access' => "Accès",
	'groups:edit:tools' => "Outils",
	'groups:edit:settings' => "Configuration",
	'groups:membershiprequests' => 'Gérer les demandes d\'adhésion',
	'groups:membershiprequests:pending' => 'Gérer les demandes d\'adhésion (%s)',
	'groups:invitedmembers' => "Gérer les invitations",
	'groups:invitations' => 'Invitations du groupe',
	'groups:invitations:pending' => 'Invitations du groupe (%s)',
	
	'relationship:invited' => '%2$s a été invité à rejoindre %1$s',
	'relationship:membership_request' => '%s a demandé à rejoindre %s',

	'groups:icon' => 'Icône du groupe (laisser vide pour ne pas changer)',
	'groups:name' => 'Nom du groupe',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brève description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Site web',
	'groups:members' => 'Membres du groupe',

	'groups:members_count' => '%s membres',

	'groups:members:title' => 'Les membres de %s',
	'groups:members:more' => "Voir tous les membres",
	'groups:membership' => "Permissions d'adhésion au groupe",
	'groups:content_access_mode' => "Accès aux contenus du groupe",
	'groups:content_access_mode:warning' => "Attention : le changement de ce paramètre ne changera pas le niveau d'accès des contenus déjà publiés.",
	'groups:content_access_mode:unrestricted' => "Sans restriction - Les membres peuvent définir les accès au niveau de chaque nouvelle publication",
	'groups:content_access_mode:membersonly' => "Membres seulement - Seuls les membres du groupe peuvent accéder à son contenu",
	'groups:access' => "Autorisations d'accès",
	'groups:owner' => "Propriétaire",
	'groups:owner:warning' => "Attention : si vous faites cette modification vous ne serez plus le propriétaire du groupe.",
	'groups:widget:num_display' => 'Nombre de groupes à afficher',
	'widgets:a_users_groups:name' => 'Adhésion au groupe',
	'widgets:a_users_groups:description' => 'Affiche les groupes dont vous êtes membre sur votre profil',

	'groups:noaccess' => 'Vous n\'avez pas accès au groupe',
	'groups:cantcreate' => 'Vous ne pouvez créer un groupe. Seuls les administrateurs le peuvent.',
	'groups:cantedit' => 'Vous ne pouvez pas modifier ce groupe',
	'groups:saved' => 'Groupe enregistré',
	'groups:save_error' => 'Le groupe n\'a pas pu être enregistré',
	'groups:featured' => 'Groupes à la Une',
	'groups:makeunfeatured' => 'Retirer de la Une',
	'groups:makefeatured' => 'Mettre en Une',
	'groups:featuredon' => 'Le groupe %s est maintenant en Une.',
	'groups:unfeatured' => 'Le groupe %s n\'est plus en Une.',
	'groups:featured_error' => 'Groupe invalide.',
	'groups:nofeatured' => 'Pas de groupe à la Une',
	'groups:joinrequest' => 'Demander à rejoindre le groupe',
	'groups:join' => 'Rejoindre le groupe',
	'groups:leave' => 'Quitter le groupe',
	'groups:invite' => 'Inviter des contacts',
	'groups:invite:title' => 'Invitez des contacts à rejoindre ce groupe',
	'groups:invite:friends:help' => 'Rechercher un contact par nom ou par identifiant et sélectionner le contact dans la liste',
	'groups:invite:resend' => 'Renvoyer les invitations aux membres déjà invités',
	'groups:invite:member' => 'Déjà membre du groupe',
	'groups:invite:invited' => 'Déjà invité dans ce groupe',

	'groups:nofriendsatall' => 'Vous n\'avez pas de contact à inviter !',
	'groups:group' => "Groupe",
	'groups:search:title' => "Rechercher des groupes avec le tag '%s'",
	'groups:search:none' => "Aucun groupe correspondant n'a été trouvé",
	'groups:search_in_group' => "Chercher dans ce groupe",
	'groups:acl' => "Groupe : %s",
	'groups:acl:in_context' => 'Membres du groupe',

	'groups:notfound' => "Le groupe n'a pas été trouvé",
	
	'groups:requests:none' => 'Personne ne demande à rejoindre le groupe en ce moment.',

	'groups:invitations:none' => 'Aucune invitation en attente.',

	'groups:open' => "groupe ouvert",
	'groups:closed' => "groupe fermé",
	'groups:member' => "membres",
	'groups:search' => "Rechercher des groupes",

	'groups:more' => 'Plus de groupes',
	'groups:none' => 'Aucun groupe',

	/**
	 * Access
	 */
	'groups:access:private' => 'Fermé - Les utilisateurs doivent être invités',
	'groups:access:public' => 'Ouvert - N\'importe quel utilisateur peut rejoindre le groupe',
	'groups:access:group' => 'Membres du groupe seulement',
	'groups:closedgroup' => "L'adhésion à ce groupe est fermée.",
	'groups:closedgroup:request' => 'Pour être membre, cliquez sur le lien "Demander à rejoindre le groupe".',
	'groups:closedgroup:membersonly' => "Les adhésions à ce groupe sont fermées et son contenu est accessible uniquement aux membres.",
	'groups:opengroup:membersonly' => "Le contenu de ce groupe est accessible uniquement à ses membres.",
	'groups:opengroup:membersonly:join' => 'Pour faire partie des membres, cliquez sur le lien du menu "Rejoindre le groupe".',
	'groups:visibility' => 'Qui peut voir ce groupe ?',
	'groups:content_default_access' => 'Accès par défaut des publications dans le groupe',
	'groups:content_default_access:help' => 'Ici vous pouvez configurer le niveau d\'accès par défaut pour les nouveaux contenus publiés dans ce groupe. Le mode de gestion des contenus du groupe peut rendre l\'option choisie sans effet.',
	'groups:content_default_access:not_configured' => 'Aucun accès par défaut, au choix du membre',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Groupes',

	'groups:notitle' => 'Les groupes doivent avoir un titre',
	'groups:cantjoin' => 'Impossible de rejoindre le groupe',
	'groups:cantleave' => 'Impossible de quitter le groupe',
	'groups:removeuser' => 'Retirer du groupe',
	'groups:cantremove' => 'Impossible de retirer l\'utilisateur du groupe',
	'groups:removed' => '%s a bien été retiré du groupe',
	'groups:addedtogroup' => 'Ajout de l\'utilisateur au groupe réussi',
	'groups:joinrequestnotmade' => 'Impossible de demander à rejoindre le groupe',
	'groups:joinrequestmade' => 'La demande à rejoindre le groupe a bien été effectuée',
	'groups:joinrequest:exists' => 'Vous avez déjà fait une demande d\'adhésion pour ce groupe.',
	'groups:button:joined' => 'Membre',
	'groups:button:owned' => 'Propriétaire',
	'groups:joined' => 'Vous avez bien rejoint le groupe !',
	'groups:left' => 'Vous avez bien quitté le groupe',
	'groups:userinvited' => 'L\'utilisateur a été invité.',
	'groups:usernotinvited' => 'L\'utilisateur n\'a pas pu être invité',
	'groups:useralreadyinvited' => 'L\'utilisateur a déjà été invité',
	'groups:invite:subject' => "%s vous êtes invité à rejoindre %s !",
	'groups:joinrequest:remove:check' => 'Confirmez-vous vouloir supprimer cette demande d\'adhésion ?',
	'groups:invite:remove:check' => 'Confirmez-vous vouloir supprimer cette invitation ?',
	'groups:invite:body' => "%s vous invite à rejoindre le groupe '%s'.

Cliquez sur le lien ci-dessous pour voir vos invitations :
%s",

	'groups:welcome:subject' => "Bienvenue dans le groupe %s !",
	'groups:welcome:body' => "Vous êtes maintenant membre du groupe '%s'.

Cliquez le lien ci-dessous pour commencer à participer !
%s",

	'groups:request:subject' => "%s a demandé à rejoindre %s",
	'groups:request:body' => "%s a demandé à rejoindre le groupe '%s'.

Pour voir son profil :
%s

ou pour voir les demandes d'adhésion au groupe :
%s",

	'river:group:create' => '%s a créé le groupe %s',
	'river:group:join' => '%s a rejoint le groupe %s',

	'groups:allowhiddengroups' => 'Activer les groupes privés (invisibles) ?',
	'groups:whocancreate' => 'Qui peut créer un nouveau groupe ?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'L\'invitation a été supprimée',
	'groups:joinrequestkilled' => 'La demande d\'adhésion a été supprimée.',
	'groups:error:addedtogroup' => "Impossible d'ajouter %s au groupe",
	'groups:add:alreadymember' => "%s est déjà membre de ce groupe",
	
	// Notification settings
	'groups:usersettings:notification:group_join:description' => "Paramètres de notification de groupe par défaut quand vous rejoignez un groupe",
	
	'groups:usersettings:notifications:title' => 'Notifications du groupe',
	'groups:usersettings:notifications:description' => 'Pour recevoir des notifications quand de nouveaux contenus sont publiés dans un groupe dont vous êtes membre, cherchez et choisissez ci-dessous la ou les méthodes de notification que vous souhaitez utiliser.',
);
