<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "グループ",
	'groups:owned' => "私が作ったグループ",
	'groups:owned:user' => '%s さんが作ったグループ',
	'groups:yours' => "My グループ",
	'groups:user' => "%s さんのグループ",
	'groups:all' => "全グループ",
	'groups:add' => "新規グループの作成",
	'groups:edit' => "グループの編集",
	'groups:delete' => 'グループの削除',
	'groups:membershiprequests' => '参加リクエストの管理',
	'groups:membershiprequests:pending' => '参加リクエストの管理 (%s)',
	'groups:invitations' => 'グループへの招待',
	'groups:invitations:pending' => 'グループへの招待 (%s)',

	'groups:icon' => 'グループアイコン(変更なしなら空欄のまま)',
	'groups:name' => 'グループ名',
	'groups:username' => 'グループの省略名(URLに表示されるので英数文字のみ使用してください)',
	'groups:description' => '説明',
	'groups:briefdescription' => '簡単な説明',
	'groups:interests' => 'タグ',
	'groups:website' => 'Website',
	'groups:members' => 'グループメンバ',
	'groups:my_status' => 'Myステータス',
	'groups:my_status:group_owner' => 'このグループのオーナーです',
	'groups:my_status:group_member' => 'このグループのメンバーです',
	'groups:subscribed' => 'グループから通知 on',
	'groups:unsubscribed' => 'グループからの通知 off',

	'groups:members:title' => '%s のメンバ',
	'groups:members:more' => "メンバ一覧",
	'groups:membership' => "グループ参加の許可",
	'groups:content_access_mode' => "グループコンテントへのアクセス（読み書き）の設定",
	'groups:content_access_mode:warning' => "警告: 変更はこれから作成されるコンテントのみ有効で、すでに存在しているグループコンテントに対しては効果ありません。",
	'groups:content_access_mode:unrestricted' => "制限なし - アクセスはコンテントレベルでの設定に依ります。",
	'groups:content_access_mode:membersonly' => "メンバ限定 - 非メンバはグループコンテントに決してアクセスできません。",
	'groups:access' => "公開範囲の許可",
	'groups:owner' => "班長",
	'groups:owner:warning' => "警告: この値を変更しますと、あなたはこのグループの班長ではなくなってしまいますが、よろしいでしょうか。",
	'groups:widget:num_display' => '一覧表示数',
	'groups:widget:membership' => '参加グループ',
	'groups:widgets:description' => '所属するグループをプロフィールに表示する',

	'groups:widget:group_activity:title' => 'グループアクティビティ',
	'groups:widget:group_activity:description' => 'あなたの所属する１グループのアクティビティを見る',
	'groups:widget:group_activity:edit:select' => 'グループを選択してください',
	'groups:widget:group_activity:content:noactivity' => 'このグループでのアクティビティはありません',
	'groups:widget:group_activity:content:noselect' => 'グループを選択するにはこのウィジェットを編集してください',

	'groups:noaccess' => 'グループへのアクセスを許可しない',
	'groups:permissions:error' => 'あなたの権限ではこれはできません',
	'groups:ingroup' => 'グループ内で',
	'groups:cantcreate' => 'あなたは、グループを作成することができません。管理者のみ作成できます。',
	'groups:cantedit' => 'このグループを編集できません。',
	'groups:saved' => 'グループを保存しました',
	'groups:save_error' => 'Group could not be saved',
	'groups:featured' => 'クローズアップ',
	'groups:makeunfeatured' => 'クローズアップをやめる',
	'groups:makefeatured' => 'クローズアップに登録する',
	'groups:featuredon' => '%s は、クローズアップ欄に表示されます',
	'groups:unfeatured' => '%s は、クローズアップ欄から外されました',
	'groups:featured_error' => '不正なグループです。',
	'groups:nofeatured' => 'クローズアップされたグループはありません',
	'groups:joinrequest' => '参加希望',
	'groups:join' => 'グループに参加',
	'groups:leave' => '脱退',
	'groups:invite' => '友達を招待',
	'groups:invite:title' => 'このグループに友達を招待する',
	'groups:inviteto' => "%s に友達を招待",
	'groups:nofriends' => "すでにあなたのすべての友達をこのグループに招待しています。",
	'groups:nofriendsatall' => '招待する友達がいません',
	'groups:viagroups' => "(グループから)",
	'groups:group' => "グループ",
	'groups:search:tags' => "タグ",
	'groups:search:title' => "「 %s 」でタグ付けされたグループを検索する",
	'groups:search:none' => "検索に引っかかったグループはありませんでした",
	'groups:search_in_group' => "このグループ内を検索",
	'groups:acl' => "グループ: %s",

	'discussion:topic:notify:summary' => '新しい議題「%s」が投稿されました',
	'discussion:topic:notify:subject' => '新着議題: %s',
	'discussion:topic:notify:body' =>
'%s さんは、グループ %s に新しい議題を投稿しました:

題名: %s

%s

この議題を閲覧したり議題に返答するには:
%s
',

	'discussion:reply:notify:summary' => '議題 %s に対して新しい返答がありました。',
	'discussion:reply:notify:subject' => '新着返答:  議題 %s',
	'discussion:reply:notify:body' =>
'%1$s さんがグループ %3$s の議題「%2$s」に返答しました:

%4$s

この会議を閲覧したり議題に返答するには:
%5$s
',

	'groups:activity' => "グループのうごき",
	'groups:enableactivity' => 'グループのうごきを有効にする',
	'groups:activity:none' => "グループのうごきはありません。",

	'groups:notfound' => "グループが見つかりません。",
	'groups:notfound:details' => "グループは存在しないか、アクセス許可がありません。",

	'groups:requests:none' => '現在、会員リクエストはありません。',

	'groups:invitations:none' => '現在、グループへの招待はありません。',

	'item:object:groupforumtopic' => "議題",
	'item:object:discussion_reply' => "議論の返答",

	'groupforumtopic:new' => "新規投稿",

	'groups:count' => "グループ数",
	'groups:open' => "オープングループ",
	'groups:closed' => "クローズドグループ",
	'groups:member' => "会員",
	'groups:searchtag' => "タグでグループを検索",

	'groups:more' => '次のグループ',
	'groups:none' => 'グループはまだ作られていません',

	/**
	 * Access
	 */
	'groups:access:private' => 'クローズド - 招待制です。',
	'groups:access:public' => 'フリー参加 - 誰でも参加できます。',
	'groups:access:group' => 'グループ参加者のみ',
	'groups:closedgroup' => "ここは参加者限定のグループ（クローズド・グループ）です。",
	'groups:closedgroup:request' => 'このグループへの参加をご希望される場合は「参加希望」をクリックしてください。参加リクエストが送信されます。',
	'groups:closedgroup:membersonly' => "このグループはクローズドグループなのでグループ参加者以外の方にはアクセスできません。",
	'groups:opengroup:membersonly' => "このグループのコンテントにはグループ参加者のみアクセスできます。",
	'groups:opengroup:membersonly:join' => 'グループに参加するには、「参加」メニューリンクをクリックしてください。',
	'groups:visibility' => 'このグループのコンテンツをみることができる人',

	/**
	 * Group tools
	 */
	'groups:enableforum' => 'グループ会議の利用',
	'groups:lastupdated' => '最終更新 %s(%s さん)',
	'groups:lastcomment' => '最新コメント %s(%s さん)',

	/**
	 * Group discussion
	 */
	'discussion' => '会議室',
	'discussion:add' => '議題を追加',
	'discussion:latest' => '最新の話題',
	'discussion:group' => 'グループ会議',
	'discussion:none' => '議論はありません',
	'discussion:reply:title' => '%s さんからの返答',

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
	
	'admin:groups' => 'グループ',

	'reply:this' => '返答する',

	'group:replies' => '返答',
	'groups:forum:created' => '%s（返答 %d 件）を作成しました',
	'groups:forum:created:single' => '%s（返答 %d 件）を作成しました',
	'groups:forum' => 'グループ会議',
	'groups:addtopic' => '新規議題作成',
	'groups:forumlatest' => '最新のやりとり',
	'groups:latestdiscussion' => '最新のやりとり',
	'groupspost:success' => 'コメントを投稿しました。',
	'groupspost:failure' => '返答を保存スル際に問題が発生しました。',
	'groups:alldiscussion' => '最新のやりとり',
	'groups:edittopic' => '議題の編集',
	'groups:topicmessage' => '議題メッセージ',
	'groups:topicstatus' => '議題の状態',
	'groups:reply' => 'コメントの投稿',
	'groups:topic' => '議題',
	'groups:posts' => '投稿',
	'groups:lastperson' => '最後に投稿した人',
	'groups:when' => 'いつ',
	'grouptopic:notcreated' => '議題はありません。',
	'groups:topicclosed' => '議論終了',
	'grouptopic:created' => '議題を作成しました。',
	'groups:topicsticky' => 'スティッキー',
	'groups:topicisclosed' => 'この議題は終了しました。',
	'groups:topiccloseddesc' => 'この議題は終了しました。新しいコメントは受け付けていません。',
	'grouptopic:error' => 'グループ議題が作成できません。システム管理者に問い合わせください。',
	'groups:forumpost:edited' => "投稿を編集しました。",
	'groups:forumpost:error' => "投稿を編集する際に問題が発生しました。",

	'groups:privategroup' => 'このグループはクローズドグループです。参加希望を申請してください。',
	'groups:notitle' => 'グループ作成にはグループ名が必要です。',
	'groups:cantjoin' => 'グループに参加できません。',
	'groups:cantleave' => 'グループから脱退することができません。',
	'groups:removeuser' => 'グループから削除',
	'groups:cantremove' => 'グループからユーザを削除することができません。',
	'groups:removed' => '%sさんをグループから削除しました。',
	'groups:addedtogroup' => 'グループにユーザーを追加しました。',
	'groups:joinrequestnotmade' => 'グループ参加の申請に失敗しました。',
	'groups:joinrequestmade' => 'グループ参加希望を申請しました。',
	'groups:joined' => 'グループに参加しました！',
	'groups:left' => 'グループから脱退しました。',
	'groups:notowner' => '申し訳ありません。あなたはこのグループの班長ではありません。',
	'groups:notmember' => '申し訳ありません。あなたはこのグループの参加者ではありません。',
	'groups:alreadymember' => 'あなたはすでにこのグループの参加者です。',
	'groups:userinvited' => 'ユーザを招待しました。',
	'groups:usernotinvited' => 'ユーザを招待できませんでした。',
	'groups:useralreadyinvited' => 'ユーザはすでに招待済みです。',
	'groups:invite:subject' => "%sさん、%s に招待されています。",
	'groups:updated' => "最新の返答:(%s さんより)「 %s 」",
	'groups:started' => "開始日: %s",
	'groups:joinrequest:remove:check' => 'この招待リクエストを削除してよいですか？',
	'groups:invite:remove:check' => 'この招待を破棄してもよろしいですか？',
	'groups:invite:body' => "%s さん、こんにちは。

%s さんがあなたをグループ 「%s」 に招待しています。下のリンクをクリックして招待状を覧下さい。

%s",

	'groups:welcome:subject' => "ようこそ、「 %s 」グループへ！",
	'groups:welcome:body' => "%s さん、こんにちは。

あなたは「%s」グループに参加しました！ 下のリンクからグループページへアクセスできます。

%s",

	'groups:request:subject' => "%s さんは「 %s 」に参加希望を申請しました。",
	'groups:request:body' => "%s さん、こんにちは。

%s さんはグループ「 %s 」への参加を希望しています。プロフィールを見るには：

%s

グループの参加希望を見るには：

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s さんは、グループ「 %s 」を作成しました。',
	'river:join:group:default' => '%s さんは、グループ「 %s 」に参加しました。',
	'river:create:object:groupforumtopic' => '%s さんは、新しく議題「 %s 」を追加しました。',
	'river:reply:object:groupforumtopic' => '%s さんは、議題「 %s 」に返答しました',
	'river:reply:view' => '返答を見る',

	'groups:nowidgets' => 'このグループに設定されているウィジェットはありません。',


	'groups:widgets:members:title' => 'グループメンバ',
	'groups:widgets:members:description' => 'メンバ一覧',
	'groups:widgets:members:label:displaynum' => 'メンバ一覧',
	'groups:widgets:members:label:pleaseedit' => 'このウィジェットを設定してください。',

	'groups:widgets:entities:title' => "グループのオブジェクト",
	'groups:widgets:entities:description' => "このグループで保存されたオブジェクト一覧。",
	'groups:widgets:entities:label:displaynum' => 'グループのオブジェクト一覧',
	'groups:widgets:entities:label:pleaseedit' => 'このウィジェットを設定してください。',

	'groups:forumtopic:edited' => '議題を編集しました。',

	'groups:allowhiddengroups' => 'プライベート（不可視）なグループを許可しますか？',
	'groups:whocancreate' => 'グループを新規作成できる人',

	/**
	 * Action messages
	 */
	'group:deleted' => 'グループとグループのコンテンツを削除しました。',
	'group:notdeleted' => 'グループが削除できませんでした。',

	'group:notfound' => 'そのグループは見つけることができませんでした。',
	'grouppost:deleted' => '投稿を削除しました。',
	'grouppost:notdeleted' => '投稿が削除できませんでした。',
	'groupstopic:deleted' => '議題を削除しました。',
	'groupstopic:notdeleted' => '議題を削除できませんでした。',
	'grouptopic:blank' => '議題はありません。',
	'grouptopic:notfound' => 'その議題は見つかりませんでした。',
	'grouppost:nopost' => '投稿がありません。',
	'groups:deletewarning' => "このグループを削除していいですか？削除したら元に戻す事はできません！",

	'groups:invitekilled' => '招待状を削除しました。',
	'groups:joinrequestkilled' => '参加希望申請を削除しました。',
	'groups:error:addedtogroup' => "%s さんをグループに加えることができませんでした。",
	'groups:add:alreadymember' => "%s さんは、すでにこのグループのメンバーです。",

	/**
	 * ecml
	 */
	'groups:ecml:discussion' => 'グループ会議',
	'groups:ecml:groupprofile' => 'グループプロフィール',
);
