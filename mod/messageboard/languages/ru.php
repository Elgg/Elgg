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

	'messageboard:board' => "Доска",
	'messageboard:messageboard' => "доска",
	'messageboard:none' => "Пока никто не написал на стене.",
	'messageboard:num_display' => "Число отображаемых записей",
	'messageboard:user' => "стену пользователя %s",
	'messageboard:owner' => '%s\'s message board',
	'messageboard:owner_history' => '%s\'s posts on %s\'s message board',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Запись на стене размещена.",
	'messageboard:deleted' => "Запись удалена.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'У Вас есть новая запись на стене.',
	'messageboard:email:body' => "You have a new message board comment from %s.

It reads:

%s

To view your message board comments, click here:
%s

To view %s's profile, click here:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Простите, но Вы должны что-нибудь написать перед размещением записи.",
	'messageboard:notdeleted' => "Простите, удаление невозможно.",

	'messageboard:failure' => "Простите, при размещении записи произошла ошибка. Попробуйте снова.",

	'widgets:messageboard:name' => "Доска",
	'widgets:messageboard:description' => "Это стена, которую можно добавить в Ваш профиль и на которой смогут писать Ваши друзья.",
);
