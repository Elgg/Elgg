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

	'messageboard:board' => "Nachrichten-Pinnwand",
	'messageboard:messageboard' => "Nachrichten-Pinnwand",
	'messageboard:none' => "Es gibt noch keine Nachrichten auf dieser Pinnwand.",
	'messageboard:num_display' => "Anzahl der anzuzeigenden Nachrichten",
	'messageboard:user' => "Nachrichten-Pinnwand von %s",
	'messageboard:owner' => 'Nachrichten-Pinnwand von %s',
	'messageboard:owner_history' => 'Nachrichten von %s auf der Pinnwand von %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s schrieb eine Nachricht auf der Pinnwand von %s",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Deine Nachricht wurde auf der Pinnwand hinzugefügt.",
	'messageboard:deleted' => "Die Nachricht wurde gelöscht.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Du hast eine neue Nachricht auf Deiner Pinnwand!',
	'messageboard:email:body' => "Du hast eine neue Nachricht von %s auf Deiner Pinnwand.

Die Nachricht lautet:

%s

Um Deine Pinnwand aufzurufen, folge dem Link:

%s

oder um das Profil von %s aufzurufen, folge dem Link:

%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Entschuldigung. Du mußt erst etwas im Nachrichtenfeld schreiben, bevor die Nachricht abgespeichert werden kann.",
	'messageboard:notdeleted' => "Entschuldigung. Die Nachricht konnte nicht gelöscht werden.",

	'messageboard:failure' => "Beim Speichern Deiner Nachricht ist ein unerwarteter Fehler aufgetreten. Bitte versuche es noch einmal.",

	'widgets:messageboard:name' => "Nachrichten-Pinnwand",
	'widgets:messageboard:description' => "Dieses Widget kannst Du zu Deiner Profilseite hinzufügen, damit Dir andere Mitglieder Nachrichten hinterlassen können.",
);
