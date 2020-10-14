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

	'messageboard:board' => "Anslagstavla",
	'messageboard:messageboard' => "anslagstavla",
	'messageboard:none' => "Det finns inget på den här anslagstavlan än.",
	'messageboard:num_display' => "Antal meddelanden att visa",
	'messageboard:user' => "%ss anslagstavla",
	'messageboard:owner' => '%ss anslagstavla',
	'messageboard:owner_history' => '%ss inlägg på %ss anslagstavla',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s skrev på %ss anslagstavla",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Tyvärr kunde vi inte ta bort det här meddelandet",
	'annotation:delete:messageboard:success' => "Du tog bort meddelandet",
	
	'messageboard:posted' => "Du skrev på anslagstavlan.",
	'messageboard:deleted' => "Du tog bort meddelandet.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Du har en ny kommentar på anslagstavlan!',
	'messageboard:email:body' => "Du har en nu kommentar på anslagstavlan från %s.

Det står:

%s

För att visa dina kommentarer på anslagstavlan, tryck här:
%s

För att visa %ss profil, tryck här:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Du måste skriva något i meddelandet innan vi kan spara det.",
	'messageboard:notdeleted' => "Tyvärr kunde vi inte ta bort det här meddelandet.",

	'messageboard:failure' => "Ett oväntat fel uppstod när ditt meddelande skulle läggas till. Vänligen försök igen.",

	'widgets:messageboard:name' => "Anslagstavla",
	'widgets:messageboard:description' => "Det här är en anslagstavla som du kan lägga i din profil, där andra användare kan kommentera.",
);
