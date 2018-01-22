<?php
return array(
	'item:object:blog' => 'Блог',
	'collection:object:blog' => 'Блоги',
	'collection:object:blog:all' => 'Все блоги',
	'collection:object:blog:owner' => '%s\'s блог',
	'collection:object:blog:group' => 'Блог группы',
	'collection:object:blog:friends' => 'Блоги друзей',
	'add:object:blog' => 'Написать сообщение',
	'edit:object:blog' => 'Изменить',

	'blog:revisions' => 'Редакции',
	'blog:archives' => 'Архив',

	'groups:tool:blog' => 'Включить блог группы',
	'blog:write' => 'Написать сообщение',

	// Editing
	'blog:excerpt' => 'Краткое описание',
	'blog:body' => 'Сообщение',
	'blog:save_status' => 'Сохранено: ',

	'blog:revision' => 'Редакция',
	'blog:auto_saved_revision' => 'Автосохраненная редакция',

	// messages
	'blog:message:saved' => 'Сохранено.',
	'blog:error:cannot_save' => 'Не могу сохранить сообщение.',
	'blog:error:cannot_auto_save' => 'Не могу автоматически сохранить.',
	'blog:error:cannot_write_to_container' => 'Нехватает прав для сохранения блога.',
	'blog:messages:warning:draft' => 'Это не сохраненный черновик сообщения!',
	'blog:edit_revision_notice' => '(Старая версия)',
	'blog:message:deleted_post' => 'Сообщение удалено.',
	'blog:error:cannot_delete_post' => 'Не могу удалить сообщение.',
	'blog:none' => 'Нет записей в блоге',
	'blog:error:missing:title' => 'Пожалуйста, введите название!',
	'blog:error:missing:description' => 'Пожалуйста, заполните сообщение!',
	'blog:error:cannot_edit_post' => 'Извините, сообщение не существует или Вы не имеете прав для его редактирования.',
	'blog:error:post_not_found' => 'Не удается найти указанную запись в блоге.',
	'blog:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'river:object:blog:create' => '%s опубликовал новую запись %s',
	'river:object:blog:comment' => '%s оставил(а) комментарий под записью %s',

	// notifications
	'blog:notify:summary' => 'Новая запись блога %s',
	'blog:notify:subject' => 'Новая запись блога: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Показать последние посты',
	'blog:moreblogs' => 'Показать больше постов',
	'blog:numbertodisplay' => 'Число отображаемых постов',
);
