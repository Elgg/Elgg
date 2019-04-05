<?php
return array(
	'item:object:blog' => 'ブログ',
	'collection:object:blog' => 'ブログ',
	'collection:object:blog:all' => 'All site blogs',
	'collection:object:blog:owner' => '%s\'s blogs',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Add blog post',
	'edit:object:blog' => 'Edit blog post',

	'blog:revisions' => '変更履歴',
	'blog:archives' => '書庫',

	'groups:tool:blog' => 'Enable group blog',
	'blog:write' => 'ブログに投稿する',

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
	'blog:message:deleted_post' => 'ブログ記事を削除しました。',
	'blog:error:cannot_delete_post' => 'ブログ記事を削除できませんでした。',
	'blog:none' => 'ブログ記事は一件もありません',
	'blog:error:missing:title' => 'ブログのタイトルを入力してください！',
	'blog:error:missing:description' => 'ブログの本文を入力してください！',
	'blog:error:cannot_edit_post' => 'この記事は存在していないか、あるいはあなたにこの記事を編集する権限がないかのどちらかです。',
	'blog:error:post_not_found' => 'お探しのブログ記事を見つけることができません。',
	'blog:error:revision_not_found' => 'この変更記録を見つけることはできませんでした。',

	// river
	'river:object:blog:create' => '%s さんがブログ %s を公開しました。',
	'river:object:blog:comment' => '%s さんがブログ %s にコメントしました。',

	// notifications
	'blog:notify:summary' => '新着ブログ「%s」',
	'blog:notify:subject' => '新着ブログ: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Display your latest blog posts',
	'blog:moreblogs' => '別のブログ記事',
	'blog:numbertodisplay' => 'ブログ記事の表示件数',
);
