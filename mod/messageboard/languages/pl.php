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

	'messageboard:board' => "Tablica ogłoszeń",
	'messageboard:messageboard' => "tablica ogłoszeń",
	'messageboard:none' => "Ta tablica ogłoszeń jest jeszcze pusta",
	'messageboard:num_display' => "Liczba wiadomości do wyświetlenia",
	'messageboard:user' => "Tablica ogłoszeń użytkownika %s",
	'messageboard:owner' => 'tablica ogłoszeń użytkownika %s',
	'messageboard:owner_history' => 'Wpisy %s na tablicy ogłoszeń użytkownika %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Twoja wiadomość została dodana pomyślnie.",
	'messageboard:deleted' => "Twoja wiadomość została skasowana.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Masz nowy komentarz na tablicy ogłoszeń!',
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

	'messageboard:blank' => "Przykro nam, musisz coś wpisać przed zapisaniem.",
	'messageboard:notdeleted' => "Przykro nam, nie można skasować tej wiadomości.",

	'messageboard:failure' => "Wystąpił nieoczekiwany błąd podczas dodawania wiadomości. Proszę spróbować ponownie.",

	'widgets:messageboard:name' => "Tablica ogłoszeń",
	'widgets:messageboard:description' => "To jest tablica ogłoszeń, którą możesz umieścić w swoim profilu, aby inni użytkownicy mogli zostawić komentarz.",
);
