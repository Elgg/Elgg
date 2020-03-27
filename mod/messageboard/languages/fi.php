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

	'messageboard:board' => "Ilmoitustaulu",
	'messageboard:messageboard' => "ilmoitustaulu",
	'messageboard:none' => "Tällä ilmoitustaululla ei ole vielä sisältöä",
	'messageboard:num_display' => "Näytettävien kohteiden määrä",
	'messageboard:user' => "Käyttäjän %s ilmoitustaulu",
	'messageboard:owner' => 'Käyttäjän %s ilmoitustaulu',
	'messageboard:owner_history' => 'Käyttäjän %s viestit käyttäjän %s ilmoitustaululla',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Viesti tallennettu.",
	'messageboard:deleted' => "Viesti poistettu.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Ilmoitustaulullasi on uusi kommentti!',
	'messageboard:email:body' => "You have a new message board comment from %s.

It reads:

%s

To view your message board comments, click here:
%s

To view %s's profile, click here:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Viestin sisältö puuttuu!",
	'messageboard:notdeleted' => "Viestin poistaminen epäonnistui.",

	'messageboard:failure' => "Viestin tallentamisessa tapahtui odottamaton virhe. Yritä uudelleen.",

	'widgets:messageboard:name' => "Ilmoitustaulu",
	'widgets:messageboard:description' => "Ilmoitustaulu, johon muut käyttäjät voivat lisätä omia kommenttejaan.",
);
