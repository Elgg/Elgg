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

	'messages' => "Berichten",
	'messages:unreadcount' => "%s ongelezen",
	'messages:user' => "Postvak in van %s",
	'messages:inbox' => "Postvak In",
	'messages:sent' => "Verstuurde berichten",
	'messages:message' => "Bericht",
	'messages:title' => "Titel",
	'messages:to:help' => "Schrijf hier de gebruikersnaam van de ontvanger.",
	'messages:sendmessage' => "Verstuur een bericht",
	'messages:add' => "Schrijf een bericht",
	'messages:sentmessages' => "Verstuurde berichten",
	'messages:toggle' => 'Selecteer alles',
	'messages:markread' => 'Markeer als gelezen',

	'messages:error' => 'Er was een probleem tijdens het opslaan van je bericht. Probeer het nogmaals.',

	'item:object:messages' => 'Bericht',
	'collection:object:messages' => 'Berichten',

	/**
	* Status messages
	*/

	'messages:posted' => "Je bericht is succesvol verzonden.",
	'messages:success:delete' => 'Berichten verwijderd',
	'messages:success:read' => 'Bericht gemarkeerd als gelezen',
	'messages:error:messages_not_selected' => 'Geen berichten geselecteerd',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'Je hebt een nieuw bericht!',
	'messages:email:body' => "Je hebt een nieuw bericht ontvangen van %s.

Het bericht is:

%s

Om naar jou berichten te gaan, klik hier:
%s

Om %s een bericht te sturen, klik hier:
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "Sorry, je moet wel tekst invullen voordat we je bericht kunnen versturen.",
	'messages:nomessages' => "Er zijn geen berichten om weer te geven.", // @todo remove in Elgg 7.0
	'messages:user:nonexist' => "We konden de geadresseerde niet in de gebruikerslijst vinden .",
	'messages:user:blank' => "Je hebt niemand geselecteerd om dit naar te sturen.",
	'messages:user:self' => "Je kunt geen berichten aan jezelf sturen!",
	'messages:user:notfriend' => "Je kunt geen berichten aan mensen sturen die geen vriend van je zijn.",

	'messages:deleted_sender' => 'Verwijderde gebruiker',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'Berichten alleen naar vrienden kunnen sturen',
	'messages:settings:friends_only:help' => 'Gebruiker kan geen berichten naar een ontvanger sturen indien deze geen vriend is',

);
