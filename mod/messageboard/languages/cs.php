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

	'messageboard:board' => "Vzkazy",
	'messageboard:messageboard' => "vzkazy",
	'messageboard:none' => "Zatím zde nejsou žádné vzkazy",
	'messageboard:num_display' => "Počet zobrazených vzkazů",
	'messageboard:user' => "Vzkazník uživatele %s",
	'messageboard:owner' => 'Vzkazník uživatele %s',
	'messageboard:owner_history' => '%s přidal/a vzkaz do vzkazníku uživatele %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Úspěšně jste přidal/a vzkaz do vzkazníku.",
	'messageboard:deleted' => "Úspěšně jste smazal/a vzkaz.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Máte novou zprávu ve vzkazníku!',
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

	'messageboard:blank' => "Omlouváme se, ale před odesláním musíte zadat nějaký vzkaz.",
	'messageboard:notdeleted' => "Bohužel nemůžeme smazat tento vzkaz.",

	'messageboard:failure' => "Při ukládání vzkazu nastala nečekaná chyba. Zkuste to prosím znovu.",

	'widgets:messageboard:name' => "Vzkazy",
	'widgets:messageboard:description' => "Vzkazník, který si můžete přidat na svůj profil a mohou vám tam psát ostatní uživatelé.",
);
