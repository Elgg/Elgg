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
	'edit:object:bookmarks' => "Редактировать закладку",

	'bookmarks:this' => "Добавить в закладки",
	'bookmarks:this:group' => "Добавить в закладки в %s",
	'bookmarks:bookmarklet' => "Добавить закладки",
	'bookmarks:bookmarklet:group' => "Добавить закладки группы",
	'bookmarks:address' => "Адрес сайта",
	'bookmarks:none' => '---',

	'bookmarks:notify:summary' => 'Новая закладка %s',
	'bookmarks:notify:subject' => 'Новая закладка: %s',
	'bookmarks:notify:body' =>
'%s добавил[а] новую закладку: %s

Адрес: %s

%s

Просмотреть и комментировать по ссылке:
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

	'bookmarks:bookmarklet:description' =>
			"A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks. To set it up, drag the button below to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Если Вы используете Internet Explorer, Вам нужно нажать правой кнопкой мыши на значок закладок, выбрать 'Добавить в избранное', а затем на адресную строку.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Вы можете сохранить любую страницу в любое время.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Закладка добавлена.",
	'entity:delete:object:bookmarks:success' => "Закладка удалена.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Простите, Ваша закладка не может быть сохранена. Проверьте название, адрес и попробуйте снова.",
	'bookmarks:unknown_bookmark' => 'Не могу найти указанную закладку',
);
