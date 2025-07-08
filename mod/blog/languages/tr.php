<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Bloglar',
	'collection:object:blog' => 'Bloglar',
	
	'collection:object:blog:all' => 'Tüm site blogları',
	'collection:object:blog:owner' => '%s kullanıcısının blogları',
	'collection:object:blog:group' => 'Grup blogları',
	'collection:object:blog:friends' => 'Arkadaşların blogları',
	'add:object:blog' => 'Blog gönderisi ekle',
	'edit:object:blog' => 'Blog gönderisini düzenle',
	
	'notification:object:blog:publish' => "Bir blog yayınlandığında bir bildirim gönderin",

	'blog:revisions' => 'Düzeltmeler',
	'blog:archives' => 'Arşivler',

	'groups:tool:blog' => 'Grup blogunu etkinleştir',

	// Editing
	'blog:excerpt' => 'Alıntı',
	'blog:body' => 'Gövde',
	'blog:save_status' => 'Son kayıt:',

	'blog:revision' => 'Düzeltme',
	
	// messages
	'blog:message:saved' => 'Blog gönderisi kaydedildi.',
	'blog:error:cannot_save' => 'Blog gönderisi kaydedilemedi.',
	'blog:error:cannot_write_to_container' => 'Gruba blog kaydedebilmek için yetkisiz erişim.',
	'blog:edit_revision_notice' => '(Eski sürüm)',
	'blog:none' => 'Blog gönderisi yok', // @todo remove in Elgg 7.0
	'blog:error:missing:title' => 'Lütfen bir blog başlığı girin!',
	'blog:error:missing:description' => 'Lütfen blogunuzun gövdesini girin!',
	'blog:error:post_not_found' => 'Belirtilen blog gönderisi bulunamadı.',
	'blog:error:revision_not_found' => 'Bu düzeltme bulunamadı.',

	// river

	// notifications
	'blog:notify:summary' => '%s adında yeni bir blog gönderisi',
	'blog:notify:subject' => 'Yeni blog gönderisi: %s',

	// widget
	'widgets:blog:name' => 'Blog gönderileri',
	'widgets:blog:description' => 'Son blog gönderilerinizi göster',
	'blog:moreblogs' => 'Daha fazla blog gönderisi',
	'blog:numbertodisplay' => 'Gösterilecek blog gönderisi sayısı',
);
