<?php
return array(
	// menu
	'admin:develop_tools' => '工具',
	'admin:develop_tools:sandbox' => '主题测试盒',
	'admin:develop_tools:inspect' => '检查',
	'admin:inspect' => '检查',
	'admin:develop_tools:unit_tests' => '单元测试',
	'admin:developers' => '开发者',
	'admin:developers:settings' => '设置',

	// settings
	'elgg_dev_tools:settings:explanation' => '控制下面的开发及Debug设置。 部分设置也出现在其他管理页面。',
	'developers:label:simple_cache' => '使用简单缓存',
	'developers:help:simple_cache' => '开发时请关闭缓存。否则，CSS 及 JavaScript 修改不会生效。 ',
	'developers:label:system_cache' => '使用系统缓存。',
	'developers:help:system_cache' => '开发过程中请将此开关关闭。否则，插件的变化无法及时生效。',
	'developers:label:debug_level' => "跟踪排错方式",
	'developers:help:debug_level' => "这将记录大量信息。 更多信息，请查看  elgg_log() 。",
	'developers:label:display_errors' => '显示 PHP 致命错误',
	'developers:help:display_errors' => "默认情况下，Elgg 的 .htaccess 文件隐去了致命错误的显示。",
	'developers:label:screen_log' => "在屏幕上显示日志",
	'developers:help:screen_log' => "这将显示 elgg_log() 和 elgg_dump() 输入及数据库查询情况。",
	'developers:label:show_strings' => "显示翻译的字符串",
	'developers:help:show_strings' => "这将显示使用 elgg_echo() 翻译的字符串。",
	'developers:label:show_modules' => "在控制台展示AMD模块已装载",
	'developers:help:show_modules' => "在javascript控制台中展示装载的模块及数据",
	'developers:label:wrap_views' => "Wrap views",
	'developers:help:wrap_views' => "This wraps almost every view with HTML comments. Useful for finding the view creating particular HTML.
⇥⇥⇥⇥⇥⇥⇥⇥⇥This can break non-HTML views in the default viewtype. See developers_wrap_views() for details.",
	'developers:label:log_events' => "记录事件及插件hooks",
	'developers:help:log_events' => "将事件及插件hooks记入日志。 警告：每页均有大量相关信息。",
	'developers:label:show_gear' => "在管理区外使用了 %s",
	'developers:help:show_gear' => "视窗右下角的图标用于让开发人员以管理权限设置及链接",
	'developers:label:submit' => "保存并更新缓存",

	'developers:debug:off' => '关闭',
	'developers:debug:error' => '错误',
	'developers:debug:warning' => '警告',
	'developers:debug:notice' => '注意',
	'developers:debug:info' => '信息',
	
	// inspection
	'developers:inspect:help' => '检查 Elgg 框架的配置',
	'developers:inspect:actions' => '活动',
	'developers:inspect:events' => '事件',
	'developers:inspect:menus' => '菜单',
	'developers:inspect:pluginhooks' => '插件 Hooks',
	'developers:inspect:priority' => '优先级',
	'developers:inspect:simplecache' => '简单缓存',
	'developers:inspect:views' => '视图',
	'developers:inspect:views:all_filtered' => "<b>注意!</b> 所有视图输出均会被这些插件过滤:",
	'developers:inspect:views:filtered' => "(被插件: %s 过滤)",
	'developers:inspect:widgets' => '小窗口',
	'developers:inspect:webservices' => 'Webservices',
	'developers:inspect:widgets:context' => '内容',
	'developers:inspect:functions' => '功能',
	'developers:inspect:file_location' => 'Elgg 根目录路径',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' 在 %s 中",
	'developers:log_queries' => "%s 数据查询 (未包含关闭时间)",

	// theme sandbox
	'theme_sandbox:intro' => '介绍',
	'theme_sandbox:breakout' => '打破 iframe',
	'theme_sandbox:buttons' => '按钮',
	'theme_sandbox:components' => '元件',
	'theme_sandbox:forms' => '表单',
	'theme_sandbox:grid' => '网格',
	'theme_sandbox:icons' => '图标',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => '布局',
	'theme_sandbox:modules' => '模块',
	'theme_sandbox:navigation' => '导航',
	'theme_sandbox:typography' => '排版',

	'theme_sandbox:icons:blurb' => '建议使用  <em>elgg_view_icon($name)</em> 或 类 elgg-icon-$name 来显示图标。',

	// unit tests
	'developers:unit_tests:description' => 'Elgg 在内核类库及函数中集成了BUG检测工具和测试程序。',
	'developers:unit_tests:warning' => '警告：不要在生产环境下进行这些测试，可能会导致数据库崩溃。',
	'developers:unit_tests:run' => '执行',

	// status messages
	'developers:settings:success' => '设置已保存，缓存已更新。',

	'developers:amd' => 'AMD',
);
