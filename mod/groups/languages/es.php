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
	'groups:invitations:pending' => 'Invitaciones de (%s)',

	'groups:icon' => 'Icono de grupo (dejar en blanco para no hacer cambios)',
	'groups:name' => 'Nombre del grupo',
	'groups:username' => 'Nombre corto del grupo (como se muestra en la URL, solo caracteres alfanum&eacute;ricos)',
	'groups:description' => 'Descripci&oacute;n completa',
	'groups:briefdescription' => 'Breve descripci&oacute;n',
	'groups:interests' => 'Etiquetas',
	'groups:website' => 'Sitio Web',
	'groups:members' => 'Miembros del grupo',
	'groups:my_status' => 'Mi estado',
	'groups:my_status:group_owner' => 'Eres dueño de este grupo',
	'groups:my_status:group_member' => 'Usted está en este grupo',
	'groups:subscribed' => 'Group notifications are on',
	'groups:unsubscribed' => 'Group notifications are off',

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
	'groups:widget:membership' => 'Miembros del grupo',
	'groups:widgets:description' => 'Muestra los grupos en dond eeres miembro',

	'groups:widget:group_activity:title' => 'Actividad del grupo',
	'groups:widget:group_activity:description' => 'Ver la actividad en uno de tus grupos',
	'groups:widget:group_activity:edit:select' => 'Selecciona un grupo',
	'groups:widget:group_activity:content:noactivity' => 'No hay actividad en este grupo',
	'groups:widget:group_activity:content:noselect' => 'Editar este widget para seleccionar un grupo',

	'groups:noaccess' => 'No hay acceso al grupo',
	'groups:ingroup' => 'en el grupo',
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
	'groups:inviteto' => "Invitar amigos a '%s'",
	'groups:nofriends' => "No hay amigos que no hayan sido invitados al grupo.",
	'groups:nofriendsatall' => 'No hay amigos para invitar',
	'groups:viagroups' => "via grupos",
	'groups:group' => "Grupo",
	'groups:search:tags' => "etiqueta",
	'groups:search:title' => "Búsqueda de grupos etiquetados con  '%s'",
	'groups:search:none' => "No se encontraron grupos que coincidan",
	'groups:search_in_group' => "Buscar en este grupo",
	'groups:acl' => "Group: '%s'",

	'groups:activity' => "Actividad del grupo",
	'groups:enableactivity' => 'Habilitar las actividades del grupo',
	'groups:activity:none' => "El grupo no ha tenido actividades a&uacute;n",

	'groups:notfound' => "No se encontr&oacute; el grupo",
	'groups:notfound:details' => "El grupo solicitado no existe o no tienes permiso para verlo",

	'groups:requests:none' => 'No hay solicitudes de membres&iacute;a.',

	'groups:invitations:none' => 'Actualmente no hay invitaciones.',

	'groups:count' => "Grupos creados",
	'groups:open' => "grupo abierto",
	'groups:closed' => "grupo cerrado",
	'groups:member' => "miembros",
	'groups:searchtag' => "Buscar grupos por etiqueta",

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
	'groups:lastupdated' => '&Uacute;ltimas actualizaciones de %s por %s',
	'groups:lastcomment' => '&Uacute;ltimos de %s por %s',

	'admin:groups' => 'Grupos',

	'groups:privategroup' => 'Este es un grupo cerrado. Debes solicitar membresía.',
	'groups:notitle' => 'El grupo debe tener un t&iacute;tulo',
	'groups:cantjoin' => 'No se puede unir al grupo',
	'groups:cantleave' => 'No se pudo abandonar el grupo',
	'groups:removeuser' => 'Eliminar del grupo',
	'groups:cantremove' => 'No se puede remover este usuario del grupo',
	'groups:removed' => 'El usuario %s ha sido eliminado del grupo',
	'groups:addedtogroup' => 'El usuario ha sido agregado con &eacute;xito',
	'groups:joinrequestnotmade' => 'No se pudo enviar la solicitud de membres&iacute;a del grupo',
	'groups:joinrequestmade' => 'Solicitar unirse al grupo',
	'groups:joined' => 'Te has unido al grupo',
	'groups:left' => 'Has abandonado el grupo',
	'groups:notowner' => 'No eres el propietario del grupo.',
	'groups:notmember' => 'No eres miembro de este grupo.',
	'groups:alreadymember' => 'Ya eres miembro de este grupo',
	'groups:userinvited' => 'El usuario ha sido invitado.',
	'groups:usernotinvited' => 'El usuario no pudo ser invitado.',
	'groups:useralreadyinvited' => 'El usuario ya ha sido invitado',
	'groups:invite:subject' => "%s te ha invitado al grupo %s",
	'groups:started' => "Iniciado por %s",
	'groups:joinrequest:remove:check' => '&iquest;Seguro que deseas cancelar la solicitud de membres&iacute;a?',
	'groups:invite:remove:check' => '&iquest;Seguro que deseas anular esta invitaci&oacute;n?',
	'groups:invite:body' => "Hi %s,

%s te ha invitado para que te unas al grupo '%s'. Haz click en el siguiente enlace para unirte:

%s",

	'groups:welcome:subject' => "Bienvenido al grupo %s",
	'groups:welcome:body' => "Hola %s!

Ahora eres miembro de '%s'. Haz click en el siguiente enlace para empezar a postear:

%s",

	'groups:request:subject' => "%s ha solicitado unirse a %s",
	'groups:request:body' => "Hola %s,

%s ha solicitado unirse al grupo '%s'. Click en el siguiente enlace para ver el perfil:

%s

O click a continuaci&oacute;n para ver las solicitudes de membres&iacute;a del grupo:

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s ha creado el grupo %s',
	'river:join:group:default' => '%s se ha unido al grupo %s',

	'groups:nowidgets' => 'No se han definido widgets para el grupo.',


	'groups:widgets:members:title' => 'Miembros del grupo',
	'groups:widgets:members:description' => 'Listar los miembros del grupo.',
	'groups:widgets:members:label:displaynum' => 'Listar los miembros de un grupo.',
	'groups:widgets:members:label:pleaseedit' => 'Por favor configura este widget.',

	'groups:widgets:entities:title' => "Objetos en el grupo",
	'groups:widgets:entities:description' => "Lista de objetos guardados en este grupo",
	'groups:widgets:entities:label:displaynum' => 'Lista de objetos de este grupo.',
	'groups:widgets:entities:label:pleaseedit' => 'Por favor configura este widget.',

	'groups:allowhiddengroups' => '&iquest;Desea habilitar los grupos provados?',
	'groups:whocancreate' => '¿Quién puede editar este grupo?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Grupo y contenidos borrados',
	'group:notdeleted' => 'El grupo no pudo ser borrado',

	'group:notfound' => 'No se pudo encontrar el Grupo',
	'groups:deletewarning' => "&iquest;Seguro que deseas borrar este grupo? No se puede deshacer",

	'groups:invitekilled' => 'El invitado ha sido eliminado.',
	'groups:joinrequestkilled' => 'La solicitud ha sido borrada.',
	'groups:error:addedtogroup' => "No ha sido posible añadir a %s al grupo.",
	'groups:add:alreadymember' => "%s ya forma parte del grupo.",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Perfiles de los grupos',
);
