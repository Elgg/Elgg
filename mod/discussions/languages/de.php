<?php

return array(
	'discussion' => 'Diskussionen',
	'discussion:add' => 'Neue Diskussion hinzufügen',
	'discussion:latest' => 'Neueste Diskussionen',
	'discussion:group' => 'Gruppen-Diskussionen',
	'discussion:none' => 'Es gibt noch keine Diskussionen.',
	'discussion:reply:title' => 'Antwort von %s',
	'discussion:new' => "Diskussion hinzufügen",
	'discussion:updated' => "Letzte Antwort von %s %s",

	'discussion:topic:created' => 'Die Diskussion wurde hinzugefügt.',
	'discussion:topic:updated' => 'Die Diskussion wurde aktualisiert.',
	'discussion:topic:deleted' => 'Die Diskussion wurde gelöscht.',

	'discussion:topic:notfound' => 'Die gewünschte Diskussion wurde leider nicht gefunden.',
	'discussion:error:notsaved' => 'Die Diskussion konnte nicht gespeichert werden.',
	'discussion:error:missing' => 'Es müssen sowohl der Titel als auch das Textfeld ausgefüllt werden.',
	'discussion:error:permissions' => 'Du hast keine Berechtigung für diese Aktion.',
	'discussion:error:notdeleted' => 'Die Diskussion konnte nicht gelöscht werden.',

	'discussion:reply:edit' => 'Antwort bearbeiten',
	'discussion:reply:deleted' => 'Die Antwort in der Diskussion wurde gelöscht.',
	'discussion:reply:error:notfound' => 'Die ausgewählte Antwort ist in dieser Diskussion nicht auffindbar.',
	'discussion:reply:error:notfound_fallback' => "Entschuldigung. Diese Antwort ist nicht auffindbar aber Du wurdest zur entsprechenden Diskussion weitergeleitet.",
	'discussion:reply:error:notdeleted' => 'Die Antwort in der Diskussion konnte nicht gelöscht werden.',

	'discussion:search:title' => 'Antwort in Diskussion: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'Du mußt das Textfeld ausfüllen, bevor Du eine Antwort hinzufügen kannst.',
	'discussion:reply:topic_not_found' => 'Die Diskussion wurde leider nicht gefunden.',
	'discussion:reply:error:cannot_edit' => 'Du kast keine Berechtigung zum Bearbeiten dieser Antwort.',
	'discussion:reply:error:permissions' => 'You are not allowed to reply to this topic',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s schrieb eine neue Diskussion %s',
	'river:reply:object:discussion' => '%s schrieb eine Antwort in der Diskussion %s',
	'river:reply:view' => 'Antwort anzeigen',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Neue Diskussion namens %s',
	'discussion:topic:notify:subject' => 'Neue Diskussion: %s',
	'discussion:topic:notify:body' =>
'%s hat eine neue Diskussion "%s" gestartet:

%s

Schau Dir die neue Diskussion an und antworte darauf:
%s
',

	'discussion:reply:notify:summary' => 'Neue Antwort in Diskussion %s',
	'discussion:reply:notify:subject' => 'Neue Antwort in Diskussion: %s',
	'discussion:reply:notify:body' =>
'%s hat in der Diskussion "%s" geantwortet:

%s

Schau Dir die Diskussion an und antworte selbst darauf:
%s
',

	'item:object:discussion' => "Diskussionen",
	'item:object:discussion_reply' => "Antworten in Diskussionen",

	'groups:enableforum' => 'Gruppen-Diskussionen aktivieren',

	'reply:this' => 'Antwort schreiben',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Gruppen-Diskussionen',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Status',
	'discussion:topic:closed:title' => 'Diskussion geschlossen.',
	'discussion:topic:closed:desc' => 'Diese Diskussion ist geschlossen und es können keine neuen Antworten mehr hinzugefügt werden.',

	'discussion:replies' => 'Antworten',
	'discussion:addtopic' => 'Diskussion hinzufügen',
	'discussion:post:success' => 'Deine Antwort wurde gespeichert.',
	'discussion:post:failure' => 'Beim Speichern Deiner Antwort ist ein Problem aufgetreten.',
	'discussion:topic:edit' => 'Diskussion bearbeiten',
	'discussion:topic:description' => 'Textinhalt der Diskussion',

	'discussion:reply:edited' => "Die Änderung wurde gespeichert.",
	'discussion:reply:error' => "Beim Speichern der Änderung ist ein Problem aufgetreten.",
);
