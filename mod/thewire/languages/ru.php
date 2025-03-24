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
	'collection:object:thewire:mentions' => "Публикации с упоминанием @%s",
	'notification:object:thewire:create' => "Отправить уведомление при создании публикации в ленте",
	'notifications:mute:object:thewire' => "о публикации в ленте '%s'",
	
	'entity:edit:object:thewire:success' => 'Публикация сохранена',

	'thewire:menu:filter:mentions' => "Упоминания",
	
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
	'thewire:blank' => "Введите текст перед постом.",
	'thewire:notsaved' => "Не удается сохранить пост в ленте.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'Новый пост в ленте: %s',
	'thewire:notify:subject' => "Новый пост в ленте от %s",
	'thewire:notify:reply' => '%s ответил %s в ленте:',
	'thewire:notify:post' => '%s опубликовал в ленте:',
	'thewire:notify:footer' => "Просмотреть и ответить:\n%s",
	
	'notification:mentions:object:thewire:subject' => '%s упомянул вас в посте',

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Максимальное количество символов поста:",
	'thewire:settings:limit:none' => "Без ограничения",
	
	/**
	 * Exceptions
	 */
	'ValidationException:thewire:limit' => "Длина поста превышает установленный предел",
);
