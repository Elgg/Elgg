<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'install:title' => 'Elgg インストール',
	'install:welcome' => 'こんにちは、ようこそ',
	'install:requirements' => '必要条件の確認',
	'install:database' => 'データベースのインストール',
	'install:settings' => 'サイトの構築',
	'install:admin' => 'admin（管理者）アカウントの作成',
	'install:complete' => '終了',

	'install:next' => '次',
	'install:refresh' => 'リフレッシュ',
	
	'install:requirements:instructions:success' => "あなたのサーバは必要条件をみたしています。",
	'install:requirements:instructions:failure' => "あなたのサーバは、必要な条件を満たしていませんでした。下記の問題点を修正したあと、このページをリフレッシュしてください。ページの下にある問題解決へのリンクをクリックしてくだされば、よい解決法が見つかるかもしれません。",
	'install:requirements:instructions:warning' => "あなたのサーバーは必要条件を満たしていますが、警告が出ています。詳細を知るには、インストール時の問題解決のページをチェックすることをお勧めいたしたします。",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Web サーバ',
	'install:require:settings' => '設定ファイル',
	'install:require:database' => 'データベース',

	'install:check:php:version' => 'Elgg をインストールするには PHP %s かそれ以上が必要です。このサーバのPHPはバージョン %s です。',
	'install:check:php:extension' => 'Elgg をインストールするには PHP extension %s が必要です。',
	'install:check:php:extension:recommend' => 'PHP extension %s がインストールされていることを推奨します。',
	'install:check:php:open_basedir' => 'open_basedir PHP directive のせいで、Elggがデータディレクトリにファイルを保存することができません。',
	'install:check:php:safe_mode' => 'PHPがセーフモードですとElggに問題が発生することがあるので、おすすめできません。',
	'install:check:php:arg_separator' => 'Elggがうまく動くためには、 arg_separator.output が「&」でなければいけません。ちなみに、あなたのサーバでは「 %s 」に設定されています。',
	'install:check:php:register_globals' => 'register globals は off にしてください。',
	'install:check:php:session.auto_start' => "Elggがうまく動くには、ession.auto_start はoffにしなければいけません。あなたのサーバの設定を変更するか、Elggの「.htaccess」ファイルにこの宣言を加えてください。",
	'install:check:readsettings' => '設定ファイルはengineディレクトリにあるのですが、Webサーバがそのファイル読むことができませんでした。ファイルを削除するか、ファイルのパーミションを読み込み許可に変更してください。',

	'install:check:php:success' => "あなたのサーバのPHPはElggの全ての必要女権を満たしています。",
	'install:check:rewrite:success' => 'リライトルール(rewrite rules)の検査に成功しました。',
	'install:check:database' => 'Elggがデータベースを読み込むときに、データベースの必要条件をチェックします。',

	'install:database:instructions' => "もし、Elgg用のデータベースをまだ作成していないのでしたら、今作成してください。データベースが準備出来ましたら、Elggデータベスを初期化するために、以下の値を入力してください。",
	'install:database:error' => 'Elgg用データベースを作成するときにエラーが発生しましたのでインストール作業を継続することができません。上のメッセージを見なおして問題を解決してください。わからないことがございましたら、下記のインストール時の問題解決(the Install troubleshooting)リンクをたどってElgg community forumsに投稿していただけますと、お手伝いできるかもしれません。',

	'install:database:label:dbuser' =>  'データベースのユーザ名',
	'install:database:label:dbpassword' => 'データベースのパスワード',
	'install:database:label:dbname' => 'データベースの名前',
	'install:database:label:dbhost' => 'データベースのホスト',
	'install:database:label:dbprefix' => 'データベースのテーブル名につける接頭辞(Prefix)',
	'install:database:label:timezone' => "タイムゾーン",

	'install:database:help:dbuser' => 'このユーザはElgg用に作成したMySQLデータベースに対して全ての権限持っていなければいけません。',
	'install:database:help:dbpassword' => '上のデータベースユーザのアカウントに対するパスワード',
	'install:database:help:dbname' => 'Elgg用データベースの名前',
	'install:database:help:dbhost' => 'MySQL serverのあるホスト名(たいていは、localhost)',
	'install:database:help:dbprefix' => "全てのElgg用テーブル名につける接頭辞(Prefix)(たいていは、elgg_)",
	'install:database:help:timezone' => "サイトが扱う既定のタイムゾーン",

	'install:settings:label:sitename' => 'サイトの名前',
	'install:settings:label:siteemail' => 'サイトのEmailアドレス',
	'install:database:label:wwwroot' => 'サイトのURL',
	'install:settings:label:path' => 'Elgg インストールディレクトリ',
	'install:database:label:dataroot' => 'データディレクトリ',
	'install:settings:label:language' => 'サイトて使用する言語',
	'install:settings:label:siteaccess' => '規定のサイトアクセス',
	'install:label:combo:dataroot' => 'Elgg はデータディレクトリを作成します。',

	'install:settings:help:sitename' => '新しくElggを導入するサイトの名前',
	'install:settings:help:siteemail' => 'Elggがユーザと連絡するときに使用するEmailアドレス',
	'install:database:help:wwwroot' => 'このサイトのアドレス (たいていはElgg はたいていはこのアドレスを正しく推定します)',
	'install:settings:help:path' => 'Elggのコードが格納されているディレクトリ(たいていはElggはこのアドレスを正しく推定します)',
	'install:database:help:dataroot' => 'Elggがファイルを保存するために使用するディレクトリで、あなたが前もって作成しておかなければなりません。(「次」をクリックするとこのディレクトリのパーミションをチェックします)',
	'install:settings:help:dataroot:apache' => '次のいずれかを選択してください。１．データディレクトリを作成する。２．ユーザファイルを入れておくためにあなたがすでに作成したディレクトリ名を入力する。(「次」をクリックするとこのディレクトリのパーミッションをチェクします)',
	'install:settings:help:language' => 'このサイトの既定の言語',
	'install:settings:help:siteaccess' => 'ユーザが新規作成したコンテントの既定のアクセスレベル',

	'install:admin:instructions' => "管理者(administrator)アカウントを作成しましょう。",

	'install:admin:label:displayname' => '表示用の名前',
	'install:admin:label:email' => 'Email アドレス',
	'install:admin:label:username' => 'ログイン名',
	'install:admin:label:password1' => 'パスワード',
	'install:admin:label:password2' => 'パスワード（確認）',

	'install:admin:help:displayname' => 'このアカウントでログインした時に実際にサイトで表示される名前',
	'install:admin:help:username' => 'ログイン時に使用するユーザアカウント名',
	'install:admin:help:password1' => "アカウントのパスワードは少なくとも %u 文字以上でなければいけません",
	'install:admin:help:password2' => '念の為もう一度上と同じパスワードを入力してください。',

	'install:admin:password:mismatch' => 'パスワードは一致しなければなりません。',
	'install:admin:password:empty' => 'パスワードが空欄のままです。',
	'install:admin:password:tooshort' => 'パスワードが短すぎます。',
	'install:admin:cannot_create' => 'admin （管理者）アカウントを作成できません。',

	'install:complete:instructions' => 'あなたのElggサイトは準備が整い、もう使用できます。あなたのサイトへは、下のボタンをクリックしてください。',
	'install:complete:gotosite' => 'サイトへ行く',

	'InstallationException:CannotLoadSettings' => 'Elgg は、設定ファイルを読み込みことができませんでした。 そのファイルが存在しないか、ファイルのパーミッションに問題があると思われます。',

	'install:success:database' => 'データベースをインストールしました。',
	'install:success:settings' => 'サイトの設定を保存しました。',
	'install:success:admin' => 'Admin(管理者)アカウントを作成しました。',

	'install:error:htaccess' => '.htaccessファイルを作成できませんでした。',
	'install:error:settings' => '設定ファイルを作成できませんでした。',
	'install:error:databasesettings' => 'これらの設定ではデータベースに接続することができません。',
	'install:error:database_prefix' => 'データベースのプレフィックスに不適当な文字があります',
	'install:error:nodatabase' => 'データベース %s を使用出来ません。おそらく存在しないものと思われます。',
	'install:error:cannotloadtables' => 'データベーステーブルを読み込むことができません。',
	'install:error:tables_exist' => 'ご指定のデータベースにはすでにElggのテーブルが存在しています。これらのテーブルをドロップ（破棄）するか、インストーラーをリスタートする必要があります。リスタートを選択された場合は、その既存のテーブルを使用できないか試みてみます。インストーラーをリスタートするには、あなたのブラウザのアドレスバーに表示されているURLから \'?step=database\' の部分を削除したあと、Enterキーを押してください。',
	'install:error:readsettingsphp' => '/elgg-config/settings.example.php ファイルを読み込めません',
	'install:error:writesettingphp' => '/elgg-config/settings.php ファイルに書き込めません',
	'install:error:requiredfield' => '%s が必須です',
	'install:error:relative_path' => 'データディレクトリ用に指定された「 %s 」は絶対パスでは無いと思われます。',
	'install:error:datadirectoryexists' => 'データディレクトリ用に指定された「 %s 」は存在しません。',
	'install:error:writedatadirectory' => 'データディレクトリ用に指定された「 %s 」はwebサーバからは書き込み不可です。',
	'install:error:locationdatadirectory' => 'データディレクトリ用に指定された「 %s 」は安全性のため、インストールパスの外側になければなりません。',
	'install:error:emailaddress' => '%s は正しいemailアドレスではありません。',
	'install:error:createsite' => 'サイトを作成することができませんでした。',
	'install:error:savesitesettings' => 'サイトの設定を保存することができませんでした。',
	'install:error:loadadmin' => 'adminユーザを読み込むことができませんでした。',
	'install:error:adminaccess' => '新規ユーザにadmin特権を与えることができません。',
	'install:error:adminlogin' => '新しいadminユーザに自動的にログインすることができません。',
	'install:error:rewrite:apache' => 'あなたのサーバでは、Apache web サーバが起動されていると思われます。',
	'install:error:rewrite:nginx' => 'あなたのサーバでは、Nginx web サーバが起動されていると思われます。',
	'install:error:rewrite:lighttpd' => 'あなたのサーバでは、Lighttpd web サーバが起動されていると思われます。',
	'install:error:rewrite:iis' => 'あなたのサーバでは、IIS web サーバが起動されていると思われます。',
	'install:error:rewrite:htaccess:write_permission' => 'あなたのWebサーバはElggディレクトリ下に .htaccess ファイルを作成する許可を持っていません。手動で install/config/htaccess.dist ファイルを .htaccess ファイルにコピーするか、Elggディレクトリのパーミッションを変更してください。',
	'install:error:rewrite:htaccess:read_permission' => 'Elggディレクトリ下に .htaccess ファイルがありますが、webサーバには読み込み不可になっています。',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Elggディレクトリ下に .htaccess ファイルがありますが、このファイルはElggが作成したものではありません。削除してください。',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Elggディレクトリ下には、 .htaccess ファイルがあるのですが、古いElggのもののようです。webサーバをテストするリライトルールが含まれていません。',
	'install:error:rewrite:htaccess:cannot_copy' => '.htaccess ファイルの作成中によくわからないエラーが起こりました。手動にて install/config/htaccess.dist ファイルを .htaccess ファイルにコピーしてください。',
	'install:error:rewrite:altserver' => 'リライトルールがのテストに失敗しました。Elggリライトルールでwebサーバを設定してもう一度テストをこころみてください。',
	'install:error:rewrite:unknown' => 'あなたのサーバで起動されているwebサーバを特定することができませんでした。リライトルールにも失敗したようです。残念ですがアドバイスも出来そうにありません。問題解決リンクをチェックしてみてください。',
	'install:warning:rewrite:unknown' => 'あなたのサーバはリライトルールの自動テストをサポートしていないようです。その上、あなたのご使用のブラウザはJavaScriptでのチェッキングをサポートしていません。インストールを続行できますが、問題が発生することがあるかもしれません。次のリンクをクリックすれば、リライトルールを手動でテストすることができます：<a href="%s" target="_blank">テスト</a>。テストがうまく行けば success（成功）の文字が表示されるはずです。',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => '対応できないエラーが発生しそれをログに記録しました。あなたがサイトの管理者の方でしたら、ファイルをチェックしてみてください。そうでないならサイトの管理者に次の情報をお知らせください。:',
	'DatabaseException:WrongCredentials' => "Elgg は与えられた証明では接続出来ませんでした。設定ファイルをチェックしてみてください。",
);
