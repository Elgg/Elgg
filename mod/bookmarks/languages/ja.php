<?php
return [

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "ブックマーク",
	'bookmarks:add' => "新規ブックマーク登録",
	'bookmarks:edit' => "ブックマークを編集",
	'bookmarks:owner' => "%s さんのブックマーク",
	'bookmarks:friends' => "友達のブックマーク",
	'bookmarks:everyone' => "サイト全体のブックマーク",
	'bookmarks:this' => "このページをブックマークする",
	'bookmarks:this:group' => "%s のブックマーク",
	'bookmarks:bookmarklet' => "ブックマークレットの取得",
	'bookmarks:bookmarklet:group' => "グループのブックマークレットの取得",
	'bookmarks:inbox' => "Bookmarks inbox",
	'bookmarks:address' => "ブックマークのアドレス",
	'bookmarks:none' => 'ブックマークはひとつも登録されていません',

	'bookmarks:notify:summary' => '新着ブックマーク「%s」があります。',
	'bookmarks:notify:subject' => '新着ブックマーク: %s',
	'bookmarks:notify:body' =>
'%s さんが新しいブックマークを追加しました: %s

アドレス: %s

%s

このブックマークに対して閲覧・コメントするには:
%s
',

	'bookmarks:delete:confirm' => "削除してもよろしいですか?",

	'bookmarks:numbertodisplay' => '表示するブックマークの件数',

	'river:create:object:bookmarks' => '%s さんは、%s をブックマークに登録しました。',
	'river:comment:object:bookmarks' => '%s さんは、ブックマーク %s にコメントしました。',
	'bookmarks:river:annotate' => 'このブックマークへのコメント',
	'bookmarks:river:item' => 'アイテム',

	'item:object:bookmarks' => 'ブックマーク',

	'bookmarks:group' => 'グループブックマーク',
	'bookmarks:enablebookmarks' => 'グループブックマークの利用',
	'bookmarks:nogroup' => 'このグループには、まだブックマークが登録されていません。',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "あなたが最近つけたブックマークを表示します。",

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
	'bookmarks:delete:success' => "ブックマークから削除しました。",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "ブックマークに登録できませんでした。タイトル欄とアドレス欄に入力したことを確かめて、もう一度試してみてください。",
	'bookmarks:save:invalid' => "このブックマークのアドレスはどこか間違っていますので、保存することはできませんでした。",
	'bookmarks:delete:failed' => "ブックマークから削除できませんでした。もう一度試してみてください。",
	'bookmarks:unknown_bookmark' => 'お探しのブックマークを見つけることができません。',
];
