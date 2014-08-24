<?php
return array(
	'blog' => 'ブログ',
	'blog:blogs' => 'ブログ',
	'blog:revisions' => '変更履歴',
	'blog:archives' => '書庫',
	'blog:blog' => 'ブログ',
	'item:object:blog' => 'ブログ',

	'blog:title:user_blogs' => '%s さんのブログ',
	'blog:title:all_blogs' => 'サイトの全ブログ',
	'blog:title:friends' => '友達のブログ',

	'blog:group' => 'グループブログ',
	'blog:enableblog' => 'グループブログを使えるようにする',
	'blog:write' => 'ブログに投稿する',

	// Editing
	'blog:add' => 'ブログ記事を追加',
	'blog:edit' => 'ブログ記事を編集',
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
	'blog:message:deleted_post' => 'ブログ記事を削除しました。',
	'blog:error:cannot_delete_post' => 'ブログ記事を削除できませんでした。',
	'blog:none' => 'ブログ記事は一件もありません',
	'blog:error:missing:title' => 'ブログのタイトルを入力してください！',
	'blog:error:missing:description' => 'ブログの本文を入力してください！',
	'blog:error:cannot_edit_post' => 'この記事は存在していないか、あるいはあなたにこの記事を編集する権限がないかのどちらかです。',
	'blog:error:post_not_found' => 'お探しのブログ記事を見つけることができません。',
	'blog:error:revision_not_found' => 'この変更記録を見つけることはできませんでした。',

	// river
	'river:create:object:blog' => '%s さんは、ブログ「%s」を公表しました。',
	'river:comment:object:blog' => '%s さんは、ブログ「%s」にコメントしました。',

	// notifications
	'blog:notify:summary' => '新着ブログ「%s」',
	'blog:notify:subject' => '新着ブログ: %s',
	'blog:notify:body' =>
'
%s さんは、新しいブログを公開しました: %s

%s

閲覧・コメントするには、:
%s
',

	// widget
	'blog:widget:description' => 'あなたの最近のブログ記事を表示',
	'blog:moreblogs' => '別のブログ記事',
	'blog:numbertodisplay' => 'ブログ記事の表示件数',
	'blog:noblogs' => 'ブログ記事は一つもありません'
);