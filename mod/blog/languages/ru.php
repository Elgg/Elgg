<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Блог',
	'collection:object:blog' => 'Блоги',
	'collection:object:blog:all' => 'Все блоги',
	'collection:object:blog:owner' => 'Блоги %s',
	'collection:object:blog:group' => 'Блоги группы',
	'collection:object:blog:friends' => 'Блоги друзей',
	'add:object:blog' => 'Создать публикацию',
	'edit:object:blog' => 'Изменить публикацию',
	'notification:object:blog:publish' => "Отправить уведомление при публикации блога",
	'notifications:mute:object:blog' => "о блоге '%s'",

	'blog:revisions' => 'Редакции',
	'blog:archives' => 'Архив',

	'groups:tool:blog' => 'Включить блог группы',

	// Editing
	'blog:excerpt' => 'Краткое описание',
	'blog:body' => 'Сообщение',
	'blog:save_status' => 'Сохранено: ',

	'blog:revision' => 'Редакция',
	'blog:auto_saved_revision' => 'Автосохраненная редакция',

	// messages
	'blog:message:saved' => 'Публикация сохранена.',
	'blog:error:cannot_save' => 'Не удается сохранить публикацию.',
	'blog:error:cannot_auto_save' => 'Не удается автоматически сохранить публикацию.',
	'blog:error:cannot_write_to_container' => 'Недостаточно прав для сохранения блога в группе.',
	'blog:messages:warning:draft' => 'Это не сохраненный черновик публикации!',
	'blog:edit_revision_notice' => '(Старая версия)',
	'blog:none' => 'Нет публикаций в блоге',
	'blog:error:missing:title' => 'Введите название!',
	'blog:error:missing:description' => 'Заполните сообщение публикации!',
	'blog:error:post_not_found' => 'Не удается найти указанную запись в блоге.',
	'blog:error:revision_not_found' => 'Не удается найти эту версию.',

	// river
	'river:object:blog:create' => '%s опубликовал в блоге %s',
	'river:object:blog:comment' => '%s прокомментировал в блоге %s',

	// notifications
	'blog:notify:summary' => 'Новая запись блога %s',
	'blog:notify:subject' => 'Новая запись блога: %s',
	'blog:notify:body' => '%s опубликовал в блоге: %s

%s

Просмотреть и прокомментировать запись в блоге:
%s',

	// widget
	'widgets:blog:name' => 'Записи блога',
	'widgets:blog:description' => 'Показать последние посты',
	'blog:moreblogs' => 'Показать больше постов',
	'blog:numbertodisplay' => 'Число отображаемых постов',
);
