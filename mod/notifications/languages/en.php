<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'notifications:settings:title' => 'Notification settings',
	'notifications:settings:default:description' => 'Default notification settings for events from the system',
	'notifications:settings:content_create:description' => 'Default notification settings for new content you created, this can cause notifications when others take action on you content like leaving a comment',
	
	'notifications:subscriptions:changesettings' => 'Notifications',
	
	'notifications:subscriptions:users:title' => 'Notifications per user',
	'notifications:subscriptions:users:description' => 'To receive notifications from your friends (on an individual basis) when they create new content, find them below and select the notification method you would like to use.',

	'notifications:subscriptions:groups:title' => 'Group notifications',
	'notifications:subscriptions:groups:description' => 'To receive notifications when new content is added to a group you are a member of, find it below and select the notification method(s) you would like to use.',

	'notifications:subscriptions:success' => 'Your notifications settings have been saved.',

	'notifications:subscriptions:no_results' => 'There are no subscription records yet',
	
	'notifications:groups:subscribed' => 'Group notifications are on',
	'notifications:groups:unsubscribed' => 'Group notifications are off',

	'notifications:upgrade:2021040801:title' => "Migrate Access collection notification preferences",
	'notifications:upgrade:2021040801:description' => "A new way to store notification preferences has been introduced. This upgrade migrates the old settings to the new logic.",
);
