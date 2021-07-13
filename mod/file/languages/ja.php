<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'ファイル',
	'collection:object:file' => 'ファイル',
	'collection:object:file:all' => "サイト全体のファイル",
	'collection:object:file:owner' => "%s さんのファイル",
	'collection:object:file:friends' => "友達のファイル",
	'collection:object:file:group' => "グループファイル",
	'add:object:file' => "ファイルをアップロード",
	'edit:object:file' => "ファイル編集",
	'notification:object:file:create' => "ファイルが作成されたときに通知を送る",
	'notifications:mute:object:file' => "ファイル '%s' について",

	'file:more' => "もっとみる",
	'file:list' => "リスト表示",

	'file:num_files' => "表示数",
	'file:replace' => 'ファイルの置き換え(変更しない場合は空欄のまま)',
	'file:list:title' => "%s さんの %s %s",

	'file:file' => "ファイル",

	'file:list:list' => 'リスト表示に切り替え',
	'file:list:gallery' => 'ギャラリー表示に切り替え',

	'file:type:' => 'ファイル',
	'file:type:all' => "すべてのファイル",
	'file:type:video' => "動画",
	'file:type:document' => "ドキュメント",
	'file:type:audio' => "音声",
	'file:type:image' => "画像",
	'file:type:general' => "その他",

	'file:user:type:video' => "%s さんの動画",
	'file:user:type:document' => "%s さんのドキュメント",
	'file:user:type:audio' => "%s さんの音声",
	'file:user:type:image' => "%s さん画像",
	'file:user:type:general' => "%s さんのその他のファイル",

	'file:friends:type:video' => "友達の動画",
	'file:friends:type:document' => "友達のドキュメント",
	'file:friends:type:audio' => "友達の音声",
	'file:friends:type:image' => "友達の画像",
	'file:friends:type:general' => "友達のその他のファイル",

	'widgets:filerepo:name' => "ファイル・ウィジェット",
	'widgets:filerepo:description' => "あなたのファイル一覧",

	'groups:tool:file' => 'グループファイルを使用する',

	'river:object:file:create' => '%s さんはファイル %s をアップロードしました',
	'river:object:file:comment' => '%s さんはファイル %s にコメントしました',

	'file:notify:summary' => '新着ファイル「%s」がアップロードされました。',
	'file:notify:subject' => '新着ファイル: %s',
	'file:notify:body' => '%s さんが新しいファイルをアップロードしました: %s

%s

表示とコメントは:
%s',

	/**
	 * Status messages
	 */

	'file:saved' => "ファイルを保存しました。",
	'entity:delete:object:file:success' => "ファイルを削除しました。",

	/**
	 * Error messages
	 */

	'file:none' => "ファイルがありません。",
	'file:uploadfailed' => "申し訳ありません。ファイルを保存できません。",
	'file:noaccess' => "あなたには、このファイルを変更する権限がありません。",
	'file:cannotload' => "ファイルをアップロードするときにエラーが生じました",
);
