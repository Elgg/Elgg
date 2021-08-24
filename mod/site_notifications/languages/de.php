<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Seiten-Benachrichtigungen',
	'notification:method:site' => 'Seite',
	'site_notifications:topbar' => 'Benachrichtigungen',
	'item:object:site_notification' => 'Seiten-Benachrichtigung',
	'collection:object:site_notification' => 'Seiten-Benachrichtigungen',

	'site_notifications:unread' => 'Ungelesen',
	'site_notifications:read' => 'Gelesen',
	
	'site_notifications:settings:unread_cleanup_days' => 'Ungelesene Seiten-Benachrichtigungen nach X Tagen löschen',
	'site_notifications:settings:unread_cleanup_days:help' => 'Ungelesene Seiten-Benachrichtigungen werden nach der angegebenen Anzahl von Tagen automatisch gelöscht.',
	'site_notifications:settings:read_cleanup_days' => 'Gelesene Seiten-Benachrichtigungen nach X Tagen löschen',
	'site_notifications:settings:read_cleanup_days:help' => 'Gelesene Seiten-Benachrichtigungen werden nach der angegebenen Anzahl von Tagen automatisch gelöscht. Lass das Eingabefeld leer, damit die Seiten-Benachrichtigungen nicht automatisch gelöscht werden.',
	
	'site_notifications:empty' => 'Es sind keine Seiten-Benachrichtigungen vorhanden.',
	'site_notifications:toggle_all' => 'Alle auswählen',
	'site_notifications:mark_read' => 'Als gelesen markieren',
	'site_notifications:mark_read:confirm' => 'Bist Du sicher, daß Du alle ausgewählten Seiten-Benachrichtigungen als gelesen markieren willst?',
	'site_notifications:error:notifications_not_selected' => 'Es sind keine Seiten-Benachrichtigungen ausgewählt.',
	'site_notifications:success:delete' => 'Die Seiten-Benachrichtigungen wurden gelöscht.',
	'site_notifications:success:mark_read' => 'Die Seiten-Benachrichtigungen wurden als gelesen markiert.',
	
	'site_notifications:cron:linked_cleanup:start' => 'Automatisches Löschen der Seiten-Benachrichtigungen ohne Verknüpfung mit einer Entität gestartet.',
	'site_notifications:cron:linked_cleanup:end' => 'Automatisches Löschen von %s Seiten-Benachrichtigungen ohne Verknüpfung mit einer Entität abgeschlossen.',
	'site_notifications:cron:unread_cleanup:start' => 'Automatisches Löschen der ungelesenen Seiten-Benachrichtigungen, die älter als %s Tage sind, gestartet.',
	'site_notifications:cron:unread_cleanup:end' => 'Automatisches Löschen von ungelesenen Seiten-Benachrichtigungen, die älter als %s Tage waren, abgeschlossen.',
	'site_notifications:cron:read_cleanup:start' => 'Automatisches Löschen der gelesenen Seiten-Benachrichtigungen, die älter als %s Tage sind, gestartet.',
	'site_notifications:cron:read_cleanup:end' => 'Automatisches Löschen von gelesenen Seiten-Benachrichtigungen, die älter als %s Tage waren, abgeschlossen.',
);
