<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Notifications du site',
	'notification:method:site' => 'Site',
	'site_notifications:topbar' => 'Notifications',
	'item:object:site_notification' => 'Notification du site',
	'collection:object:site_notification' => 'Notifications du site',

	'site_notifications:unread' => 'Non lu',
	'site_notifications:read' => 'Lu',
	
	'site_notifications:settings:unread_cleanup_days' => 'Nettoyer les notifications non lues après x jours',
	'site_notifications:settings:unread_cleanup_days:help' => 'Les notifications non lues seront nettoyées après le nombre de jours indiqué. Laisser vide pour ne pas nettoyer les notifications.',
	'site_notifications:settings:read_cleanup_days' => 'Nettoyer les notifications lues après x jours',
	'site_notifications:settings:read_cleanup_days:help' => 'Les notifications lues seront nettoyées après le nombre de jours indiqué. Laisser vide pour ne pas nettoyer les notifications.',
	
	'site_notifications:empty' => 'Pas de notification',
	'site_notifications:toggle_all' => 'Inverser la sélection',
	'site_notifications:mark_read' => 'Marquer comme lu',
	'site_notifications:mark_read:confirm' => 'Confirmez-vous vouloir marquer toutes les notifications sélectionnées comme lues ?',
	'site_notifications:delete:confirm' => 'Confirmez-vous vouloir supprimer toutes les notifications sélectionnées sélectionnées ?',
	'site_notifications:error:notifications_not_selected' => 'Pas de notification sélectionnée',
	'site_notifications:success:delete' => 'Notifications supprimées',
	'site_notifications:success:mark_read' => 'Notifications marquées comme lues',
	
	'site_notifications:cron:linked_cleanup:start' => 'Site Notifications est en train de nettoyer les notifications sans entité liée',
	'site_notifications:cron:linked_cleanup:end' => 'Site Notifications a nettoyé %s notifications sans entité liée',
	'site_notifications:cron:unread_cleanup:start' => 'Site Notifications est en train de nettoyer les notifications non lues de plus de %s jours',
	'site_notifications:cron:unread_cleanup:end' => 'Site Notifications a nettoyé %s notifications non lues',
	'site_notifications:cron:read_cleanup:start' => 'Site Notifications est en train de nettoyer les notifications lues de plus de %s jours',
	'site_notifications:cron:read_cleanup:end' => 'Site Notifications a nettoyé %s notifications lues',
);
