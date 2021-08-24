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
	'collection:object:page:all' => "Все страницы",
	'collection:object:page:owner' => "Страницы %s",
	'collection:object:page:friends' => "Страницы друзей",
	'collection:object:page:group' => "Страницы группы",
	'add:object:page' => "Добавить страницу",
	'edit:object:page' => "Изменить страницу",
	'notification:object:page:create' => "Отправить уведомление при создании страницы",
	'notifications:mute:object:page' => "о странице '%s'",

	'groups:tool:pages' => 'Включить страницы группы',
	
	'annotation:delete:page:success' => 'Редакция страницы была удалена.',
	'annotation:delete:page:fail' => 'Редакция страницы не может быть удалена.',

	'pages:history' => "Архив",
	'pages:revision' => "Редакция",

	'pages:navigation' => "Навигация",

	'pages:notify:summary' => 'Новая страница с названием %s',
	'pages:notify:subject' => "Новая страница: %s",
	'pages:notify:body' => '%s добавил новую страницу: %s

%s

Просмотреть и комментировать страницу:
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

	'pages:title' => 'Название страницы',
	'pages:description' => 'Содержимое страницы',
	'pages:tags' => 'Теги',
	'pages:parent_guid' => 'Родительская страница',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Вы не можете изменить эту страницу',
	'pages:saved' => 'Страница сохранена',
	'pages:notsaved' => 'Страница не может быть сохранена',
	'pages:error:no_title' => 'Вы должны указать название страницы.',
	'entity:delete:object:page:success' => 'Страница успешно удалена.',

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

	'pages:newchild' => "Создать под-страницу",
);
