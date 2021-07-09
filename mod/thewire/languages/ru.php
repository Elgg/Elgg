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
	'thewire' => "Лента",

	'item:object:thewire' => "Публикация в ленте",
	'collection:object:thewire' => 'Публикации в ленте',
	'collection:object:thewire:all' => "Все публикации в ленте",
	'collection:object:thewire:owner' => "Публикации в ленте пользователя %s",
	'collection:object:thewire:friends' => "Публикации в лентах друзей",
	'notification:object:thewire:create' => "Отправить уведомление при создании публикации в ленте",
	'notifications:mute:object:thewire' => "о публикации в ленте '%s'",

	'thewire:replying' => "Ответить пользователю %s (@%s), который написал",
	'thewire:thread' => "Ветка",
	'thewire:charleft' => "символов осталось",
	'thewire:tags' => "Публикации ленты с тегом '%s'",
	'thewire:noposts' => "В ленте нет публикаций",

	'thewire:by' => 'Публикация пользователя %s',

	'thewire:form:body:placeholder' => "Что происходит?",
	
	/**
	 * The wire river
	 */
	'river:object:thewire:create' => "%s опубликовано в %s",
	'thewire:wire' => 'лента',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'Показать ваши последние публикации в ленте',
	'thewire:num' => 'Количество публикаций для отображения',
	'thewire:moreposts' => 'Больше публикаций',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Опубликовано в ленте.",
	'thewire:deleted' => "Публикация удалена.",
	'thewire:blank' => "Введите текст перед публикацией.",
	'thewire:notsaved' => "Не удается сохранить публикацию в ленте.",
	'thewire:notdeleted' => "Не удается удалить публикацию.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'Новая публикация в ленте: %s',
	'thewire:notify:subject' => "Новая публикация в ленте от %s",
	'thewire:notify:reply' => '%s ответил %s в ленте:',
	'thewire:notify:post' => '%s опубликовал в ленте:',
	'thewire:notify:footer' => "Просмотреть и ответить:\n%s",

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Максимальное количество символов сообщения:",
	'thewire:settings:limit:none' => "Без ограничения",
);
