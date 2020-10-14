<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blogit',
	'collection:object:blog' => 'Blogit',
	'collection:object:blog:all' => 'Kaikki blogit',
	'collection:object:blog:owner' => 'Käyttäjän %s blogit',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Ystävien blogit',
	'add:object:blog' => 'Luo uusi blogiviesti',
	'edit:object:blog' => 'Muokkaa blogiviestiä',

	'blog:revisions' => 'Versiot',
	'blog:archives' => 'Arkisto',

	'groups:tool:blog' => 'Ota käyttöön ryhmän blogi',
	'blog:write' => 'Lisää blogiviesti',

	// Editing
	'blog:excerpt' => 'Tiivistelmä',
	'blog:body' => 'Viesti',
	'blog:save_status' => 'Tallennettu viimeksi: ',

	'blog:revision' => 'Versio',
	'blog:auto_saved_revision' => 'Versio  Auto Saved Revision',

	// messages
	'blog:message:saved' => 'Blogi tallennettu.',
	'blog:error:cannot_save' => 'Blogiviestiä ei voida tallentaa.',
	'blog:error:cannot_auto_save' => 'Blogin automaattinen tallentaminen ei toimi.',
	'blog:error:cannot_write_to_container' => 'Sinulla ei ole oikeuksia luoda blogia tähän ryhmään.',
	'blog:messages:warning:draft' => 'Tästä blogiviestistä on tallentamaton luonnos!',
	'blog:edit_revision_notice' => '(Vanha versio)',
	'blog:message:deleted_post' => 'Blogiviesti poistettu.',
	'blog:error:cannot_delete_post' => 'Blogiviestiä ei voida poistaa.',
	'blog:none' => 'Ei blogiviestejä',
	'blog:error:missing:title' => 'Syötä blogille otsikko!',
	'blog:error:missing:description' => 'Syötä blogiviestin sisältö!',
	'blog:error:cannot_edit_post' => 'Tämä blogiviesti on saatettu poistaa tai sinulla ei ole oikeuksia sen muokkaamiseen.',
	'blog:error:post_not_found' => 'Blogiviestiä ei löydy.',
	'blog:error:revision_not_found' => 'Versiota ei löydy.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Uusi blogiviesti %s',
	'blog:notify:subject' => 'Uusi blogiviesti: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Näytä viimeisimmät blogiviestisi',
	'blog:moreblogs' => 'Lisää blogiviestejä',
	'blog:numbertodisplay' => 'Näytettävien kohteiden määrä',
);
