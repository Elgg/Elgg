<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blogs',
	'collection:object:blog' => 'Blogs',
	
	'collection:object:blog:all' => 'Alle blogge',
	'collection:object:blog:owner' => '%s\'s blogge',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Tilføj blogindlæg',
	'edit:object:blog' => 'Rediger blogindlæg',

	'blog:revisions' => 'Revision',
	'blog:archives' => 'Arkiver',

	'groups:tool:blog' => 'Aktiver gruppe blog',

	// Editing
	'blog:excerpt' => 'Uddrag',
	'blog:body' => 'Brødtekst',
	'blog:save_status' => 'Sidst gemt: ',

	'blog:revision' => 'Revision',
	
	// messages
	'blog:message:saved' => 'Blogindlæg gemt.',
	'blog:error:cannot_save' => 'Kan ikke gemme blogindlæg.',
	'blog:error:cannot_write_to_container' => 'Utilstrækkelig adgang til at gemme bloggen til gruppe.',
	'blog:edit_revision_notice' => '(Gammel version)',
	'blog:none' => 'Ingen blogindlæg', // @todo remove in Elgg 7.0
	'blog:error:missing:title' => 'Angiv en blog titel!',
	'blog:error:missing:description' => 'Indtast venligst brødteksten til ​​din blog!',
	'blog:error:post_not_found' => 'Kan ikke finde det specifikke blog indlæg',
	'blog:error:revision_not_found' => 'Kan ikke finde denne revision.',

	// river

	// notifications
	'blog:notify:summary' => 'Nyt blog indlæg kaldt %s',
	'blog:notify:subject' => 'Nyt blot indlæg: %s',

	// widget
	'widgets:blog:description' => 'Vis dit seneste blogindlæg',
	'blog:moreblogs' => 'Flere blogindlæg',
	'blog:numbertodisplay' => 'Antal af blogindlæg, der skal vises',
);
