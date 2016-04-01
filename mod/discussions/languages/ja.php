<?php

return array(
	'discussion' => '会議室',
	'discussion:add' => '議題を追加',
	'discussion:latest' => '最新の話題',
	'discussion:group' => 'グループ会議',
	'discussion:none' => '議論はありません',
	'discussion:reply:title' => '%s さんからの返答',
	'discussion:new' => "新規投稿",
	'discussion:updated' => "最新の返答:(%s さんより)「 %s 」",

	'discussion:topic:created' => '議題を作成しました',
	'discussion:topic:updated' => '議題を更新しました',
	'discussion:topic:deleted' => '議題を削除しました',

	'discussion:topic:notfound' => '議題は見つかりませんでした',
	'discussion:error:notsaved' => 'この議題を保存できませんでした',
	'discussion:error:missing' => 'タイトルとメッセージは必須項目です',
	'discussion:error:permissions' => 'あなたには、このアクションを行う権限がありません',
	'discussion:error:notdeleted' => '議題を削除できませんでした。',

	'discussion:reply:edit' => '返答を編集',
	'discussion:reply:deleted' => '返答を削除しました。',
	'discussion:reply:error:notfound' => '議論の返答は見つかりませんでした。',
	'discussion:reply:error:notfound_fallback' => "申し訳ありません。お探しの返答は見つかりませんでした。オリジナルの会議の議論にご案内します。",
	'discussion:reply:error:notdeleted' => '返答を削除することができませんでした。',

	'discussion:search:title' => '議題「 %s 」に返答する',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => '返答が空なので投稿できません',
	'discussion:reply:topic_not_found' => '議題は見つかりませんでした',
	'discussion:reply:error:cannot_edit' => 'あなたには、この返答を編集する権限がありません',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s さんは、新しく議題「 %s 」を追加しました。',
	'river:reply:object:discussion' => '%s さんは、議題「 %s 」に返答しました',
	'river:reply:view' => '返答を見る',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => '新しい議題「%s」が投稿されました',
	'discussion:topic:notify:subject' => '新着議題: %s',
	'discussion:topic:notify:body' =>
'%s さんが新しい議題 「%s」を追加しました:

%s

閲覧または返答するには:
%s
',

	'discussion:reply:notify:summary' => '新着返答:  議題 %s',
	'discussion:reply:notify:subject' => '新着返答:  議題 %s',
	'discussion:reply:notify:body' =>
'%sさんが議題「%s」に返答しました:

%s

閲覧または返答するには:
%s
',

	'item:object:discussion' => "議題",
	'item:object:discussion_reply' => "議論の返答",

	'groups:enableforum' => 'グループ会議の利用',

	'reply:this' => '返答する',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'グループ会議',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => '議題の状態',
	'discussion:topic:closed:title' => 'この議題は終了しました。',
	'discussion:topic:closed:desc' => 'この議題は終了しました。新しいコメントは受け付けていません。',

	'discussion:replies' => '返信',
	'discussion:addtopic' => '議題を追加',
	'discussion:post:success' => 'コメントを投稿しました',
	'discussion:post:failure' => '返答を保存する際に問題が発生しました',
	'discussion:topic:edit' => '議題の編集',
	'discussion:topic:description' => '議題メッセージ',

	'discussion:reply:edited' => "投稿を編集しました。",
	'discussion:reply:error' => "投稿を編集する際に問題が発生しました。",
);
