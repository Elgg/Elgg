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
	'thewire' => "つぶやき",

	'item:object:thewire' => "つぶやき",
	'collection:object:thewire' => 'つぶやきの記事',
	'collection:object:thewire:all' => "みんなのつぶやき",
	'collection:object:thewire:owner' => "%s さんのつぶやき",
	'collection:object:thewire:friends' => "友達のつぶやき",
	'notification:object:thewire:create' => "つぶやき記事が投稿されたときに通知を送る",
	'notifications:mute:object:thewire' => "つぶやき記事 '%s' について",

	'thewire:replying' => "返信: %s (@%s) さんへ 内容",
	'thewire:thread' => "スレッド",
	'thewire:charleft' => "残りの文字数（半角文字で）",
	'thewire:tags' => "「 %s 」でタグ付けされたつぶやき",
	'thewire:noposts' => "つぶやきはありません",

	'thewire:by' => '%s さんのつぶやき',

	'thewire:form:body:placeholder' => "調子はどうですか？",
	
	/**
	 * The wire river
	 */
	'river:object:thewire:create' => "%s さんが %s に投稿しました",
	'thewire:wire' => 'つぶやき',

	/**
	 * Wire widget
	 */
	
	'widgets:thewire:description' => 'アタナの最近のつぶやきを表示',
	'thewire:num' => '表示数',
	'thewire:moreposts' => 'もっと見る',

	/**
	 * Status messages
	 */
	'thewire:posted' => "あなたのつぶやきを投稿しました。",
	'thewire:deleted' => "つぶやきを削除しまいした。",
	'thewire:blank' => "申し訳ありません、入力欄が空欄なので投稿できません。",
	'thewire:notsaved' => "申し訳ありません。このつぶやきを保存できませんでした",
	'thewire:notdeleted' => "申し訳ありません、この投稿を削除できませんでした。",

	/**
	 * Notifications
	 */
	'thewire:notify:summary' => '新しいつぶやき: %s',
	'thewire:notify:subject' => "%s さんの新しいつぶやき",
	'thewire:notify:reply' => '%s さんが %s さんのつぶやきに返答しました:',
	'thewire:notify:post' => '%s さんはつぶやきました:',
	'thewire:notify:footer' => "閲覧・返答するには:\n%s",

	/**
	 * Settings
	 */
	'thewire:settings:limit' => "つぶやきに使用できる最大の文字数:",
	'thewire:settings:limit:none' => "制限なし",
);
