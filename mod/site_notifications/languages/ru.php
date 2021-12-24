<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Уведомления сайта',
	'notification:method:site' => 'Сайт',
	'site_notifications:topbar' => 'Уведомления',
	'item:object:site_notification' => 'Уведомление сайта',
	'collection:object:site_notification' => 'Уведомления сайта',

	'site_notifications:unread' => 'Непрочитано',
	'site_notifications:read' => 'Прочитано',
	
	'site_notifications:settings:unread_cleanup_days' => 'Очищать непрочитанные уведомления через х дней',
	'site_notifications:settings:unread_cleanup_days:help' => 'Непрочитанные уведомления будут очищены, спустя указанное число дней. Оставьте незаполненным, чтобы не очищать уведомления.',
	'site_notifications:settings:unread_cleanup_interval' => 'Интервал очистки непрочитанных уведомлений',
	'site_notifications:settings:unread_cleanup_interval:help' => 'Как часто следует очищать непрочитанные уведомления. На сайтах с высокой активностью вы можете увеличить интервал, чтобы не пропустить новые уведомления. ',
	'site_notifications:settings:read_cleanup_days' => 'Очищать прочитанные уведомления через х дней',
	'site_notifications:settings:read_cleanup_days:help' => 'Прочитанные уведомления будут очищены, спустя указанное число дней. Оставьте незаполненным, чтобы не очищать уведомления.',
	'site_notifications:settings:read_cleanup_interval' => 'Интервал очистки прочитанных уведомлений',
	'site_notifications:settings:read_cleanup_interval:help' => 'Как часто следует очищать прочитанные уведомления. На сайтах с высокой активностью вы можете увеличить интервал, чтобы не пропустить новые уведомления. ',
	
	'site_notifications:empty' => 'Нет уведомлений',
	'site_notifications:toggle_all' => 'Отметить все',
	'site_notifications:mark_read' => 'Отметить прочитанным',
	'site_notifications:mark_read:confirm' => 'Уверены, что хотите отметить все выбранные уведомления как прочитанные?',
	'site_notifications:delete:confirm' => 'Уверены, что хотите удалить все выбранные уведомления?',
	'site_notifications:error:notifications_not_selected' => 'Уведомления не выбраны',
	'site_notifications:success:delete' => 'Уведомления удалены',
	'site_notifications:success:mark_read' => 'Уведомления отмечены прочитанными',
	
	'site_notifications:cron:linked_cleanup:start' => 'Очистка уведомлений без связанных сущностей',
	'site_notifications:cron:linked_cleanup:end' => 'Очищено %s уведомлений без связанных сущностей',
	'site_notifications:cron:unread_cleanup:start' => 'Очистка непрочитанных уведомлений старше %s дней',
	'site_notifications:cron:unread_cleanup:end' => 'Очищено %s непрочитанных уведомлений',
	'site_notifications:cron:read_cleanup:start' => 'Очистка прочитанных уведомлений старше %s дней',
	'site_notifications:cron:read_cleanup:end' => 'Очищено %s прочитанных уведомлений',
);
