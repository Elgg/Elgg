<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Grupos",
	'groups:owned' => "Grupos que administro",
	'groups:owned:user' => 'Grupos que administra %s',
	'groups:yours' => "Mis grupos",
	'groups:user' => "Grupos de %s",
	'groups:all' => "Todos los grupos",
	'groups:add' => "Crear un nuevo grupo",
	'groups:edit' => "Editar grupo",
	'groups:delete' => 'Borrar grupo',
	'groups:membershiprequests' => 'Administrar solicitudes de uni&oacute;n a grupos',
	'groups:membershiprequests:pending' => 'Administrar solicitudes de uni&oacute;n (%s)',
	'groups:invitations' => 'Invitaciones de grupo',
	'groups:invitations:pending' => 'Invitaciones de grupo (%s)',

	'groups:icon' => 'Icono de grupo (dejar en blanco para no hacer cambios)',
	'groups:name' => 'Nombre del grupo',
	'groups:description' => 'Descripci&oacute;n completa',
	'groups:briefdescription' => 'Breve descripci&oacute;n',
	'groups:interests' => 'Etiquetas',
	'groups:website' => 'Sitio Web',
	'groups:members' => 'Miembros del grupo',

	'groups:members_count' => '%s members',

	'groups:members:title' => 'Miembros de %s',
	'groups:members:more' => "Ver todos los miembros",
	'groups:membership' => "Ver los permisos de los miembros",
	'groups:content_access_mode' => "Accesabilidad del contenido del grupo",
	'groups:content_access_mode:warning' => "Advertencia: Cambiar esta preferencia no cambiara los permisos de acceso al contenido existente en el grupo.",
	'groups:content_access_mode:unrestricted' => "Sin restringir &mdash; el acceso depende de la configuración individual de cada contenido.",
	'groups:content_access_mode:membersonly' => "Solo miembros - Los que no son miembros nunca podrán accesar al contenido del grupo",
	'groups:access' => "Permisos de acceso",
	'groups:owner' => "Propietario",
	'groups:owner:warning' => "Advertencia: si cambia este valor, usted ya no será el dueño de este grupo.",
	'groups:widget:num_display' => 'N&uacute;mero de miembros a mostrar',
	'widgets:a_users_groups:name' => 'Group membership',
	'widgets:a_users_groups:description' => 'Display the groups you are a member of on your profile',

	'groups:noaccess' => 'No hay acceso al grupo',
	'groups:cantcreate' => 'No se puede crear un grupo. Sólo los administradores pueden.',
	'groups:cantedit' => 'No puedes editar este grupo',
	'groups:saved' => 'Grupo guardado',
	'groups:save_error' => 'El grupo no se ha podido guardar',
	'groups:featured' => 'Grupos destacados',
	'groups:makeunfeatured' => 'No destacar',
	'groups:makefeatured' => 'Destacar',
	'groups:featuredon' => '%s es miembro de un grupo destacado.',
	'groups:unfeatured' => '%s se ha eliminado de los grupos destacados.',
	'groups:featured_error' => 'Grupo no válido.',
	'groups:nofeatured' => 'Sin grupos destacados.',
	'groups:joinrequest' => 'Solicitar unirse',
	'groups:join' => 'Unirse al grupo',
	'groups:leave' => 'Abandonar el grupo',
	'groups:invite' => 'Invitar amigos',
	'groups:invite:title' => 'Invitar amigos a este grupo',
	'groups:invite:friends:help' => 'Search for a friend by name or username and select the friend from the list',
	'groups:invite:resend' => 'Resend the invitations to already invited users',

	'groups:nofriendsatall' => 'No hay amigos para invitar',
	'groups:group' => "Grupo",
	'groups:search:tags' => "etiqueta",
	'groups:search:title' => "Búsqueda de grupos etiquetados con  '%s'",
	'groups:search:none' => "No se encontraron grupos que coincidan",
	'groups:search_in_group' => "Buscar en este grupo",
	'groups:acl' => "Group: '%s'",
	'groups:acl:in_context' => 'Group members',

	'groups:notfound' => "No se encontr&oacute; el grupo",
	
	'groups:requests:none' => 'No hay solicitudes de membres&iacute;a.',

	'groups:invitations:none' => 'Actualmente no hay invitaciones.',

	'groups:open' => "grupo abierto",
	'groups:closed' => "grupo cerrado",
	'groups:member' => "miembros",
	'groups:search' => "Search for groups",

	'groups:more' => 'M&aacute;s grupos',
	'groups:none' => 'No hay grupos',

	/**
	 * Access
	 */
	'groups:access:private' => 'Cerrado &mdash; los miembros deben ser invitados',
	'groups:access:public' => 'Abierto &mdash; cualquiera puede unirse',
	'groups:access:group' => 'S&oacute;lo miembros del grupo',
	'groups:closedgroup' => "La membresía de este grupo esta cerrada.",
	'groups:closedgroup:request' => 'Para pedir ser agregado, de click sobre el link "Pedir membresía".',
	'groups:closedgroup:membersonly' => "La membresía de este grupo es cerrada y su contenido solo puede ser accesible para los miembros.",
	'groups:opengroup:membersonly' => "El contenido de este grupo solo es accesible por sus miembros.",
	'groups:opengroup:membersonly:join' => 'Para ser un miembro, de click sobre el link de "Unirse al grupo".',
	'groups:visibility' => '&iquest;Quienes pueden ver este grupo?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupos',

	'groups:notitle' => 'El grupo debe tener un t&iacute;tulo',
	'groups:cantjoin' => 'No se puede unir al grupo',
	'groups:cantleave' => 'No se pudo abandonar el grupo',
	'groups:removeuser' => 'Eliminar del grupo',
	'groups:cantremove' => 'No se puede remover este usuario del grupo',
	'groups:removed' => 'El usuario %s ha sido eliminado del grupo',
	'groups:addedtogroup' => 'El usuario ha sido agregado con &eacute;xito',
	'groups:joinrequestnotmade' => 'No se pudo enviar la solicitud de membres&iacute;a del grupo',
	'groups:joinrequestmade' => 'Solicitar unirse al grupo',
	'groups:joinrequest:exists' => 'Ya eres miembro de este grupo',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'Te has unido al grupo',
	'groups:left' => 'Has abandonado el grupo',
	'groups:userinvited' => 'El usuario ha sido invitado.',
	'groups:usernotinvited' => 'El usuario no pudo ser invitado.',
	'groups:useralreadyinvited' => 'El usuario ya ha sido invitado',
	'groups:invite:subject' => "%s te ha invitado al grupo %s",
	'groups:joinrequest:remove:check' => '&iquest;Seguro que deseas cancelar la solicitud de membres&iacute;a?',
	'groups:invite:remove:check' => '&iquest;Seguro que deseas anular esta invitaci&oacute;n?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group.

Click below to view your invitations:
%s",

	'groups:welcome:subject' => "Bienvenido al grupo %s",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group.

Click below to begin posting!
%s",

	'groups:request:subject' => "%s ha solicitado unirse a %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group.

Click below to view their profile:
%s

or click below to view the group's join requests:
%s",

	'river:group:create' => '%s created the group %s',
	'river:group:join' => '%s joined the group %s',

	'groups:allowhiddengroups' => '&iquest;Desea habilitar los grupos provados?',
	'groups:whocancreate' => '¿Quién puede editar este grupo?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Group and group contents deleted',
	'groups:notdeleted' => 'Group could not be deleted',
	'groups:deletewarning' => "&iquest;Seguro que deseas borrar este grupo? No se puede deshacer",

	'groups:invitekilled' => 'El invitado ha sido eliminado.',
	'groups:joinrequestkilled' => 'La solicitud ha sido borrada.',
	'groups:error:addedtogroup' => "No ha sido posible añadir a %s al grupo.",
	'groups:add:alreadymember' => "%s ya forma parte del grupo.",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Perfiles de los grupos',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Transfer group icons to new location',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade aligns will align group plugin with the requirements of the new API.',
);
