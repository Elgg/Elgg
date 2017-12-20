<?php

return array(
	'discussion' => 'Debats',
	'discussion:add' => 'Afegir tema de debat',
	'discussion:latest' => 'Darrers debats',
	'discussion:group' => 'Grups de debat',
	'discussion:none' => 'No hi ha debats',
	'discussion:reply:title' => 'Respost per %s',
	'discussion:new' => "Afegir article de debat",
	'discussion:updated' => "Darrera resposta feta per %s %s",

	'discussion:topic:created' => 'El tema de debat s\'ha creat',
	'discussion:topic:updated' => 'El tema de debat s\'ha actualitzat',
	'discussion:topic:deleted' => 'El tema de debat s\'ha esborrat',

	'discussion:topic:notfound' => 'No s\'ha trobat el tema de debat',
	'discussion:error:notsaved' => 'No ha estat possible desar aquest tema',
	'discussion:error:missing' => 'Tant el de títol com el de missatge són camps obligatoris',
	'discussion:error:permissions' => 'No tens permisos per a realitzar aquesta acció',
	'discussion:error:notdeleted' => 'No s\'ha pogut esborrar el tema de debat',

	'discussion:reply:edit' => 'Edita la resposta',
	'discussion:reply:deleted' => 'La resposta al debat ha estat esborrada',
	'discussion:reply:error:notfound' => 'La resposta al debat no s\'ha trobat',
	'discussion:reply:error:notfound_fallback' => "Perdona, no hem pogut trobar la resposta que buscaves, però et redirigim al tema de debat original.",
	'discussion:reply:error:notdeleted' => 'No es pot esborrar la resposta al debat',

	'discussion:search:title' => 'Resposta al tema: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'No pots publicar una resposta en blanc',
	'discussion:reply:topic_not_found' => 'No s\'ha trobat el tema de debat',
	'discussion:reply:error:cannot_edit' => 'No tens permís per a editar aquesta resposta',
	'discussion:reply:error:permissions' => 'No tens autorització per a respondre a aquest tema',

	/**
	 * River
	 */
	'river:create:object:discussion' => 'En/Na %s ha afegit un nou tema de debat: %s',
	'river:reply:object:discussion' => 'En/Na %s ha respost sobre el tema de debat: %s',
	'river:reply:view' => 'veure la resposta',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nou tema de debat, anomenat %s',
	'discussion:topic:notify:subject' => 'Nou tema de debat: %s',
	'discussion:topic:notify:body' =>
'%s added a new discussion topic "%s":

%s

View and reply to the discussion topic:
%s
',

	'discussion:reply:notify:summary' => 'New reply in topic: %s',
	'discussion:reply:notify:subject' => 'New reply in topic: %s',
	'discussion:reply:notify:body' =>
'%s replied to the discussion topic "%s":

%s

View and reply to the discussion:
%s
',

	'item:object:discussion' => "Discussion topics",
	'item:object:discussion_reply' => "Discussion replies",

	'groups:enableforum' => 'Enable group discussions',

	'reply:this' => 'Reply to this',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Group Discussions',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Topic status',
	'discussion:topic:closed:title' => 'This discussion is closed.',
	'discussion:topic:closed:desc' => 'This discussion is closed and is not accepting new comments.',

	'discussion:replies' => 'Replies',
	'discussion:addtopic' => 'Add a topic',
	'discussion:post:success' => 'Your reply was succesfully posted',
	'discussion:post:failure' => 'There was problem while posting your reply',
	'discussion:topic:edit' => 'Edit topic',
	'discussion:topic:description' => 'Topic message',

	'discussion:reply:edited' => "You have successfully edited the forum post.",
	'discussion:reply:error' => "There was a problem editing the forum post.",
);
