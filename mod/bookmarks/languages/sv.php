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
	'item:object:bookmarks' => 'Bokmärke',
	'collection:object:bookmarks' => 'Bokmärken',
	'collection:object:bookmarks:group' => 'Gruppbokmärken',
	'collection:object:bookmarks:all' => "Webbplatsens alla bokmärken",
	'collection:object:bookmarks:owner' => "%ss bokmärken",
	'collection:object:bookmarks:friends' => "Vänners bokmärken",
	'add:object:bookmarks' => "Lägg till ett bokmärke",
	'edit:object:bookmarks' => "Redigera bokmärke",

	'bookmarks:this' => "Bokmärk den här sidan",
	'bookmarks:this:group' => "Bokmärke i %s",
	'bookmarks:bookmarklet' => "Hämta scriptbokmärket",
	'bookmarks:bookmarklet:group' => "Hämta scriptbokmärket för grupp",
	'bookmarks:address' => "Bokmärkets adress",
	'bookmarks:none' => 'Inga bokmärken',

	'bookmarks:notify:summary' => 'Nytt bokmärke med namnet %s',
	'bookmarks:notify:subject' => 'Nytt bokmärke: %s',

	'bookmarks:numbertodisplay' => 'Antalet bokmärken att visa',

	'river:object:bookmarks:create' => '%s bokmärkte %s',
	'river:object:bookmarks:comment' => '%s kommenterade ett bokmärke %s',

	'groups:tool:bookmarks' => 'Aktivera gruppbokmärken',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Bokmärken',
	'widgets:bookmarks:description' => "Visa dina senaste bokmärken.",

	'bookmarks:bookmarklet:description' => "Ett scriptbokmärke är en speciell typ av knapp som du sparar i bokmärkesfältet i din webbläsare. Det låter dig att spara vilken resurs som helst som du hittar på nätet i dina bokmärken. För ställa in det, dra knappen nedan till bokmärkesfältet i din webbläsare: ",
	'bookmarks:bookmarklet:descriptionie' => "Om du använder Internet Explorer, måste du högerklicka på scriptbokmärkets ikon, välja \"lägg till favoriter\", och sen \"Bokmärkesfältet\".",
	'bookmarks:bookmarklet:description:conclusion' => "Du kan sen bokmärka vilken sida som helst som du besöker, genom att trycka på knappen i din webbläsare, när som helst.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Ditt objekt bokmärktes.",
	'entity:delete:object:bookmarks:success' => "Bokmärket togs bort.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Ditt bokmärke kunde inte sparas. Se till att du skrev in en titel och adress och försök sedan igen.",
);
