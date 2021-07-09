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

	'messages' => "Nachrichten",
	'messages:unreadcount' => "%s ungelesene",
	'messages:user' => "Inbox von %s",
	'messages:inbox' => "Inbox",
	'messages:sent' => "Gesendet",
	'messages:message' => "Nachricht",
	'messages:title' => "Betreff",
	'messages:to:help' => "Gebe hier den Benutzernamen des Empfängers der Nachricht ein.",
	'messages:inbox' => "Inbox",
	'messages:sendmessage' => "Nachricht senden",
	'messages:add' => "Nachricht verfassen",
	'messages:sentmessages' => "Gesendete Nachrichten",
	'messages:toggle' => 'Alle auswählen',
	'messages:markread' => 'Als gelesen markieren',

	'notification:method:site' => 'Seite',

	'messages:error' => 'Beim Speichern Deiner Nachricht ist ein Problem aufgetreten. Bitte versuche es noch einmal.',

	'item:object:messages' => 'Nachricht',
	'collection:object:messages' => 'Nachrichten',

	/**
	* Status messages
	*/

	'messages:posted' => "Deine Nachricht wurde gesendet.",
	'messages:success:delete' => 'Die Nachrichten wurden gelöscht.',
	'messages:success:read' => 'Die Nachrichten wurden als gelesen markiert.',
	'messages:error:messages_not_selected' => 'Es wurden keine Nachrichten ausgewählt.',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'Du hast eine neue Nachricht!',
	'messages:email:body' => "Du hast eine neue Nachricht von %s erhalten.

Die Nachricht lautet:

%s


Um zu Deiner Inbox zu kommen, folge dem Link:
%s

und um %s eine Nachricht zu schicken, folge dem Link:
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "Entschuldigung, Du mußt erst etwas im Nachrichtenteil schreiben, bevor die Nachricht versendet werden kann.",
	'messages:nomessages' => "Es gibt keine Nachrichten.",
	'messages:user:nonexist' => "Wir konnten den Empfänger der Nachricht nicht in der Datenbank der Community-Seite finden.",
	'messages:user:blank' => "Du hast keinen Empfänger für Deine Nachricht ausgewählt.",
	'messages:user:self' => "Du kannst keine Nachricht an Dich selbst senden.",
	'messages:user:notfriend' => "Du kannst keine Nachricht an ein Mitglied senden, mit dem Du nicht befreundet bist.",

	'messages:deleted_sender' => 'Ehemaliges Mitglied',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'Nachrichten können nur an Freunde gesendet werden',
	'messages:settings:friends_only:help' => 'Mitglieder können eine Nachricht nicht an ein anderes Mitglied senden, wenn sie nicht befreundet sind.',

);
