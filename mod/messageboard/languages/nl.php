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

	'messageboard:board' => "Berichtenbox",
	'messageboard:none' => "Er zijn nog geen berichten geplaatst",
	'messageboard:num_display' => "Aantal berichten om weer te geven",
	'messageboard:owner' => 'Berichtenbox van %s',
	'messageboard:owner_history' => 'Berichten van %s in de berichtenbox van %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s liet een bericht achter in de berichtenbox van %s",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we konden het bericht niet verwijderen.",
	'annotation:delete:messageboard:success' => "Je bericht is succesvol verwijderd.",
	
	'messageboard:posted' => "Je bericht is succesvol geplaatst.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Er is een nieuwe reactie op een bericht!',
	'messageboard:email:body' => "Er is een nieuw bericht achtergelaten door %s.

Het bericht is:

%s

Om je berichtenbox te bekijken, klik hier:
%s

Om naar het profiel van %s te gaan, klik hier:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Sorry, je moet wel iets typen voordat we je bericht kunnen opslaan!",

	'messageboard:failure' => "Er is een fout opgetreden tijdens het toevoegen van je bericht. Probeer het nogmaals.",

	'widgets:messageboard:name' => "Berichtenbox",
	'widgets:messageboard:description' => "Dit is een berichtenbox die je op je profiel kunt plaatsen. Andere gebruikers kunnen via deze box een reactie aan je sturen.",
);
