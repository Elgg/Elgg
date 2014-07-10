<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "ページ",
	'pages:owner' => "%sさんのページ",
	'pages:friends' => "友達のページ",
	'pages:all' => "サイト内のページ",
	'pages:add' => "ページを追加",

	'pages:group' => "グループページ",
	'groups:enablepages' => 'グループページを使用する',

	'pages:new' => "新規ページ",
	'pages:edit' => "このページを編集",
	'pages:delete' => "このページを削除",
	'pages:history' => "履歴",
	'pages:view' => "ページをみる",
	'pages:revision' => "リビジョン",
	'pages:current_revision' => "現在のリビジョン",
	'pages:revert' => "戻す",

	'pages:navigation' => "ナビゲーション",

	'pages:notify:summary' => '新規ページ「%s」が追加されました',
	'pages:notify:subject' => "新規ページ: %s",
	'pages:notify:body' =>
'%s さんが新規ページ「%s」を追加しました:

%s

このページについて閲覧・コメントするには:
%s
',
	'item:object:page_top' => '最上位のページ',
	'item:object:page' => 'ページ',
	'pages:nogroup' => 'このグループにはまだページがありません',
	'pages:more' => 'More pages',
	'pages:none' => 'No pages created yet',

	/**
	* River
	**/

	'river:create:object:page' => '%sさんがページ「%s」を作成しました。',
	'river:create:object:page_top' => '%sさんがページ「%s」を作成しました。',
	'river:update:object:page' => '%sさんがページ「%s」を更新しました。',
	'river:update:object:page_top' => '%sさんがページ「%s」を更新しました。',
	'river:comment:object:page' => '%sさんがページ「%s」にコメントしました。',
	'river:comment:object:page_top' => '%sさんがページ「%s」にコメントしました。',

	/**
	 * Form fields
	 */

	'pages:title' => 'タイトル',
	'pages:description' => '本文',
	'pages:tags' => 'タグ',
	'pages:parent_guid' => '親ページ',
	'pages:access_id' => '公開範囲',
	'pages:write_access_id' => '書込許可',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'ページを閲覧できません。',
	'pages:cantedit' => 'このページの編集はできません。',
	'pages:saved' => 'ページを保存しました。',
	'pages:notsaved' => 'ページを保存できません。',
	'pages:error:no_title' => 'このページにはタイトルが付けられていません。タイトルをつけてください。',
	'pages:delete:success' => 'ページを削除しました。',
	'pages:delete:failure' => 'ページが削除できません。',
	'pages:revision:delete:success' => 'ページのリビジョンを削除しました。',
	'pages:revision:delete:failure' => 'ページのリビジョンを削除できませんでした。',
	'pages:revision:not_found' => 'このリビジョンを見つけることができませんでした。',

	/**
	 * Page
	 */
	'pages:strapline' => '最終更新： %s （更新者 %s ）',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'リビジョンを作成しました： %s （作成者 %s ）',

	/**
	 * Widget
	 **/

	'pages:num' => '表示数',
	'pages:widget:description' => "あなたのページを一覧表示します。",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "ページをみる",
	'pages:label:edit' => "ページの編集",
	'pages:label:history' => "履歴",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "このページ",
	'pages:sidebar:children' => "子ページ",
	'pages:sidebar:parent' => "親ページ",

	'pages:newchild' => "子ページを作成",
	'pages:backtoparent' => "「 %s 」にもどる",
);
