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

	'messageboard:board' => "Стена сообщений",
	'messageboard:none' => "Пока никто не написал на стене.",
	'messageboard:num_display' => "Число отображаемых записей",
	'messageboard:owner' => 'Стена сообщений пользователя %s',
	'messageboard:owner_history' => 'Публикации пользователя %s на стене сообщений пользователя %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s опубликовал на стене сообщений пользователя %s",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Мы не можем удалить это сообщение",
	'annotation:delete:messageboard:success' => "Сообщение успешно удалено",
	
	'messageboard:posted' => "Запись на стене размещена.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'У Вас есть новая запись на стене.',
	'messageboard:email:body' => "У вас новая публикация на стене сообщений от %s.

Текст публикации:

%s

Нажмите здесь, чтобы посмотреть публикации на стене сообщений:
%s

Для просмотра профиля %s нажмите здесь:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Простите, но Вы должны что-нибудь написать перед размещением записи.",

	'messageboard:failure' => "Простите, при размещении записи произошла ошибка. Попробуйте снова.",

	'widgets:messageboard:name' => "Стена сообщений",
	'widgets:messageboard:description' => "Это стена, которую можно добавить в Ваш профиль и на которой смогут писать пользователи.",
);
