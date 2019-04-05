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
	'groups:description' => '説明',
	'groups:briefdescription' => '簡単な説明',
	'groups:interests' => 'タグ',
	'groups:website' => 'Website',
	'groups:members' => 'グループメンバ',

	'groups:members_count' => '%s members',

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
	'widgets:a_users_groups:name' => 'Group membership',
	'widgets:a_users_groups:description' => 'Display the groups you are a member of on your profile',

	'groups:noaccess' => 'グループへのアクセスを許可しない',
	'groups:cantcreate' => 'あなたは、グループを作成することができません。管理者のみ作成できます。',
	'groups:cantedit' => 'このグループを編集できません。',
	'groups:saved' => 'グループを保存しました',
	'groups:save_error' => 'グループを保存できませんでした',
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
	'groups:invite:friends:help' => 'Search for a friend by name or username and select the friend from the list',
	'groups:invite:resend' => 'Resend the invitations to already invited users',

	'groups:nofriendsatall' => '招待する友達がいません',
	'groups:group' => "グループ",
	'groups:search:tags' => "タグ",
	'groups:search:title' => "「 %s 」でタグ付けされたグループを検索する",
	'groups:search:none' => "検索に引っかかったグループはありませんでした",
	'groups:search_in_group' => "このグループ内を検索",
	'groups:acl' => "グループ: %s",
	'groups:acl:in_context' => 'Group members',

	'groups:notfound' => "グループが見つかりません。",
	
	'groups:requests:none' => '現在、会員リクエストはありません。',

	'groups:invitations:none' => '現在、グループへの招待はありません。',

	'groups:open' => "オープングループ",
	'groups:closed' => "クローズドグループ",
	'groups:member' => "会員",
	'groups:search' => "Search for groups",

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

	'admin:groups' => 'グループ',

	'groups:notitle' => 'グループ作成にはグループ名が必要です。',
	'groups:cantjoin' => 'グループに参加できません。',
	'groups:cantleave' => 'グループから脱退することができません。',
	'groups:removeuser' => 'グループから削除',
	'groups:cantremove' => 'グループからユーザを削除することができません。',
	'groups:removed' => '%sさんをグループから削除しました。',
	'groups:addedtogroup' => 'グループにユーザーを追加しました。',
	'groups:joinrequestnotmade' => 'グループ参加の申請に失敗しました。',
	'groups:joinrequestmade' => 'グループ参加希望を申請しました。',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'グループに参加しました！',
	'groups:left' => 'グループから脱退しました。',
	'groups:userinvited' => 'ユーザを招待しました。',
	'groups:usernotinvited' => 'ユーザを招待できませんでした。',
	'groups:useralreadyinvited' => 'ユーザはすでに招待済みです。',
	'groups:invite:subject' => "%sさん、%s に招待されています。",
	'groups:joinrequest:remove:check' => 'この招待リクエストを削除してよいですか？',
	'groups:invite:remove:check' => 'この招待を破棄してもよろしいですか？',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group.

Click below to view your invitations:
%s",

	'groups:welcome:subject' => "ようこそ、「 %s 」グループへ！",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group.

Click below to begin posting!
%s",

	'groups:request:subject' => "%s さんは「 %s 」に参加希望を申請しました。",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group.

Click below to view their profile:
%s

or click below to view the group's join requests:
%s",

	'river:group:create' => '%s created the group %s',
	'river:group:join' => '%s joined the group %s',

	'groups:allowhiddengroups' => 'プライベート（不可視）なグループを許可しますか？',
	'groups:whocancreate' => 'グループを新規作成できる人',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Group and group contents deleted',
	'groups:notdeleted' => 'Group could not be deleted',
	'groups:deletewarning' => "このグループを削除していいですか？削除したら元に戻す事はできません！",

	'groups:invitekilled' => '招待状を削除しました。',
	'groups:joinrequestkilled' => '参加希望申請を削除しました。',
	'groups:error:addedtogroup' => "%s さんをグループに加えることができませんでした。",
	'groups:add:alreadymember' => "%s さんは、すでにこのグループのメンバーです。",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'グループプロフィール',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Transfer group icons to new location',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade aligns will align group plugin with the requirements of the new API.',
);
