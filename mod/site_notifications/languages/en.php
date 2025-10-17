<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Site Notifications',
	'notification:method:site' => 'Site',
	'site_notifications:topbar' => 'Notifications',
	'item:object:site_notification' => 'Site notification',
	'collection:object:site_notification' => 'Site notifications',
	'list:object:site_notification:no_results' => 'No site notifications found',

	'site_notifications:unread' => 'Unread',
	'site_notifications:read' => 'Read',
	
	'site_notifications:settings:unread_cleanup_days' => 'Cleanup unread notifications after x days',
	'site_notifications:settings:unread_cleanup_days:help' => 'Unread notifications will be cleaned up after the given number of days. Leave empty to not cleanup notifications.',
	'site_notifications:settings:unread_cleanup_interval' => 'Cleanup unread notifications interval',
	'site_notifications:settings:unread_cleanup_interval:help' => 'How often should the unread notifications be cleaned up. On higher activity sites you might want to increase the interval in order to keep up with the number of new site notifications.',
	'site_notifications:settings:read_cleanup_days' => 'Cleanup read notifications after x days',
	'site_notifications:settings:read_cleanup_days:help' => 'Read notifications will be cleaned up after the given number of days. Leave empty to not cleanup notifications.',
	'site_notifications:settings:read_cleanup_interval' => 'Cleanup read notifications interval',
	'site_notifications:settings:read_cleanup_interval:help' => 'How often should the read notifications be cleaned up. On higher activity sites you might want to increase the interval in order to keep up with the number of new site notifications',
	
	'site_notifications:toggle_all' => 'Toggle all',
	'site_notifications:mark_read' => 'Mark as read',
	'site_notifications:mark_read:confirm' => 'Are you sure you wish to mark all selected notifications as read?',
	'site_notifications:delete:confirm' => 'Are you sure you wish to delete all selected notifications?',
	'site_notifications:error:notifications_not_selected' => 'No notifications selected',
	'site_notifications:success:delete' => 'Notifications deleted',
	'site_notifications:success:mark_read' => 'Notifications marked as read',
	
	'site_notifications:cron:linked_cleanup:end' => 'Site notifications cleaned up %s notifications without linked entities',
	'site_notifications:cron:unread_cleanup:end' => 'Site notifications cleaned up %s unread notifications',
	'site_notifications:cron:read_cleanup:end' => 'Site notifications cleaned up %s read notifications',
);
