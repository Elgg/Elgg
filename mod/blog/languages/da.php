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
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Tilføj blogindlæg',
	'edit:object:blog' => 'Rediger blogindlæg',

	'blog:revisions' => 'Revision',
	'blog:archives' => 'Arkiver',

	'groups:tool:blog' => 'Aktiver gruppe blog',
	'blog:write' => 'Skriv et blogindlæg',

	// Editing
	'blog:excerpt' => 'Uddrag',
	'blog:body' => 'Brødtekst',
	'blog:save_status' => 'Sidst gemt: ',

	'blog:revision' => 'Revision',
	'blog:auto_saved_revision' => 'Auto gemt revision',

	// messages
	'blog:message:saved' => 'Blogindlæg gemt.',
	'blog:error:cannot_save' => 'Kan ikke gemme blogindlæg.',
	'blog:error:cannot_auto_save' => 'Kan ikke automatisk gemme blog indlægget',
	'blog:error:cannot_write_to_container' => 'Utilstrækkelig adgang til at gemme bloggen til gruppe.',
	'blog:messages:warning:draft' => 'Der er en ikke gemt kladde til dette indlæg!',
	'blog:edit_revision_notice' => '(Gammel version)',
	'blog:message:deleted_post' => 'Blogindlæg slettet.',
	'blog:error:cannot_delete_post' => 'Kan ikke slette blogindlæg.',
	'blog:none' => 'Ingen blogindlæg',
	'blog:error:missing:title' => 'Angiv en blog titel!',
	'blog:error:missing:description' => 'Indtast venligst brødteksten til ​​din blog!',
	'blog:error:cannot_edit_post' => 'Dette indlæg eksisterer måske ikke, eller du har måske ikke tilladelse til at redigere det.',
	'blog:error:post_not_found' => 'Kan ikke finde det specifikke blog indlæg',
	'blog:error:revision_not_found' => 'Kan ikke finde denne revision.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Nyt blog indlæg kaldt %s',
	'blog:notify:subject' => 'Nyt blot indlæg: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Vis dit seneste blogindlæg',
	'blog:moreblogs' => 'Flere blogindlæg',
	'blog:numbertodisplay' => 'Antal af blogindlæg, der skal vises',
);
