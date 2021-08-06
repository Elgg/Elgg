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

	'item:object:page' => 'ページ',
	'collection:object:page' => 'ページ',
	'collection:object:page:all' => "サイト内のページ",
	'collection:object:page:owner' => "%sさんのページ",
	'collection:object:page:friends' => "友達のページ",
	'collection:object:page:group' => "グループページ",
	'add:object:page' => "ページを追加",
	'edit:object:page' => "このページを編集",
	'notification:object:page:create' => "ページが作成されたときに通知を送る",
	'notifications:mute:object:page' => "ページ '%s' について",

	'groups:tool:pages' => 'グループページを使用する',
	
	'annotation:delete:page:success' => 'ページ履歴の削除に成功しました',
	'annotation:delete:page:fail' => 'ページ履歴を削除できませんでした',

	'pages:history' => "履歴",
	'pages:revision' => "リビジョン",

	'pages:navigation' => "ナビゲーション",

	'pages:notify:summary' => '新規ページ「%s」が追加されました',
	'pages:notify:subject' => "新規ページ: %s",
	'pages:notify:body' => '%s さんが新しいページを追加しました: %s

%s

このページの表示とコメントは:
%s',

	'pages:more' => 'More pages',
	'pages:none' => 'No pages created yet',

	/**
	* River
	**/

	'river:object:page:create' => '%s さんはページ「 %s 」を作成しました',
	'river:object:page:update' => '%s さんはページ「 %s 」を更新しました',
	'river:object:page:comment' => '%s さんはページ「 %s 」にコメントしました',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'タイトル',
	'pages:description' => '本文',
	'pages:tags' => 'タグ',
	'pages:parent_guid' => '親ページ',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'このページの編集はできません。',
	'pages:saved' => 'ページを保存しました。',
	'pages:notsaved' => 'ページを保存できません。',
	'pages:error:no_title' => 'このページにはタイトルが付けられていません。タイトルをつけてください。',
	'entity:delete:object:page:success' => 'ページを削除しました。',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'リビジョンを作成しました： %s （作成者 %s ）',

	/**
	 * Widget
	 **/

	'pages:num' => '表示数',
	'widgets:pages:name' => 'ページ',
	'widgets:pages:description' => "あなたのページを一覧表示します。",

	'pages:newchild' => "子ページを作成",
);
