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
	'item:object:bookmarks' => 'Закладки',
	'collection:object:bookmarks' => 'Закладки',
	'collection:object:bookmarks:group' => 'Закладки группы',
	'collection:object:bookmarks:all' => "Все закладки",
	'collection:object:bookmarks:owner' => "Закладки %s",
	'collection:object:bookmarks:friends' => "Закладки друзей",
	'add:object:bookmarks' => "Добавить в закладки",
	'edit:object:bookmarks' => "Изменить закладку",
	'notification:object:bookmarks:create' => "Отправить уведомление при создании закладки",
	'notifications:mute:object:bookmarks' => "о закладке '%s'",

	'bookmarks:this' => "Добавить в закладки",
	'bookmarks:this:group' => "Добавить в закладки в %s",
	'bookmarks:bookmarklet' => "Закладки",
	'bookmarks:bookmarklet:group' => "Закладки группы",
	'bookmarks:address' => "Адрес сайта",
	'bookmarks:none' => 'Нет закладок',

	'bookmarks:notify:summary' => 'Новая закладка %s',
	'bookmarks:notify:subject' => 'Новая закладка: %s',
	'bookmarks:notify:body' => '%s добавил закладку: %s

Ссылка: %s

%s

Просмотреть и комментировать закладку:
%s
',

	'bookmarks:numbertodisplay' => 'Число отображаемых закладок',

	'river:object:bookmarks:create' => '%s добавил в закладки %s',
	'river:object:bookmarks:comment' => '%s прокомментировал закладку %s',

	'groups:tool:bookmarks' => 'Включить закладки группы',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Закладки',
	'widgets:bookmarks:description' => "Показать ваши последние закладки.",

	'bookmarks:bookmarklet:description' => "Закладки - это специальная кнопка для сохранения ссылок в вашем браузере. Это позволяет вам сохранить любую ссылку, чтобы потом переходить по ней на сайт. Перетащите кнопку на панель вашего браузера:",
	'bookmarks:bookmarklet:descriptionie' => "Если Вы используете Internet Explorer, Вам нужно нажать правой кнопкой мыши на значок закладок, выбрать 'Добавить в избранное', а затем на адресную строку.",
	'bookmarks:bookmarklet:description:conclusion' => "Вы можете сохранить любую страницу в любое время.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Закладка добавлена.",
	'entity:delete:object:bookmarks:success' => "Закладка удалена.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Ваша закладка не может быть сохранена. Проверьте название, адрес и попробуйте снова.",
);
