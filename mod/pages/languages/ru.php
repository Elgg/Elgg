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

	'item:object:page' => 'Документы',
	'collection:object:page' => 'Документы',
	'collection:object:page:all' => "Все документы сайта",
	'collection:object:page:owner' => "Документы %s",
	'collection:object:page:friends' => "Документы друзей",
	'collection:object:page:group' => "Документы группы",
	'add:object:page' => "Добавить документ",
	'edit:object:page' => "Редактировать документы",

	'groups:tool:pages' => 'Включить документы группы',
	
	'annotation:delete:page:success' => 'The page revision was successfully deleted',
	'annotation:delete:page:fail' => 'The page revision could not be deleted',

	'pages:delete' => "Удалить документы",
	'pages:history' => "Архив",
	'pages:view' => "Просмотреть документ",
	'pages:revision' => "Версия",

	'pages:navigation' => "Навигация",

	'pages:notify:summary' => 'Новая документ с названием %s',
	'pages:notify:subject' => "Новый документ: %s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => 'Еще',
	'pages:none' => 'Еще нет документов',

	/**
	* River
	**/

	'river:object:page:create' => '%s создал документ %s',
	'river:object:page:update' => '%s обновил документ %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Заголовок документа',
	'pages:description' => 'Содержимое документа',
	'pages:tags' => 'Теги',
	'pages:parent_guid' => 'Родительствий документ',
	'pages:access_id' => 'Доступ',
	'pages:write_access_id' => 'Доступ на запись',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Вы не можете редактировать этот документ.',
	'pages:saved' => 'Документ сохранен.',
	'pages:notsaved' => 'Документ не может быть сохранен.',
	'pages:error:no_title' => 'Вы должны указать название документа.',
	'entity:delete:object:page:success' => 'Ваш документ успешно удален.',
	'pages:revision:delete:success' => 'Версия документа была удалена.',
	'pages:revision:delete:failure' => 'Версия документа не может быть удалена.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Версия создана %s пользователем %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Число отображаемых документов',
	'widgets:pages:name' => 'Документы',
	'widgets:pages:description' => "Это список Ваших документов.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Просмотр",
	'pages:label:edit' => "Редактировать",
	'pages:label:history' => "История",

	'pages:newchild' => "Создать дочерний документ",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
