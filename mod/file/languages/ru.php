<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Файл',
	'collection:object:file' => 'Файлы',
	'collection:object:file:all' => "Файлы всего сайта",
	'collection:object:file:owner' => "Файлы %s",
	'collection:object:file:friends' => "Файлы друга",
	'collection:object:file:group' => "Сгруппировать файлы",
	'add:object:file' => "Загрузить файл",
	'edit:object:file' => "Редактировать файл",
	'notification:object:file:create' => "Отправить уведомление при создании файла",
	'notifications:mute:object:file' => "о файле '%s'",

	'file:more' => "Еще файлы",
	'file:list' => "в виде списка",

	'file:num_files' => "Число отображаемых файлов",
	'file:replace' => 'Заменить файл (оставьте пустым чтобы не менять файл)',
	'file:list:title' => "%s's %s %s",
	'file:title:friends' => "Friends'",

	'file:file' => "Файл",

	'file:list:list' => 'Вид списком',
	'file:list:gallery' => 'Вид галлерея',

	'file:types' => "Типы загружаемых файлов",

	'file:type:' => 'Файлы',
	'file:type:all' => "Все файлы",
	'file:type:video' => "Видео",
	'file:type:document' => "Документы",
	'file:type:audio' => "Аудио",
	'file:type:image' => "Изображения",
	'file:type:general' => "Основные файлы",

	'file:user:type:video' => "Видео %s",
	'file:user:type:document' => "Документы %s",
	'file:user:type:audio' => "Аудио %s",
	'file:user:type:image' => "Изображения %s",
	'file:user:type:general' => "Основные файлы %s",

	'file:friends:type:video' => "Видео Ваших друзей",
	'file:friends:type:document' => "Документы Ваших друзей",
	'file:friends:type:audio' => "Аудио Ваших друзей",
	'file:friends:type:image' => "Изображения Ваших друзей",
	'file:friends:type:general' => "Основные файлы Ваших друзей",

	'widgets:filerepo:name' => "Файлы",
	'widgets:filerepo:description' => "Ваши последние файлы",

	'groups:tool:file' => 'Разрешить группировку файлов',

	'river:object:file:create' => '%sзагрузил файл %s',
	'river:object:file:comment' => '%sпрокомментировал файл %s',

	'file:notify:summary' => 'Новый файл: %s',
	'file:notify:subject' => 'Новый файл: %s',
	'file:notify:body' => '%s загрузил новый файл: %s

%s

Просмотр и комментирование файла:
%s',

	/**
	 * Status messages
	 */

	'file:saved' => "Файл успешно сохранен.",
	'entity:delete:object:file:success' => "Файл успешно удален.",

	/**
	 * Error messages
	 */

	'file:none' => "---",
	'file:uploadfailed' => "Простите, файл не сохранен.",
	'file:noaccess' => "У Вас нет прав для изменения этого файла.",
	'file:cannotload' => "Ошибка загрузки файла",
);
