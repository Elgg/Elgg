<?php

return array(
/**
 * Sites
 */

	'item:site:site' => 'Site',
	'collection:site:site' => 'Sites',
	'index:content' => '<p>Welcome to your Elgg site.</p><p><strong>Tip:</strong> Many sites use the <code>activity</code> plugin to place a site activity stream on this page.</p>',

/**
 * Sessions
 */

	'login' => "ログイン",
	'loginok' => "ログインしました。",
	'loginerror' => "ログインできませんでした。このサイトの登録したかどうかご確認の上、もう一度お試しください。",
	'login:empty' => "ログイン名とパスワードが必要です。",
	'login:baduser' => "あなたのログインアカウントを読み込むことができませんでした。",
	'auth:nopams' => "内部エラー。ユーザ認証機能がインストールされていません。",

	'logout' => "ログアウト",
	'logoutok' => "ログアウトしました。",
	'logouterror' => "ログアウトできませんでした。もう一度お試しください。",
	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "要求されたページはログインしないとご覧になることはできません。",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "要求されたページは管理者でないとご覧になることはできません。",
	'membershiprequired' => "要求されたページはこのグループのメンバでないとご覧になることはできません。",
	'limited_access' => "あなたには要求されたページを閲覧する十分な権限はありません。",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "致命的なエラーです",
	'exception:contact_admin' => '復帰不可能なエラーが発生しましたのでログに記録しました。サイト管理者にコンタクトをとって次の情報を報告してください。:',

	'actionundefined' => "要求されたアクション(%s) はこのシステムで定義されていません。",
	'actionnotfound' => "%s のアクションファイルが見つかりませんでした。",
	'actionloggedout' => "ログアウトのままですと、アクションを実行できません。",
	'actionunauthorized' => 'あなたの権限では、このアクションを実行することはできません。',

	'ajax:error' => 'AJAXコールを実行中に予期せぬエラーが起こりました。おそらく、サーバへの接続が切断されたからかもしれません。',
	'ajax:not_is_xhr' => 'AJAX views には直接アクセスはできません',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) は、設定に間違いのあるプラグインですので、起動不可となっています。 原因に当たっては、Elgg wiki (http://learn.elgg.org/) を参考にしてください。 ",
	'PluginException:CannotStart' => '%s (guid: %s) は起動できず停止状態のままです。理由: %s',
	'PluginException:InvalidID' => "%s は、不正なプラグインIDです。",
	'PluginException:InvalidPath' => "%s は、不正なプラグインのpathです",
	'PluginException:InvalidManifest' => '%s プラグインのマニフェストファイルに間違いがあります。',
	'PluginException:InvalidPlugin' => '%s は、不正なプラグインです。',
	'PluginException:InvalidPlugin:Details' => '%s は、不正なプラグインです。: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin のインスタンスは null であってはいけません。GUID、plugin ID もしくはfull path を渡してください。',
	'ElggPlugin:MissingID' => 'プラグインID (guid %s) が、ありません。',
	'ElggPlugin:NoPluginPackagePackage' => 'プラグインID %s (guid %s) のElggPluginPackage がありません。',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => '必要なファイル "%s" が見つかりません。',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'マニフェストのIDに一致させるには、このプラグインのディレクトリの名前を "%s" に変えなければいけません。',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'マニフェストに記述されている依存関係のタイプ "%s" が正しくありません。',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'マニフェストに記述されているプロバイドのタイプ "%s" が正しくありません。',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'プラグイン %3$s で依存関係のタイプ %2$s の 「%1$s」 が正しくありません。依存関係が循環しています。',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Error:ID' => 'Error in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error in plugin path "%s"',
	'ElggPlugin:Error:Unknown' => 'Undefined plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => '%s (プラグイン %s (guid: %s))が %s に含まれていません。パーミッションを調べてください！',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'プラグイン %s (guid: %s)のViewディレクトリを %s で開くことができません。パーミッションを調べてください！',
	'ElggPlugin:Exception:NoID' => 'プラグイン guid %s のIDがありません！',
	'ElggPlugin:Exception:InvalidPackage' => 'Package cannot be loaded',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest is missing or invalid',
	'PluginException:NoPluginName' => "プラグイン名を見つけることができませんでした。",
	'PluginException:ParserError' => 'API(var. %s)でプラグイン %s のマニフェストを解析するときにエラーが発生しました)。',
	'PluginException:NoAvailableParser' => 'マニフェストAPI(Ver. %s)のパーサをプラグイン%sの中で見つけることができません。',
	'PluginException:ParserErrorMissingRequiredAttribute' => "マニフェストファイル内で'%s'属性が必要なのですがプラグイン%sの中ではありませんでした。",
	'ElggPlugin:InvalidAndDeactivated' => '%s は不正なプラグインですので起動されませんでした。',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => '必須',
	'ElggPlugin:Dependencies:Suggests' => '示唆',
	'ElggPlugin:Dependencies:Conflicts' => '混乱',
	'ElggPlugin:Dependencies:Conflicted' => '混乱した',
	'ElggPlugin:Dependencies:Provides' => '生成',
	'ElggPlugin:Dependencies:Priority' => '優先',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg version',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ind セッティング: %s',
	'ElggPlugin:Dependencies:Plugin' => 'プラグイン:%s',
	'ElggPlugin:Dependencies:Priority:After' => '%s の後',
	'ElggPlugin:Dependencies:Priority:Before' => '%s の前',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s は、インストールされていません',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'ありません',

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

	'PageNotFoundException' => 'The page you are trying to view does not exist or you do not have permissions to view it',
	'EntityNotFoundException' => 'The content you were trying to access has been removed or you do not have permissions to access it.',
	'EntityPermissionsException' => 'You do not have sufficient permissions for this action.',
	'GatekeeperException' => 'You do not have permissions to view the page you are trying to access',
	'BadRequestException' => 'Bad request',
	'ValidationException' => 'Submitted data did not meet the requirements, please check your input.',
	'LogicException:InterfaceNotImplemented' => '%s must implement %s',

	'deprecatedfunction' => '警告: このコードは廃止された時代遅れの関数「 %s 」を使用しており、このバージョンのElggとは互換性がありません。',

	'pageownerunavailable' => '警告： ページオーナー %d を許可できません。',
	'viewfailure' => 'View %s において内部エラーが発生しました。',
	'view:missing_param' => "View %2\$s で必要なパラメータ「 %1\$s 」がありません。",
	'changebookmark' => 'このページに対するあなたのブックマークを変更してください。',
	'noaccess' => 'あなたが閲覧しようとしているコンテントはすでに削除されてしまっているか、あるいはあなたに閲覧する権限がないかどちらかです。',
	'error:missing_data' => 'あなたのリクエストにおいていくつかデータの欠損がありました。',
	'save:fail' => 'データを保存するのに失敗しました',
	'save:success' => 'データを保存しました',

	'forward:error' => 'Sorry. An error occurred while redirecting to you to another site.',

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

	'access:label:private' => "Private",
	'access:label:logged_in' => "Logged in users",
	'access:label:public' => "Public",
	'access:label:logged_out' => "Logged out users",
	'access:label:friends' => "Friends",
	'access' => "公開範囲",
	'access:overridenotice' => "注意: グループポリシーですでに設定されているので、このコンテントはグループメンバーからのみしかアクセスすることができません。",
	'access:limited:label' => "限定公開",
	'access:help' => "コンテンツの公開範囲を設定します。",
	'access:read' => "読み込みアクセス",
	'access:write' => "書き込みアクセス",
	'access:admin_only' => "管理者のみ",
	'access:missing_name' => "アクセスレベルの名前がありません",
	'access:comments:change' => "この会議は現在限られた人しか見ることができないようになっています。あなたがこれをシェアする人に対しては十分に考慮してください。",

/**
 * Dashboard and widgets
 */

	'dashboard' => "ダッシュボード",
	'dashboard:nowidgets' => "ダッシュボードでは、あなたのアクティビティやこのサイトでのあなたに関するコンテンツを表示させることができます。",

	'widgets:add' => 'ウィジェットを追加',
	'widgets:add:description' => "下のウィジェットボタンをクリックして、ページに追加してみてください。",
	'widgets:position:fixed' => '（固定した位置）',
	'widget:unavailable' => 'すでに、このウィジェットを追加済みです。',
	'widget:numbertodisplay' => '表示するアイテムの数',

	'widget:delete' => '%s を削除',
	'widget:edit' => 'このウィジェットをカスタマイズする',

	'widgets' => "ウィジェット",
	'widget' => "ウィジェット",
	'item:object:widget' => "ウィジェット",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "ウィジェットを保存しました。",
	'widgets:save:failure' => "ウィジェットを保存できませんでした。",
	'widgets:add:success' => "ウィジェットを追加しました。",
	'widgets:add:failure' => "あなたのウィジェットを追加できませんでした。",
	'widgets:move:failure' => "ウィジェットを新しい場所に移動できませんでした。",
	'widgets:remove:failure' => "このウィジェットを削除することができませんでした",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "グループ",
	'item:group' => "グループ",
	'collection:group' => 'Groups',
	'item:group:group' => "Group",
	'collection:group:group' => 'Groups',
	'groups:tool_gatekeeper' => "The requested functionality is currently not enabled in this group",

/**
 * Users
 */

	'user' => "ユーザ",
	'item:user' => "ユーザ",
	'collection:user' => 'Users',
	'item:user:user' => 'User',
	'collection:user:user' => 'Users',

	'friends' => "友達",
	'collection:friends' => 'Friends\' %s',

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
	
	'action:user:validate:already' => "%s was already validated",
	'action:user:validate:success' => "%s has been validated",
	'action:user:validate:error' => "An error occurred while validating %s",

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
	'river:user:friend' => "%s is now a friend with %s",
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
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => '正しいユーザではありません',
	'activity:owner' => 'アクティビティ一覧',

	

/**
 * Notifications
 */
	'notifications:usersettings' => "通知設定",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "通知設定を保存しました。",
	'notifications:usersettings:save:fail' => "通知設定を保存する際に問題が起こりました。",

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
	'next' => "次へ",
	'previous' => "前へ",

	'viewtype:change' => "表示の仕方の変更",
	'viewtype:list' => "リスト",
	'viewtype:gallery' => "ギャラリ",

	'tag:search:startblurb' => "「%s」と一致したタグは:",

	'user:search:startblurb' => "「%s」と一致したユーザ:",
	'user:search:finishblurb' => "もっとみる",

	'group:search:startblurb' => "「%s」と一致したグループは:",
	'group:search:finishblurb' => "もっとみる",
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

	'registration:noname' => 'Display name is required.',
	'registration:notemail' => 'あなたが入力したEメールアドレスは、正しいものでは無いようです。',
	'registration:userexists' => 'そのログイン名はすでに使われています。',
	'registration:usernametooshort' => 'ログイン名は半角英字で %u 文字以上にしてください。',
	'registration:usernametoolong' => 'あなたのログイン名は長過ぎます。 %u 文字以内でお願いします。',
	'registration:passwordtooshort' => 'パスワードは半角英字で %u 文字以上にしてください。',
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
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Could not change username on the system.",

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
	'user:username:help' => 'Please be aware that changing a username will change all dynamic user related links',

	'user:password:lost' => 'パスワードを忘れた場合',
	'user:password:hash_missing' => 'Regretfully, we must ask you to reset your password. We have improved the security of passwords on the site, but were unable to migrate all accounts in the process.',
	'user:password:changereq:success' => '新しいパスワード発行の手続きをしました。ご登録のEメールあてに確認のメールを送信しました。',
	'user:password:changereq:fail' => '新しいパスワード発行の手続きに失敗しました。',

	'user:password:text' => '新しいパスワードを再発行されたい場合は、ログイン名もしくは電子メールアドレスを入力し送信ボタンを押してください。',

	'user:persistent' => '次回入力を省略',

	'walled_garden:home' => 'Home',

/**
 * Administration
 */
	'menu:page:header:administer' => '管理',
	'menu:page:header:configure' => '設定',
	'menu:page:header:develop' => '開発',
	'menu:page:header:information' => 'Information',
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
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => '最後に行った Cron Jobs',
	'admin:cron:period' => 'Cron の間隔',
	'admin:cron:friendly' => '最後に完了した時間',
	'admin:cron:date' => '日付と時間',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
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
	'admin:users:unvalidated' => 'Unvalidated',
	'admin:users:unvalidated:no_results' => 'No unvalidated users.',
	'admin:users:unvalidated:registered' => 'Registered: %s',
	
	'admin:configure_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'アップグレード',
	'admin:upgrades:finished' => 'Completed',
	'admin:upgrades:db' => 'Database upgrades',
	'admin:upgrades:db:name' => 'Upgrade name',
	'admin:upgrades:db:start_time' => 'Start time',
	'admin:upgrades:db:end_time' => 'End time',
	'admin:upgrades:db:duration' => 'Duration',
	'admin:upgrades:menu:pending' => 'Pending upgrades',
	'admin:upgrades:menu:completed' => 'Completed upgrades',
	'admin:upgrades:menu:db' => 'Database upgrades',
	'admin:upgrades:menu:run_single' => 'Run this upgrade',
	'admin:upgrades:run' => 'Run upgrades now',
	'admin:upgrades:error:invalid_upgrade' => 'Entity %s does not exist or not a valid instance of ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Batch runner for the upgrade %s (%s) could not be instantiated',
	'admin:upgrades:completed' => 'Upgrade "%s" completed at %s',
	'admin:upgrades:completed:errors' => 'Upgrade "%s" completed at %s but encountered %s errors',
	'admin:upgrades:failed' => 'Upgrade "%s" failed',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" was reset',

	'admin:settings' => 'セッティング',
	'admin:settings:basic' => '基本設定',
	'admin:settings:advanced' => '詳細設定',
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
	'admin:statistics:numentities' => 'Content Statistics',
	'admin:statistics:numentities:type' => 'Content type',
	'admin:statistics:numentities:number' => 'Number',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Welcome',
	'admin:widget:admin_welcome:help' => "Elggの管理エリアについての短い紹介",
	'admin:widget:admin_welcome:intro' =>
'Elggにようこそ！現在あなたが見ている画面は管理用のダッシュボードです。このページはサイトで何がおこっているかを追跡するのに便利なようにできています。',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into
three sections:
	<dl>
		<dt>Administer</dt><dd>Basic tasks like managing users, monitoring reported content and activating plugins.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or configuring settings of a plugin.</dd>
		<dt>Information</dt><dd>Information about your site like statistics.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>
",

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

	'admin:notices:delete_all' => 'Dismiss all %s notices',
	'admin:notices:could_not_delete' => '通知を消去することができませんでした。',
	'item:object:admin_notice' => '通知の管理',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => '管理者オプション',

	'admin:security' => 'Security',
	'admin:security:settings' => 'Settings',
	'admin:security:settings:description' => 'On this page you can configure some security features. Please read the settings carefully.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:notifications' => 'Notifications',
	'admin:security:settings:label:site_secret' => 'Site secret',
	
	'admin:security:settings:notify_admins' => 'Notify all site administrators when an admin is added or removed',
	'admin:security:settings:notify_admins:help' => 'This will send out a notification to all site administrators that one of the admins added/removed a site administrator.',
	
	'admin:security:settings:notify_user_admin' => 'Notify the user when the admin role is added or removed',
	'admin:security:settings:notify_user_admin:help' => 'This will send a notification to the user that the admin role was added to/removed from their account.',
	
	'admin:security:settings:notify_user_ban' => 'Notify the user when their account gets (un)banned',
	'admin:security:settings:notify_user_ban:help' => 'This will send a notification to the user that their account was (un)banned.',
	
	'admin:security:settings:protect_upgrade' => 'Protect upgrade.php',
	'admin:security:settings:protect_upgrade:help' => 'This will protect upgrade.php so you require a valid token or you\'ll have to be an administrator.',
	'admin:security:settings:protect_upgrade:token' => 'In order to be able to use the upgrade.php when logged out or as a non admin, the following URL needs to be used:',
	
	'admin:security:settings:protect_cron' => 'Protect the /cron URLs',
	'admin:security:settings:protect_cron:help' => 'This will protect the /cron URLs with a token, only if a valid token is provided will the cron execute.',
	'admin:security:settings:protect_cron:token' => 'In order to be able to use the /cron URLs the following tokens needs to be used. Please note that each interval has its own token.',
	'admin:security:settings:protect_cron:toggle' => 'Show/hide cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => 'Disable autocomplete on password fields',
	'admin:security:settings:disable_password_autocomplete:help' => 'Data entered in these fields will be cached by the browser. An attacker who can access the victim\'s browser could steal this information. This is especially important if the application is commonly used in shared computers such as cyber cafes or airport terminals. If you disable this, password management tools can no longer autofill these fields. The support for the autocomplete attribute can be browser specific.',
	
	'admin:security:settings:email_require_password' => 'Require password to change email address',
	'admin:security:settings:email_require_password:help' => 'When the user wishes to change their email address, require that they provide their current password.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:security:settings:site_secret:regenerate' => "Regenerate site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	
	'admin:site:secret:regenerated' => "Your site secret has been regenerated",
	'admin:site:secret:prevented' => "The regeneration of the site secret was prevented",
	
	'admin:notification:make_admin:admin:subject' => 'A new site administrator was added to %s',
	'admin:notification:make_admin:admin:body' => 'Hi %s,

%s made %s a site administrator of %s.

To view the profile of the new administrator, click here:
%s

To go to the site, click here:
%s',
	
	'admin:notification:make_admin:user:subject' => 'You were added as a site administator of %s',
	'admin:notification:make_admin:user:body' => 'Hi %s,

%s made you a site administrator of %s.

To go to the site, click here:
%s',
	'admin:notification:remove_admin:admin:subject' => 'A site administrator was removed from %s',
	'admin:notification:remove_admin:admin:body' => 'Hi %s,

%s removed %s as a site administrator of %s.

To view the profile of the old administrator, click here:
%s

To go to the site, click here:
%s',
	
	'admin:notification:remove_admin:user:subject' => 'You were removed as a site administator from %s',
	'admin:notification:remove_admin:user:body' => 'Hi %s,

%s removed you as site administrator of %s.

To go to the site, click here:
%s',
	'user:notification:ban:subject' => 'Your account on %s was banned',
	'user:notification:ban:body' => 'Hi %s,

Your account on %s was banned.

To go to the site, click here:
%s',
	
	'user:notification:unban:subject' => 'Your account on %s is no longer banned',
	'user:notification:unban:body' => 'Hi %s,

Your account on %s is no longer banned. You can use the site again.

To go to the site, click here:
%s',
	
/**
 * Plugins
 */

	'plugins:disabled' => '「disabled」というファイルがmodディレクトリにありますので、プラグインらを読み込みこんでおりません。',
	'plugins:settings:save:ok' => "プラグイン %s のセッティングを保存しました。",
	'plugins:settings:save:fail' => "プラグイン %s のセッティングを保存する際に問題が発生しました",
	'plugins:usersettings:save:ok' => "プラグイン %s のユーザセッティングを保存しました。",
	'plugins:usersettings:save:fail' => "プラグイン %s のユーザセッティングを保存する際に問題が発生しました",
	'item:object:plugin' => 'プラグイン',
	'collection:object:plugin' => 'Plugins',

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
	'admin:plugins:label:author' => "開発者",
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
	'admin:plugins:label:contributors' => '寄与者',
	'admin:plugins:label:contributors:name' => '名前',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Website',
	'admin:plugins:label:contributors:username' => 'コミュニティのユーザーネーム',
	'admin:plugins:label:contributors:description' => '説明',
	'admin:plugins:label:dependencies' => '依存関係',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'このプラグインは依存関係が不適切なので起動できません。詳細情報で依存関係をチェックしてください。',
	'admin:plugins:warning:invalid' => 'このプラグインは正しくありません: %s',
	'admin:plugins:warning:invalid:check_docs' => '問題解決のヒントは、 <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">the Elgg documentation</a> にあるかもしれません。',
	'admin:plugins:cannot_activate' => '起動できません',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => '選択されたプラグインは、すでに起動しています。',
	'admin:plugins:already:inactive' => '選択されたプラグインはすでに停止しています。',

	'admin:plugins:set_priority:yes' => "%s を並べ直しました。",
	'admin:plugins:set_priority:no' => "%s を並べ直せませんでした。",
	'admin:plugins:set_priority:no_with_msg' => "%s を並べ直せませんでした。Error: %s",
	'admin:plugins:deactivate:yes' => "%s を停止状態にしました。",
	'admin:plugins:deactivate:no' => "%s を停止できませんでした。",
	'admin:plugins:deactivate:no_with_msg' => "%s を停止できませんでした。Error: %s",
	'admin:plugins:activate:yes' => "%s を起動状態にしました。",
	'admin:plugins:activate:no' => "% sを起動できませんでした。",
	'admin:plugins:activate:no_with_msg' => "%s を起動できませんでした。Error: %s",
	'admin:plugins:categories:all' => '全てのカテゴリ',
	'admin:plugins:plugin_website' => 'プラグインのウェブサイト',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'プラグインの設定',
	'admin:plugins:warning:unmet_dependencies_active' => 'このプラグインは起動状態ですが、依存関係に問題があります。下の"詳細情報"をチェックしてください。',

	'admin:plugins:dependencies:type' => 'タイプ',
	'admin:plugins:dependencies:name' => '名前',
	'admin:plugins:dependencies:expected_value' => '推奨値',
	'admin:plugins:dependencies:local_value' => '実際の値',
	'admin:plugins:dependencies:comment' => 'コメント',

	'admin:statistics:description' => "これはあなたのサイトの大ざぱな統計情報です。更に詳細な統計情報が必要なときは、専門的な管理機能をご利用ください。",
	'admin:statistics:opt:description' => "サイト上のユーザとオブジェクトに関する統計情報を表示します。",
	'admin:statistics:opt:linktext' => "統計情報をみる...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "サイト統計情報（数値）",
	'admin:statistics:label:numusers' => "ユーザ数",
	'admin:statistics:label:numonline' => "ログイン中のユーザ数",
	'admin:statistics:label:onlineusers' => "ログイン中のユーザ",
	'admin:statistics:label:admins'=>"管理者",
	'admin:statistics:label:version' => "Elgg バージョン",
	'admin:statistics:label:version:release' => "リリース",
	'admin:statistics:label:version:version' => "バージョン",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Show PHPInfo',
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
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure memcache (or redis).
',

	'admin:server:label:redis' => 'Redis',
	'admin:server:redis:inactive' => '
		Redis is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure redis (or memcache).
',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => '
		OPcache is not available on this server or it has not yet been enabled.
		For improved performance, it is recommended that you enable and configure OPcache.
',
	
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

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'メインメニュー項目の構築設定',
	'admin:menu_items:description' => 'どのメニューアイテムをfeaturedリンクとして表示したいのかを選択してください。使用しない項目は、メニューリストの最後の"もっと"以下に追加されます。',
	'admin:menu_items:hide_toolbar_entries' => 'ツールバーメニューからリンクを削除する。',
	'admin:menu_items:saved' => 'メニュー項目を保存しました。',
	'admin:add_menu_item' => 'カスタムメニュー項目を追加する',
	'admin:add_menu_item:description' => 'ナビゲーションメニューにカスタム項目を追加するため、表示名とURLを欄に入れててください。',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => '不明なウィジェットのタイプです。',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "このサイトの robots.txt ファイルを編集します。",
	'admin:robots.txt:plugins' => "プラグインは編集結果を robots.txt ファイルに追加しています。",
	'admin:robots.txt:subdir' => "Elggがサブディレクトリにインストールされているため、The robots.txt tool は機能しないでしょう。",
	'admin:robots.txt:physical' => "robots.txt ファイルが存在しますので、 robots.txt tool は機能しないでしょう。",

	'admin:maintenance_mode:default_message' => '申し訳ありません。このサイトは現在メンテナンス中で接続出来ません。',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
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
	
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "保存",
	'save_go' => "Save, and go to %s",
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
	'validate' => 'Validate',
	'read_more' => 'Read more',

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
	'content:latest:blurb' => 'もしくは、ここをクリックしてサイト全体での新しい記事を見る',

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
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'ユーザを作成しました。',
	'useradd:body' => '%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

Username: %s
Password: %s

Once you have logged in, we highly recommend that you change your password.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "クリックすると消えます。",


/**
 * Messages
 */
	'messages:title:success' => 'Success',
	'messages:title:error' => 'Error',
	'messages:title:warning' => 'Warning',
	'messages:title:help' => 'Help',
	'messages:title:notice' => 'Notice',

/**
 * Import / export
 */

	'importsuccess' => "データのインポートに成功しました。",
	'importfail' => "OpenDDデータのインポートに失敗しました。",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "Now!",
	'friendlytime:minutes' => "%s 分前",
	'friendlytime:minutes:singular' => "1 分前",
	'friendlytime:hours' => "%s 時間前",
	'friendlytime:hours:singular' => "1 時間前",
	'friendlytime:days' => "%s 日前",
	'friendlytime:days:singular' => "昨日",
	'friendlytime:date_format' => 'Y年m月d日@ H:i',
	'friendlytime:date_format:short' => 'j M Y',

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
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
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
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
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
	'admin:legend:content_access' => 'コンテント・アクセス',
	'admin:legend:site_access' => 'サイト・アクセス',
	'admin:legend:debug' => 'デバッグとログ',
	
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	
	'upgrading' => 'アップグレード中...',
	'upgrade:core' => 'Elggをアップグレードしました。',
	'upgrade:unlock' => 'アプグレードのロックを解除する',
	'upgrade:unlock:confirm' => "もうひとつアップグレードがありますのでデータベースをロックします。複数のアップグレードを同時に実行するのは危険です。他のアップグレードがないことをご確認の上作業を継続してください。ロックを解除しますか？",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "アップグレードできません。別のアップグレードが実行されています。アップグレードのロックを解除するには、管理セクションに行ってください。",
	'upgrade:unlock:success' => "アップグレードのロックを解除しました。",
	'upgrade:unable_to_upgrade' => 'アップグレードできませんでした',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API(旧称 OAuth LIb)はアップグレード中に停止しました。必要なら、手動でプラグインを再起動させてください。',
	'upgrade:site_secret_warning:moderate' => "システムの安全性を改善するために、サイトの秘密キーを再生成するようにしましょう。設定 &gt; セッティング &gt; 詳細設定で設定出来ます。",
	'upgrade:site_secret_warning:weak' => "システムの安全性を改善するためにサイトの秘密キーを再生成してください。設定 &gt; セッティング &gt; 詳細設定",

	'deprecated:function' => '関数 %s() は廃止され %s()に変わりました。',

	'admin:pending_upgrades' => 'サイトはアップグレードの途中で中断されています。これ以降は直接あなたの操作が必要です。',
	'admin:view_upgrades' => '中断されているアップグレードを見る。',
	'item:object:elgg_upgrade' => 'サイトのアップグレード',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'このインストールは最新の状態です！',

	'upgrade:item_count' => 'アップグレードが必要なものが <b>%s</b> 個あります。',
	'upgrade:warning' => '<b>警告:</b>大きなサイトならアップグレードするのに少々時間がかかるかもしれません。',
	'upgrade:success_count' => 'アップグレード済み:',
	'upgrade:error_count' => 'エラー:',
	'upgrade:finished' => 'アプグレードを完了しました',
	'upgrade:finished_with_errors' => '<p>アップグレードはエラーが出て終了してしまいました。 ページをリフレッシュして、もう一度アップグレードを実行してください。</p></p><br />再びエラーが起こったなら、原因を究明するためサーバのエラーログをチェックしてみてください。Elgg community の <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> にエラーを修正するための答えがあるかもしれません。</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
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
	'email:address:password' => "Password",
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "新しいEメールアドレスを保存しました。アドレスが正しいかどうかの確認が求められています。",
	'email:save:fail' => "Eメールアドレスを保存できませんでした。",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s さんはあなたを友達に登録しました！",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "パスワードが変更されました!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "パスワードをリセットしました",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "パスワード変更の申請",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

%s",

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
	'collection:object:comment' => 'Comments',

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "コメントする",
	'generic_comments:edit' => "コメントを編集",
	'generic_comments:post' => "コメントを投稿する",
	'generic_comments:text' => "コメント",
	'generic_comments:latest' => "最新のコメント",
	'generic_comment:posted' => "コメントを投稿しました。",
	'generic_comment:updated' => "コメントを更新しました。",
	'entity:delete:object:comment:success' => "The comment was successfully deleted.",
	'generic_comment:blank' => "申し訳ありません。コメント内容が空欄のため保存できません。",
	'generic_comment:notfound' => "申し訳ありません。お探しのコメントは見つかりませんでした。",
	'generic_comment:notfound_fallback' => "申し訳ありません。お探しのコメントは見つかりませんでしたが、コメントされていたページへご案内しました。",
	'generic_comment:failure' => "コメントを保存する際に予期せぬエラーが発生しました。",
	'generic_comment:none' => 'コメントはありません',
	'generic_comment:title' => '%s さんが付けたコメント',
	'generic_comment:on' => '%s さんが %s にコメント',
	'generic_comments:latest:posted' => '投稿:',

	'generic_comment:notification:owner:subject' => 'You have a new comment!',
	'generic_comment:notification:owner:summary' => 'You have a new comment!',
	'generic_comment:notification:owner:body' => "You have a new comment on your item \"%s\" from %s. It reads:

%s

To reply or view the original item, click here:
%s

To view %s's profile, click here:
%s",
	
	'generic_comment:notification:user:subject' => 'A new comment on: %s',
	'generic_comment:notification:user:summary' => 'A new comment on: %s',
	'generic_comment:notification:user:body' => "A new comment was made on \"%s\" by %s. It reads:

%s

To reply or view the original item, click here:
%s

To view %s's profile, click here:
%s",

/**
 * Entities
 */

	'byline' => 'By %s',
	'byline:ingroup' => '%sグループ内',
	'entity:default:missingsupport:popup' => 'この情報を正確に表示できません。利用していたプラグインがうまく動作していないか、アンインストールされた可能性があります。',

	'entity:delete:item' => '項目',
	'entity:delete:item_not_found' => '項目が見つかりませんでした。',
	'entity:delete:permission_denied' => 'あなたには、この項目を削除する権限がありません。',
	'entity:delete:success' => '%s は削除されました。',
	'entity:delete:fail' => '%s は削除できませんでした。',

	'entity:can_delete:invaliduser' => 'ユーザが存在しませんので、user_guid[%s] に対して canDelete() をチェックできません。 ',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'フォームに __token もしくは __ts 項目が欠けています',
	'actiongatekeeper:tokeninvalid' => "あなたが使用しているページの期限が切れました。もう一度試してみてください。",
	'actiongatekeeper:timeerror' => 'ご覧のページは閲覧期限が切れています。再度ページを読み込んでください。',
	'actiongatekeeper:pluginprevents' => '申し訳ありません。原因不明の理由であなたのフォームを送信することができませんでした。',
	'actiongatekeeper:uploadexceeded' => 'アップロードファイルのサイズがこのサイトの管理者が設定した最大値を超えています。',
	'actiongatekeeper:crosssitelogin' => "異なるドメインからのログインは禁止しています。もう一度試してみてください。",

/**
 * Word blacklists
 */

	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'タグ',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => '%s への接続に失敗しました。コンテントを保存するときに問題が発生したようです。このページを再読込してください。',
	'js:security:token_refreshed' => '%s への接続が復帰しました！',
	'js:lightbox:current' => "画像 %s枚目(全%s枚)",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",

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

	"core:upgrade:2017080900:title" => "Alter database encoding for multi-byte support",
	"core:upgrade:2017080900:description" => "Alters database and table encoding to utf8mb4, in order to support multi-byte characters such as emoji",

	"core:upgrade:2017080950:title" => "Update default security parameters",
	"core:upgrade:2017080950:description" => "Installed Elgg version introduces additional security parameters. It is recommended that your run this upgrade to configure the defaults. You can later update these parameters in your site settings.",

	"core:upgrade:2017121200:title" => "Create friends access collections",
	"core:upgrade:2017121200:description" => "Migrates the friends access collection to an actual access collection",

	"core:upgrade:2018041800:title" => "Activate new plugins",
	"core:upgrade:2018041800:description" => "Certain core features have been extracted into plugins. This upgrade activates these plugins to maintain compatibility with third-party plugins that maybe dependant on these features",

	"core:upgrade:2018041801:title" => "Delete old plugin entities",
	"core:upgrade:2018041801:description" => "Deletes entities associated with plugins removed in Elgg 3.0",
	
	"core:upgrade:2018061401:title" => "Migrate cron log entries",
	"core:upgrade:2018061401:description" => "Migrate the cron log entries in the database to the new location.",
);
