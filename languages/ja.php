<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
/**
 * Sites
 */

	'item:site:site' => 'サイト',
	'collection:site:site' => 'サイト',

/**
 * Sessions
 */

	'login' => "ログイン",
	'loginok' => "ログインしました。",
	'login:empty' => "ログイン名とパスワードが必要です。",
	'login:baduser' => "あなたのログインアカウントを読み込むことができませんでした。",
	'auth:nopams' => "内部エラー。ユーザ認証機能がインストールされていません。",

	'logout' => "ログアウト",
	'logoutok' => "ログアウトしました。",
	'logouterror' => "ログアウトできませんでした。もう一度お試しください。",
	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "要求されたページはログインしないとご覧になることはできません。",
	'adminrequired' => "要求されたページは管理者でないとご覧になることはできません。",
	'membershiprequired' => "要求されたページはこのグループのメンバでないとご覧になることはできません。",
	'limited_access' => "あなたには要求されたページを閲覧する十分な権限はありません。",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "致命的なエラーです",
	'exception:contact_admin' => '復帰不可能なエラーが発生しましたのでログに記録しました。サイト管理者にコンタクトをとって次の情報を報告してください。:',

	'actionnotfound' => "%s のアクションファイルが見つかりませんでした。",
	'actionunauthorized' => 'あなたの権限では、このアクションを実行することはできません。',

	'ajax:error' => 'AJAXコールを実行中に予期せぬエラーが起こりました。おそらく、サーバへの接続が切断されたからかもしれません。',
	'ajax:not_is_xhr' => 'AJAX views には直接アクセスはできません',

	'PluginException:CannotStart' => '%s (guid: %s) は起動できず停止状態のままです。理由: %s',
	'PluginException:InvalidID' => "%s は、不正なプラグインIDです。",
	'PluginException:InvalidPath' => "%s は、不正なプラグインのpathです",
	'ElggPlugin:MissingID' => 'プラグインID (guid %s) が、ありません。',
	'ElggPlugin:Exception:CannotIncludeFile' => '%s (プラグイン %s (guid: %s))が %s に含まれていません。パーミッションを調べてください！',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'プラグイン %s (guid: %s)のViewディレクトリを %s で開くことができません。パーミッションを調べてください！',
	'ElggPlugin:InvalidAndDeactivated' => '%s は不正なプラグインですので起動されませんでした。',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:ActiveDependent' => '%s と依存関係にある他のプラグインが存在します。 このプラグインを無効にする前に、次のプラグインを先に無効にしなければなりません。: %s',

	'ElggMenuBuilder:Trees:NoParents' => '親リンクの無いメニュー項目があります',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'メニュー項目[%s]には、親リンク[%s]がありません',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'メニュー項目[%s]に対して2重登録が見つかりました',

	'RegistrationException:EmptyPassword' => 'パスワードの項目は空欄のままにはできません',
	'RegistrationException:PasswordMismatch' => 'パスワードは一致させなければなりません',
	'LoginException:BannedUser' => 'あなたはこのサイトに出入り禁止になっていますのでログインできません。',
	'LoginException:UsernameFailure' => 'ログインできません。あなたのログイン名とパスワードをもう一度お確かめください。',
	'LoginException:PasswordFailure' => 'ログインでできません。あなたのログイン名とパスワードをもう一度お確かめください。',
	'LoginException:AccountLocked' => 'ログイン失敗が多いので、あなたのアカウントをロックしています',
	'LoginException:ChangePasswordFailure' => '現在ご使用になられているパスワードのチェックに失敗しました。',
	'LoginException:Unknown' => '不明なエラーがおこりましたので、ログインできませんでした。',

	'UserFetchFailureException' => 'user_guid[%s] のユーザが存在しないため、パーミッションのチェックができません。',
	'BadRequestException' => 'リクエストが変です',

	'viewfailure' => 'View %s において内部エラーが発生しました。',
	'changebookmark' => 'このページに対するあなたのブックマークを変更してください。',
	'error:missing_data' => 'あなたのリクエストにおいていくつかデータの欠損がありました。',
	'save:fail' => 'データを保存するのに失敗しました',
	'save:success' => 'データを保存しました',

	'error:default:title' => 'アレッ？',
	'error:default:content' => 'アレッ？何かがおかしいです。',
	'error:400:title' => 'リクエストが変です',
	'error:400:content' => '申し訳ありません。そのリクエストは正しくないか不完全です。',
	'error:403:title' => '禁止',
	'error:403:content' => '申し訳ありません。要求されたページへのアクセスが許可されていません。',
	'error:404:title' => 'ページが見つかりませんでした',
	'error:404:content' => '申し訳ありません。ご要望のページを見つけることができませんでした',

	'upload:error:ini_size' => 'アップロードしようとされているファイルは、サイズが大きすぎるようです。',
	'upload:error:form_size' => 'アップロードしようとされているファイルは、サイズが大きすぎるようです。',
	'upload:error:partial' => 'ファイルのアップロードはまだ完了していませんでした。',
	'upload:error:no_file' => 'ファイルを選択してください。',
	'upload:error:no_tmp_dir' => 'アップロードされたファイルを保存出来ませんでした。',
	'upload:error:cant_write' => 'アップロードされたファイルを保存出来ませんでした。',
	'upload:error:extension' => 'アップロードされたファイルを保存出来ませんでした。',
	'upload:error:unknown' => 'ファイルアップロードに失敗しました。',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Banned',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Description',
	'table_columns:fromView:link' => 'Name/Title',
	'table_columns:fromView:icon' => 'Icon',
	'table_columns:fromView:item' => 'Item',
	'table_columns:fromView:language' => 'Language',
	'table_columns:fromView:owner' => 'Owner',
	'table_columns:fromView:time_created' => 'Time Created',
	'table_columns:fromView:time_updated' => 'Time Updated',
	'table_columns:fromView:user' => 'User',

	'table_columns:fromProperty:description' => 'Description',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Name',
	'table_columns:fromProperty:type' => 'Type',
	'table_columns:fromProperty:username' => 'Username',

	'table_columns:fromMethod:getSubtype' => 'Subtype',
	'table_columns:fromMethod:getDisplayName' => 'Name/Title',
	'table_columns:fromMethod:getMimeType' => 'MIME Type',
	'table_columns:fromMethod:getSimpleType' => 'Type',

/**
 * User details
 */

	'name' => "名前",
	'email' => "電子メール",
	'username' => "ログイン名",
	'loginusername' => "ログイン名もしくは電子メール",
	'password' => "パスワード",
	'passwordagain' => "パスワード（確認）",
	'admin_option' => "このユーザに管理者権限を与える",
	'autogen_password_option' => "Automatically generate a secure password?",

/**
 * Access
 */
	'access:label:friends' => "友達",
	'access' => "公開範囲",
	'access:limited:label' => "限定公開",
	'access:help' => "コンテンツの公開範囲を設定します。",
	'access:read' => "読み込みアクセス",
	'access:write' => "書き込みアクセス",
	'access:admin_only' => "管理者のみ",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "ダッシュボード",
	'dashboard:nowidgets' => "ダッシュボードでは、あなたのアクティビティやこのサイトでのあなたに関するコンテンツを表示させることができます。",

	'widgets:add' => 'ウィジェットを追加',
	'widgets:add:description' => "下のウィジェットボタンをクリックして、ページに追加してみてください。",
	'widget:unavailable' => 'すでに、このウィジェットを追加済みです。',
	'widget:numbertodisplay' => '表示するアイテムの数',

	'widget:delete' => '%s を削除',
	'widget:edit' => 'このウィジェットをカスタマイズする',

	'widgets' => "ウィジェット",
	'widget' => "ウィジェット",
	'item:object:widget' => "ウィジェット",
	'collection:object:widget' => 'Widgets（ウィジェット）',
	'widgets:save:success' => "ウィジェットを保存しました。",
	'widgets:save:failure' => "ウィジェットを保存できませんでした。",
	'widgets:add:success' => "ウィジェットを追加しました。",
	'widgets:add:failure' => "あなたのウィジェットを追加できませんでした。",
	'widgets:move:failure' => "ウィジェットを新しい場所に移動できませんでした。",
	'widgets:remove:failure' => "このウィジェットを削除することができませんでした",
	
/**
 * Groups
 */

	'group' => "グループ",
	'item:group' => "グループ",
	'collection:group' => 'グループ',
	'item:group:group' => "グループ",
	'collection:group:group' => 'グループ',

/**
 * Users
 */

	'user' => "ユーザ",
	'item:user' => "ユーザ",
	'collection:user' => 'ユーザ',
	'item:user:user' => 'ユーザ',
	'collection:user:user' => 'ユーザ',

	'friends' => "友達",

	'avatar' => 'アバター',
	'avatar:noaccess' => "このユーザのアバターを編集する権限はあなたにはありません",
	'avatar:create' => 'アバターを作る',
	'avatar:edit' => 'アバターを編集する',
	'avatar:upload' => '新しいアバターをアップロードする',
	'avatar:current' => '現在使用中のアバター',
	'avatar:remove' => 'アバターを削除して、デフォルトのアイコンに戻す',
	'avatar:crop:title' => 'アバターの切り貼りツール',
	'avatar:upload:instructions' => "あなたのアバターは、このサイト内であなたの顔写真として表示されます。好きな時に変更することができます。(ファイルの形式はPNG,JPG,GIFのいずれかでお願いします)",
	'avatar:create:instructions' => 'アバターに使用する画像の大きさををマウスで調整してください。加工された後がどのような感じになるか右のボックスにプレビューとして表示されます。決まったら「アバターを作成する」ボタンを押してください。加工後の画像はアバターとしてこのサイトで表示されます。',
	'avatar:upload:success' => 'アバター画像は無事アップロードされました。',
	'avatar:upload:fail' => 'アバター画像のアップロードに失敗しました',
	'avatar:resize:fail' => 'アバター画像の大きさ変更に失敗しました',
	'avatar:crop:success' => 'アバター画像の切り取りに成功しました',
	'avatar:crop:fail' => 'アバター画像の切り取りに失敗しました',
	'avatar:remove:success' => 'アバターを無事削除しました',
	'avatar:remove:fail' => 'アバターをの削除に失敗しました。',

/**
 * Feeds
 */
	'feed:rss' => 'このページをRSSフィードする',
	'feed:rss:title' => 'このページのRSSフィード',
/**
 * Links
 */
	'link:view' => 'リンクを見る',
	'link:view:all' => '全て見る',


/**
 * River
 */
	'river' => "River",
	'river:update:user:avatar' => '%sさんが、新しいアバターを設定しました',
	'river:noaccess' => 'このアイテムを見る権限がありません。',
	'river:posted:generic' => '%sさんが投稿しました。',
	'riveritem:single:user' => 'ユーザ',
	'riveritem:plural:user' => 'ユーザ',
	'river:ingroup' => '%sグループ内',
	'river:none' => '近況報告はありません',
	'river:update' => '%s さんの更新',
	'river:delete' => 'このアクティビティ項目を削除しました',
	'river:delete:success' => 'Activity item has been deleted',
	'river:delete:fail' => 'Activity item could not be deleted',
	'river:delete:lack_permission' => 'You lack permission to delete this activity item',
	'river:subject:invalid_subject' => '正しいユーザではありません',
	'activity:owner' => 'アクティビティ一覧',

/**
 * Relationships
 */

/**
 * Notifications
 */
	'notification:method:email' => 'Email',
	'notification:subject' => '%s についての通知',
	'notification:body' => '%s にて新しいアクティビティを見る',

/**
 * Search
 */

	'search' => "検索",
	'searchtitle' => "検索: %s",
	'users:searchtitle' => "ユーザ検索: %s",
	'groups:searchtitle' => "グループ検索: %s",
	'advancedsearchtitle' => "%s(%sと一致)",
	'notfound' => "検索結果なし",

	'viewtype:change' => "表示の仕方の変更",
	'viewtype:list' => "リスト",
	'viewtype:gallery' => "ギャラリ",
	'search:go' => 'Go',
	'userpicker:only_friends' => '友達のみ',

/**
 * Account
 */

	'account' => "アカウント",
	'settings' => "設定",
	'tools' => "ツール",
	'settings:edit' => '設定を編集',

	'register' => "新規登録",
	'registerok' => "あなたは %s で登録されました。",
	'registerbad' => "未知のエラーのため、登録作業が失敗しました。",
	'registerdisabled' => "システム管理者が新規登録を禁止しています。",
	'register:fields' => 'すべての項目が必須となります。',
	'registration:notemail' => 'あなたが入力したEメールアドレスは、正しいものでは無いようです。',
	'registration:userexists' => 'そのログイン名はすでに使われています。',
	'registration:usernametooshort' => 'ログイン名は半角英字で %u 文字以上にしてください。',
	'registration:usernametoolong' => 'あなたのログイン名は長過ぎます。 %u 文字以内でお願いします。',
	'registration:dupeemail' => 'そのEメールアドレスはすでに利用されています。',
	'registration:invalidchars' => '申し訳ありません。入力されたログイン名には利用できない文字「 %s 」が含まれています。次のこれらの文字は使えません: %s',
	'registration:emailnotvalid' => '申し訳ありません。入力されたEメールアドレスは、このシステムで使えません。',
	'registration:passwordnotvalid' => '申し訳ありません。入力されたパスワードは、このシステムで使えません。',
	'registration:usernamenotvalid' => '申し訳ありません。入力されたログイン名は、このシステムで使えません。',

	'adduser' => "ユーザ登録",
	'adduser:ok' => "新しいユーザを登録しました。",
	'adduser:bad' => "新しいユーザが登録できません。",

	'user:set:name' => "アカウント編集",
	'user:name:label' => "表示名",
	'user:name:success' => "表示名を変更しました。",
	'user:name:fail' => "表示名を変更できませんでした。",

	'user:set:password' => "パスワード",
	'user:current_password:label' => '現在のパスワード',
	'user:password:label' => "新しいパスワード",
	'user:password2:label' => "新しいパスワード（確認）",
	'user:password:success' => "パスワードを変更しました。",
	'user:password:fail' => "パスワードが変更できませんでした。",
	'user:password:fail:notsame' => "パスワードが一致しません。",
	'user:password:fail:tooshort' => "パスワードが短すぎるので登録できません。",
	'user:password:fail:incorrect_current_password' => '先ほど入力されたパスワードは間違っています。',
	'user:changepassword:unknown_user' => 'ユーザが見当たりません。',
	'user:changepassword:change_password_confirm' => 'パスワードを変更します。',

	'user:set:language' => "言語設定",
	'user:language:label' => "言語の設定",
	'user:language:success' => "言語の設定を更新しました。",
	'user:language:fail' => "言語の設定を保存できませんでした。",

	'user:username:notfound' => 'ログイン名 %s が見当たりません。',

	'user:password:lost' => 'パスワードを忘れた場合',
	'user:password:changereq:success' => '新しいパスワード発行の手続きをしました。ご登録のEメールあてに確認のメールを送信しました。',
	'user:password:changereq:fail' => '新しいパスワード発行の手続きに失敗しました。',

	'user:password:text' => '新しいパスワードを再発行されたい場合は、ログイン名もしくは電子メールアドレスを入力し送信ボタンを押してください。',

	'user:persistent' => '次回入力を省略',

/**
 * Password requirements
 */
	
/**
 * Administration
 */
	'menu:page:header:administer' => '管理',
	'menu:page:header:configure' => '設定',
	'menu:page:header:develop' => '開発',
	'menu:page:header:default' => 'その他',

	'admin:view_site' => 'サイトを見る',
	'admin:loggedin' => '%s でログイン中',
	'admin:menu' => 'メニュー',

	'admin:configuration:success' => "設定を保存しました。",
	'admin:configuration:fail' => "設定を保存できませんでした。",
	'admin:configuration:dataroot:relative_path' => '「 %s 」をデータルートとして仕様出来ません：絶対パスを使用してください。',
	'admin:configuration:default_limit' => '項目の数は、１ページ当たり最低でも1つ以上にしてください。',

	'admin:unknown_section' => '不正な管理セクションです',

	'admin' => "管理",
	'admin:description' => "この管理パネルでは、ユーザの管理からプラグインの振る舞いにいたるまで、システムの全ての事柄をコントロールすることができます。開始するには以下のオプションを選択してください。",
	
	'admin:statistics' => '統計情報',
	'admin:server' => 'サーバ',
	'admin:cron:record' => '最後に行った Cron Jobs',
	'admin:cron:period' => 'Cron の間隔',
	'admin:cron:friendly' => '最後に完了した時間',
	'admin:cron:date' => '日付と時間',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:complete' => 'Cron jobs for "%s" completed at %s',

	'admin:appearance' => '見た目',
	'admin:administer_utilities' => 'ユーティリティ',
	'admin:develop_utilities' => 'ユーティリティ',
	'admin:configure_utilities' => 'ユーティリティ',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "ユーザ",
	'admin:users:online' => 'オンライン中',
	'admin:users:newest' => '最新',
	'admin:users:admins' => '管理者',
	'admin:users:add' => '新規ユーザ追加',
	'admin:users:description' => "この管理者パネルでサイト内でのユーザの設定をコントロールすることができます。開始するには、下のオプションを選択してください。",
	'admin:users:adduser:label' => "新規ユーザを追加するには、ここをクリック...",
	'admin:users:opt:linktext' => "ユーザ設定...",
	'admin:users:opt:description' => "ユーザとアカウント情報の設定",
	'admin:users:find' => '検索',
	'admin:users:unvalidated' => '未確認',
	'admin:users:unvalidated:no_results' => '未確認のユーザは、いません。',
	
	'admin:configure_utilities:maintenance' => 'メンテナンス・モード',
	'admin:upgrades' => 'アップグレード',

	'admin:settings' => 'セッティング',
	'admin:settings:basic' => '基本設定',
	'admin:settings:advanced' => '詳細設定',
	'admin:settings:users' => 'ユーザ',
	'admin:site:description' => "この管理パネルでは、インストールしたサイト全体に関わる設定をコントロールすることができます。はじめるには、以下のオプションを選択してください。",
	'admin:site:opt:linktext' => "サイトの構築..",
	'admin:settings:in_settings_file' => 'この設定は、settings.php 内で行えます。',

	'site_secret:current_strength' => 'キーの強さ',
	'site_secret:strength:weak' => "弱い",
	'site_secret:strength_msg:weak' => "サイトの秘密キーを再設定することを強くおすすめします。",
	'site_secret:strength:moderate' => "中くらい",
	'site_secret:strength_msg:moderate' => "サイトの安全性をもっと高めるためにサイトの秘密キーを再設定することをおすすめします。",
	'site_secret:strength:strong' => "強い",
	'site_secret:strength_msg:strong' => "あなたのサイトの秘密キーは十分に強固です。キーを作りなおす必要はありません。",

	'admin:dashboard' => 'ダッシュボード',
	'admin:widget:online_users' => 'オンライン中のユーザ',
	'admin:widget:online_users:help' => '現在サイトにいるユーザのリスト',
	'admin:widget:new_users' => '新規ユーザ',
	'admin:widget:new_users:help' => '新規ユーザのリスト',
	'admin:widget:banned_users' => '出入り禁止のユーザ',
	'admin:widget:banned_users:help' => '出入り禁止のユーザのリスト',
	'admin:widget:content_stats' => 'コンテントの統計情報',
	'admin:widget:content_stats:help' => 'ユーザが作成したコンテントの記録を保存しています。',
	'admin:widget:cron_status' => 'Cronの状態',
	'admin:widget:cron_status:help' => '最後に cron jobs が完了したときの状態を表示する',

	'admin:widget:admin_welcome' => 'Welcome',
	'admin:widget:admin_welcome:help' => "Elggの管理エリアについての短い紹介",
	'admin:widget:admin_welcome:intro' => 'Elggにようこそ！現在あなたが見ている画面は管理用のダッシュボードです。このページはサイトで何がおこっているかを追跡するのに便利なようにできています。',

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />フッタリンクに’使える’リソースがありますので、チェックしてみてください。Elggをご使用いただき、誠にありがとうございました。',

	'admin:widget:control_panel' => 'コントロールパネル',
	'admin:widget:control_panel:help' => "簡単に各種設定を行うことができます。",

	'admin:cache:flush' => 'キャッシュをクリアする',
	'admin:cache:flushed' => "サイトのキャシュをクリアしました",

	'admin:footer:faq' => '管理FAQ',
	'admin:footer:manual' => '管理マニュアル',
	'admin:footer:community_forums' => 'Elggコミュニティーフォーラム',
	'admin:footer:blog' => 'Elggブログ',

	'admin:plugins:category:all' => '全プラグイン',
	'admin:plugins:category:active' => '起動中のプラグイン',
	'admin:plugins:category:inactive' => '停止中のプラグイン',
	'admin:plugins:category:admin' => '管理者',
	'admin:plugins:category:bundled' => 'Bundled',
	'admin:plugins:category:nonbundled' => 'Non-bundled',
	'admin:plugins:category:content' => 'コンテント',
	'admin:plugins:category:development' => '開発',
	'admin:plugins:category:enhancement' => '機能拡張',
	'admin:plugins:category:api' => 'サービスAPI',
	'admin:plugins:category:communication' => 'コミュニケーション',
	'admin:plugins:category:security' => 'セキュリティーとスパム',
	'admin:plugins:category:social' => 'ソーシャル',
	'admin:plugins:category:multimedia' => 'マルチメディア',
	'admin:plugins:category:theme' => 'テーマ',
	'admin:plugins:category:widget' => 'ウィジェット',
	'admin:plugins:category:utility' => 'ユーティリティ',

	'admin:plugins:markdown:unknown_plugin' => '不明なプラグイン',
	'admin:plugins:markdown:unknown_file' => '不明なファイル',
	'admin:notices:could_not_delete' => '通知を消去することができませんでした。',
	'item:object:admin_notice' => '通知の管理',

	'admin:options' => '管理者オプション',
	
	'admin:security:settings' => 'セッティング',
	'admin:security:settings:label:account' => 'アカウント',
	'admin:security:settings:label:notifications' => '通知',

/**
 * Plugins
 */

	'plugins:disabled' => '「disabled」というファイルがmodディレクトリにありますので、プラグインらを読み込みこんでおりません。',
	'plugins:settings:save:ok' => "プラグイン %s のセッティングを保存しました。",
	'plugins:settings:save:fail' => "プラグイン %s のセッティングを保存する際に問題が発生しました",
	'plugins:usersettings:save:ok' => "プラグイン %s のユーザセッティングを保存しました。",
	'plugins:usersettings:save:fail' => "プラグイン %s のユーザセッティングを保存する際に問題が発生しました",
	
	'item:object:plugin' => 'プラグイン',
	'collection:object:plugin' => 'プラグイン管理',

	'admin:plugins' => "プラグイン管理",
	'admin:plugins:activate_all' => '全て起動する',
	'admin:plugins:deactivate_all' => 'すべて停止する',
	'admin:plugins:activate' => '起動',
	'admin:plugins:deactivate' => '停止',
	'admin:plugins:description' => "この管理パネルでは、インストールしたツールの管理や構築設定を行います。",
	'admin:plugins:opt:linktext' => "ツールの設定...",
	'admin:plugins:opt:description' => "インストールされたツールを構築するための各種設定をします",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "名前",
	'admin:plugins:label:copyright' => "コピーライト",
	'admin:plugins:label:categories' => 'カテゴリ',
	'admin:plugins:label:licence' => "ライセンス",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "ファイル",
	'admin:plugins:label:resources' => "リソース",
	'admin:plugins:label:screenshots' => "スクリーンショット",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "問題を報告する",
	'admin:plugins:label:donate' => "寄付する",
	'admin:plugins:label:moreinfo' => '詳細情報',
	'admin:plugins:label:version' => 'バージョン',
	'admin:plugins:label:location' => '場所',
	'admin:plugins:label:priority' => '優先度',
	'admin:plugins:label:dependencies' => '依存関係',

	'admin:plugins:warning:unmet_dependencies' => 'このプラグインは依存関係が不適切なので起動できません。詳細情報で依存関係をチェックしてください。',
	'admin:plugins:warning:invalid' => 'このプラグインは正しくありません: %s',
	'admin:plugins:warning:invalid:check_docs' => '問題解決のヒントは、 <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">the Elgg documentation</a> にあるかもしれません。',
	'admin:plugins:cannot_activate' => '起動できません',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => '選択されたプラグインは、すでに起動しています。',
	'admin:plugins:already:inactive' => '選択されたプラグインはすでに停止しています。',

	'admin:plugins:set_priority:yes' => "%s を並べ直しました。",
	'admin:plugins:set_priority:no' => "%s を並べ直せませんでした。",
	'admin:plugins:deactivate:yes' => "%s を停止状態にしました。",
	'admin:plugins:deactivate:no' => "%s を停止できませんでした。",
	'admin:plugins:deactivate:no_with_msg' => "%s を停止できませんでした。Error: %s",
	'admin:plugins:activate:yes' => "%s を起動状態にしました。",
	'admin:plugins:activate:no_with_msg' => "%s を起動できませんでした。Error: %s",
	'admin:plugins:categories:all' => '全てのカテゴリ',
	'admin:plugins:plugin_website' => 'プラグインのウェブサイト',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'プラグインの設定',
	'admin:plugins:warning:unmet_dependencies_active' => 'このプラグインは起動状態ですが、依存関係に問題があります。下の"詳細情報"をチェックしてください。',

	'admin:statistics:description' => "これはあなたのサイトの大ざぱな統計情報です。更に詳細な統計情報が必要なときは、専門的な管理機能をご利用ください。",
	'admin:statistics:opt:description' => "サイト上のユーザとオブジェクトに関する統計情報を表示します。",
	'admin:statistics:opt:linktext' => "統計情報をみる...",
	'admin:statistics:label:numentities' => "サイト統計情報（数値）",
	'admin:statistics:label:numusers' => "ユーザ数",
	'admin:statistics:label:numonline' => "ログイン中のユーザ数",
	'admin:statistics:label:onlineusers' => "ログイン中のユーザ",
	'admin:statistics:label:admins'=>"管理者",
	'admin:statistics:label:version' => "Elgg バージョン",
	'admin:statistics:label:version:release' => "リリース",
	'admin:statistics:label:version:version' => "バージョン",
	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Webサーバ',
	'admin:server:label:server' => 'サーバ',
	'admin:server:label:log_location' => 'ログ記録の保存場所',
	'admin:server:label:php_version' => 'PHP version',
	'admin:server:label:php_ini' => 'PHP ini ファイルの場所',
	'admin:server:label:php_log' => 'PHP ログ',
	'admin:server:label:mem_avail' => 'メモリの利用可能量',
	'admin:server:label:mem_used' => 'メモリの使用量',
	'admin:server:error_log' => "Web サーバのエラーログ",
	'admin:server:label:post_max_size' => '最大 POST サイズ',
	'admin:server:label:upload_max_filesize' => '最大アップロードサイズ',
	'admin:server:warning:post_max_too_small' => '(注: このサイズは、post_max_size よりも小さくなければなりません。)',
	
	'admin:server:requirements:php_extension' => "PHP extension: %s",
	
	'admin:user:label:search' => "ユーザ検索",
	'admin:user:label:searchbutton' => "検索",

	'admin:user:ban:no' => "ユーザを禁止できません。",
	'admin:user:ban:yes' => "ユーザの出入りを禁止",
	'admin:user:self:ban:no' => "自分自身を入場禁止にすることはできません。",
	'admin:user:unban:no' => "入場禁止措置を解除することができませんでした。",
	'admin:user:unban:yes' => "ユーザの入場禁止措置を解除しました。",
	'admin:user:delete:no' => "ユーザを削除することができませんでした。",
	'admin:user:delete:yes' => "%s さんを除名（削除）しました。",
	'admin:user:self:delete:no' => "自分自身を除名（削除）することはできません。",

	'admin:user:resetpassword:yes' => "パスワードをリセットして、ユーザに通知します。",
	'admin:user:resetpassword:no' => "パスワードはリセットできませんでした。",

	'admin:user:makeadmin:yes' => "このユーザに管理者権限を与えました。",
	'admin:user:makeadmin:no' => "このユーザに管理者権限を与えることができませんでした。",

	'admin:user:removeadmin:yes' => "ユーザはもう管理者権限を持っていません。",
	'admin:user:removeadmin:no' => "このユーザの管理者権限が解除できませんでした。",
	'admin:user:self:removeadmin:no' => "自分自身の管理者権限を削除することはできません。",
	'admin:menu_items:configure' => 'メインメニュー項目の構築設定',
	'admin:menu_items:hide_toolbar_entries' => 'ツールバーメニューからリンクを削除する。',
	'admin:menu_items:saved' => 'メニュー項目を保存しました。',
	'admin:add_menu_item' => 'カスタムメニュー項目を追加する',
	'admin:add_menu_item:description' => 'ナビゲーションメニューにカスタム項目を追加するため、表示名とURLを欄に入れててください。',
	'admin:default_widgets:unknown_type' => '不明なウィジェットのタイプです。',

	'admin:robots.txt:instructions' => "このサイトの robots.txt ファイルを編集します。",
	'admin:robots.txt:plugins' => "プラグインは編集結果を robots.txt ファイルに追加しています。",
	'admin:robots.txt:subdir' => "Elggがサブディレクトリにインストールされているため、The robots.txt tool は機能しないでしょう。",
	'admin:robots.txt:physical' => "robots.txt ファイルが存在しますので、 robots.txt tool は機能しないでしょう。",

	'admin:maintenance_mode:default_message' => '申し訳ありません。このサイトは現在メンテナンス中で接続出来ません。',
	'admin:maintenance_mode:mode_label' => 'メンテナンス・モード',
	'admin:maintenance_mode:message_label' => 'メンテナンス・モードに入っているときに、ユーザに表示されるメッセージ',
	'admin:maintenance_mode:saved' => 'メンテナンス・モードの設定が保存されました。',
	'admin:maintenance_mode:indicator_menu_item' => 'サイトはメンテナンス・モードになっていあます。',
	'admin:login' => '管理者ログイン',

/**
 * User settings
 */

	'usersettings:description' => "ユーザセッティングパネルを使うと、ユーザマネージメントからプラグインの振る舞い方まで、あなたの個人的な設定の全てを管理することができます。開始するには、以下のオプションを選択してください。",

	'usersettings:statistics' => "あなたの統計情報",
	'usersettings:statistics:opt:description' => "サイト上のユーザとオブジェクトに関する統計情報を表示します。",
	'usersettings:statistics:opt:linktext' => "アカウントの統計情報",

	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "%s さんの設定",
	'usersettings:user:opt:description' => "ユーザ設定の管理を行います。",
	'usersettings:user:opt:linktext' => "設定の変更",

	'usersettings:plugins' => "ツール",
	'usersettings:plugins:opt:description' => "あなたの起動したツール（もしあれば）の設定をします",
	'usersettings:plugins:opt:linktext' => "ツールの設定をする",

	'usersettings:plugins:description' => "このパネルでは、システム管理者がインストールしたツールの個人的なコントロールや設定をすることができます。",
	'usersettings:statistics:label:numentities' => "コンテント",

	'usersettings:statistics:yourdetails' => "詳細",
	'usersettings:statistics:label:name' => "氏名",
	'usersettings:statistics:label:email' => "Eメール",
	'usersettings:statistics:label:membersince' => "利用開始時期",
	'usersettings:statistics:label:lastlogin' => "前回のログイン",

/**
 * Activity river
 */

	'river:all' => '全アクティビティ',
	'river:mine' => 'My アクティビティ',
	'river:owner' => '%s さんのアクティビティ',
	'river:friends' => '友達のアクティティ',
	'river:select' => '表示:%s',
	'river:comments:more' => '+%u もっと',
	'river:comments:all' => '全ての %u さんのコメントを表示する',
	'river:generic_comment' => 'commented on %s %s',

/**
 * Icons
 */

	'icon:size' => "アイコンのサイズ",
	'icon:size:topbar' => "トップバー",
	'icon:size:tiny' => "Tiny",
	'icon:size:small' => "Small",
	'icon:size:medium' => "Medium",
	'icon:size:large' => "Large",
	'icon:size:master' => "Extra Large",

/**
 * Generic action words
 */

	'save' => "保存",
	'reset' => 'リセット',
	'publish' => "公開",
	'cancel' => "キャンセル",
	'saving' => "保存中...",
	'update' => "更新",
	'preview' => "プレビュー",
	'edit' => "編集",
	'delete' => "削除",
	'accept' => "承認する",
	'reject' => "拒否",
	'decline' => "Decline",
	'approve' => "賛成",
	'activate' => "起動",
	'deactivate' => "停止",
	'disapprove' => "反対",
	'revoke' => "破棄",
	'load' => "読込",
	'upload' => "アップロード",
	'download' => "ダウンロード",
	'ban' => "投稿禁止",
	'unban' => "投稿禁止解除",
	'banned' => "入場禁止",
	'enable' => "有効にする",
	'disable' => "無効にする",
	'request' => "リクエスト",
	'complete' => "完了",
	'open' => '開く',
	'close' => '閉じる',
	'hide' => '隠す',
	'show' => '表示する',
	'reply' => "返信",
	'more' => 'もっと',
	'more_info' => '更に詳しく',
	'comments' => 'コメント',
	'import' => 'インポート',
	'export' => 'エクスポート',
	'untitled' => 'タイトルなし',
	'help' => 'ヘルプ',
	'send' => '送信',
	'post' => '投稿',
	'submit' => '送信',
	'comment' => 'コメント',
	'upgrade' => 'アップグレード',
	'sort' => '並び替え',
	'filter' => 'フィルタ',
	'new' => '新規',
	'add' => '追加',
	'create' => '作成',
	'remove' => '削除',
	'revert' => '戻す',
	'validate' => '確認済み',
	'next' => '次へ',
	'previous' => '前へ',
	
	'site' => 'サイト',
	'activity' => 'アクティビティ',
	'members' => 'メンバ',
	'menu' => 'メニュー',

	'up' => '上へ',
	'down' => '下へ',
	'top' => '最初',
	'bottom' => '最後',
	'right' => '右',
	'left' => '左',
	'back' => '後へ',

	'invite' => "招待する",

	'resetpassword' => "パスワードをリセットする",
	'changepassword' => "パスワードを変更",
	'makeadmin' => "管理者権限を与える",
	'removeadmin' => "管理者権限を外す",

	'option:yes' => "はい",
	'option:no' => "いいえ",

	'unknown' => 'よくわからない',
	'never' => '未',

	'active' => 'アクティブ',
	'total' => '総数',
	'unvalidated' => '未確認',
	
	'ok' => 'OK',
	'any' => 'Any',
	'error' => 'エラー',

	'other' => 'その他',
	'options' => 'オプション',
	'advanced' => '詳細設定',

	'learnmore' => "詳細はここをクリック",
	'unknown_error' => '原因不明のエラーが発生しました。',

	'content' => "コンテント",
	'content:latest' => '最新のアクティビティ',

	'link:text' => 'リンク一覧',

/**
 * Generic questions
 */

	'question:areyousure' => 'よろしいですか？',

/**
 * Status
 */

	'status' => 'ステータス',
	'status:unsaved_draft' => '未保存の下書き',
	'status:draft' => '下書き',
	'status:unpublished' => '未公開',
	'status:published' => '公開済み',
	'status:featured' => '注目',
	'status:open' => 'オープン',
	'status:closed' => 'クローズド',
	'status:active' => 'アクティブ',

/**
 * Generic sorts
 */

	'sort:newest' => '新しい順',
	'sort:popular' => '人気順',
	'sort:alpha' => 'アルファベット順',
	'sort:priority' => '優先度順',

/**
 * Generic data words
 */

	'title' => "タイトル",
	'description' => "説明",
	'tags' => "タグ",
	'all' => "全部",
	'mine' => "自分の",

	'by' => 'by',
	'none' => 'none',

	'annotations' => "注釈",
	'relationships' => "関連",
	'metadata' => "メタデータ",
	'tagcloud' => "タグクラウド",

	'on' => 'On',
	'off' => 'Off',

/**
 * Entity actions
 */

	'edit:this' => 'これを編集',
	'delete:this' => 'これを削除',
	'comment:this' => 'コメントをつける',

/**
 * Input / output strings
 */

	'deleteconfirm' => "このアイテムを削除してよいですか？",
	'deleteconfirm:plural' => "これらのアイテムを削除してもよろしいですか？",

/**
 * User add
 */

	'useradd:subject' => 'ユーザを作成しました。',

/**
 * Messages
 */
	'messages:title:error' => 'Error',
	'messages:title:warning' => 'Warning',
	'messages:title:help' => 'ヘルプ',
	'messages:title:notice' => 'Notice',
	'messages:title:info' => 'Info',

/**
 * Time
 */
	'input:date_format:datepicker' => '', // jQuery UI datepicker format

	'friendlytime:justnow' => "Now!",
	'friendlytime:minutes' => "%s 分前",
	'friendlytime:minutes:singular' => "1 分前",
	'friendlytime:hours' => "%s 時間前",
	'friendlytime:hours:singular' => "1 時間前",
	'friendlytime:days' => "%s 日前",
	'friendlytime:days:singular' => "昨日",
	'friendlytime:date_format' => 'Y年m月d日@ H:i',

	'friendlytime:future:minutes' => "%s分で",
	'friendlytime:future:minutes:singular' => "1分で",
	'friendlytime:future:hours' => "%s時間で",
	'friendlytime:future:hours:singular' => "1時間で",
	'friendlytime:future:days' => "%s日で",
	'friendlytime:future:days:singular' => "明日",

	'date:month:01' => '1月 %s',
	'date:month:02' => '2月 %s',
	'date:month:03' => '3月 %s',
	'date:month:04' => '4月 %s',
	'date:month:05' => '5月 %s',
	'date:month:06' => '6月 %s',
	'date:month:07' => '7月 %s',
	'date:month:08' => '8月 %s',
	'date:month:09' => '9月 %s',
	'date:month:10' => '10月 %s',
	'date:month:11' => '11月 %s',
	'date:month:12' => '12月 %s',

	'date:month:short:01' => '1月%s日',
	'date:month:short:02' => '2月%s日',
	'date:month:short:03' => '3月%s日',
	'date:month:short:04' => '4月%s日',
	'date:month:short:05' => '5月%s日',
	'date:month:short:06' => '6月%s日',
	'date:month:short:07' => '7月%s日',
	'date:month:short:08' => '8月%s日',
	'date:month:short:09' => '9月%s日',
	'date:month:short:10' => '10月%s日',
	'date:month:short:11' => '11月%s日',
	'date:month:short:12' => '12月%s日',

	'date:weekday:0' => 'Sunday',
	'date:weekday:1' => 'Monday',
	'date:weekday:2' => 'Tuesday',
	'date:weekday:3' => 'Wednesday',
	'date:weekday:4' => 'Thursday',
	'date:weekday:5' => 'Friday',
	'date:weekday:6' => 'Saturday',

	'date:weekday:short:0' => '日',
	'date:weekday:short:1' => '月',
	'date:weekday:short:2' => '火',
	'date:weekday:short:3' => '水',
	'date:weekday:short:4' => '木',
	'date:weekday:short:5' => '金',
	'date:weekday:short:6' => '土',

	'interval:minute' => '毎分',
	'interval:fiveminute' => '5分毎',
	'interval:fifteenmin' => '15分毎',
	'interval:halfhour' => '30分毎',
	'interval:hourly' => '1時間毎',
	'interval:daily' => '毎日',
	'interval:weekly' => '毎週',
	'interval:monthly' => '毎月',
	'interval:yearly' => '毎年',

/**
 * System settings
 */

	'installation:sitename' => "あなたのサイト名:",
	'installation:sitedescription' => "あなたのサイトのちょっとした説明（任意）:",
	'installation:wwwroot' => "サイトのURL",
	'installation:path' => "Elggのインストール先のフルパス",
	'installation:dataroot' => "データディレクトリのフルパス",
	'installation:dataroot:warning' => "手作業でこのディレクトリを作成しないといけません。Elggのインストールしたディレクトリと別のところのほうがいいでしょう。",
	'installation:sitepermissions' => "デフォルトのアクセス権限",
	'installation:language' => "サイトのデフォルトの言語",
	'installation:debug' => "サーバのログに書き込まれる情報の量をコントロールします。",
	'installation:debug:label' => "ログレベル:",
	'installation:debug:none' => 'デバッグモードをOFFにする（推奨）',
	'installation:debug:error' => '致命的なエラーのみ表示する',
	'installation:debug:warning' => 'エラーと警告を表示する',
	'installation:debug:notice' => 'エラーと警告と通告を記録する',
	'installation:debug:info' => '全てを記録する',

	// Walled Garden support
	'installation:registration:description' => 'ユーザ登録はデフォルトで可能となっています。人が勝手に自分で登録できるようにしたくなければ、OFFにしてください。',
	'installation:registration:label' => '新規ユーザに登録ができるようにする',
	'installation:walled_garden:description' => '非会員がサイトの内容を閲覧できないようにする（ただし、ログインページや登録ページのようなパブリックなWebページを除く）。',
	'installation:walled_garden:label' => 'ページをログインユーザ限定にする',

	'installation:view' => "あなたのサイトのデフォルトで使用するviewを入力してください。デフォルトviewを使用する場合は、空欄のままにしておいてください。(よくわからない場合は、そのままにしておいてください)",

	'installation:siteemail' => "サイトの電子メールアドレス（システムメールを送信するときに使用します）:",
	'installation:default_limit' => "1ページ当たりの項目数の既定値",

	'admin:site:access:warning' => "ユーザが新しくコンテントを作成する際に示されるプライバシー・セッティングです。これを変更しても、すでにあるコンテントへのアクセス権は変更されません。",
	'installation:allow_user_default_access:description' => "チェックすると、各ユーザがそれぞれののプライバシー・セッティングを設定することができます。この場合、システムのプライバシー・セッティングは上書きされます。",
	'installation:allow_user_default_access:label' => "ユーザがデフォルトのアクセス権を設定できるようにする",

	'installation:simplecache:description' => "このsimple cacheは、CSSやJavaScriptなどの静的コンテントをキャッシュすることによって、サイトのパフォーマンスを改善させます。",
	'installation:simplecache:label' => "Simple cache を使う(推奨)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "Simple cache は JavaScripte と CSS ファイルを圧縮することでパフォーマンスも改善することができます。（simple cacheを「有効」にする必要があります。）",
	'installation:minify_js:label' => "JavaScript を圧縮（推奨）",
	'installation:minify_css:label' => "CSS を圧縮（推奨）",

	'installation:htaccess:needs_upgrade' => ".htaccess をアップデートしてください。そうすることにより、path が GET のパラメタ __elgg_uri にインジェクトされます。( install/config/htaccess.dist ファイルを参考にしてください。)",
	'installation:htaccess:localhost:connectionfailed' => "Elggはrewriteルールのプロパティをテストするために自分自身に接続することはできません。curlが動作することとlocalhostへの接続を妨げるようなIPの制限設定が無いかどうかを確認してください。",

	'installation:systemcache:description' => "ステムキャッシュはデータをファイルにキャッシュすることでElggの読み込み時間を少なくします。",
	'installation:systemcache:label' => "システムキャッシュを使う(推奨)",

	'admin:legend:system' => 'システム',
	'admin:legend:caching' => 'キャシュ',
	'admin:legend:content' => 'コンテント',
	'admin:legend:content_access' => 'コンテント・アクセス',
	'admin:legend:site_access' => 'サイト・アクセス',
	'admin:legend:debug' => 'デバッグとログ',

	'upgrading' => 'アップグレード中...',
	'upgrade:core' => 'Elggをアップグレードしました。',
	'upgrade:unlock' => 'アプグレードのロックを解除する',
	'upgrade:unlock:confirm' => "もうひとつアップグレードがありますのでデータベースをロックします。複数のアップグレードを同時に実行するのは危険です。他のアップグレードがないことをご確認の上作業を継続してください。ロックを解除しますか？",
	'upgrade:locked' => "アップグレードできません。別のアップグレードが実行されています。アップグレードのロックを解除するには、管理セクションに行ってください。",
	'upgrade:unlock:success' => "アップグレードのロックを解除しました。",

	'admin:pending_upgrades' => 'サイトはアップグレードの途中で中断されています。これ以降は直接あなたの操作が必要です。',
	'admin:view_upgrades' => '中断されているアップグレードを見る。',
	'item:object:elgg_upgrade' => 'サイトのアップグレード',
	'admin:upgrades:none' => 'このインストールは最新の状態です！',

	'upgrade:success_count' => 'アップグレード済み:',
	'upgrade:finished' => 'アプグレードを完了しました',
	'upgrade:finished_with_errors' => '<p>アップグレードはエラーが出て終了してしまいました。 ページをリフレッシュして、もう一度アップグレードを実行してください。</p></p><br />再びエラーが起こったなら、原因を究明するためサーバのエラーログをチェックしてみてください。Elgg community の <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> にエラーを修正するための答えがあるかもしれません。</p>',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "ようこそ",
	'welcome:user' => 'ようこそ、%s さん！',

/**
 * Emails
 */

	'email:from' => 'From',
	'email:to' => 'To',
	'email:subject' => 'Subject',
	'email:body' => 'Body',

	'email:settings' => "Eメール設定",
	'email:address:label' => "Eメールアドレス",
	'email:address:password' => "パスワード",

	'email:save:success' => "新しいEメールアドレスを保存しました。アドレスが正しいかどうかの確認が求められています。",
	'email:save:fail' => "Eメールアドレスを保存できませんでした。",

	'friend:newfriend:subject' => "%s さんはあなたを友達に登録しました！",

	'email:changepassword:subject' => "パスワードが変更されました!",

	'email:resetpassword:subject' => "パスワードをリセットしました",

	'email:changereq:subject' => "パスワード変更の申請",

/**
 * user default access
 */

	'default_access:settings' => "あなたのデフォルトの公開範囲",
	'default_access:label' => "デフォルトの公開範囲",
	'user:default_access:success' => "新しい公開範囲の設定を保存しました。",
	'user:default_access:failure' => "新しい公開範囲の設定が保存できません。",

/**
 * Comments
 */

	'comments:count' => "%s さんのコメント",
	'item:object:comment' => 'コメント',
	'collection:object:comment' => 'コメント',

	'generic_comments:add' => "コメントする",
	'generic_comments:edit' => "コメントを編集",
	'generic_comments:post' => "コメントを投稿する",
	'generic_comments:text' => "コメント",
	'generic_comments:latest' => "最新のコメント",
	'generic_comment:posted' => "コメントを投稿しました。",
	'generic_comment:updated' => "コメントを更新しました。",
	'entity:delete:object:comment:success' => "コメントを削除しました。",
	'generic_comment:blank' => "申し訳ありません。コメント内容が空欄のため保存できません。",
	'generic_comment:notfound' => "申し訳ありません。お探しのコメントは見つかりませんでした。",
	'generic_comment:failure' => "コメントを保存する際に予期せぬエラーが発生しました。",
	'generic_comment:none' => 'コメントはありません',
	'generic_comment:title' => '%s さんが付けたコメント',
	'generic_comment:on' => '%s さんが %s にコメント',
	'generic_comments:latest:posted' => '投稿:',

/**
 * Entities
 */

	'byline' => 'By %s',
	'byline:ingroup' => '%sグループ内',
	
	'entity:delete:item' => '項目',
	'entity:delete:item_not_found' => '項目が見つかりませんでした。',
	'entity:delete:permission_denied' => 'あなたには、この項目を削除する権限がありません。',
	'entity:delete:success' => '%s は削除されました。',
	'entity:delete:fail' => '%s は削除できませんでした。',

/**
 * Annotations
 */
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'フォームに __token もしくは __ts 項目が欠けています',
	'actiongatekeeper:tokeninvalid' => "あなたが使用しているページの期限が切れました。もう一度試してみてください。",
	'actiongatekeeper:timeerror' => 'ご覧のページは閲覧期限が切れています。再度ページを読み込んでください。',
	'actiongatekeeper:pluginprevents' => '申し訳ありません。原因不明の理由であなたのフォームを送信することができませんでした。',
	'actiongatekeeper:uploadexceeded' => 'アップロードファイルのサイズがこのサイトの管理者が設定した最大値を超えています。',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => '%s への接続に失敗しました。コンテントを保存するときに問題が発生したようです。このページを再読込してください。',
	'js:lightbox:current' => "画像 %s枚目(全%s枚)",

/**
 * Diagnostics
 */
	
/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",
	
/**
 * Cli commands
 */
	
/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"cmn" => "中国語（官話）", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese(日本語)",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portuguese (Brazil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romanian (Romania)",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"sr_latin" => "Serbian (Latin)",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => '必須です',
);
