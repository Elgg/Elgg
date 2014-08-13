<?php
return array(
	'twitter_api' => 'Twitter サービス',

	'twitter_api:requires_oauth' => 'Twitterサービス機能を使用するには OAuth Libraries plugin が起動されている必要があります',

	'twitter_api:consumer_key' => 'Consumer Key',
	'twitter_api:consumer_secret' => 'Consumer Secret',

	'twitter_api:settings:instructions' => 'consumer key と secret from <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>が必要です。新しい app アプリケーションを開いてください。アプリケーションタイプとして「Browser」 、アクセスタイプとして「Read & Write」を選択してください。コルバックURLは %stwitter_api/authorize です。',

	'twitter_api:usersettings:description' => "あなたのアカウント %s とTwitterを関連付けします",
	'twitter_api:usersettings:request' => "あなたのTwitterアカウントにアクセスするには、まず最初に%sを<a href=\"%s\">承認</a> させなければいけません。",
	'twitter_api:usersettings:cannot_revoke' => "あなたは、Twitterのアカウントとの関連付けを削除することはできません。削除するには電子メールアドレスまたはパスワードをいれてください。<a href=\"%s\">今すぐ作る</a>.",
	'twitter_api:authorize:error' => 'Twitterの承認が取れまんでした',
	'twitter_api:authorize:success' => 'Twitterへのアクセスの承認が取れました',

	'twitter_api:usersettings:authorized' => "%s で承認が取れました。Twitter account: @%s.",
	'twitter_api:usersettings:revoke' => 'Twitterのアクセスする解除するには<a href="%s">クリック</a>してください。',
	'twitter_api:usersettings:site_not_configured' => 'この機能を使用するには、管理者は最初にTwitterの設定をセッティング してください。',

	'twitter_api:revoke:success' => 'Twitter へのアクセスが解除されました',

	'twitter_api:post_to_twitter' => "Twitterにユーザの「つぶやき」記事を送信しますか?",

	'twitter_api:login' => 'ユーザにTwitterのアカウントでサインインできるようにしますか？',
	'twitter_api:new_users' => 'ユーザ登録ができないように設定されていますが、Twitterのアカウントを持っている者については、そのアカウントで新規登録できるようにしますか？',
	'twitter_api:login:success' => 'あなたは、ログインしています。',
	'twitter_api:login:error' => 'Twitterでログインできませんでした。',
	'twitter_api:login:email' => "新しく %s でアカウントを取るには正しく電子メールアドスが設定されていなくてはいけません。",

	'twitter_api:invalid_page' => '不正なページです',

	'twitter_api:deprecated_callback_url' => 'コールバックURLがTwitter APIを使用するために%sに変更されました。これを変更するには管理者に問い合せてみてください。',

	'twitter_api:interstitial:settings' => '設定をする',
	'twitter_api:interstitial:description' => '%sを使用する準備がほぼ整いました！ 次に進む前にもう少し詳しい情報を入力してください。しかし、もしTwitterがダウンしていたり、あなたが自分のアカウントとTwitterへの関連付けをしないようにするのなら、このままログインできます。',

	'twitter_api:interstitial:username' => 'これは、あなたのログイン名です。変更することはできません。もし、パスワードを設定したのなら、ログイン名もしくは電子メールアドレスを使用してログインできます。',

	'twitter_api:interstitial:name' => 'この名前は、他の人に見えます。あなたはこの名前で他の人と交流することができます。',

	'twitter_api:interstitial:email' => 'あなたの電子メールアドレス。でファORTでは他の人は見ることはできません。',

	'twitter_api:interstitial:password' => 'ログインパスワード（Twitterがダウンしていたり、あなたが自分のアカウントと関連付けない場合に使用します）',
	'twitter_api:interstitial:password2' => '確認のためにもう一度同じパスワードを入力してください',

	'twitter_api:interstitial:no_thanks' => 'No thanks',

	'twitter_api:interstitial:no_display_name' => '氏名を入力してください',
	'twitter_api:interstitial:invalid_email' => '正しい電子メールアドレスを入力してください（空欄でも良い）',
	'twitter_api:interstitial:existing_email' => 'この電子メールアドレスはすでにこのサイトに登録されています。',
	'twitter_api:interstitial:password_mismatch' => 'パスワードが違います。',
	'twitter_api:interstitial:cannot_save' => 'アカウントの詳細を細んすることができませんでした。',
	'twitter_api:interstitial:saved' => 'アカウントの詳細を保存しました！',
);
