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

	'messageboard:board' => "Safata de comentaris",
	'messageboard:messageboard' => "safata de comentaris",
	'messageboard:none' => "No hi ha cap comentari a la teva safata",
	'messageboard:num_display' => "Nombre de comentaris a mostrar",
	'messageboard:user' => "a la safata personal de %s",
	'messageboard:owner' => 'Safata personal de %s',
	'messageboard:owner_history' => 'Comentaris de %s a la safata personal de %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "S'ha escrit el comentari",
	'messageboard:deleted' => "S'ha esborrat el comentari",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Tens un nou comentari a la safata',
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

	'messageboard:blank' => "Has de posar alguna cosa a la safata de comentaris abans d'enviar-ho, sinó anem malament!",
	'messageboard:notdeleted' => "No s'ha pogut esborrar el comentari, torna-ho a provar o posa't en contacte amb els/les administradors/es.",

	'messageboard:failure' => "Error xungo, el sistema no en té ni idea de què està passant. Torna-ho a provar o posa't en contacte amb els/les administradors/es.",

	'widgets:messageboard:name' => "Safata de comentaris",
	'widgets:messageboard:description' => "Aquesta és la safata dels comentaris. Qualsevol, fins i tot tu, pot deixar un missatge al teu perfil.",
);
