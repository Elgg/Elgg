<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'item:object:bookmarks' => 'Lesezeichen',
	'collection:object:bookmarks' => 'Lesezeichen',
	'collection:object:bookmarks:group' => 'Gruppen-Lesezeichen',
	'collection:object:bookmarks:all' => "Alle Lesezeichen der Community",
	'collection:object:bookmarks:owner' => "Lesezeichen von %s",
	'collection:object:bookmarks:friends' => "Lesezeichen Deiner Freunde",
	'add:object:bookmarks' => "Lesezeichen hinzufügen",
	'edit:object:bookmarks' => "Lesezeichen editieren",
	'notification:object:bookmarks:create' => "Sende eine Benachrichtigung bei Hinzufügen eines Lesezeichens",
	'notifications:mute:object:bookmarks' => "über das Lesezeichen '%s'",

	'bookmarks:this' => "Lesezeichen für diese Seite hinzufügen",
	'bookmarks:this:group' => "Lesezeichen in %s setzen",
	'bookmarks:bookmarklet' => "Bookmarklet zum Browser hinzufügen",
	'bookmarks:bookmarklet:group' => "Gruppen-Bookmarklet zum Browser hinzufügen",
	'bookmarks:address' => "Zieladresse des Lesezeichens",
	'bookmarks:none' => 'Noch keine Lesezeichen vorhanden.',

	'bookmarks:notify:summary' => 'Ein neues Lesezeichen %s wurde erstellt',
	'bookmarks:notify:subject' => 'Neues Lesezeichen: %s',
	'bookmarks:notify:body' => '%s hat ein neues Lesezeichen erstellt: %s

Adresse: %s

%s

Schau Dir das neue Lesezeichen an und schreibe einen Kommentar:
%s',

	'bookmarks:numbertodisplay' => 'Anzahl der anzuzeigenden Lesezeichen-Einträge.',

	'river:object:bookmarks:create' => '%s hat das Lesezeichen %s hinzugefügt',
	'river:object:bookmarks:comment' => '%s kommentierte das Lesezeichen %s',

	'groups:tool:bookmarks' => 'Gruppen-Lesezeichen aktivieren',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Lesezeichen',
	'widgets:bookmarks:description' => "Dieses Widget zeigt Deine neuesten Lesezeichen an.",

	'bookmarks:bookmarklet:description' => "Ein Lesezeichen-Bookmarklet ist eine spezielle Schaltfläche, die Du zur Lesezeichen-Leiste in Deinem Browser hinzufügen kannst. Das Bookmarklet ermöglicht es Dir, für eine Internetseite, die Du zu einem späteren Zeitpunkt noch einmal besuchen willst, ein Lesezeichen zu erstellen. Um das Bookmarklet einzurichten, ziehe die angezeigte Schaltfläche einfach in die Lesezeichen-Leiste Deines Browsers:",
	'bookmarks:bookmarklet:descriptionie' => "Wenn Du den Internet Explorer verwendest, klicke mit der rechten Maustaste auf das Bookmarklet-Icon, wähle 'Zu Favoriten hinzufügen' und dann die Lesezeichen-Leiste.",
	'bookmarks:bookmarklet:description:conclusion' => "Du kannst dann ein Lesezeichen für eine Seite erstellen, indem Du auf die Bookmarklet-Schaltfläche in der Lesezeichen-Leiste des Browsers klickst.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Das Lesezeichen wurde gespeichert.",
	'entity:delete:object:bookmarks:success' => "Das Lesezeichen wurde gelöscht.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Das Lesezeichen konnte nicht gespeichert werden. Bitte gebe einen Titel und eine Zieladresse an und versuche es noch einmal.",
);
