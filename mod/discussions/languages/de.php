<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Diskussionen",
	
	'add:object:discussion' => 'Diskussion hinzufügen',
	'edit:object:discussion' => 'Diskussion bearbeiten',
	'collection:object:discussion' => 'Diskussionen',
	'collection:object:discussion:group' => 'Gruppen-Diskussionen',
	'collection:object:discussion:my_groups' => 'Diskussionen in meinen Gruppen',
	'notification:object:discussion:create' => "Sende eine Benachrichtigung bei Start einer neuen Diskussion",
	'notifications:mute:object:discussion' => "über die Diskussion '%s'",
	
	'discussion:settings:enable_global_discussions' => 'Diskussionen allgemein aktivieren',
	'discussion:settings:enable_global_discussions:help' => 'Erlaube Diskussionen ausserhalb von Gruppen.',

	'discussion:latest' => 'Neueste Diskussionen',
	'discussion:none' => 'Es gibt noch keine Diskussionen.',
	'discussion:updated' => "Letzte Antwort von %s %s",

	'discussion:topic:created' => 'Die Diskussion wurde hinzugefügt.',
	'discussion:topic:updated' => 'Die Diskussion wurde aktualisiert.',
	'entity:delete:object:discussion:success' => 'Die Diskussion wurde gelöscht.',

	'discussion:topic:notfound' => 'Die gewünschte Diskussion wurde leider nicht gefunden.',
	'discussion:error:notsaved' => 'Die Diskussion konnte nicht gespeichert werden.',
	'discussion:error:missing' => 'Es müssen sowohl der Titel als auch der Textinhalt der Diskussion ausgefüllt werden.',
	'discussion:error:permissions' => 'Du hast keine Berechtigung für diese Aktion.',
	'discussion:error:no_groups' => "Du bist noch bei keiner Gruppe Mitglied.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s hat die neue Diskussion %s hinzugefügt',
	'river:object:discussion:comment' => '%s schrieb eine Antwort in der Diskussion %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Neue Diskussion namens %s',
	'discussion:topic:notify:subject' => 'Neue Diskussion: %s',
	'discussion:topic:notify:body' => '%s hat die neue Diskussion "%s" gestartet:

%s

Schau Dir die neue Diskussion an und antworte darauf:
%s',

	'discussion:comment:notify:summary' => 'Neue Antwort in Diskussion %s',
	'discussion:comment:notify:subject' => 'Neue Antwort in Diskussion: %s',
	'discussion:comment:notify:body' => '%s hat in der Diskussion "%s" geantwortet:

%s

Schau Dir die Diskussion an und antworte selbst darauf:
%s',

	'groups:tool:forum' => 'Gruppen-Diskussionen aktivieren',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status',
	'discussion:topic:closed:title' => 'Diskussion geschlossen.',
	'discussion:topic:closed:desc' => 'Diese Diskussion ist geschlossen und es können keine neuen Antworten mehr hinzugefügt werden.',

	'discussion:topic:description' => 'Textinhalt der Diskussion',
	'discussion:topic:toggle_status:open' => 'Die Diskussion wurde wiedereröffnet.',
	'discussion:topic:toggle_status:open:confirm' => 'Bist Du sicher, daß Du diese Diskussion wiedereröffnen willst?',
	'discussion:topic:toggle_status:closed' => 'Die Diskussion wurde geschlossen.',
	'discussion:topic:toggle_status:closed:confirm' => 'Bist Du sicher, daß Du diese Diskussion schließen willst?',
);
