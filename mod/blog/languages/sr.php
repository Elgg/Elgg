<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Блогови',
	'collection:object:blog' => 'Блогови',
	'collection:object:blog:all' => 'Сви блогови',
	'collection:object:blog:owner' => '%s\'s блог',
	'collection:object:blog:friends' => 'Блогови пријатеља',
	'add:object:blog' => 'Додај блог чланак',
	'edit:object:blog' => 'Уреди блог чланак',

	'blog:revisions' => 'Ревизије',
	'blog:archives' => 'Архива',

	'groups:tool:blog' => 'Укључи блог групе',

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
	'blog:none' => 'Нема чланака.',
	'blog:error:missing:title' => 'Унесите наслов чланка.',
	'blog:error:missing:description' => 'Унесите текст вашег блога.',
	'blog:error:post_not_found' => 'Не могу да нађем тражени чланак.',
	'blog:error:revision_not_found' => 'Не могу да нађем ову ревизију.',

	// river

	// notifications
	'blog:notify:summary' => 'Нови блог чланак %s',
	'blog:notify:subject' => 'Нови блог чланак: %s',

	// widget
	'widgets:blog:description' => 'Прикажи твоје најновије блог чланке',
	'blog:moreblogs' => 'Више блог чланака',
	'blog:numbertodisplay' => 'Број блог чланака за приказ',
);
