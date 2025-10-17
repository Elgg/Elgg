<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'site_notifications' => 'Notificações do Site',
	'notification:method:site' => 'Site',
	'site_notifications:topbar' => 'Notificações',
	'item:object:site_notification' => 'Notificação do Site',
	'collection:object:site_notification' => 'Notificação do Site',

	'site_notifications:unread' => 'Não lidas',
	'site_notifications:read' => 'Lidas',
	
	'site_notifications:settings:unread_cleanup_days' => 'Limpar notificações não lidas após x dias',
	'site_notifications:settings:unread_cleanup_days:help' => 'Notificações não lidas serão limpas após o número de dias informado. Deixe vazio para não limpar as notificações.',
	'site_notifications:settings:unread_cleanup_interval' => 'Intervalo para limpar notificações não lidas',
	'site_notifications:settings:unread_cleanup_interval:help' => 'Com que frequência as notificações não lidas devem ser limpas. Em sites com muita atividade, você pode aumentar esse intervalo para acompanhar o volume de notificações.',
	'site_notifications:settings:read_cleanup_days' => 'Limpar notificações lidas após x dias',
	'site_notifications:settings:read_cleanup_days:help' => 'Notificações lidas serão limpas após o número de dias informado. Deixe vazio para não limpar as notificações.',
	'site_notifications:settings:read_cleanup_interval' => 'Intervalo para limpar notificações lidas',
	'site_notifications:settings:read_cleanup_interval:help' => 'Com que frequência as notificações lidas devem ser limpas. Em sites com muita atividade, você pode aumentar esse intervalo para acompanhar o volume de notificações.',
	
	'site_notifications:toggle_all' => 'Selecionar Tudo',
	'site_notifications:mark_read' => 'Marcar como lidas',
	'site_notifications:mark_read:confirm' => 'Tem certeza que deseja marcar todas as notificações selecionadas como lidas?',
	'site_notifications:delete:confirm' => 'Tem certeza que deseja excluir todas as notificações selecionadas?',
	'site_notifications:error:notifications_not_selected' => 'Nenhuma notificação selecionada',
	'site_notifications:success:delete' => 'Notificações excluídas',
	'site_notifications:success:mark_read' => 'Notificações marcadas como lidas',
	
	'site_notifications:cron:linked_cleanup:end' => 'Notificações do Site limpas: %s notificações sem entidades vinculadas',
	'site_notifications:cron:unread_cleanup:end' => 'Notificações do Site limpas: %s notificações não lidas',
	'site_notifications:cron:read_cleanup:end' => 'Notificações do Site limpas: %s notificações lidas',
);
