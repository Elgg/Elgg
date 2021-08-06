<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'item:object:bookmarks' => 'ブックマーク',
	'collection:object:bookmarks' => 'ブックマーク',
	'collection:object:bookmarks:group' => 'グループブックマーク',
	'collection:object:bookmarks:all' => "サイト全体のブックマーク",
	'collection:object:bookmarks:owner' => "%s さんのブックマーク",
	'collection:object:bookmarks:friends' => "友達のブックマーク",
	'add:object:bookmarks' => "新規ブックマーク登録",
	'edit:object:bookmarks' => "ブックマークを編集",
	'notification:object:bookmarks:create' => "ブックマークが作成されたときに通知を送る",
	'notifications:mute:object:bookmarks' => "ブックマーク '%s' について",

	'bookmarks:this' => "このページをブックマークする",
	'bookmarks:this:group' => "%s のブックマーク",
	'bookmarks:bookmarklet' => "ブックマークレットの取得",
	'bookmarks:bookmarklet:group' => "グループのブックマークレットの取得",
	'bookmarks:address' => "ブックマークのアドレス",
	'bookmarks:none' => 'ブックマークはひとつも登録されていません',

	'bookmarks:notify:summary' => '新着ブックマーク「%s」があります。',
	'bookmarks:notify:subject' => '新着ブックマーク: %s',

	'bookmarks:numbertodisplay' => '表示するブックマークの件数',

	'river:object:bookmarks:create' => '%s さんが %s をブックマークしました',
	'river:object:bookmarks:comment' => '%s さんがブックマーク %s にコメントしました',

	'groups:tool:bookmarks' => 'グループブックマークの利用',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'ブックマーク',
	'widgets:bookmarks:description' => "あなたが最近つけたブックマークを表示します。",

	'bookmarks:bookmarklet:description' => "ブックマークレットとはブラウザのリンクバーに保存される特殊なボタンのことです。この機能を使うことによってWeb上で見つけたリソースを全部ブックマークに保存することができます。これを設定するには、下のボタンをブラウザのリンクバーにドラッグしてください:",
	'bookmarks:bookmarklet:descriptionie' => "Internet Explorerをお使いの方はブックマークレットアイコンを右クリックしてから「お気に入りに保存」を選択していただき、その後、リンクバーに登録してください。",
	'bookmarks:bookmarklet:description:conclusion' => "ブラウザのボタンをクリックすると訪れたページをブックマークすることができあます。",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "ブックマークに登録しました。",
	'entity:delete:object:bookmarks:success' => "ブックマークを削除しました",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "ブックマークに登録できませんでした。タイトル欄とアドレス欄に入力したことを確かめて、もう一度試してみてください。",
);
