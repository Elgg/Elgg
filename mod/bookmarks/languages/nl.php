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
	'item:object:bookmarks' => 'Favorieten',
	'collection:object:bookmarks' => 'Favorieten',
	'collection:object:bookmarks:group' => 'Groepsfavorieten',
	'collection:object:bookmarks:all' => "Alle favorieten",
	'collection:object:bookmarks:owner' => "Favorieten van %s",
	'collection:object:bookmarks:friends' => "Favorieten van vrienden",
	'add:object:bookmarks' => "Favoriet toevoegen",
	'edit:object:bookmarks' => "Bewerk favoriet",
	'notification:object:bookmarks:create' => "Stuur een notificatie wanneer een favoriet is gemaakt",
	'notifications:mute:object:bookmarks' => "over de favoriet '%s'",

	'bookmarks:this' => "Voeg favoriet toe",
	'bookmarks:this:group' => "Favoriet in '%s'",
	'bookmarks:bookmarklet' => "Bookmarklet",
	'bookmarks:bookmarklet:group' => "Verkrijg groeps-bookmarklet",
	'bookmarks:address' => "Adres van de favoriet",
	'bookmarks:none' => 'Geen favorieten',

	'bookmarks:notify:summary' => 'Nieuwe favoriet genaamd \'%s\'',
	'bookmarks:notify:subject' => 'Nieuwe favoriet: %s',
	'bookmarks:notify:body' => '%s heeft een nieuwe favoriet toegevoegd: %s

Adres: %s

%s

Bekijk en reageer op de favoriet:
%s',

	'bookmarks:numbertodisplay' => 'Aantal favorieten om weer te geven',

	'river:object:bookmarks:create' => '%s heeft %s als favoriet toegevoegd',
	'river:object:bookmarks:comment' => '%s reageerde op de favoriet%s',

	'groups:tool:bookmarks' => 'Schakel groepsfavorieten in',
	'groups:tool:bookmarks:description' => 'Laat groepsleden een favorieten delen in deze groep.',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Favorieten',
	'widgets:bookmarks:description' => "Toon je laatste favorieten",

	'bookmarks:bookmarklet:description' => "Een bookmarklet is een speciale knop die je toevoegt aan de favorietenbalk van je browser. Dit geeft je de mogelijkheid om op willekeurige pagina op het internet de URL naar deze pagina direct op te slaan in je favorieten op deze site. Sleep de onderstaande knop naar je favorieten balk om dit mogelijk te maken:",
	'bookmarks:bookmarklet:descriptionie' => "Als je Internet Explorer gebruikt moet je met de rechter muisknop op de bookmarklet klikken. Kies daarna voor 'toevoegen aan favorieten', en vervolgens de \"Links-balk\".",
	'bookmarks:bookmarklet:description:conclusion' => "Je kunt dan iedere pagina die je bezoekt markeren door op de link te klikken.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Je favoriet is succesvol opgeslagen.",
	'entity:delete:object:bookmarks:success' => "De favoriet is verwijderd",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Je favoriet kon niet worden opgeslagen. Probeer het nogmaals.",
);
