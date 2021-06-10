<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'ツール',
	
	// menu
	'admin:develop_tools:sandbox' => 'テーマの見本',
	'admin:develop_tools:inspect' => '内部を見る',
	'admin:inspect' => '内部を見る',
	'admin:develop_tools:unit_tests' => 'ユニットテスト',
	'admin:developers' => '開発者',
	'admin:developers:settings' => 'セッティング',

	// settings
	'elgg_dev_tools:settings:explanation' => '開発とデバッグの設定をしてください。いくつかのセッティングは別のadminページででも行えます。',
	'developers:label:simple_cache' => 'Simple cacheを使用する',
	'developers:help:simple_cache' => '開発中はファイルキャシュをオフにしてください。せっかくview（cssも含む）を変更しても、それが反映されないことがあります。',
	'developers:label:system_cache' => 'システムキャッシュを使用する',
	'developers:help:system_cache' => '開発中は、これをオフにしてください。せっかくプラグインの変更を行なっても、登録されないでしょう。',
	'developers:label:debug_level' => "トレースレベル",
	'developers:help:debug_level' => "ログ情報の量を調節します。詳しくは、elgg_log()を参照してください。",
	'developers:label:display_errors' => '致命的なPHPエラーを表示します',
	'developers:help:display_errors' => "デフォルトでは、Elggの .htaccessファイルが致命的エラーの表示を抑制しています。",
	'developers:label:screen_log' => "画面にログを出力",
	'developers:help:screen_log' => "elgg_log()とelgg_dump()の出力と、DBクエリの数を表示します。",
	'developers:label:show_strings' => "翻訳を表示する代わりに翻訳キーを表示します",
	'developers:help:show_strings' => "elgg_echo()で使われる翻訳キー(\$message_key)を表示します。",
	'developers:label:show_modules' => "コンソールにロードされた AMD モジュールを表示する",
	'developers:help:show_modules' => "ロードされたモジュールや値をあなたの JavaScript コンソールに配信します。",
	'developers:label:wrap_views' => "Wrap views",
	'developers:label:log_events' => "イベントとプラグインhooksを記録する",
	'developers:help:log_events' => "イベントとプラグインhooksをログに記録します。【警告】１ページでも、たくさん吐き出しますので注意してください。",
	'developers:label:show_gear' => "adminエリアの外側で %s を使用する",
	'developers:help:show_gear' => "ビューポートの右下のアイコン。それを通してadminsが開発用設定とリンクにアクセスできるようになります。",

	'developers:label:submit' => "キャッシュの保存と消去",
	
	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Error',
	'developers:debug:warning' => 'Warning',
	'developers:debug:notice' => 'Notice',
	'developers:debug:info' => 'Info',
	
	// entity explorer
	'developers:entity_explorer:info:metadata' => 'メタデータ',
	'developers:entity_explorer:info:relationships' => '関連',
	
	// inspection
	'developers:inspect:help' => 'Elggフレームワークの構築設定を覗いてみる',
	'developers:inspect:actions' => 'Actions（アクション）',
	'developers:inspect:events' => 'Events（イベント）',
	'developers:inspect:menus' => 'Menus（メニュー）',
	'developers:inspect:pluginhooks' => 'Plugin Hooks（プラグイン・フック）',
	'developers:inspect:priority' => '優先度',
	'developers:inspect:simplecache' => 'Simple Cache（シンプル・キャッシュ）',
	'developers:inspect:views' => 'Views（ビュー）',
	'developers:inspect:views:all_filtered' => "<b>Note!</b> All view input/output is filtered through these Plugin Hooks:",
	'developers:inspect:views:input_filtered' => "(input filtered by plugin hook: %s)",
	'developers:inspect:views:filtered' => "(plugin hook「 %s 」によってフィルタされています)",
	'developers:inspect:widgets' => 'Widgets（ウィジェット）',
	'developers:inspect:widgets:context' => 'Context（コンテキスト）',
	'developers:inspect:functions' => 'Functions（関数）',
	'developers:inspect:file' => 'ファイル',
	'developers:inspect:middleware' => 'ファイル',
	'developers:inspect:service:name' => 'Name',

	// event logging
	'developers:request_stats' => "リクエストの統計(シャットダウンイベントは除きます)",
	'developers:event_log_msg' => "%s: '%s, %s' in %s",
	'developers:log_queries' => "DBクエリ:%s",
	'developers:boot_cache_rebuilt' => "ブートキャッシュはこのリクエストで再構築されます",
	'developers:elapsed_time' => "経過時間(s)",

	// theme sandbox
	'theme_sandbox:intro' => 'イントロダクション',
	'theme_sandbox:breakout' => 'iframe外で表示する',
	'theme_sandbox:buttons' => 'ボダン',
	'theme_sandbox:components' => 'コンポーネント',
	'theme_sandbox:forms' => 'フォーム',
	'theme_sandbox:grid' => 'グリッド',
	'theme_sandbox:icons' => 'アイコン',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'レイアウト',
	'theme_sandbox:modules' => 'モジュール',
	'theme_sandbox:navigation' => 'ナビゲーション',
	'theme_sandbox:typography' => '文字の体裁',

	'theme_sandbox:icons:blurb' => 'アイコンを表示させるには、 <em>elgg_view_icon($name)</em> または、 elgg-icon-$name クラスを使用してください。',

	// status messages
	'developers:settings:success' => '設定を保存しました',

	'developers:amd' => 'AMD',
);
