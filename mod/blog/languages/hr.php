<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Blog',
	'collection:object:blog' => 'Blogovi',
	'collection:object:blog:all' => 'Svi blogovi',
	'collection:object:blog:owner' => '%sblog',
	'collection:object:blog:group' => 'Skupni blogovi',
	'collection:object:blog:friends' => 'Blog prijatelja',
	'add:object:blog' => 'Dodaj objavu na blogu',
	'edit:object:blog' => 'Uredi objavu na blogu',

	'blog:revisions' => 'Revizije',
	'blog:archives' => 'Arhive',

	'groups:tool:blog' => 'Omogući skupni blog',

	// Editing
	'blog:excerpt' => 'Izvod',
	'blog:body' => 'Sadržaj',
	'blog:save_status' => 'Zadnja izmjena:',

	'blog:revision' => 'Revizija',
	'blog:auto_saved_revision' => 'Automatski spremljena revizija',

	// messages
	'blog:message:saved' => 'Objava na blogu je sačuvana. ',
	'blog:error:cannot_save' => 'Nije moguće sačuvati objavu na blogu. ',
	'blog:error:cannot_auto_save' => 'Nije moguće automatski sačuvati objavu na blogu. ',
	'blog:error:cannot_write_to_container' => 'Nije dozvoljeno spremanje bloga u grupi. ',
	'blog:messages:warning:draft' => 'Postoji nesačuvana inačica ove objave!',
	'blog:edit_revision_notice' => '(Prethodna inačica)',
	'blog:none' => 'Ne postoji objava na blogu. ',
	'blog:error:missing:title' => 'Molimo upišite naslov bloga!',
	'blog:error:missing:description' => 'Molimo upišite sadržaj bloga!',
	'blog:error:post_not_found' => 'Nije moguće pronaći navedenu objavu na blogu. ',
	'blog:error:revision_not_found' => 'Nije moguće pronaći ovu reviziju. ',

	// river
	'river:object:blog:create' => '%s publicirao je objavu na blogu %s',
	'river:object:blog:comment' => '%s komentirao je na blogu %s',

	// notifications
	'blog:notify:summary' => 'Nova objava na blogu ima naslov  %s',
	'blog:notify:subject' => 'Nova objava na blogu: %s',

	// widget
	'widgets:blog:name' => 'Objava na blogu',
	'widgets:blog:description' => 'Prikaži zadnju objavu na blogu',
	'blog:moreblogs' => 'Više objava na blogu',
	'blog:numbertodisplay' => 'Broj objava na blogu koje će se prikazati',
);
