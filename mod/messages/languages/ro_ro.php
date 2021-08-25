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

	'messages' => "Mesaje",
	'messages:unreadcount' => "%s necitite",
	'messages:user' => "Inbox %s",
	'messages:inbox' => "Primite",
	'messages:sent' => "Trimise",
	'messages:message' => "Mesaj",
	'messages:title' => "Subiect",
	'messages:to:help' => "Scrie numele de utilizator al destinatarului aici",
	'messages:inbox' => "Primite",
	'messages:sendmessage' => "Trimite un mesaj",
	'messages:add' => "Compune un mesaj",
	'messages:sentmessages' => "Mesaje trimise",
	'messages:toggle' => 'Comută toate',
	'messages:markread' => 'Marchează ca citit',

	'notification:method:site' => 'Site',

	'messages:error' => 'A fost o problemă la salvarea mesajului tău. Te rugăm să încerci din nou.',

	'item:object:messages' => 'Mesaj',
	'collection:object:messages' => 'Mesaje',

	/**
	* Status messages
	*/

	'messages:posted' => "Mesajul tău a fost trimis cu succes.",
	'messages:success:delete' => 'Mesaj șters',
	'messages:success:read' => 'Mesaje marcate ca și citite',
	'messages:error:messages_not_selected' => 'Nici un mesaj selectat',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'Ai un mesaj nou!',
	'messages:email:body' => "Ai un mesaj nou de la %s.

Scrie:

%s

Pentru a vedea mesajul, apasă aici:
%s

Pentru a-i trimite un mesaj utilizatorului %s, apasă aici:
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "Scuze; trebuie să adaugi ceva în corpul mesajului înainte să îl putem salva.",
	'messages:nomessages' => "Nu există mesaje.",
	'messages:user:nonexist' => "Nu am putut găsi destinatarul în baza de date.",
	'messages:user:blank' => "Nu ai selectat pe cineva pentru a trimite.",
	'messages:user:self' => "Nu-ți poți trimite un mesaj ție.",
	'messages:user:notfriend' => "Nu poți trimite un mesaj către un utilizator care nu îți este prieten/ă.",

	'messages:deleted_sender' => 'Utilizator șters',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'Mesajele pot fi trimise numai către prieteni',
	'messages:settings:friends_only:help' => 'Utilizatorul nu va putea trimite un mesaj dacă destinatarul nu îi este prieten/ă',

);
