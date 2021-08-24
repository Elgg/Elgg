<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blogs',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'Alle Blogs der Community',
	'collection:object:blog:owner' => 'Blogs von %s',
	'collection:object:blog:group' => 'Gruppen-Blogs',
	'collection:object:blog:friends' => 'Blogs Deiner Freunde',
	'add:object:blog' => 'Blog-Eintrag verfassen',
	'edit:object:blog' => 'Blog-Eintrag editieren',
	'notification:object:blog:publish' => "Sende eine Benachrichtigung bei Veröffentlichung eines Blog-Eintrags",
	'notifications:mute:object:blog' => "über den Blog-Eintrag '%s'",

	'blog:revisions' => 'Revisionen',
	'blog:archives' => 'Ältere Blogs',

	'groups:tool:blog' => 'Gruppen-Blogs aktivieren',

	// Editing
	'blog:excerpt' => 'Auszug',
	'blog:body' => 'Blogtext',
	'blog:save_status' => 'Zuletzt gespeichert: ',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Automatisch gespeicherte Revision',

	// messages
	'blog:message:saved' => 'Dein Blog-Eintrag wurde gespeichert.',
	'blog:error:cannot_save' => 'Dein Blog-Eintrag konnte nicht gespeichert werden.',
	'blog:error:cannot_auto_save' => 'Das automatische Speichern Deines Blog-Eintrags ist fehlgeschlagen.',
	'blog:error:cannot_write_to_container' => 'Keine ausreichenden Zugriffsrechte zum Speichern des Blog-Eintrags im Gruppenblog vorhanden.',
	'blog:messages:warning:draft' => 'Die Entwurfsversion dieses Eintrags wurde nocht nicht gespeichert!',
	'blog:edit_revision_notice' => '(Alte Revision)',
	'blog:none' => 'Keine Blog-Einträge vorhanden.',
	'blog:error:missing:title' => 'Bitte einen Titel für Deinen Blog-Eintrag angeben!',
	'blog:error:missing:description' => 'Bitte gebe den Text Deines Blog-Eintrags ein!',
	'blog:error:post_not_found' => 'Der ausgewählte Blog-Eintrag ist nicht auffindbar.',
	'blog:error:revision_not_found' => 'Diese Revision ist nicht verfügbar.',

	// river
	'river:object:blog:create' => '%s veröffentlichte den Blog-Eintrag %s',
	'river:object:blog:comment' => '%s kommentierte den Blog-Eintrag %s',

	// notifications
	'blog:notify:summary' => 'Ein neuer Blog-Eintrag mit dem Titel %s wurde erstellt',
	'blog:notify:subject' => 'Ein neuer Blog-Eintrag: %s',
	'blog:notify:body' => '%s hat einen neuen Blog-Eintrag erstellt: %s

%s

Schau Dir den neuen Blog-Eintrag an und schreibe einen Kommentar:
%s',

	// widget
	'widgets:blog:name' => 'Blog-Einträge',
	'widgets:blog:description' => 'Dieses Widget zeigt Deine neuesten Blog-Einträge an.',
	'blog:moreblogs' => 'Weitere Blog-Einträge',
	'blog:numbertodisplay' => 'Anzahl der anzuzeigenden Blog-Einträge',
);
