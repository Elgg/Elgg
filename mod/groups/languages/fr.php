<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Groupes",
	'groups:owned' => "Les groupes que je possède",
	'groups:owned:user' => 'Les groupes que %s possède',
	'groups:yours' => "Mes groupes",
	'groups:user' => "Les groupes de %s",
	'groups:all' => "Tous les groupes",
	'groups:add' => "Créer un nouveau groupe",
	'groups:edit' => "Modifier le groupe",
	'groups:delete' => 'Supprimer le groupe',
	'groups:membershiprequests' => 'Gérer les membres souhaitant se joindre au groupe',
	'groups:membershiprequests:pending' => 'Gérer les membres souhaitant se joindre au groupe (%s)',
	'groups:invitations' => 'Invitations du groupe',
	'groups:invitations:pending' => 'Invitations du groupe (%s)',

	'groups:icon' => 'Icone du groupe (ne rien inscrire pour laisser inchangé)',
	'groups:name' => 'Nom du groupe',
	'groups:username' => 'Nom court du goupe (Qui s\'affichera dans l\'URL : en caractères alphanumériques)',
	'groups:description' => 'Description',
	'groups:briefdescription' => 'Brève description',
	'groups:interests' => 'Tags',
	'groups:website' => 'Site web',
	'groups:members' => 'Membres du groupe',
	'groups:my_status' => 'Mon status',
	'groups:my_status:group_owner' => 'Vous possédez ce groupe',
	'groups:my_status:group_member' => 'Vous êtes dans ce groupe',
	'groups:subscribed' => 'Notifications du groupe actif',
	'groups:unsubscribed' => 'Notifications du groupe inactif',

	'groups:members:title' => 'Les membres de %s',
	'groups:members:more' => "Voir tous les membres",
	'groups:membership' => "Permissions d'accès au groupe",
	'groups:content_access_mode' => "Accessibilité au contenu du groupe",
	'groups:content_access_mode:warning' => "Attention: la modification de ce paramètre ne changera pas l'autorisation d'accès au contenu du groupe existant.",
	'groups:content_access_mode:unrestricted' => "Sans restriction - L'accès dépend des réglages au niveau du contenu",
	'groups:content_access_mode:membersonly' => "Membres seulement - Les non-membres ne peuvent pas accéder au contenu du groupe",
	'groups:access' => "Permissions d'accès",
	'groups:owner' => "Propriétaire",
	'groups:owner:warning' => "Attention: si vous changez cette valeur, vous ne serez plus le propriétaire du groupe.",
	'groups:widget:num_display' => 'Nombre de groupes à afficher',
	'groups:widget:membership' => 'Adhésion au groupe',
	'groups:widgets:description' => 'Afficher les groupes dont vous êtes membre dans votre profil',

	'groups:widget:group_activity:title' => 'Activité du Groupe',
	'groups:widget:group_activity:description' => 'Afficher l\'activité dans un de vos groupes',
	'groups:widget:group_activity:edit:select' => 'Sélectionnez un groupe',
	'groups:widget:group_activity:content:noactivity' => 'Il n\'y a pas d\'activité dans ce groupe',
	'groups:widget:group_activity:content:noselect' => 'Editez ce widget pour sélectionner un groupe',

	'groups:noaccess' => 'Vous n\'avez pas accès au groupe',
	'groups:ingroup' => 'dans le groupe',
	'groups:cantcreate' => 'Vous ne pouvez créer un groupe. Seul les administrateurs peuvent.',
	'groups:cantedit' => 'Vous ne pouvez pas modifier ce groupe',
	'groups:saved' => 'Groupe enregistré',
	'groups:save_error' => 'Le groupe n\'a pas pu être enregistré',
	'groups:featured' => 'Les groupes à la une',
	'groups:makeunfeatured' => 'Enlever de la une',
	'groups:makefeatured' => 'Mettre à la une',
	'groups:featuredon' => '%s est maintenant un groupe à la une.',
	'groups:unfeatured' => 's% a été enlevé par les groupes à la une.',
	'groups:featured_error' => 'Groupe invalide.',
	'groups:nofeatured' => 'Pas de groupes à la une',
	'groups:joinrequest' => 'Demander une adhésion au groupe',
	'groups:join' => 'Rejoindre le groupe',
	'groups:leave' => 'Quitter le groupe',
	'groups:invite' => 'Inviter des contacts',
	'groups:invite:title' => 'Invitez des amis à ce groupe',
	'groups:inviteto' => "Inviter des contacts au groupe '%s'",
	'groups:nofriends' => "Vous n'avez plus de contacts à inviter à ce groupe.",
	'groups:nofriendsatall' => 'Vous n\'avez pas d\'amis à inviter !',
	'groups:viagroups' => "Via les groupes",
	'groups:group' => "Groupe",
	'groups:search:tags' => "Tag",
	'groups:search:title' => "Rechercher des groupes qui contiennent le tag '% s'",
	'groups:search:none' => "Aucun groupe correspondant n'a été trouvé",
	'groups:search_in_group' => "Chercher dans ce groupe",
	'groups:acl' => "Groupe : %s",

	'groups:activity' => "Activité du Groupe",
	'groups:enableactivity' => 'Rendre disponible Activité de groupe',
	'groups:activity:none' => "Il n'y a pas encore d'activité de groupe",

	'groups:notfound' => "Le groupe n'a pas été trouvé",
	'groups:notfound:details' => "Le groupe que vous recherchez n'existe pas, ou alors vous n'avez pas la permission d'y accéder",

	'groups:requests:none' => 'Il n\'y a pas de membre demandant de rejoindre le groupe en ce moment.',

	'groups:invitations:none' => 'Il n\'y a pas d\'invitations en attente.',

	'groups:count' => "groupe créé",
	'groups:open' => "groupe ouvert",
	'groups:closed' => "groupe fermé",
	'groups:member' => "membres",
	'groups:searchtag' => "Rechercher des groupes par des mots-clé",

	'groups:more' => 'Plus de groupes',
	'groups:none' => 'Aucun groupe',

	/**
	 * Access
	 */
	'groups:access:private' => 'Fermé - Les utilisateurs doivent être invités',
	'groups:access:public' => 'Ouvert - N\'importe quel utilisateur peut rejoindre le groupe',
	'groups:access:group' => 'Membres du groupe seulement',
	'groups:closedgroup' => "L'adhésion à ce groupe est fermé.",
	'groups:closedgroup:request' => 'Pour être membre, clicker sur "Demande d\'adhésion" sur le menu.',
	'groups:closedgroup:membersonly' => "Les inscriptions à ce groupe sont fermées et son contenu est accessible uniquement aux membres.",
	'groups:opengroup:membersonly' => "Le contenu de ce groupe est accessible uniquement à ses membres.",
	'groups:opengroup:membersonly:join' => 'Pour faire partie des membres, cliquez sur le lien du menu "Rejoindre le groupe".',
	'groups:visibility' => 'Qui peut voir ce groupe ?',

	/**
	 * Group tools
	 */
	'groups:lastupdated' => 'Dernière mise à jour le %s par %s',
	'groups:lastcomment' => 'Dernier commentaire %s by %s',

	'admin:groups' => 'Groupes',

	'groups:privategroup' => 'Ce groupe est privé. Il est nécessaire de demander une adhésion.',
	'groups:notitle' => 'Les groupes doivent avoir un titre',
	'groups:cantjoin' => 'N\'a pas pu rejoindre le groupe',
	'groups:cantleave' => 'N\'a pas pu quitter le groupe',
	'groups:removeuser' => 'Retirer du groupe',
	'groups:cantremove' => 'Ne peut retirer l\'utilisateur du groupe',
	'groups:removed' => 'Retiré du groupe %s avec succès',
	'groups:addedtogroup' => 'A ajouté avec succés l\'utilisateur au groupe',
	'groups:joinrequestnotmade' => 'La demande d\'adhésion n\'a pas pu être réalisée',
	'groups:joinrequestmade' => 'La demande d\'adhésion s\'est déroulée avec succés',
	'groups:joined' => 'Vous avez rejoint le groupe avec succés !',
	'groups:left' => 'Vous avez quitter le groupe avec succés',
	'groups:notowner' => 'Désolé, vous n\'êtes pas le propriétaire du groupe.',
	'groups:notmember' => 'Désolé, vous n\'êtes pas membre de ce groupe.',
	'groups:alreadymember' => 'Vous êtes déjà membre de ce groupe !',
	'groups:userinvited' => 'L\'utilisateur a été invité.',
	'groups:usernotinvited' => 'L\'utilisateur n\'a pas pu être invité',
	'groups:useralreadyinvited' => 'L\'utilisateur a déjà été invité',
	'groups:invite:subject' => "%s vous avez été invité(e) à rejoindre %s !",
	'groups:started' => "Démarré par %s",
	'groups:joinrequest:remove:check' => 'Etes-vous sûr de vouloir supprimer cette demande d\'adhésion ?',
	'groups:invite:remove:check' => 'Etes-vous sûr de vouloir supprimer cette invitation ?',
	'groups:invite:body' => "Bonjour %s,

Vous avez été invité(e) à rejoindre le groupe '%s' cliquez sur le lien ci-dessous pour confirmer:

%s",

	'groups:welcome:subject' => "Bienvenue dans le groupe %s !",
	'groups:welcome:body' => "Bonjour %s !
		
Vous êtes maintenant membre du groupe '%s' ! Cliquez le lien ci-dessous pour commencer à participer !

%s",

	'groups:request:subject' => "%s a demandé une adhésion à %s",
	'groups:request:body' => "Bonjour %s,

%s a demandé à rejoindre le groupe '%s', cliquez le lien ci-dessous pour voir son profil :

%s

ou cliquez le lien ci-dessous pour confirmer son adhésion :

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s a créé le groupe %s',
	'river:join:group:default' => '%s a rejoint le groupe %s',

	'groups:nowidgets' => 'Aucun widget n\'ont été défini pour ce groupe.',


	'groups:widgets:members:title' => 'Membres du groupe',
	'groups:widgets:members:description' => 'Lister les membres d\'un groupe.',
	'groups:widgets:members:label:displaynum' => 'Lister les membres d\'un groupe.',
	'groups:widgets:members:label:pleaseedit' => 'Merci de configurer ce widget.',

	'groups:widgets:entities:title' => "Objets dans le groupe",
	'groups:widgets:entities:description' => "Lister les objets enregistré dans ce groupe",
	'groups:widgets:entities:label:displaynum' => 'Lister les objets d\'un groupe.',
	'groups:widgets:entities:label:pleaseedit' => 'Merci de configurer ce widget.',

	'groups:allowhiddengroups' => 'Voulez-vous permettre les groupes privés (invisibles) ?',
	'groups:whocancreate' => 'Qui peut créer un nouveau groupe ?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Contenus du groupe et groupe supprimés',
	'group:notdeleted' => 'Le groupe n\'a pas pu être supprimé',

	'group:notfound' => 'Impossible de trouver le groupe',
	'groups:deletewarning' => "Etes-vous sur de vouloir supprimer ce groupe ? Cette action est irréversible !",

	'groups:invitekilled' => 'L\'invitation a été supprimée',
	'groups:joinrequestkilled' => 'La demande d\'adhésion a été supprimée.',
	'groups:error:addedtogroup' => "Impossible d'ajouter %s au groupe",
	'groups:add:alreadymember' => "%s est déjà un membre de ce groupe",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Les profils de groupe',
);
