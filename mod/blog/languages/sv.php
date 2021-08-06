<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blogg',
	'collection:object:blog' => 'Bloggar',
	'collection:object:blog:all' => 'Alla bloggar på sajten',
	'collection:object:blog:owner' => '%ss bloggar',
	'collection:object:blog:group' => 'Gruppbloggar',
	'collection:object:blog:friends' => 'Vänners bloggar',
	'add:object:blog' => 'Lägg till blogginlägg',
	'edit:object:blog' => 'Redigera blogginlägg',

	'blog:revisions' => 'Utgåvor',
	'blog:archives' => 'Arkiv',

	'groups:tool:blog' => 'Aktivera gruppblogg',

	// Editing
	'blog:excerpt' => 'Utdrag',
	'blog:body' => 'Innehåll',
	'blog:save_status' => 'Senast sparat:',

	'blog:revision' => 'Utgåva',
	'blog:auto_saved_revision' => 'Automatiskt Sparad Utgåva',

	// messages
	'blog:message:saved' => 'Blogginlägg sparat.',
	'blog:error:cannot_save' => 'Kan inte spara blogginlägg.',
	'blog:error:cannot_auto_save' => 'Kan inte automatiskt spara blogginlägg.',
	'blog:error:cannot_write_to_container' => 'Otillräcklig åtkomst för att spara blogg till grupp.',
	'blog:messages:warning:draft' => 'Det finns ett osparat utkast av detta inlägg!',
	'blog:edit_revision_notice' => '(Gammal version)',
	'blog:none' => 'Inga blogginlägg',
	'blog:error:missing:title' => 'Vänligen ange en titel på bloggen!',
	'blog:error:missing:description' => 'Vänligen ange innehåll av din blogg!',
	'blog:error:post_not_found' => 'Kan inte hitta det angivna blogginlägget.',
	'blog:error:revision_not_found' => 'Kan inte hitta denna utgåva.',

	// river
	'river:object:blog:create' => '%s publicerade ett blogginlägg %s',
	'river:object:blog:comment' => '%s kommenterade på bloggen %s',

	// notifications
	'blog:notify:summary' => 'Nytt blogginlägg kallat %s',
	'blog:notify:subject' => 'Nytt blogginlägg: %s',

	// widget
	'widgets:blog:name' => 'Blogginlägg',
	'widgets:blog:description' => 'Visa dina senaste blogginlägg',
	'blog:moreblogs' => 'Mer blogginlägg',
	'blog:numbertodisplay' => 'Antal blogginlägg att visa',
);
