<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Notificări Site',
	'notification:method:site' => 'Site',
	'site_notifications:topbar' => 'Notificări',
	'item:object:site_notification' => 'Notificare site',
	'collection:object:site_notification' => 'Notificări site',

	'site_notifications:unread' => 'Necitite',
	'site_notifications:read' => 'Citite',
	
	'site_notifications:settings:unread_cleanup_days' => 'Curăță notificările necitite după x zile',
	'site_notifications:settings:unread_cleanup_days:help' => 'Notificările necitite vor fi curățate după numărul de zile dat. Lasă gol pentru a nu curăța notificările.',
	'site_notifications:settings:read_cleanup_days' => 'Curăță notificările citite după x zile',
	'site_notifications:settings:read_cleanup_days:help' => 'Notificările citite vor fi curățate după numărul de zile dat. Lasă gol pentru a nu curăța notificările.',
	
	'site_notifications:empty' => 'Nu există notificări',
	'site_notifications:toggle_all' => 'Comută toate',
	'site_notifications:mark_read' => 'Marchează ca citită',
	'site_notifications:mark_read:confirm' => 'Sigur dorești să marchezi toate notificările selectate ca și citite?',
	'site_notifications:error:notifications_not_selected' => 'Nici o notificare selectată',
	'site_notifications:success:delete' => 'Notificări șterse',
	'site_notifications:success:mark_read' => 'Notificările au fost marcate ca și citite',
	
	'site_notifications:cron:linked_cleanup:start' => 'Notificările siteului, curățirea notificărilor care nu au entități corelate',
);
