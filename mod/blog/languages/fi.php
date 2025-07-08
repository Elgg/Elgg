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
	'collection:object:blog:friends' => 'Ystävien blogit',
	'add:object:blog' => 'Luo uusi blogiviesti',
	'edit:object:blog' => 'Muokkaa blogiviestiä',

	'blog:revisions' => 'Versiot',
	'blog:archives' => 'Arkisto',

	'groups:tool:blog' => 'Ota käyttöön ryhmän blogi',

	// Editing
	'blog:excerpt' => 'Tiivistelmä',
	'blog:body' => 'Viesti',
	'blog:save_status' => 'Tallennettu viimeksi: ',

	'blog:revision' => 'Versio',
	
	// messages
	'blog:message:saved' => 'Blogi tallennettu.',
	'blog:error:cannot_save' => 'Blogiviestiä ei voida tallentaa.',
	'blog:error:cannot_write_to_container' => 'Sinulla ei ole oikeuksia luoda blogia tähän ryhmään.',
	'blog:edit_revision_notice' => '(Vanha versio)',
	'blog:none' => 'Ei blogiviestejä', // @todo remove in Elgg 7.0
	'blog:error:missing:title' => 'Syötä blogille otsikko!',
	'blog:error:missing:description' => 'Syötä blogiviestin sisältö!',
	'blog:error:post_not_found' => 'Blogiviestiä ei löydy.',
	'blog:error:revision_not_found' => 'Versiota ei löydy.',

	// river

	// notifications
	'blog:notify:summary' => 'Uusi blogiviesti %s',
	'blog:notify:subject' => 'Uusi blogiviesti: %s',

	// widget
	'widgets:blog:description' => 'Näytä viimeisimmät blogiviestisi',
	'blog:moreblogs' => 'Lisää blogiviestejä',
	'blog:numbertodisplay' => 'Näytettävien kohteiden määrä',
);
