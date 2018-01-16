<?php

return array(
	'discussion' => 'Discusiones',
	'discussion:add' => 'Añadir tema de discusión',
	'discussion:latest' => 'Últimas discusiones',
	'discussion:group' => 'Discusiones de grupo',
	'discussion:none' => 'No hay discusiones',
	'discussion:reply:title' => 'Respuesta por %s',
	'discussion:new' => "Añadir un tema de discusión",
	'discussion:updated' => "Última respuesta por %s %s",

	'discussion:topic:created' => 'Se ha creado el tema de discusión',
	'discussion:topic:updated' => 'Se ha actualizado el tema de discusión',
	'discussion:topic:deleted' => 'Se ha borrado el tema de discusión',

	'discussion:topic:notfound' => 'No se ha encontrado el tema de discusión',
	'discussion:error:notsaved' => 'No se ha podido guardar este tema',
	'discussion:error:missing' => 'El título y el mensaje son campos necesarios',
	'discussion:error:permissions' => 'No tienes permiso para hacer eso',
	'discussion:error:notdeleted' => 'No se ha podido borrar el tema de discusión',

	'discussion:reply:edit' => 'Editar respuesta',
	'discussion:reply:deleted' => 'Se ha borrado la respuesta',
	'discussion:reply:error:notfound' => 'No se ha encontrado la respuesta',
	'discussion:reply:error:notfound_fallback' => "Lo sentimos. No hemos podido encontrar la respuesta especificada, pero te hemos redirigido al tema original.",
	'discussion:reply:error:notdeleted' => 'No se ha podido borrar la respuesta',

	'discussion:search:title' => 'Respuesta al tema: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'No puedes enviar una respuesta vacía',
	'discussion:reply:topic_not_found' => 'No se ha encontrado el tema de discusión',
	'discussion:reply:error:cannot_edit' => 'No tienes permisos para editar esta respuesta',
	'discussion:reply:error:permissions' => 'No se te permite responder a este tema',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s añadió un nuevo tema de discusión %s',
	'river:reply:object:discussion' => '%s respondió al tema de discusión %s',
	'river:reply:view' => 'ver respuesta',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nuevo tema de discusión llamado %s',
	'discussion:topic:notify:subject' => 'Nuevo tema de discusión: %s',
	'discussion:topic:notify:body' =>
'%s añadió un nuevo tema de discusión "%s":

%s

Ver y responder al tema de discusión:
%s
',

	'discussion:reply:notify:summary' => 'Nueva respuesta en el tema: %s',
	'discussion:reply:notify:subject' => 'Nueva respuesta en el tema: %s',
	'discussion:reply:notify:body' =>
'%s respondió al tema de discusión "%s":

%s

Ver y responder a la discusión:
%s
',

	'item:object:discussion' => "Temas de discusión",
	'item:object:discussion_reply' => "Respuestas en discusiones",

	'groups:enableforum' => 'Habilitar discusiones de grupo',

	'reply:this' => 'Responder a esto',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Discusiones de grupo',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Estado del tema',
	'discussion:topic:closed:title' => 'Esta discusión está cerrada.',
	'discussion:topic:closed:desc' => 'Esta discusión está cerrada y no se aceptan nuevos comentarios.',

	'discussion:replies' => 'Respuestas',
	'discussion:addtopic' => 'Añadir un tema',
	'discussion:post:success' => 'Tu respuesta se ha publicado',
	'discussion:post:failure' => 'Ha habido un problema publicando tu respuesta',
	'discussion:topic:edit' => 'Editar tema',
	'discussion:topic:description' => 'Mensaje del tema',

	'discussion:reply:edited' => "Has editado la publicación del foro correctamente.",
	'discussion:reply:error' => "Ha habido un problema editando la publicación del foro.",
);
