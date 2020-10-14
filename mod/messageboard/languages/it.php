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

	'messageboard:board' => "Pannello messaggi",
	'messageboard:messageboard' => "pannello messaggi",
	'messageboard:none' => "Ancora nulla in questo pannello messaggi",
	'messageboard:num_display' => "Numero di messaggi da visualizzare",
	'messageboard:user' => "Pannello messaggi di %s",
	'messageboard:owner' => 'Pannello messaggi di %s',
	'messageboard:owner_history' => 'Messaggi di %s sul pannello messaggi di %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s ha postato sulla bacheca messaggi di %s",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Messaggio privato inviato.",
	'messageboard:deleted' => "Messaggio rimosso.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Hai un nuovo messaggio privato!',
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

	'messageboard:blank' => "Devi scrivere qualcosa nel messaggio per poterlo salvare.",
	'messageboard:notdeleted' => "Impossibile eliminare questo messaggio.",

	'messageboard:failure' => "Si è verificato un errore imprevisto aggiungendo il tuo messaggio. Per favore riprova.",

	'widgets:messageboard:name' => "Pannello messaggi",
	'widgets:messageboard:description' => "Puoi aggiungere questo pannello messaggi al tuo profilo per permettere agli altri utenti di inviarti dei messaggi privati.",
);
