<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Дискуссия",
	
	'add:object:discussion' => 'Добавить дискуссию',
	'edit:object:discussion' => 'Изменить дискуссию',
	'collection:object:discussion' => 'Дискуссии',
	'collection:object:discussion:group' => 'Дискуссии группы',
	'collection:object:discussion:my_groups' => 'Дискуссии в моих группах',
	'notification:object:discussion:create' => "Отправить уведомление при создании дискуссии",
	'notifications:mute:object:discussion' => "о дискуссии '%s'",
	
	'discussion:settings:enable_global_discussions' => 'Включить глобальные дискуссии',
	'discussion:settings:enable_global_discussions:help' => 'Разрешить создавать дискуссии вне групп',

	'discussion:latest' => 'Недавние дискуссии',
	'discussion:none' => 'Нет дискуссий',
	'discussion:updated' => "Последний комментарий%s %s",

	'discussion:topic:created' => 'Дискуссия создана.',
	'discussion:topic:updated' => 'Дискуссия обновлена.',
	'entity:delete:object:discussion:success' => 'Дискуссия удалена.',

	'discussion:topic:notfound' => 'Дискуссия не найдена',
	'discussion:error:notsaved' => 'Не удается сохранить эту дискуссию',
	'discussion:error:missing' => 'Оба поля заголовок и тема являются обязательными',
	'discussion:error:permissions' => 'У Вас нет разрешений выполнять это действие',
	'discussion:error:no_groups' => "Вы не являетесь участником ни одной группы",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s добавил новую дискуссию %s',
	'river:object:discussion:comment' => '%s прокомментировал дискуссию  %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Новая дискуссия названа %s',
	'discussion:topic:notify:subject' => 'Новая дискуссия: %s',
	'discussion:topic:notify:body' => '%s добавил новую дискуссию "%s":

%s

Просмотреть и ответить в дискуссии:
%s',

	'discussion:comment:notify:summary' => 'Новый комментарий в дискуссии: %s',
	'discussion:comment:notify:subject' => 'Новый комментарий в дискуссии: %s',
	'discussion:comment:notify:body' => '%s прокомментировал в теме дискуссии "%s":

%s

Просмотр и комментирование дискуссии:
%s',
	
	'notification:mentions:object:discussion:subject' => '%s упомянул вас в дискуссии',

	'groups:tool:forum' => 'Включить дискуссии в группах',
	'groups:tool:forum:description' => 'Разрешить участникам группы начинать дискуссию в этой группе.',

	'discussions:groups:edit:add_group_subscribers_to_discussion_comments' => 'Добавьте подписчика группы в уведомления о комментариях к дискуссии',
	
	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Статус дискуссии',
	'discussion:topic:closed:title' => 'Дискуссия закрыта.',
	'discussion:topic:closed:desc' => 'Дискуссия закрыта и не принимает новые комментарии.',
	'discussion:topic:container' => 'Выберите группу, чтобы начать это обсуждение',
	'discussion:topic:container:help' => 'По умолчанию, начало обсуждения в группе ограничивается доступом членов группы.',

	'discussion:topic:description' => 'Тема дискуссии',
	'discussion:topic:toggle_status:open' => 'Дискуссия снова открыта',
	'discussion:topic:toggle_status:open:confirm' => 'Уверены, что хотите снова открыть дискуссию?',
	'discussion:topic:toggle_status:closed' => 'Дискуссия закрыта',
	'discussion:topic:toggle_status:closed:confirm' => 'Уверены, что хотите закрыть эту дискуссию?',
	
	// widgets
	'widgets:discussions:name' => 'Дискуссии',
	'widgets:discussions:description' => 'Отображает недавние дискуссии',
);
