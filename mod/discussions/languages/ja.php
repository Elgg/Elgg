<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "議題",
	
	'add:object:discussion' => '議題を追加',
	'edit:object:discussion' => '議題の編集',
	'collection:object:discussion:group' => 'グループ会議',

	'discussion:latest' => '最新の話題',
	'discussion:none' => '議論はありません',
	'discussion:updated' => "最新の返答:(%s さんより)「 %s 」",

	'discussion:topic:created' => '議題を作成しました',
	'discussion:topic:updated' => '議題を更新しました',
	'entity:delete:object:discussion:success' => '議題を削除しました',

	'discussion:topic:notfound' => '議題は見つかりませんでした',
	'discussion:error:notsaved' => 'この議題を保存できませんでした',
	'discussion:error:missing' => 'タイトルとメッセージは必須項目です',
	'discussion:error:permissions' => 'あなたには、このアクションを行う権限がありません',

	/**
	 * River
	 */
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => '新しい議題「%s」が投稿されました',
	'discussion:topic:notify:subject' => '新着議題: %s',
	'discussion:topic:notify:body' =>
'',

	'discussion:comment:notify:summary' => '新着返答:  議題 %s',
	'discussion:comment:notify:subject' => '新着返答:  議題 %s',
	'discussion:comment:notify:body' =>
'',

	'groups:tool:forum' => 'グループ会議の利用',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => '議題の状態',
	'discussion:topic:closed:title' => 'この議題は終了しました。',
	'discussion:topic:closed:desc' => 'この議題は終了しました。新しいコメントは受け付けていません。',

	'discussion:topic:description' => '議題メッセージ',
);
