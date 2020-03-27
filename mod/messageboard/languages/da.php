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

	'messageboard:board' => "Opslagstavle",
	'messageboard:messageboard' => "opslagstavle",
	'messageboard:none' => "Der er intet på denne opslagstavle endnu",
	'messageboard:num_display' => "Antal viste beskeder",
	'messageboard:user' => "%s's opslagstavle",
	'messageboard:owner' => '%s\'s opslagstavle',
	'messageboard:owner_history' => '%s\'s beskeder på %s\'s opslagstavle',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Du har skrevet på opslagstavlen.",
	'messageboard:deleted' => "Du har slettet beskeden.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Du har en ny kommentar på opslagstavlen!',
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

	'messageboard:blank' => "Beklager, men du er nødt til at skrive noget i beskeden før vi kan gemme den.",
	'messageboard:notdeleted' => "Beklager, beskeden kunne ikke slettes.",

	'messageboard:failure' => "En uventet fejl skete under tilføjelsen af din besked. Prøv venligst igen.",

	'widgets:messageboard:name' => "Opslagstavle",
	'widgets:messageboard:description' => "Dette er en opslagstavle, som du kan bruge på din profil, så andre brugere kan kommentere.",
);
