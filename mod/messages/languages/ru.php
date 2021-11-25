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

	'messages' => "Сообщения",
	'messages:unreadcount' => "%s непрочитанных",
	'messages:user' => "Входящие %s",
	'messages:inbox' => "Входящие",
	'messages:sent' => "Отравленные",
	'messages:message' => "Сообщение",
	'messages:title' => "Тема",
	'messages:to:help' => "Напишите здесь имя пользователя получателя.",
	'messages:inbox' => "Входящие",
	'messages:sendmessage' => "Отправить сообщение",
	'messages:add' => "Написать сообщение",
	'messages:sentmessages' => "Отравленные сообщения",
	'messages:toggle' => 'Отметить все',
	'messages:markread' => 'Отметить прочитанными',

	'notification:method:site' => 'Сайт',

	'messages:error' => 'Возникла проблема с сохранением вашего сообщения. Попробуйте еще раз.',

	'item:object:messages' => 'Сообщение',
	'collection:object:messages' => 'Сообщения',

	/**
	* Status messages
	*/

	'messages:posted' => "Ваше сообщение успешно отправлено.",
	'messages:success:delete' => 'Сообщения удалены',
	'messages:success:read' => 'Сообщения отмечены прочитанными',
	'messages:error:messages_not_selected' => 'Не выбраны сообщения',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'У вас новое сообщение',
	'messages:email:body' => "У вас новое сообщение от %s.

В нем говорится:

%s

Нажмите здесь, чтобы просмотреть сообщение:
%s

Нажмите здесь, чтобы отправить сообщение %s:
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "Вам нужно написать что-то в сообщении, прежде чем мы сможем его сохранить.",
	'messages:nomessages' => "Сообщений нет.",
	'messages:user:nonexist' => "Мы не смогли найти получателя в базе данных пользователей.",
	'messages:user:blank' => "Вы не выбрали, кому отправить это сообщение.",
	'messages:user:self' => "Вы не можете отправить сообщение самому себе.",
	'messages:user:notfriend' => "Вы не можете отправить сообщение пользователю, который не является вашим другом.",

	'messages:deleted_sender' => 'Пользователя не существует',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'Сообщения можно отправлять только друзьям',
	'messages:settings:friends_only:help' => 'Пользователь не сможет отправить сообщение, если получатель не является его другом',

);
