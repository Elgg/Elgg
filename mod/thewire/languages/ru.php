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
	'thewire' => "Микроблог",

	'item:object:thewire' => "Микроблог",
	'collection:object:thewire' => 'Wire posts',
	'collection:object:thewire:all' => "Все записи микроблога",
	'collection:object:thewire:owner' => "Микроблог пользователя %s",
	'collection:object:thewire:friends' => "Микроблоги друзей",

	'thewire:replying' => "Ответить %s (@%s), который писал(а) в своем микроблоге",
	'thewire:thread' => "Микроблог пользователя",
	'thewire:charleft' => "символов осталось",
	'thewire:tags' => "Сообщения микроблога с тегом '%s'",
	'thewire:noposts' => "В микроблоге нет постов",

	'thewire:by' => 'Запись пользователя %s',
	'thewire:previous:help' => "Посмотреть первый пост",
	'thewire:hide:help' => "Скрыть первый пост",

	'thewire:form:body:placeholder' => "What's happening?",
	
	/**
	 * The wire river
	 */
	'river:object:thewire:create' => "%s posted to %s",
	'thewire:wire' => 'микроблог',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'Показать ваши последние записи',
	'thewire:num' => 'Кол-во записей',
	'thewire:moreposts' => 'Показать больше',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Запись добавлена.",
	'thewire:deleted' => "Запись удалена.",
	'thewire:blank' => "Ну ты же ничего не ввел дружище.",
	'thewire:notsaved' => "Не могу сохранить запись.",
	'thewire:notdeleted' => "Не могу удалить запись.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'Новая запись микроблога: %s',
	'thewire:notify:subject' => "Новая запись от %s",
	'thewire:notify:reply' => '%s ответил(а) %s в микроблоге:',
	'thewire:notify:post' => '%s написал(а) в микроблоге:',
	'thewire:notify:footer' => "Просмотреть и ответить:\n%s",

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Максимальное количество символов сообщения:",
	'thewire:settings:limit:none' => "Без ограничения",
);
