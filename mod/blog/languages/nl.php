<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blog',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'Alle blogs van de site',
	'collection:object:blog:owner' => 'Blogs van %s',
	'collection:object:blog:group' => 'Groepsblogs',
	'collection:object:blog:friends' => 'Blogs van vrienden',
	'add:object:blog' => 'Blog toevoegen',
	'edit:object:blog' => 'Bewerk blog',
	'notification:object:blog:publish' => "Stuur een notificatie wanneer een blog is gepubliceerd",
	'notifications:mute:object:blog' => "over de blog '%s'",

	'blog:revisions' => 'Revisies',
	'blog:archives' => 'Archieven',

	'groups:tool:blog' => 'Groepsblog inschakelen',
	'groups:tool:blog:description' => 'Laat groepsleden een blog schrijven in deze groep.',

	// Editing
	'blog:excerpt' => 'Uittreksel',
	'blog:body' => 'Bericht',
	'blog:save_status' => 'Laatst opgeslagen:',

	'blog:revision' => 'Revisie',
	
	// messages
	'blog:message:saved' => 'Blog opgeslagen',
	'blog:error:cannot_save' => 'Blog kon niet worden opgeslagen.',
	'blog:error:cannot_write_to_container' => 'Je hebt onvoldoende rechten om de blog in de groep op te slaan.',
	'blog:edit_revision_notice' => '(Oude versie)',
	'blog:none' => 'Geen blogs',
	'blog:error:missing:title' => 'Geef een titel aan de blog!',
	'blog:error:missing:description' => 'Vertel iets in je blog!',
	'blog:error:post_not_found' => 'Deze blog is verwijderd, ongeldig, of je hebt onvoldoende rechten om hem te mogen zien.',
	'blog:error:revision_not_found' => 'Kan deze revisie niet vinden.',

	// river
	'river:object:blog:create' => '%s publiceerde een blog %s',
	'river:object:blog:comment' => '%s reageerde op de blog %s',

	// notifications
	'blog:notify:summary' => 'Nieuwe blog met de titel \'%s\'',
	'blog:notify:subject' => 'Nieuwe blog: %s',
	'blog:notify:body' => '%s publiceerde een nieuwe blog: %s

%s

Bekijk en reageer hier op de blog:
%s',
	
	'notification:mentions:object:blog:subject' => '%s heeft je vermeld in een blog',

	// widget
	'widgets:blog:name' => 'Blogs',
	'widgets:blog:description' => 'Toon je laatste blogs',
	'blog:moreblogs' => 'Meer blogs',
	'blog:numbertodisplay' => 'Aantal blogs om te tonen',
);
