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
	
	'groups' => "Grupos",
	'groups:owned' => "Grupos que administra",
	'groups:owned:user' => 'Grupos que administra %s',
	'groups:yours' => "Grupos seus",
	'groups:user' => "Grupos de %s",
	'groups:all' => "Todos os grupos",
	'groups:add' => "Crear un grupo",
	'groups:edit' => "Editar o grupo",
	'groups:membershiprequests' => 'Xestionar as solicitudes de admisión',
	'groups:membershiprequests:pending' => 'Xestionar as solicitudes de admisión (%s)',
	'groups:invitations' => 'Invitacións ao grupo',
	'groups:invitations:pending' => 'Invitacións ao grupo (%s)',

	'groups:icon' => 'Icona do grupo (deixe o cambio baleiro para mantela)',
	'groups:name' => 'Nome',
	'groups:description' => 'Descrición',
	'groups:briefdescription' => 'Descrición breve.',
	'groups:interests' => 'Etiquetas',
	'groups:website' => 'Sitio web',
	'groups:members' => 'Membros',

	'groups:members:title' => 'Membros de %s',
	'groups:members:more' => "Ver todos os membros",
	'groups:membership' => "Permisos de pertenza ao grupo",
	'groups:content_access_mode' => "Acceso ao contido do grupo",
	'groups:content_access_mode:warning' => "Aviso: cambiar esta opción non afectará aos permisos de acceso do contido existente do grupo.",
	'groups:content_access_mode:unrestricted' => "Ilimitado — O acceso ao contido depende da configuración de acceso de cada contido.",
	'groups:content_access_mode:membersonly' => "Só membros — Só os membros do grupo poden acceder ao seu contido.",
	'groups:access' => "Permisos de acces",
	'groups:owner' => "Administrador",
	'groups:owner:warning' => "Aviso: se cambia o valor, deixará de ser o administrador do grupo.",
	'groups:widget:num_display' => 'Número de grupos para mostrar',
	'widgets:a_users_groups:name' => 'Grupos aos que pertence',
	'widgets:a_users_groups:description' => 'Mostrar os grupos aos que pertence no seu perfil.',

	'groups:noaccess' => 'Non ten acceso ao grupo.',
	'groups:cantcreate' => 'Só os administradores poden crear grupos.',
	'groups:cantedit' => 'Non pode editar o grupo.',
	'groups:saved' => 'Gardouse o grupo.',
	'groups:save_error' => 'Non foi posíbel gardar o grupo.',
	'groups:featured' => 'Grupos destacados',
	'groups:makeunfeatured' => 'Deixar de destacar',
	'groups:makefeatured' => 'Descatar',
	'groups:featuredon' => 'O grupo %s está destacado.',
	'groups:unfeatured' => 'Deixo de destacarse %s.',
	'groups:featured_error' => 'Grupo non válido.',
	'groups:nofeatured' => 'Non hai grupos salientados.',
	'groups:joinrequest' => 'Solicitar unirse',
	'groups:join' => 'Unirse ao grupo',
	'groups:leave' => 'Deixar o grupo',
	'groups:invite' => 'Invitar contactos',
	'groups:invite:title' => 'Invitar contactos a unirse a este grupo.',

	'groups:nofriendsatall' => 'Non ten contactos que invitar.',
	'groups:group' => "Grupo",
	'groups:search:title' => "Buscar grupos coa etiqueta «%s»",
	'groups:search:none' => "Non se atopou ningunha coincidencia.",
	'groups:search_in_group' => "Buscar no grupo",
	'groups:acl' => "Grupo: %s",
	'groups:acl:in_context' => 'Membros',

	'groups:notfound' => "Non se atopou o grupo.",
	
	'groups:requests:none' => 'Non hai ningunha solicitude de admisión pendente.',

	'groups:invitations:none' => 'Non hai ningunha invitación.',

	'groups:open' => "Grupo abert",
	'groups:closed' => "Grupo pechado",
	'groups:member' => "Membros",

	'groups:more' => 'Máis grupos',
	'groups:none' => 'Non hai grupos.',

	/**
	 * Access
	 */
	'groups:access:private' => 'Pechado — Só pode unirse mediante invitación',
	'groups:access:public' => 'Aberto — Calquera pode unirse',
	'groups:access:group' => 'Só para membros',
	'groups:closedgroup' => "O grupo está pechado a novos membros.",
	'groups:closedgroup:request' => 'Para solicitar que o admitan, prema «Solicitar unirse».',
	'groups:closedgroup:membersonly' => "O grupo está pechado a novos membros e o seu contido só poden velos os membros do grupo.",
	'groups:opengroup:membersonly' => "O contido do grupo só poden velo os membros do grupo.",
	'groups:opengroup:membersonly:join' => 'Para facerse membro, prema «Unirse ao grupo».',
	'groups:visibility' => 'Quen pode ver o grupo?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupos',

	'groups:notitle' => 'Os grupos necesitan un título.',
	'groups:cantjoin' => 'Non foi posíbel unirse ao grupo.',
	'groups:cantleave' => 'Non foi posíbel deixar o grupo.',
	'groups:removeuser' => 'Retirar do grupo',
	'groups:cantremove' => 'Non foi posíbel retirar o usuario do grupo.',
	'groups:removed' => 'Retirouse a %s do grupo.',
	'groups:addedtogroup' => 'Engadiuse o usuario ao grupo.',
	'groups:joinrequestnotmade' => 'Non foi posíbel solicitar unirse ao grupo.',
	'groups:joinrequestmade' => 'Solicitouse unirse ao grupo.',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:joined' => 'Uniuse ao grupo.',
	'groups:left' => 'Deixou o grupo.',
	'groups:userinvited' => 'Invitouse o usuario.',
	'groups:usernotinvited' => 'Non foi posíbel invitar o usuario.',
	'groups:useralreadyinvited' => 'Xa se invitara o usuario.',
	'groups:invite:subject' => "%s recibiu unha invitación a %s.",
	'groups:joinrequest:remove:check' => 'Está seguro de que quere eliminar a solicitude de admisión?',
	'groups:invite:remove:check' => 'Está seguro de que quere eliminar a invitación?',

	'groups:welcome:subject' => "Benvida ao grupo «%s»!",

	'groups:request:subject' => "%s solicitou unirse a %s",

	'groups:allowhiddengroups' => 'Quere permitir grupos privados (invisíbeis)?',
	'groups:whocancreate' => 'Quen pode crear grupos novos?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Eliminouse a invitación',
	'groups:joinrequestkilled' => 'Eliminouse a solicitude de admisión',
	'groups:error:addedtogroup' => "Non foi posíbel engadir a %s ao grupo",
	'groups:add:alreadymember' => "%s xa pertence ao grupo.",
	
	// Notification settings
);
