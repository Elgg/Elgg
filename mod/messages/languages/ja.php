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

	'messages' => "メッセージ",
	'messages:unreadcount' => "未読 %s 件",
	'messages:user' => "%s さんの受信箱",
	'messages:inbox' => "受信箱",
	'messages:sent' => "送信済みメッセージ",
	'messages:message' => "メッセージ",
	'messages:title' => "タイトル",
	'messages:to:help' => "受取人のユーザ名を入力してください。",
	'messages:inbox' => "受信箱",
	'messages:sendmessage' => "メッセージの送信",
	'messages:add' => "メッセージの作成",
	'messages:sentmessages' => "送信済みメッセージ",
	'messages:toggle' => '全てを選択',
	'messages:markread' => '既読マーク',

	'notification:method:site' => 'サイト',

	'messages:error' => 'メッセージの保存の際に問題が発生しました。もう一度やり直してください。',

	'item:object:messages' => 'メッセージ',
	'collection:object:messages' => 'メッセージ',

	/**
	* Status messages
	*/

	'messages:posted' => "メッセージを送信しました。",
	'messages:success:delete' => 'メッセージを削除しました',
	'messages:success:read' => 'メッセージを「既読」にしました',
	'messages:error:messages_not_selected' => '選択されてるメッセージはありません。',

	/**
	* Email messages
	*/

	'messages:email:subject' => '新しいメッセージが届きました！',
	'messages:email:body' => " %s さんからのメッセージがあります。

内容:

%s

めっせーじをみるには:
%s

%s さんにメッセージを送るには:
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "申し訳ありません。メッセージの本文が空欄のため、保存できません。。",
	'messages:nomessages' => "メッセージがありません。",
	'messages:user:nonexist' => "ユーザー一覧にその送信先がありません。",
	'messages:user:blank' => "送信先を指定してください。",
	'messages:user:self' => "自分宛にメールを送ることはできません。",
	'messages:user:notfriend' => "友達以外のユーザにメッセージを送ることはできません。",

	'messages:deleted_sender' => '削除されたユーザ',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'メッセージは友達宛のみに送ることができます。',
	'messages:settings:friends_only:help' => 'ユーザは友達宛以外にメッセージを送ることができません。',

);
