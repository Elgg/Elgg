<?php
return array(
	'item:object:blog' => 'Блогови',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'All site blogs',
	'collection:object:blog:owner' => '%s\'s blogs',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Add blog post',
	'edit:object:blog' => 'Edit blog post',

	'blog:revisions' => 'Ревизије',
	'blog:archives' => 'Архива',

	'groups:tool:blog' => 'Enable group blog',
	'blog:write' => 'Напиши блог чланак',

	// Editing
	'blog:excerpt' => 'Издвојено',
	'blog:body' => 'Текст',
	'blog:save_status' => 'Задњи пут сачуваноЧ',

	'blog:revision' => 'Ревизија',
	'blog:auto_saved_revision' => 'Аутоматски сачувана ревизија',

	// messages
	'blog:message:saved' => 'Блог чланак је сачуван.',
	'blog:error:cannot_save' => 'Није успело чување чланка.',
	'blog:error:cannot_auto_save' => 'Није успело аутоматско чување чланка.',
	'blog:error:cannot_write_to_container' => 'Недовољне привилегије да би чланак био сачуван.',
	'blog:messages:warning:draft' => 'Постоји несачуван нацрт овог чланка!',
	'blog:edit_revision_notice' => '(Стара верзија)',
	'blog:message:deleted_post' => 'Чланак је обрисан.',
	'blog:error:cannot_delete_post' => 'Није успело брисање чланка.',
	'blog:none' => 'Нема чланака.',
	'blog:error:missing:title' => 'Унесите наслов чланка.',
	'blog:error:missing:description' => 'Унесите текст вашег блога.',
	'blog:error:cannot_edit_post' => 'Овај чланак мозда не постоји, а можда ви немате привилегије да га уређујете.',
	'blog:error:post_not_found' => 'Не могу да нађем тражени чланак.',
	'blog:error:revision_not_found' => 'Не могу да нађем ову ревизију.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Нови блог чланак %s',
	'blog:notify:subject' => 'Нови блог чланак: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Display your latest blog posts',
	'blog:moreblogs' => 'Више блог чланака',
	'blog:numbertodisplay' => 'Број блог чланака за приказ',
);
