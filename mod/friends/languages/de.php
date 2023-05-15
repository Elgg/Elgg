<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s hat eine Freundschaftsanfrage an %s gestellt.",
	'relationship:friendrequest:pending' => "%s möchte mit Dir befreundet sein!",
	'relationship:friendrequest:sent' => "Du hast eine Freundschaftsanfrage an %s gestellt.",
	
	// plugin settings
	'friends:settings:request:label' => "Bestätigung für Freundschaftsanfragen aktivieren",
	'friends:settings:request:help' => "Benutzer können die Freundschaftsanfragen annehmen oder ablehnen. Die Freundschaftsbeziehung wird bei Zustimmung in beide Richtungen erstellt.",
	
	'friends:owned' => "Freunde von %s",
	'friend:add' => "Zu Freundesliste hinzufügen",
	'friend:remove' => "Aus Freundesliste entfernen",
	'friends:menu:request:status:pending' => "Ausstehende Freundschaftsanfragen",

	'friends:add:successful' => "%s wurde zu Deiner Freundesliste hinzugefügt.",
	'friends:add:duplicate' => "Du bist bereits mit %s befreundet.",
	'friends:add:failure' => "%s konnte nicht zu Deiner Freundesliste hinzugefügt werden.",
	'friends:request:successful' => 'Eine Freundschaftsanfrage wurde an %s gesendet.',
	'friends:request:error' => 'Bei der Verarbeitung Deiner Freundschaftsanfrage an %s ist ein Fehler aufgetreten.',

	'friends:remove:successful' => "%s wurde aus Deiner Freundesliste entfernt.",
	'friends:remove:no_friend' => "Du bist nicht mit %s befreundet.",
	'friends:remove:failure' => "%s konnte nicht aus Deiner Freundesliste entfernt werden.",

	'friends:none' => "Noch keine Freunde.",
	'friends:of:owned' => "Mitglieder, die mit %s befreundet sind",

	'friends:of' => "Befreundet mit",
	
	'friends:request:pending' => "Ausstehende Freundschaftsanfragen",
	'friends:request:pending:none' => "Es gibt keine ausstehenden Freundschaftsanfragen.",
	'friends:request:sent' => "Gesendete Freundschaftsanfragen",
	'friends:request:sent:none' => "Es wurden keine Freundschaftsanfragen versendet.",
	
	'friends:num_display' => "Anzahl der anzuzeigenden Freunde",
	
	'widgets:friends:name' => "Freunde",
	'widgets:friends:description' => "Auflistung einiger Deiner Freunde.",
	
	'widgets:friends_of:name' => "Freund von",
	'widgets:friends_of:description' => "Auflistung der Mitglieder, die Dich als Freund haben.",
	
	'friends:notification:request:subject' => "%s möchte mit Dir befreundet sein!",
	
	'friends:notification:request:decline:subject' => "%s hat Deine Freundschaftsanfrage abgelehnt",
	'friends:notification:request:decline:message' => "%s hat deine Freundschaftsanfrage abgelehnt.",
	
	'friends:notification:request:accept:subject' => "%s hat Deine Freundschaftsanfrage angenommen",
	'friends:notification:request:accept:message' => "%s ist nun mit Dir befreundet.",
	
	'friends:action:friendrequest:revoke:fail' => "Während des Widerrufs der Freundschaftsanfrage ist ein Fehler aufgetreten. Bitte versuche es erneut.",
	'friends:action:friendrequest:revoke:success' => "Die Freundschaftsanfragen wurde widerrufen.",
	
	'friends:action:friendrequest:decline:fail' => "Während der Ablehnung der Freundschaftsanfrage ist ein Fehler aufgetreten. Bitte versuche es erneut.",
	'friends:action:friendrequest:decline:success' => "Die Freundschaftsanfrage wurde abgelehnt.",
	
	'friends:action:friendrequest:accept:success' => "Die Freundschaftsanfrage wurde angenommen.",
	
	// notification settings
	'friends:notification:settings:description' => 'Standardmäßige Benachrichtigungseinstellungen für Mitglieder, die Du zu Deiner Freundesliste hinzufügst.',
);
