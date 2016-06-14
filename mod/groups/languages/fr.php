<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Groupes",
	'groups:owned' => "Groupes dont je suis responsable",
	'groups:owned:user' => 'Groupes dont %s est responsable',
	'groups:yours' => "Mes groupes",
	'groups:user' => "Groupes de %s",
	'groups:all' => "Tous les groupes",
	'groups:add' => "Créer un nouveau groupe",
	'groups:edit' => "Modifier le groupe",
	'groups:delete' => 'Supprimer le groupe',
	'groups:membershiprequests' => 'Gérer les demandes à rejoindre le groupe',
	'groups:membershiprequests:pending' => 'Gérer les demandes à rejoindre le groupe (%s)',
	'groups:invitations' => 'Invitations du groupe',
	'groups:invitations:pending' => 'Invitations du groupe (%s)',

	'groups:icon' => 'Icône du groupe (ne rien sélectionner pour laisser inchangé)',
	'groups:name' => 'Nom du groupe',
	'groups:username' => 'Nom court du goupe (affiché dans l\'URL : en caractères alphanumériques)',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brève description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Site web',
	'groups:members' => 'Membres du groupe',
	'groups:my_status' => 'Mon statut',
	'groups:my_status:group_owner' => 'Vous êtes responsable de ce groupe',
	'groups:my_status:group_member' => 'Vous êtes dans ce groupe',
	'groups:subscribed' => 'Les notifications du groupe sont activées',
	'groups:unsubscribed' => 'Les notifications du groupe sont désactivées',

	'groups:members:title' => 'Les membres de %s',
	'groups:members:more' => "Voir tous les membres",
	'groups:membership' => "Type d'adhésion",
	'groups:content_access_mode' => "Accès par défaut des nouveaux contenus",
	'groups:content_access_mode:warning' => "Attention : la modification de ce paramètre ne changera pas le niveau d'accès des contenus déjà publiés.",
	'groups:content_access_mode:unrestricted' => "Sans restriction - Les membres peuvent définir les accès des nouveaux contenus",
	'groups:content_access_mode:membersonly' => "Membres seulement - Les nouveaux contenus sont réservés aux membres du groupe",
	'groups:access' => "Niveau d'accès",
	'groups:owner' => "Propriétaire",
	'groups:owner:warning' => "Attention : si vous faites cette modification vous ne serez plus le propriétaire du groupe.",
	'groups:widget:num_display' => 'Nombre de groupes à afficher',
	'groups:widget:membership' => 'Groupes',
	'groups:widgets:description' => 'Affiche les groupes dont vous êtes membre sur votre page de profil',

	'groups:widget:group_activity:title' => 'Activité du Groupe',
	'groups:widget:group_activity:description' => 'Affiche l\'activité d\'un de vos groupes',
	'groups:widget:group_activity:edit:select' => 'Sélectionnez un groupe',
	'groups:widget:group_activity:content:noactivity' => 'Aucune activité dans ce groupe',
	'groups:widget:group_activity:content:noselect' => 'Configurez ce widget pour sélectionner un groupe',

	'groups:noaccess' => 'Vous n\'avez pas accès au groupe',
	'groups:ingroup' => 'dans le groupe',
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
	'groups:joinrequest' => 'Demander à rejoindre au groupe',
	'groups:join' => 'Rejoindre le groupe',
	'groups:leave' => 'Quitter le groupe',
	'groups:invite' => 'Inviter des contacts',
	'groups:invite:title' => 'Invitez des contacts à rejoindre ce groupe',
	'groups:inviteto' => "Inviter des contacts à rejoindre le groupe '%s'",
	'groups:nofriends' => "Vous n'avez plus de contact à inviter à rejoindre ce groupe.",
	'groups:nofriendsatall' => 'Vous n\'avez pas de contact à inviter !',
	'groups:viagroups' => "via les groupes",
	'groups:group' => "Groupe",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Rechercher des groupes avec le tag \"%s\"",
	'groups:search:none' => "Aucun groupe correspondant n'a été trouvé",
	'groups:search_in_group' => "Chercher dans ce groupe",
	'groups:acl' => "Groupe : %s",

	'groups:activity' => "Activité du groupe",
	'groups:enableactivity' => 'Activer l\'activité du groupe',
	'groups:activity:none' => "Il n'y a pas encore eu d'activité dans ce groupe",

	'groups:notfound' => "Le groupe n'a pas été trouvé",
	'groups:notfound:details' => "Le groupe que vous recherchez n'existe pas, ou alors vous n'avez pas la permission d'y accéder",

	'groups:requests:none' => 'Personne ne demande à rejoindre le groupe en ce moment.',

	'groups:invitations:none' => 'Aucune invitation en attente.',

	'groups:count' => "groupe créé",
	'groups:open' => "groupe ouvert",
	'groups:closed' => "groupe fermé",
	'groups:member' => "membres",
	'groups:searchtag' => "Rechercher des groupes par tag",

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
	'groups:visibility' => 'Visibilité du groupe ?',

	/**
	 * Group tools
	 */
	'groups:lastupdated' => 'Dernière mise à jour %s par %s',
	'groups:lastcomment' => 'Dernier commentaire %s by %s',

	'admin:groups' => 'Groupes',

	'groups:privategroup' => 'Ce groupe est privé. Il est nécessaire de demander une adhésion.',
	'groups:notitle' => 'Les groupes doivent avoir un titre',
	'groups:cantjoin' => 'Impossible de rejoindre le groupe',
	'groups:cantleave' => 'Impossible de quitter le groupe',
	'groups:removeuser' => 'Retirer du groupe',
	'groups:cantremove' => 'Impossible de retirer l\'utilisateur du groupe',
	'groups:removed' => '%s a bien été retiré du groupe',
	'groups:addedtogroup' => 'Ajout de l\'utilisateur au groupe réussi',
	'groups:joinrequestnotmade' => 'Impossible de demander à rejoindre le groupe',
	'groups:joinrequestmade' => 'La demande à rejoindre le groupe a bien été effectuée',
	'groups:joined' => 'Vous avez bien rejoint le groupe !',
	'groups:left' => 'Vous avez bien quitté le groupe',
	'groups:notowner' => 'Désolé, vous n\'êtes pas le propriétaire du groupe.',
	'groups:notmember' => 'Désolé, vous n\'êtes pas membre de ce groupe.',
	'groups:alreadymember' => 'Vous êtes déjà membre de ce groupe !',
	'groups:userinvited' => 'L\'utilisateur a été invité.',
	'groups:usernotinvited' => 'L\'utilisateur n\'a pas pu être invité',
	'groups:useralreadyinvited' => 'L\'utilisateur a déjà été invité',
	'groups:invite:subject' => "%s vous avez été invité(e) à rejoindre %s !",
	'groups:started' => "Démarré par %s",
	'groups:joinrequest:remove:check' => 'Confirmez-vous vouloir supprimer cette demande d\'adhésion ?',
	'groups:invite:remove:check' => 'Confirmez-vous vouloir supprimer cette invitation ?',
	'groups:invite:body' => "Bonjour %s,

Vous avez été invité(e) à rejoindre le groupe '%s' cliquez sur le lien ci-dessous pour confirmer:

%s",

	'groups:welcome:subject' => "Bienvenue dans le groupe %s !",
	'groups:welcome:body' => "Bonjour %s !
		
Vous êtes maintenant membre du groupe \"%s\" ! Cliquez le lien ci-dessous pour commencer à participer !

%s",

	'groups:request:subject' => "%s a demandé à rejoindre %s",
	'groups:request:body' => "Bonjour %s,

%s a demandé à rejoindre le groupe \"%s'\". Cliquez le lien ci-dessous pour voir son profil :

%s

ou cliquez sur le lien ci-dessous pour confirmer son adhésion :

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s a créé le groupe %s',
	'river:join:group:default' => '%s a rejoint le groupe %s',

	'groups:nowidgets' => 'Aucun widget n\'a été défini pour ce groupe.',


	'groups:widgets:members:title' => 'Membres du groupe',
	'groups:widgets:members:description' => 'Liste les membres d\'un groupe.',
	'groups:widgets:members:label:displaynum' => 'Liste les membres d\'un groupe.',
	'groups:widgets:members:label:pleaseedit' => 'Veuillez configurer ce widget.',

	'groups:widgets:entities:title' => "Objets dans le groupe",
	'groups:widgets:entities:description' => "Lister les objets enregistrés dans ce groupe",
	'groups:widgets:entities:label:displaynum' => 'Lister les objets d\'un groupe.',
	'groups:widgets:entities:label:pleaseedit' => 'Veuillez configurer ce widget.',

	'groups:allowhiddengroups' => 'Activer les groupes privés (invisibles) ?',
	'groups:whocancreate' => 'Qui peut créer un nouveau groupe ?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Le groupe et son contenu ont bien été supprimés',
	'group:notdeleted' => 'Le groupe n\'a pas pu être supprimé',

	'group:notfound' => 'Impossible de trouver le groupe',
	'groups:deletewarning' => "Confirmez-vous vouloir supprimer ce groupe ? Cette action est irréversible !",

	'groups:invitekilled' => 'L\'invitation a été supprimée',
	'groups:joinrequestkilled' => 'La demande d\'adhésion a été supprimée.',
	'groups:error:addedtogroup' => "Impossible d'ajouter %s au groupe",
	'groups:add:alreadymember' => "%s est déjà membre de ce groupe",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Profils de groupe',
);
