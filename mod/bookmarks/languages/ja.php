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

	'bookmarks:this' => "このページをブックマークする",
	'bookmarks:this:group' => "%s のブックマーク",
	'bookmarks:bookmarklet' => "ブックマークレットの取得",
	'bookmarks:bookmarklet:group' => "グループのブックマークレットの取得",
	'bookmarks:address' => "ブックマークのアドレス",
	'bookmarks:none' => 'ブックマークはひとつも登録されていません',

	'bookmarks:notify:summary' => '新着ブックマーク「%s」があります。',
	'bookmarks:notify:subject' => '新着ブックマーク: %s',
	'bookmarks:notify:body' =>
'%s added a new bookmark: %s

Address: %s

%s

View and comment on the bookmark:
%s
',

	'bookmarks:numbertodisplay' => '表示するブックマークの件数',

	'river:object:bookmarks:create' => '%s bookmarked %s',
	'river:object:bookmarks:comment' => '%s commented on a bookmark %s',

	'groups:tool:bookmarks' => 'グループブックマークの利用',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'ブックマーク',
	'widgets:bookmarks:description' => "あなたが最近つけたブックマークを表示します。",

	'bookmarks:bookmarklet:description' =>
			"A bookmarklet is a special kind of button you save to your browser's links bar. This allows you to save any resource you find on the web to your bookmarks. To set it up, drag the button below to your browser's links bar:",

	'bookmarks:bookmarklet:descriptionie' =>
			"Internet Explorerをお使いの方はブックマークレットアイコンを右クリックしてから「お気に入りに保存」を選択していただき、その後、リンクバーに登録してください。",

	'bookmarks:bookmarklet:description:conclusion' =>
			"ブラウザのボタンをクリックすると訪れたページをブックマークすることができあます。",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "ブックマークに登録しました。",
	'entity:delete:object:bookmarks:success' => "The bookmark was deleted.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "ブックマークに登録できませんでした。タイトル欄とアドレス欄に入力したことを確かめて、もう一度試してみてください。",
	'bookmarks:unknown_bookmark' => 'お探しのブックマークを見つけることができません。',
);
