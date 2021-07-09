<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s さんが %s に友達申請しました。",
	'relationship:friendrequest:pending' => "%s さんが友達になりたいと言っています。",
	'relationship:friendrequest:sent' => "あなたは %s に友達申請しました。",
	
	// plugin settings
	'friends:settings:request:description' => "既定では任意のユーザー同士友達になることができ、お互いのアクティビティをフォローすることができます。
ユーザAがユーザーBと友達になりたいときには友達申請機能を有効にしたあと、ユーザBは友達申請を承認をしなければいけません。承認後ユーザAはユーザBと友達関係になり、同時にユーザBはユーザAと友達関係になります。",
	'friends:settings:request:label' => "友達申請を有効にする",
	'friends:settings:request:help' => "ユーザは友達申請を承認する必要があります。その結果、友達関係は双方向のものとなります。",
	
	'friends:owned' => "%s さんの友達",
	'friend:add' => "友達を追加",
	'friend:remove' => "友達を削除",
	'friends:menu:request:status:pending' => "友達申請は完了していません",

	'friends:add:successful' => "あなたは %s さんと友達になりました。",
	'friends:add:duplicate' => "あなたは既に %s さんと友達です",
	'friends:add:failure' => "%s さんを友達に加える処理ができませんでした。",
	'friends:request:successful' => ' %s さんに友達申請を送りました',
	'friends:request:error' => '%s さんとの友達申請処理中にエラーが生じました',

	'friends:remove:successful' => "%s さんを友達リストから外しました。",
	'friends:remove:no_friend' => "あなたと %s さんは友達ではありません",
	'friends:remove:failure' => "%s さんを友達リストから外すことができませんでした。",

	'friends:none' => "現在友達はいません。",
	'friends:of:owned' => "%s さんと友達の人",

	'friends:of' => "Friends of",
	
	'friends:request:pending' => "友達申請の処理を中断する",
	'friends:request:pending:none' => "友達申請の処理が中断しているものはありません。",
	'friends:request:sent' => "友達申請をする",
	'friends:request:sent:none' => "友達申請はまだ１つも送られていません",
	
	'friends:num_display' => "表示する友達の数",
	
	'widgets:friends:name' => "友達",
	'widgets:friends:description' => "友達を何人か表示する",
	
	'friends:notification:request:subject' => "%s さんが友達になりたいと言っています！",
	'friends:notification:request:message' => "%s さんが %s で友達申請をしました。

友達申請を表示するには、ここをクリックしてください:
%s",
	
	'friends:notification:request:decline:subject' => "%s さんがあなたの友達申請を断りました",
	'friends:notification:request:decline:message' => "%s さんがあなたの友達申請を断りました。",
	
	'friends:notification:request:accept:subject' => "%s さんがあなたの友達申請を承認しました",
	'friends:notification:request:accept:message' => "%s さんがあなたの友達申請を承認しました。",
	
	'friends:action:friendrequest:revoke:fail' => "友達申請を取り消す処理中にエラーが生じました。もう一度お試しください",
	'friends:action:friendrequest:revoke:success' => "友達申請が取り消されました",
	
	'friends:action:friendrequest:decline:fail' => "友達申請を断る処理中にエラーが生じました。もう一度お試しください。",
	'friends:action:friendrequest:decline:success' => "友達申請が断られました",
	
	'friends:action:friendrequest:accept:success' => "友達申請が承認されました",
	
	// notification settings
	'friends:notification:settings:description' => 'あなたが友達を追加したときの既定の通知設定',
);
