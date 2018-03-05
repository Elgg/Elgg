<?php

return array(
	'add:object:discussion' => 'Добавить тему',
	'edit:object:discussion' => 'Изменить тему',

	'discussion:latest' => 'Последние обсуждения',
	'collection:object:discussion:group' => 'Групповые обсуждения',
	'discussion:none' => 'Нет обсуждений',
	'discussion:updated' => "Последний комментарий%s %s",

	'discussion:topic:created' => 'Тема обсуждения создана.',
	'discussion:topic:updated' => 'Тема обсуждения обновлена.',
	'entity:delete:object:discussion:success' => 'Тема обсуждения удалена.',

	'discussion:topic:notfound' => 'Тема обсуждения не найдена',
	'discussion:error:notsaved' => 'Не могу сохранить эту тему',
	'discussion:error:missing' => 'Оба поля заголовок и сообщение являются обязательными',
	'discussion:error:permissions' => 'У Вас нет разрешений выполнять это действие',

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s добавил новую тему для обсуждения %s',
	'river:object:discussion:comment' => '%s прокомментировал обсуждаемую тему  %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Новая тема для обсуждения называется %s',
	'discussion:topic:notify:subject' => 'Новая тема для обсуждения: %s',
	'discussion:topic:notify:body' =>
'%s добавил новую тему для обсуждения "%s":

%s

Просмотреть и ответить на обсуждаемую тему:
%s
',

	'discussion:comment:notify:summary' => 'Новый комментарий в теме: %s',
	'discussion:comment:notify:subject' => 'Новый комментарий в теме: %s',
	'discussion:comment:notify:body' =>
'%s прокомментировал тему "%s":

%s

Просмотреть обсуждение и прокомментировать:
%s
',

	'item:object:discussion' => "Тема обсуждения",
	'collection:object:discussion' => 'Темы обсуждения',

	'groups:tool:forum' => 'Включить групповые обсуждения',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Статус темы',
	'discussion:topic:closed:title' => 'Обсуждение закрыто.',
	'discussion:topic:closed:desc' => 'Обсуждение закрыто и не принимает новые комментарии.',

	'discussion:topic:description' => 'Сообщение темы',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Перенести реплики обсуждения в комментарии",
	'discussions:upgrade:2017112800:description' => "Реплики обсуждения, использовали свой подтип, это было переведено к одному типу в комментарии.",
	'discussions:upgrade:2017112801:title' => "Перенести поток активности связанной с обсуждаемыми репликами",
	'discussions:upgrade:2017112801:description' => "Discussion replies used to have their own subtype, this has been unified into comments.",
);
