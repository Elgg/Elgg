<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Файл',
	'item:object:file:application' => 'Приложение',
	'item:object:file:archive' => 'Архив',
	'item:object:file:excel' => 'Excel',
	'item:object:file:image' => 'Изображение',
	'item:object:file:music' => 'Музыка',
	'item:object:file:openoffice' => 'OpenOffice',
	'item:object:file:pdf' => 'PDF',
	'item:object:file:ppt' => 'PowerPoint',
	'item:object:file:text' => 'Текст',
	'item:object:file:vcard' => 'vCard',
	'item:object:file:video' => 'Видео',
	'item:object:file:word' => 'Word',
	
	'file:upgrade:2022092801:title' => 'Переместить файлы',
	'file:upgrade:2022092801:description' => 'Перемещает файлы, загруженные с помощью плагина "Файлы", в папку объекта, а не в папку владельца.',
	
	'collection:object:file' => 'Файлы',
	'collection:object:file:all' => "Все файлы",
	'collection:object:file:owner' => "Файлы %s",
	'collection:object:file:friends' => "Файлы друзей",
	'collection:object:file:group' => "Файлы группы",
	'add:object:file' => "Загрузить файл",
	'edit:object:file' => "Изменить файл",
	'notification:object:file:create' => "Отправить уведомление при создании файла",
	'notifications:mute:object:file' => "о файле '%s'",

	'file:more' => "Еще файлы",
	'file:list' => "в виде списка",

	'file:num_files' => "Число отображаемых файлов",
	'file:replace' => 'Заменить файл (оставьте пустым чтобы не менять файл)',
	'file:list:title' => "%s %s %s",

	'file:file' => "Файл",

	'file:list:list' => 'Вид списком',
	'file:list:gallery' => 'Вид галлерея',

	'file:type:' => 'Файлы',
	'file:type:all' => "Все файлы",
	'file:type:video' => "Видео",
	'file:type:document' => "Документы",
	'file:type:audio' => "Аудио",
	'file:type:image' => "Изображения",
	'file:type:general' => "Другие",

	'file:user:type:video' => "Видео %s",
	'file:user:type:document' => "Документы %s",
	'file:user:type:audio' => "Аудио %s",
	'file:user:type:image' => "Изображения %s",
	'file:user:type:general' => "Другие файлы %s",

	'file:friends:type:video' => "Видео ваших друзей",
	'file:friends:type:document' => "Документы ваших друзей",
	'file:friends:type:audio' => "Аудио ваших друзей",
	'file:friends:type:image' => "Изображения ваших друзей",
	'file:friends:type:general' => "Другие файлы ваших друзей",

	'widgets:filerepo:name' => "Файлы",
	'widgets:filerepo:description' => "Ваши последние файлы",

	'groups:tool:file' => 'Разрешить файлы в группе',
	'groups:tool:file:description' => 'Разрешить членам группы обмениваться файлами в этой группе.',

	'river:object:file:create' => '%sзагрузил файл %s',
	'river:object:file:comment' => '%sпрокомментировал файл %s',

	'file:notify:summary' => 'Новый файл: %s',
	'file:notify:subject' => 'Новый файл: %s',
	'file:notify:body' => '%s загрузил новый файл: %s

%s

Просмотреть и комментировать файл:
%s',
	
	'notification:mentions:object:file:subject' => '%s упомянул вас в файле',

	/**
	 * Status messages
	 */

	'file:saved' => "Файл успешно сохранен.",
	'entity:delete:object:file:success' => "Файл успешно удален.",

	/**
	 * Error messages
	 */

	'file:none' => "Файлов нет.",
	'file:uploadfailed' => "Не удается сохранить файл.",
	'file:noaccess' => "У Вас нет прав для изменения этого файла.",
	'file:cannotload' => "Ошибка загрузки файла",
);
