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

	'item:object:page' => 'Страницы',
	'collection:object:page' => 'Страницы',
	'collection:object:page:all' => "Все страницы сайта",
	'collection:object:page:owner' => "Страницы пользователя %s",
	'collection:object:page:friends' => "Страницы друзей",
	'collection:object:page:group' => "Страницы группы",
	'add:object:page' => "Добавить документ",
	'edit:object:page' => "Изменить страницу",
	'notification:object:page:create' => "Отправить уведомление при создании страницы",
	'notifications:mute:object:page' => "о странице '%s'",

	'groups:tool:pages' => 'Включить страницы группы',
	
	'annotation:delete:page:success' => 'Редакция страницы была удалена.',
	'annotation:delete:page:fail' => 'Редакция страницы не может быть удалена.',

	'pages:delete' => "Удалить страницу",
	'pages:history' => "Архив",
	'pages:view' => "Просмотр страницы",
	'pages:revision' => "Редакция",

	'pages:navigation' => "Навигация",

	'pages:notify:summary' => 'Новая страница с названием %s',
	'pages:notify:subject' => "Новая страница: %s",
	'pages:notify:body' =>
'%s добавил новую страницу: %s

%s

Просмотр и комментирование страницы:
%s',

	'pages:more' => 'Больше страниц',
	'pages:none' => 'Страниц пока нет',

	/**
	* River
	**/

	'river:object:page:create' => '%s создал страницу %s',
	'river:object:page:update' => '%s обновил страницу %s',
	'river:object:page:comment' => '%s прокомментировал на странице %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Заголовок документа',
	'pages:description' => 'Содержимое документа',
	'pages:tags' => 'Теги',
	'pages:parent_guid' => 'Родительская страница',
	'pages:access_id' => 'Доступ на чтение',
	'pages:write_access_id' => 'Доступ на запись',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Вы не можете изменить эту страницу',
	'pages:saved' => 'Страница сохранена',
	'pages:notsaved' => 'Страница не может быть сохранена',
	'pages:error:no_title' => 'Вы должны указать название страницы.',
	'entity:delete:object:page:success' => 'Страница успешно удалена.',
	'pages:revision:delete:success' => 'Редакция страницы была удалена.',
	'pages:revision:delete:failure' => 'Редакция страницы не может быть удалена.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Редакция создана %s пользователем %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Число отображаемых страниц',
	'widgets:pages:name' => 'Страницы',
	'widgets:pages:description' => "Это список Ваших страниц.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Просмотр страницы",
	'pages:label:edit' => "Изменить страницу",
	'pages:label:history' => "История сохранений страницы",

	'pages:newchild' => "Создать под-страницу",
);
