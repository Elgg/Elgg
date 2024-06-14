<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Site Notificaties',
	'notification:method:site' => 'Site',
	'site_notifications:topbar' => 'Notificaties',
	'item:object:site_notification' => 'Site notificatie',
	'collection:object:site_notification' => 'Site notificaties',

	'site_notifications:unread' => 'Ongelezen',
	'site_notifications:read' => 'Gelezen',
	
	'site_notifications:settings:unread_cleanup_days' => 'Ruim ongelezen notificaties op na x dagen',
	'site_notifications:settings:unread_cleanup_days:help' => 'Ongelezen notificaties zullen worden opgeruimd na het opgegeven aantal dagen. Laat leeg om de notificaties nooit op te schonen.',
	'site_notifications:settings:unread_cleanup_interval' => 'Interval voor het opschonen van ongelezen notificaties',
	'site_notifications:settings:unread_cleanup_interval:help' => 'Hoe vaak moeten de ongelezen notificaties worden opgeschoond. Bij websites met een hoge activiteit kan het verhogen van de interval ervoor zorgen dat het opschonen de toestroom van de nieuwe site notificaties kan bijhouden.',
	'site_notifications:settings:read_cleanup_days' => 'Ruim gelezen notificaties op na x dagen',
	'site_notifications:settings:read_cleanup_days:help' => 'Gelezen notificaties zullen worden opgeruimd na het opgegeven aantal dagen. Laat leeg om de notificaties nooit op te schonen.',
	'site_notifications:settings:read_cleanup_interval' => 'Interval voor het opschonen van gelezen notificaties',
	'site_notifications:settings:read_cleanup_interval:help' => 'Hoe vaak moeten de gelezen notificaties worden opgeschoond. Bij websites met een hoge activiteit kan het verhogen van de interval ervoor zorgen dat het opschonen de toestroom van de nieuwe site notificaties kan bijhouden.',
	
	'site_notifications:empty' => 'Geen notificaties',
	'site_notifications:toggle_all' => 'Selecteer alles',
	'site_notifications:mark_read' => 'Markeer als gelezen',
	'site_notifications:mark_read:confirm' => 'Weet je zeker dat je alle geselecteerde notificaties als gelezen wilt markeren?',
	'site_notifications:delete:confirm' => 'Weet je zeker dat je alle geselecteerde notificaties wilt verwijderen?',
	'site_notifications:error:notifications_not_selected' => 'Geen notificaties geselecteerd',
	'site_notifications:success:delete' => 'Notificaties verwijderd',
	'site_notifications:success:mark_read' => 'Notificaties gemarkeerd als gelezen',
	
	'site_notifications:cron:linked_cleanup:end' => 'Site notificaties heeft %s notificaties opgeruimd zonder gekoppelde entiteiten',
	'site_notifications:cron:unread_cleanup:end' => 'Site notificaties heeft %s ongelezen notificaties opgeruimd',
	'site_notifications:cron:read_cleanup:end' => 'Site notificaties heeft %s gelezen notificaties opgeruimd',
);
