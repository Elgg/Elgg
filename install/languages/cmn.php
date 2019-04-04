<?php
return array(
	'install:title' => 'Elgg 安裝',
	'install:welcome' => '歡迎',
	'install:requirements' => '需求檢查',
	'install:database' => '資料庫安裝',
	'install:settings' => '組配站臺',
	'install:admin' => '建立管理帳號',
	'install:complete' => '完成',

	'install:next' => '下一步',
	'install:refresh' => '重新整理',

	'install:welcome:instructions' => "Installing Elgg has 6 simple steps and reading this welcome is the first one!

If you haven't already, read through the installation instructions included with Elgg (or click the instructions link at the bottom of the page).

If you are ready to proceed, click the Next button.",
	'install:requirements:instructions:success' => "伺服器通過了需求檢查。",
	'install:requirements:instructions:failure' => "伺服器需求檢查失敗。在您修正了以下問題之後，請重新整理這個頁面。如果您需要進一步的協助，請看看位於這個頁面底部的疑難排解鏈結。",
	'install:requirements:instructions:warning' => "伺服器通過了需求檢查，但是至少出現一個警告。我們建議您看看安裝疑難排解頁面以獲得更多細節。",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => '網頁伺服器',
	'install:require:settings' => '設定值檔案',
	'install:require:database' => '資料庫',

	'install:check:root' => 'Your web server does not have permission to create an .htaccess file in the root directory of Elgg. You have two choices:

1. Change the permissions on the root directory

2. Copy the file install/config/htaccess.dist to .htaccess',

	'install:check:php:version' => 'Elgg 要求 PHP %s 或以上。這個伺服器正在使用版本 %s。',
	'install:check:php:extension' => 'Elgg 要求 PHP 延伸功能 %s。',
	'install:check:php:extension:recommend' => '建議先安裝 PHP 延伸功能 %s。',
	'install:check:php:open_basedir' => 'PHP 指令 open_basedir 可能會防止 Elgg 儲存檔案到資料目錄。',
	'install:check:php:safe_mode' => '不建議在安全模式中執行 PHP，因為也許會與 Elgg 導致問題。',
	'install:check:php:arg_separator' => 'Elgg 的 arg_separator.output 必須是 & 才能作用，而伺服器上的值是 %s',
	'install:check:php:register_globals' => '全域的註冊必須關閉。',
	'install:check:php:session.auto_start' => "Elgg 的 session.auto_start 必須關閉才能作用。請變更伺服器的組態，或者將這個指令加入 Elgg 的 .htaccess 檔案。",

	'install:check:installdir' => 'Your web server does not have permission to create the settings.php file in your installation directory. You have two choices:

1. Change the permissions on the elgg-config directory of your Elgg installation

2. Copy the file %s/settings.example.php to elgg-config/settings.php and follow the instructions in it for setting your database parameters.',
	'install:check:readsettings' => '設定值檔案存在於引擎目錄中，但是網頁伺服器無法讀取它。您可以刪除檔案或變更它的讀取權限。',

	'install:check:php:success' => "伺服器上的 PHP 滿足 Elggs 的所有需求。",
	'install:check:rewrite:success' => '已成功測試改寫規則。',
	'install:check:database' => '已檢查過 Elgg 載入其資料庫時的需求。',

	'install:database:instructions' => "如果您尚未建立用於 Elgg 的資料庫，請現在就做，接著填入下列值以初始化 Elgg 資料庫。",
	'install:database:error' => '建立 Elgg 資料庫時出現了錯誤而無法繼續安裝。請檢閱以上的訊息並修正任何問題。如果您需要更多說明，請造訪以下的安裝疑難排解鏈結，或是貼文到 Elgg 社群論壇。',

	'install:database:label:dbuser' =>  '資料庫使用者名稱',
	'install:database:label:dbpassword' => '資料庫密碼',
	'install:database:label:dbname' => '資料庫名稱',
	'install:database:label:dbhost' => '資料庫主機',
	'install:database:label:dbprefix' => '資料表前綴',
	'install:database:label:timezone' => "Timezone",

	'install:database:help:dbuser' => '擁有您為 Elgg 所建立的 MySQL 資料庫完整權限的使用者',
	'install:database:help:dbpassword' => '用於以上資料庫的使用者密碼',
	'install:database:help:dbname' => 'Elgg 資料庫的名稱',
	'install:database:help:dbhost' => 'MySQL 伺服器的主機名稱 (通常是 localhost)',
	'install:database:help:dbprefix' => "賦予所有 Elgg 資料表的前綴 (通常是 elgg_)",
	'install:database:help:timezone' => "The default timezone in which the site will operate",

	'install:settings:instructions' => 'We need some information about the site as we configure Elgg. If you haven\'t <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">created a data directory</a> for Elgg, you need to do so now.',

	'install:settings:label:sitename' => '站臺名稱',
	'install:settings:label:siteemail' => '站臺電子郵件地址',
	'install:database:label:wwwroot' => '站臺網址',
	'install:settings:label:path' => 'Elgg 安裝目錄',
	'install:database:label:dataroot' => '資料目錄',
	'install:settings:label:language' => '站臺語言',
	'install:settings:label:siteaccess' => '預設站臺存取',
	'install:label:combo:dataroot' => 'Elgg 建立資料目錄',

	'install:settings:help:sitename' => '新建 Elgg 站臺的名稱',
	'install:settings:help:siteemail' => 'Elgg 用於聯絡使用者的電子郵件地址',
	'install:database:help:wwwroot' => '站臺的網址 (Elgg 通常能夠正確猜測)',
	'install:settings:help:path' => '您置放 Elgg 程式碼的目錄位置 (Elgg 通常能夠正確猜測)',
	'install:database:help:dataroot' => '您所建立用於 Elgg 儲存檔案的目錄 (當您按「下一步」時，將會檢查這個目錄的權限)。它必須是絕對路徑。',
	'install:settings:help:dataroot:apache' => '您可以選擇讓 Elgg 建立資料目錄，或是輸入您已建立用於儲存使用者檔案的目錄 (當您按「下一步」時，將會檢查這個目錄的權限)',
	'install:settings:help:language' => '站臺使用的預設語言',
	'install:settings:help:siteaccess' => '新使用者建立內容時的預設存取等級',

	'install:admin:instructions' => "現在是建立管理者帳號的時候了。",

	'install:admin:label:displayname' => '代號',
	'install:admin:label:email' => '電子郵件',
	'install:admin:label:username' => '使用者名稱',
	'install:admin:label:password1' => '密碼',
	'install:admin:label:password2' => '再次輸入密碼',

	'install:admin:help:displayname' => '這個帳號在站臺上所顯示的名稱',
	'install:admin:help:email' => '',
	'install:admin:help:username' => '帳號使用者的登入名稱',
	'install:admin:help:password1' => "帳號密碼必須至少有 %u 個字元長",
	'install:admin:help:password2' => '再次輸入密碼以確認',

	'install:admin:password:mismatch' => '密碼必須匹配。',
	'install:admin:password:empty' => '密碼不可為空。',
	'install:admin:password:tooshort' => '您的密碼太短',
	'install:admin:cannot_create' => '無法建立管理帳號。',

	'install:complete:instructions' => 'Elgg 站臺現在已準備好要使用。按以下按鈕以進入站臺。',
	'install:complete:gotosite' => '前往站臺',
	'install:complete:admin_notice' => 'Welcome to your Elgg site! For more options, see the %s.',
	'install:complete:admin_notice:link_text' => 'settings pages',

	'InstallationException:UnknownStep' => '%s 是不明的安裝步驟。',
	'InstallationException:MissingLibrary' => 'Could not load %s',
	'InstallationException:CannotLoadSettings' => 'Elgg could not load the settings file. It does not exist or there is a file permissions issue.',

	'install:success:database' => '資料庫已安裝。',
	'install:success:settings' => '站臺設定值已儲存。',
	'install:success:admin' => '管理帳號已建立。',

	'install:error:htaccess' => '無法建立 .htaccess',
	'install:error:settings' => '無法建立設定值檔案',
	'install:error:settings_mismatch' => 'The settings file value for "%s" does not match the given $params.',
	'install:error:databasesettings' => '無法以這些設定值連線到資料庫。',
	'install:error:database_prefix' => '在資料庫前綴中有無效字元',
	'install:error:oldmysql2' => 'MySQL 必須是版本 5.5.3 或以上。伺服器正在使用 %s。',
	'install:error:nodatabase' => '無法使用資料庫 %s。它可能不存在。',
	'install:error:cannotloadtables' => '無法載入資料表格',
	'install:error:tables_exist' => '在資料庫中已有 Elgg 表格。您需要選擇丟棄那些表格，或是重新啟動安裝程式而我們將試圖去使用它們。如果要重新啟動安裝程式，請自瀏覽器網址列中移除 \'?step=database\' 並按下輸入鍵。',
	'install:error:readsettingsphp' => 'Unable to read /elgg-config/settings.example.php',
	'install:error:writesettingphp' => 'Unable to write /elgg-config/settings.php',
	'install:error:requiredfield' => '%s 為必要項目',
	'install:error:relative_path' => '我們不認為 %s 是資料目錄的絕對路徑',
	'install:error:datadirectoryexists' => '資料目錄 %s 不存在。',
	'install:error:writedatadirectory' => '資料目錄 %s 無法由網頁伺服器寫入。',
	'install:error:locationdatadirectory' => '資料目錄 %s 基於安全必須位於安裝路徑之外。',
	'install:error:emailaddress' => '%s 並非有效的電子郵件地址',
	'install:error:createsite' => '無法建立站臺。',
	'install:error:savesitesettings' => '無法儲存站臺設定值',
	'install:error:loadadmin' => '無法載入管理者。',
	'install:error:adminaccess' => '無法賦予新使用者帳號管理權限。',
	'install:error:adminlogin' => '無法自動登入新的管理者。',
	'install:error:rewrite:apache' => '我們認為您的主機正在運行 Apache 網頁伺服器。',
	'install:error:rewrite:nginx' => '我們認為您的主機正在運行 Nginx 網頁伺服器。',
	'install:error:rewrite:lighttpd' => '我們認為您的主機正在運行 Lighttpd 網頁伺服器。',
	'install:error:rewrite:iis' => '我們認為您的主機正在運行 IIS 網頁伺服器。',
	'install:error:rewrite:allowoverride' => "The rewrite test failed and the most likely cause is that AllowOverride is not set to All for Elgg's directory. This prevents Apache from processing the .htaccess file which contains the rewrite rules.
\n\nA less likely cause is Apache is configured with an alias for your Elgg directory and you need to set the RewriteBase in your .htaccess. There are further instructions in the .htaccess file in your Elgg directory.",
	'install:error:rewrite:htaccess:write_permission' => '網頁伺服器沒有在 Elgg 的目錄中建立.htaccess 檔案的權限。您需要手動將 htaccess_dist 拷貝為 .htaccess，或是變更目錄上的權限。',
	'install:error:rewrite:htaccess:read_permission' => '在 Elgg 的目錄中有 .htaccess 檔案，但是網頁伺服器沒有讀取它的權限。',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => '在 Elgg 的目錄中有 .htaccess 檔案，但那不是由 Elgg 所建立的。請移除它。',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => '在 Elgg 的目錄中似乎是舊的 Elgg .htaccess 檔案。它不包含改寫規則用於測試網頁伺服器。',
	'install:error:rewrite:htaccess:cannot_copy' => '不明發生錯誤當建立.htaccess 檔案。您需要手動在 Elgg 的目錄中將 htaccess_dist 拷貝為 .htaccess。',
	'install:error:rewrite:altserver' => '改寫規則測試失敗。您需要組配網頁伺服器與 Elgg 的改寫規則並再次嘗試。',
	'install:error:rewrite:unknown' => '哎呀，我們無法認出在主機中運行什麼樣的網頁伺服器，而它的改寫規則失敗。我們無法提供任何特定的建言。請看看疑難排解鏈結。',
	'install:warning:rewrite:unknown' => '您的伺服器不支援自動的改寫規則測試，而您的瀏覽器不支援經由 JavaScript 的檢查。您可以繼續進行安裝，但是也許會遇到一些站臺問題。您可以藉由按下這個鏈結，來手動<a href="%s" target="_blank ">測試</a>改寫規則。如果規則發生作用，您將會看到成功的字樣。',
	'install:error:wwwroot' => '%s is not a valid URL',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => '發生了無法回復的錯誤，並且已經記錄下來。如果您是站臺管理者，請檢查您的設定檔案；否則請聯絡站臺管理者，並附上以下資訊：',
	'DatabaseException:WrongCredentials' => "Elgg 無法利用給定的憑據與資料庫連線。請檢查設定檔案。",
);
