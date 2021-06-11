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
	'thewire' => "The Wire",

	'item:object:thewire' => "Wire posts",
	'collection:object:thewire:all' => "All wire posts",
	'collection:object:thewire:owner' => "%s's wire posts",
	'collection:object:thewire:friends' => "Friends' wire posts",

	'thewire:replying' => "Replying to %s (@%s) who wrote",
	'thewire:thread' => "Wątek",
	'thewire:charleft' => "znaków pozostało",
	'thewire:tags' => "Wire posts tagged with '%s'",
	'thewire:noposts' => "No wire posts yet",

	'thewire:by' => 'Wire post by %s',

	'thewire:form:body:placeholder' => "Co się dzieje?",
	
	/**
	 * The wire river
	 */
	'thewire:wire' => 'the wire',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'Display your latest wire posts',
	'thewire:num' => 'Liczba postów do wyświetlenia',
	'thewire:moreposts' => 'More wire posts',

	/**
	 * Status messages
	 */
	'thewire:posted' => "Your message was successfully posted to the wire.",
	'thewire:deleted' => "The wire post was successfully deleted.",
	'thewire:blank' => "Musisz wprowadzić jakiś tekst przed wysłaniem.",
	'thewire:notsaved' => "Przepraszamy, nie udało się zapisać tego wpisu na Wire",
	'thewire:notdeleted' => "Sorry. We could not delete this wire post.",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => 'Nowy wpis na Wire: %s',
	'thewire:notify:subject' => "Nowy wpis na Wire od %s",
	'thewire:notify:reply' => '%s responded to %s on the wire:',
	'thewire:notify:post' => '%s posted on the wire:',
	'thewire:notify:footer' => "Wyświetl i odpowiedz:\n%s",

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "Maximum number of characters for wire messages:",
	'thewire:settings:limit:none' => "Bez limitu",
);
