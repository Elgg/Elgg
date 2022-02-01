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
	'site_notifications:settings:unread_cleanup_interval' => 'Intervalul de curățare al notificărilor necitite',
	'site_notifications:settings:unread_cleanup_interval:help' => 'Cât de des ca notificările necitite să fie curățate. Pe site-uri cu o activitate intensă s-ar putea să vrei să mărești intervalul pentru a ține pasul cu numărul de noi notificări ale site-ului.',
	'site_notifications:settings:read_cleanup_days' => 'Curăță notificările citite după x zile',
	'site_notifications:settings:read_cleanup_days:help' => 'Notificările citite vor fi curățate după numărul de zile dat. Lasă gol pentru a nu curăța notificările.',
	'site_notifications:settings:read_cleanup_interval' => 'Interval de curățare al notificărilor citite',
	'site_notifications:settings:read_cleanup_interval:help' => 'Cât de des ca notificările citite să fie curățate. Pe site-uri cu o activitate intensă s-ar putea să vrei să mărești intervalul pentru a ține pasul cu numărul de noi notificări ale site-ului.',
	
	'site_notifications:empty' => 'Nu există notificări',
	'site_notifications:toggle_all' => 'Comută toate',
	'site_notifications:mark_read' => 'Marchează ca citită',
	'site_notifications:mark_read:confirm' => 'Sigur dorești să marchezi toate notificările selectate ca și citite?',
	'site_notifications:delete:confirm' => 'Sigur dorești să ștergi toate notificările selectate?',
	'site_notifications:error:notifications_not_selected' => 'Nici o notificare selectată',
	'site_notifications:success:delete' => 'Notificări șterse',
	'site_notifications:success:mark_read' => 'Notificările au fost marcate ca și citite',
	
	'site_notifications:cron:linked_cleanup:start' => 'Notificările siteului, curățirea notificărilor care nu au entități corelate',
	'site_notifications:cron:linked_cleanup:end' => 'Notificările de site au curățat %s notificări fără entități legate',
	'site_notifications:cron:unread_cleanup:start' => 'Notificările de site au curățat notificări necitite mai vechi de %s zile',
	'site_notifications:cron:unread_cleanup:end' => 'Notificările de site au curățat %s notificări necitite',
	'site_notifications:cron:read_cleanup:start' => 'Notificările de site au curățat notificări citite mai vechi de %s zile',
	'site_notifications:cron:read_cleanup:end' => 'Notificările de site au curățat %s notificări citite',
);
