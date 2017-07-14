<?php

return [
	'discussion' => 'Discusiones',
	'discussion:add' => 'Agregar tema de discusión',
	'discussion:latest' => 'Últimas discusiones',
	'discussion:group' => 'Discusiones de grupo',
	'discussion:none' => 'No hay discusiones',
	'discussion:reply:title' => 'Respuesta a %s',
	'discussion:new' => "Agregar un tema de discusión",
	'discussion:updated' => "Última respuesta de %s %s",

	'discussion:topic:created' => 'El tema de discusión fue creado.',
	'discussion:topic:updated' => 'El tema de discusión fue actualizado.',
	'discussion:topic:deleted' => 'El tema de discusión fue eliminado',

	'discussion:topic:notfound' => 'Tema de discusión no encontrado',
	'discussion:error:notsaved' => 'No es posible guardar este tema',
	'discussion:error:missing' => 'Debes completar el campo de título y de mensaje',
	'discussion:error:permissions' => 'No tienes permiso para realizar esta acción',
	'discussion:error:notdeleted' => 'No puede eliminar este tema de discusión',

	'discussion:reply:edit' => 'Editar respuesta',
	'discussion:reply:deleted' => 'La respuesta fue eliminado.',
	'discussion:reply:error:notfound' => 'Respuesta no encontrada ',
	'discussion:reply:error:notfound_fallback' => "Lo siento, no pudimos encontrar la respuesta especificada, pero te hemos redirigido al tema de discusión original.",
	'discussion:reply:error:notdeleted' => 'No es posible eliminar la respuesta',

	'discussion:search:title' => 'Respuestas en el tema: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'No puedes publicar una respuesta vacía',
	'discussion:reply:topic_not_found' => 'El tema de discusión no fue encontrado',
	'discussion:reply:error:cannot_edit' => 'No puedes editar la respuesta',
	'discussion:reply:error:permissions' => 'No tienes permiso para editar la respuesta',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s agregó una nueva discusión con el tema %s',
	'river:reply:object:discussion' => '%s respondió en el tema de discusión  %s',
	'river:reply:view' => 'view reply',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nuevo tema de discusión llamado %s',
	'discussion:topic:notify:subject' => 'Nuevo tema de discusión: %s',
	'discussion:topic:notify:body' =>
'%s creó un nueva discusión con el tema "%s":

%s

Ver y responder al tema de discusión:
%s
',

	'discussion:reply:notify:summary' => 'Nueva respuesta en el tema: %s',
	'discussion:reply:notify:subject' => 'Nueva respuesta en el tema: %s',
	'discussion:reply:notify:body' =>
'%s respondió en el tema de discusión "%s":

%s

Ver y responder al tema de discusión:

%s
',

	'item:object:discussion' => "Temas de discusión",
	'item:object:discussion_reply' => "Respuestas a la discusión",

	'groups:enableforum' => 'Habilitar discusiones en el grupo',

	'reply:this' => 'Responder a esto',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Discusiones del grupo',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Estado del tema',
	'discussion:topic:closed:title' => 'El tema está cerrado.',
	'discussion:topic:closed:desc' => 'Esta discusión esta cerrada y no es posible publicar mas respuestas.',

	'discussion:replies' => 'Respuestas',
	'discussion:addtopic' => 'Agregar un tema',
	'discussion:post:success' => 'Tu respuesta fue publicada con éxito',
	'discussion:post:failure' => 'Hubo un problema al publicar tu respuesta',
	'discussion:topic:edit' => 'Editar tema',
	'discussion:topic:description' => 'Mensaje del tema',

	'discussion:reply:edited' => "Has editado correctamente la respuesta a la discusión.",
	'discussion:reply:error' => "Hubo un problema al editar la respuesta a la discusión.",
];
