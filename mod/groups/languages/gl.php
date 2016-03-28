<?php
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
	'groups:delete' => 'Eliminar o grupo',
	'groups:membershiprequests' => 'Xestionar as solicitudes de admisión',
	'groups:membershiprequests:pending' => 'Xestionar as solicitudes de admisión (%s)',
	'groups:invitations' => 'Invitacións ao grupo',
	'groups:invitations:pending' => 'Invitacións ao grupo (%s)',

	'groups:icon' => 'Icona do grupo (deixe o cambio baleiro para mantela)',
	'groups:name' => 'Nome',
	'groups:username' => 'Nome curto do grupo, para mostrar en URLs. Só pode ter números e letras.',
	'groups:description' => 'Descrición',
	'groups:briefdescription' => 'Descrición breve.',
	'groups:interests' => 'Etiquetas',
	'groups:website' => 'Sitio web',
	'groups:members' => 'Membros',
	'groups:my_status' => 'Rol',
	'groups:my_status:group_owner' => 'Administrador',
	'groups:my_status:group_member' => 'Membr',
	'groups:subscribed' => 'Group notifications are on',
	'groups:unsubscribed' => 'Group notifications are off',

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
	'groups:widget:membership' => 'Grupos aos que pertence',
	'groups:widgets:description' => 'Mostrar os grupos aos que pertence no seu perfil.',

	'groups:widget:group_activity:title' => 'Actividade do grupo',
	'groups:widget:group_activity:description' => 'Ver a actividade nun dos grupos ao que pertence',
	'groups:widget:group_activity:edit:select' => 'Seleccione un grupo',
	'groups:widget:group_activity:content:noactivity' => 'Este grupo non ten actividade',
	'groups:widget:group_activity:content:noselect' => 'Edite este trebello para seleccionar un grupo.',

	'groups:noaccess' => 'Non ten acceso ao grupo.',
	'groups:ingroup' => 'No grupo',
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
	'groups:inviteto' => "Invitar contactos a «%s»",
	'groups:nofriends' => "Non lle quedan contactos que non recibisen xa unha invitación para unirse a este grupo.",
	'groups:nofriendsatall' => 'Non ten contactos que invitar.',
	'groups:viagroups' => "Mediante grupos",
	'groups:group' => "Grupo",
	'groups:search:tags' => "Etiqueta",
	'groups:search:title' => "Buscar grupos coa etiqueta «%s»",
	'groups:search:none' => "Non se atopou ningunha coincidencia.",
	'groups:search_in_group' => "Buscar no grupo",
	'groups:acl' => "Grupo: %s",

	'groups:activity' => "Actividade do grupo",
	'groups:enableactivity' => 'Activar a actividade do grupo',
	'groups:activity:none' => "Aínda non hai actividade no grupo",

	'groups:notfound' => "Non se atopou o grupo.",
	'groups:notfound:details' => "O grupo solicitado non existe ou non ten permisos para acceder a el",

	'groups:requests:none' => 'Non hai ningunha solicitude de admisión pendente.',

	'groups:invitations:none' => 'Non hai ningunha invitación.',

	'groups:count' => "Grupos creados",
	'groups:open' => "Grupo abert",
	'groups:closed' => "Grupo pechado",
	'groups:member' => "Membros",
	'groups:searchtag' => "Buscar grupos por etiqueta",

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
	'groups:lastupdated' => 'Actualizado po última vez o %s por %s.',
	'groups:lastcomment' => 'O último comentario deixouno %s %s.',

	'admin:groups' => 'Grupos',

	'groups:privategroup' => 'O grupo é un grupo pechado. Solicitarase a admisión.',
	'groups:notitle' => 'Os grupos necesitan un título.',
	'groups:cantjoin' => 'Non foi posíbel unirse ao grupo.',
	'groups:cantleave' => 'Non foi posíbel deixar o grupo.',
	'groups:removeuser' => 'Retirar do grupo',
	'groups:cantremove' => 'Non foi posíbel retirar o usuario do grupo.',
	'groups:removed' => 'Retirouse a %s do grupo.',
	'groups:addedtogroup' => 'Engadiuse o usuario ao grupo.',
	'groups:joinrequestnotmade' => 'Non foi posíbel solicitar unirse ao grupo.',
	'groups:joinrequestmade' => 'Solicitouse unirse ao grupo.',
	'groups:joined' => 'Uniuse ao grupo.',
	'groups:left' => 'Deixou o grupo.',
	'groups:notowner' => 'Non é o administrador do grupo.',
	'groups:notmember' => 'Non é membro do grupo.',
	'groups:alreadymember' => 'Xa é membro do grupo.',
	'groups:userinvited' => 'Invitouse o usuario.',
	'groups:usernotinvited' => 'Non foi posíbel invitar o usuario.',
	'groups:useralreadyinvited' => 'Xa se invitara o usuario.',
	'groups:invite:subject' => "%s recibiu unha invitación a %s.",
	'groups:started' => "Iniciado por %s",
	'groups:joinrequest:remove:check' => 'Está seguro de que quere eliminar a solicitude de admisión?',
	'groups:invite:remove:check' => 'Está seguro de que quere eliminar a invitación?',
	'groups:invite:body' => "Ola, %s.

%s invitouno a unirse ao grupo «%s». Siga esta ligazón para ver as súas invitacións:

%s",

	'groups:welcome:subject' => "Benvida ao grupo «%s»!",
	'groups:welcome:body' => "Ola, %s.

Agora forma parte do grupo «%s». Siga esta ligazón para deixar unha mensaxe:

%s",

	'groups:request:subject' => "%s solicitou unirse a %s",
	'groups:request:body' => "Ola, %s.

%s solicitou unirse ao grupo «%s». Prema a seguinte ligazón para ver o seu perfil:

%s

Ou prema a seguinte ligazón para ver as solicitudes de admisión do grupo:

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s creou o grupo %s',
	'river:join:group:default' => '%s uniuse ao grupo %s',

	'groups:nowidgets' => 'Non se definiu ningún trebello para este grupo.',


	'groups:widgets:members:title' => 'Membros',
	'groups:widgets:members:description' => 'Listar os membros dun grupo.',
	'groups:widgets:members:label:displaynum' => 'Listar os membros dun grupo.',
	'groups:widgets:members:label:pleaseedit' => 'Configure o trebello.',

	'groups:widgets:entities:title' => "Obxectos no grupo",
	'groups:widgets:entities:description' => "Listar os obxectos gardados no grupo.",
	'groups:widgets:entities:label:displaynum' => 'Listar os obxectos dun grupo.',
	'groups:widgets:entities:label:pleaseedit' => 'Configure o trebello.',

	'groups:allowhiddengroups' => 'Quere permitir grupos privados (invisíbeis)?',
	'groups:whocancreate' => 'Quen pode crear grupos novos?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Elimináronse o grupo e mailos seus contidos.',
	'group:notdeleted' => 'Non foi posíbel eliminar o grupo.',

	'group:notfound' => 'Non foi posíbel atopar o grupo.',
	'groups:deletewarning' => "Está seguro de que quere eliminar o grupo? Esta operación non pode desfacerse.",

	'groups:invitekilled' => 'Eliminouse a invitación',
	'groups:joinrequestkilled' => 'Eliminouse a solicitude de admisión',
	'groups:error:addedtogroup' => "Non foi posíbel engadir a %s ao grupo",
	'groups:add:alreadymember' => "%s xa pertence ao grupo.",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Perfís do grupo',
);
