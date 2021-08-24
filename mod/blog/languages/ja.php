<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'ブログ',
	'collection:object:blog' => 'ブログ',
	'collection:object:blog:all' => 'サイトの全ブログ',
	'collection:object:blog:owner' => '%s さんのブログ',
	'collection:object:blog:group' => 'グループブログ',
	'collection:object:blog:friends' => '友達のブログ',
	'add:object:blog' => 'ブログ記事を追加',
	'edit:object:blog' => 'ブログ記事を編集',
	'notification:object:blog:publish' => "ブログが公開されたときに通知を送る",
	'notifications:mute:object:blog' => "ブログ '%s' について",

	'blog:revisions' => '変更履歴',
	'blog:archives' => '書庫',

	'groups:tool:blog' => 'グループブログを使えるようにする',

	// Editing
	'blog:excerpt' => '見出し',
	'blog:body' => '本文',
	'blog:save_status' => '最後に保存:',

	'blog:revision' => '変更履歴',
	'blog:auto_saved_revision' => '自動保存された変更履歴',

	// messages
	'blog:message:saved' => 'ブログ記事を保存しました。',
	'blog:error:cannot_save' => 'ブログ記事を保存できませんでした。',
	'blog:error:cannot_auto_save' => 'ブログ記事を自動的に保存出来ません。',
	'blog:error:cannot_write_to_container' => 'あなたの権限ではグループにブログを保存する事はできません。',
	'blog:messages:warning:draft' => '保存されていない下書きの記事があります！',
	'blog:edit_revision_notice' => '(前の版)',
	'blog:none' => 'ブログ記事は一件もありません',
	'blog:error:missing:title' => 'ブログのタイトルを入力してください！',
	'blog:error:missing:description' => 'ブログの本文を入力してください！',
	'blog:error:post_not_found' => 'お探しのブログ記事を見つけることができません。',
	'blog:error:revision_not_found' => 'この変更記録を見つけることはできませんでした。',

	// river
	'river:object:blog:create' => '%s さんがブログ %s を公開しました。',
	'river:object:blog:comment' => '%s さんがブログ %s にコメントしました。',

	// notifications
	'blog:notify:summary' => '新着ブログ「%s」',
	'blog:notify:subject' => '新着ブログ: %s',

	// widget
	'widgets:blog:name' => 'ブログ記事',
	'widgets:blog:description' => 'あなたの最近のブログ記事を表示',
	'blog:moreblogs' => '別のブログ記事',
	'blog:numbertodisplay' => 'ブログ記事の表示件数',
);
