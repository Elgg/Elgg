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

	'item:site:site' => '站点',
	'collection:site:site' => '站点',
	'index:content' => '<p>欢迎来到Elgg站点.</p><p><strong>提示:</strong> 许多站点使用 <code>激活的</code> 插件将站点动态添加到这个页面上。</p>',

/**
 * Sessions
 */

	'login' => "登录",
	'loginok' => "请勿重复登录，如果您需要切换账号请先注销。",
	'login:empty' => "请输入登录信息！",
	'login:baduser' => "此账号已经被禁止登录。",
	'auth:nopams' => "网络故障，无法认证当前登录信息！",

	'logout' => "注销",
	'logoutok' => "注销成功！",
	'logouterror' => "注销失败，请重新尝试！",
	'session_expired' => "您的登录信息已经过期。 请<a href='javascript:location.reload(true)'>重新登录</a>！",
	'session_changed_user' => "登录信息发生改变，请 <a href='javascript:location.reload(true)'>重新登录</a>！",

	'loggedinrequired' => "请登录后查看此页面！",
	'adminrequired' => "需要管理员权限！",
	'membershiprequired' => "您不是该小组成员，请先加入该研究组！",
	'limited_access' => "抱歉，您无权限查看！",
	'invalid_request_signature' => "此链接不存在或者已经过期！",

/**
 * Errors
 */

	'exception:title' => "严重错误！",
	'exception:contact_admin' => '当前错误已经被记录，请稍后访问该站点。如果多次出现请通过以下信息联系管理员：',

	'actionnotfound' => "请求文件 %s 不存在！",
	'actionunauthorized' => '您没有权限执行此操作。',

	'ajax:error' => '无法提交 AJAX 请求，可能是服务器连接中断请稍后再试！',
	'ajax:not_is_xhr' => '不能直接访问AJAX视图',

	'PluginException:CannotStart' => '%s (guid: %s) 无法启用， 原因: %s',
	'PluginException:InvalidID' => "插件ID无效： %s ",
	'PluginException:InvalidPath' => "插件路径无效 ： %s ",
	'ElggPlugin:MissingID' => '插件ID错误(guid %s)',
	'ElggPlugin:Error' => '插件错误',
	'ElggPlugin:Exception:CannotIncludeFile' => '无法包含 %s 在插件 %s (guid: %s) 中 %s。',
	'ElggPlugin:Exception:IncludeFileThrew' => '异常来自 %s 在插件 %s (guid: %s) 中 %s。',
	'ElggPlugin:Exception:CannotRegisterViews' => '无法打开插件视图 %s (guid: %s) 在 %s.',
	'ElggPlugin:InvalidAndDeactivated' => '%s 插件无效已被禁用！',
	'ElggPlugin:activate:BadConfigFormat' => '插件文件 "elgg-plugin.php" 未返回序列化数据。',
	'ElggPlugin:activate:ConfigSentOutput' => '发送 "elgg-plugin.php" 到标准输出。',

	'ElggPlugin:Dependencies:ActiveDependent' => '另一个插件将 %s 作为依赖。  取消激活此插件前请先取消： %s',

	'ElggMenuBuilder:Trees:NoParents' => '菜单项没有上级链接',
	'ElggMenuBuilder:Trees:OrphanedChild' => '菜单项 [%s] 丢失上级 [%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => '菜单项 [%s] 重复注册',

	'RegistrationException:EmptyPassword' => '密码为必填项',
	'RegistrationException:PasswordMismatch' => '密码必须相同',
	'LoginException:BannedUser' => '此账号已被禁止登录！',
	'LoginException:UsernameFailure' => '登录失败，用户名/Email不存在！',
	'LoginException:PasswordFailure' => '登录失败， 密码错误！',
	'LoginException:AccountLocked' => '由于多次登录失败，您的账户已被锁定。',
	'LoginException:ChangePasswordFailure' => '当前密码校验失败。',
	'LoginException:Unknown' => '未知错误！登录失败。（如果多次出现，请联系管理员！）',

	'UserFetchFailureException' => '用户user_guid [%s]校验失败，因为用户不存在。',

	'PageNotFoundException' => '您试图查看的页面不存在，或者您没有查看它的权限。',
	'EntityNotFoundException' => '您试图访问的内容已被删除，或者您没有访问它的权限。',
	'EntityPermissionsException' => '此操作没有足够的权限。',
	'GatekeeperException' => '您没有权限查看要访问的页面。',
	'BadRequestException' => '请求失败！',

	'viewfailure' => '在视图中存在错误 %s',
	'changebookmark' => '请更改此页的书签！',
	'error:missing_data' => '在您的请求中部分数据丢失。',
	'save:fail' => '数据保存失败！',
	'save:success' => '数据保存成功',

	'error:default:title' => 'Oops...',
	'error:default:content' => 'Oops... 页面错误！',
	'error:400:title' => '错误请求！',
	'error:400:content' => '无效请求!',
	'error:403:title' => '禁止',
	'error:403:content' => '页面禁止访问！',
	'error:404:title' => '页面不存在！',
	'error:404:content' => '抱歉，请求页面不存在！',

	'upload:error:ini_size' => '上传文件超过限制！',
	'upload:error:form_size' => '上传文件超过限制！',
	'upload:error:partial' => '文件上传未完成！',
	'upload:error:no_file' => '未选中文件。',
	'upload:error:no_tmp_dir' => '无法保存上传文件',
	'upload:error:cant_write' => '无法保存上传文件',
	'upload:error:extension' => '无法保存上传文件',
	'upload:error:unknown' => '文件上传失败',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => '管理员',
	'table_columns:fromView:banned' => '禁止',
	'table_columns:fromView:container' => '容器',
	'table_columns:fromView:excerpt' => '描述',
	'table_columns:fromView:link' => '名称/标题',
	'table_columns:fromView:icon' => '图标',
	'table_columns:fromView:item' => '栏目',
	'table_columns:fromView:language' => '语言',
	'table_columns:fromView:owner' => '自己',
	'table_columns:fromView:time_created' => '创建时间',
	'table_columns:fromView:time_updated' => '更新时间',
	'table_columns:fromView:user' => '用户',

	'table_columns:fromProperty:description' => '描述',
	'table_columns:fromProperty:email' => '邮箱',
	'table_columns:fromProperty:name' => '姓名',
	'table_columns:fromProperty:type' => '类型',
	'table_columns:fromProperty:username' => '用户名',

	'table_columns:fromMethod:getSubtype' => '子类',
	'table_columns:fromMethod:getDisplayName' => '名称/标题',
	'table_columns:fromMethod:getMimeType' => '媒体类型',
	'table_columns:fromMethod:getSimpleType' => '类型',

/**
 * User details
 */

	'name' => "姓名",
	'email' => "邮箱地址",
	'username' => "用户名",
	'loginusername' => "用户名或者邮箱",
	'password' => "密码",
	'passwordagain' => "密码 (再次输入密码确认)",
	'admin_option' => "设置此账号为管理员?",
	'autogen_password_option' => "自动生成安全密码?",

/**
 * Access
 */

	'access:label:private' => "仅限自己",
	'access:label:logged_in' => "仅限登录用户",
	'access:label:public' => "完全公开",
	'access:label:logged_out' => "非登录用户",
	'access:label:friends' => "好友",
	'access' => "谁可以查看？",
	'access:limited:label' => "限制",
	'access:help' => "访问级别",
	'access:read' => "可读访问",
	'access:write' => "可写访问",
	'access:admin_only' => "仅管理员",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "个人中心",
	'dashboard:nowidgets' => "通过个人中心可以关注与您相关的动态活动与内容。",

	'widgets:add' => '添加组件',
	'widgets:add:description' => "点击下列任意组件使其在本页显示。",
	'widget:unavailable' => '您已经添加了该组件',
	'widget:numbertodisplay' => '显示数量',

	'widget:delete' => '删除 %s',
	'widget:edit' => '自定义该组件',

	'widgets' => "组件",
	'widget' => "组件",
	'item:object:widget' => "组件",
	'collection:object:widget' => '页面组件',
	'widgets:save:success' => "该组件保存成功！",
	'widgets:save:failure' => "无法保存该组件！",
	'widgets:add:success' => "组件添加成功！",
	'widgets:add:failure' => "无法添加该组件！",
	'widgets:move:failure' => "不能保存新组件位置。",
	'widgets:remove:failure' => "不能移除该组件。",
	
/**
 * Groups
 */

	'group' => "圈子",
	'item:group' => "圈子",
	'collection:group' => '圈子',
	'item:group:group' => "圈子",
	'collection:group:group' => '圈子',
	'groups:tool_gatekeeper' => "此组中当前未启用请求的功能。",

/**
 * Users
 */

	'user' => "用户",
	'item:user' => "用户",
	'collection:user' => '用户',
	'item:user:user' => '用户',
	'collection:user:user' => '用户',

	'friends' => "好友",
	'collection:friends' => '好友\' %s',

	'avatar' => '头像',
	'avatar:noaccess' => "你没有编辑该用户头像的权限",
	'avatar:create' => '创建头像',
	'avatar:edit' => '编辑头像',
	'avatar:upload' => '上传新头像',
	'avatar:current' => '当前头像',
	'avatar:remove' => '删除头像，恢复系统默认头像图标',
	'avatar:crop:title' => '头像裁剪工具',
	'avatar:upload:instructions' => "头像在网站内显示，您可以根据自己的喜好随时更换.（头像文件类型：gif，jpg或png）",
	'avatar:create:instructions' => '点击拖动方框来裁剪头像，右边的方框显示预览，点击\'创建头像\'即可完成. ',
	'avatar:upload:success' => '头像上传成功',
	'avatar:upload:fail' => '头像上传失败',
	'avatar:resize:fail' => '调整头像大小失败',
	'avatar:crop:success' => '裁剪头像成功',
	'avatar:crop:fail' => '裁剪头像失败',
	'avatar:remove:success' => '删除头像成功',
	'avatar:remove:fail' => '删除头像失败',
	
	'action:user:validate:already' => "%s 已经验证通过",
	'action:user:validate:success' => "%s 验证成功",
	'action:user:validate:error' => "验证 %s 时出现错误！",

/**
 * Feeds
 */
	'feed:rss' => 'RSS',
	'feed:rss:title' => '订阅此页面',
/**
 * Links
 */
	'link:view' => '查看链接',
	'link:view:all' => '查看所有',


/**
 * River
 */
	'river' => "动态",
	'river:user:friend' => "%s 已经关注了 %s",
	'river:update:user:avatar' => '%s 更新了头像',
	'river:noaccess' => '您还没有权限查看该内容。',
	'river:posted:generic' => '%s 发布',
	'riveritem:single:user' => '单用户',
	'riveritem:plural:user' => '多用户',
	'river:ingroup' => '在小组%s中',
	'river:none' => '还没有动态',
	'river:update' => '%s的更新',
	'river:delete' => '删除该动态',
	'river:delete:success' => '该动态已被删除',
	'river:delete:fail' => '该动态无法删除',
	'river:delete:lack_permission' => '你无法删除该动态，权限不够！',
	'river:subject:invalid_subject' => '无效用户',
	'activity:owner' => '查看动态',

/**
 * Relationships
 */

/**
 * Notifications
 */
	'notification:method:email' => 'Email',
	'notification:subject' => '关于%s的消息提醒设置',
	'notification:body' => '在%s中查看新的动态',

/**
 * Search
 */

	'search' => "搜索",
	'searchtitle' => "搜索: %s",
	'users:searchtitle' => "搜索用户: %s",
	'groups:searchtitle' => "搜索研究组: %s",
	'advancedsearchtitle' => "%s 匹配的搜索 %s",
	'notfound' => "未找到.",

	'viewtype:change' => "更换显示方式",
	'viewtype:list' => "列表显示",
	'viewtype:gallery' => "预览显示",
	'search:go' => '开始',
	'userpicker:only_friends' => '仅限研友',

/**
 * Account
 */

	'account' => "账户",
	'settings' => "设置",
	'tools' => "工具",
	'settings:edit' => '编辑设置',

	'register' => "注册",
	'registerok' => "你已成功注册%s.",
	'registerbad' => "由于未知错误导致注册失败.",
	'registerdisabled' => "注册已被系统管理员禁用",
	'register:fields' => '全部字段都为必填',

	'registration:noname' => '需要输入昵称',
	'registration:notemail' => '电子邮箱地址无效.',
	'registration:userexists' => '用户名已经被注册',
	'registration:usernametooshort' => '用户名不能少于%u字符.',
	'registration:usernametoolong' => '用户名太长, 不能超过%u字符.',
	'registration:dupeemail' => '该邮箱地址已被注册.',
	'registration:invalidchars' => '对不起，你的用户名含有无效字符%s. 以下字符是无效的: %s',
	'registration:emailnotvalid' => '对不起，你输入的邮箱地址在此系统无效',
	'registration:passwordnotvalid' => '对不起，你输入的密码在此系统无效',
	'registration:usernamenotvalid' => '对不起，你输入的用户名无效',

	'adduser' => "添加用户",
	'adduser:ok' => "您已经成功添加了一个新用户.",
	'adduser:bad' => "新用户创建失败.",

	'user:set:name' => "账户名设置",
	'user:name:label' => "我的名字",
	'user:name:success' => "名字修改成功.",
	'user:name:fail' => "名字修改失败.",
	'user:username:success' => "成功更改系统上的用户名。",
	'user:username:fail' => "无法更改系统上的用户名。",

	'user:set:password' => "账户密码",
	'user:current_password:label' => '当前密码',
	'user:password:label' => "新密码",
	'user:password2:label' => "新密码确认",
	'user:password:success' => "密码修改成功",
	'user:password:fail' => "密码修改失败.",
	'user:password:fail:notsame' => "密码不一致!",
	'user:password:fail:tooshort' => "密码太短!",
	'user:password:fail:incorrect_current_password' => '当前密码输入不正确.',
	'user:changepassword:unknown_user' => '无效的用户名',
	'user:changepassword:change_password_confirm' => '此操作将修改您的密码.',

	'user:set:language' => "语言设置",
	'user:language:label' => "语言",
	'user:language:success' => "语言设置成功更新.",
	'user:language:fail' => "语言设置失败.",

	'user:username:notfound' => '用户名 %s未找到。',
	'user:username:help' => '请注意，更改用户名将改变所有动态的与用户相关的链接。',

	'user:password:lost' => '忘记密码',
	'user:password:hash_missing' => '很遗憾，我们必须要求您重新设置密码。我们改进了站点上的密码安全性，但无法在进程中迁移所有帐户。',
	'user:password:changereq:success' => '成功重置了密码, 邮件已经发出',
	'user:password:changereq:fail' => '不能重置新密码.',

	'user:password:text' => '输入你的用户名或者Email地址，点击找回密码按钮找回密码.',

	'user:persistent' => '记住我',

	'walled_garden:home' => '主页',

/**
 * Password requirements
 */
	
/**
 * Administration
 */
	'menu:page:header:administer' => '管理员',
	'menu:page:header:configure' => '配置',
	'menu:page:header:develop' => '开发',
	'menu:page:header:information' => '信息',
	'menu:page:header:default' => '其他',

	'admin:view_site' => '访问网站',
	'admin:loggedin' => '以%s身份登录',
	'admin:menu' => '菜单',

	'admin:configuration:success' => "设置已经保存。",
	'admin:configuration:fail' => "设置保存失败。",
	'admin:configuration:dataroot:relative_path' => '不能设置"%s" 作为数据库，因为它不是一个绝对路径。',
	'admin:configuration:default_limit' => '每页的项目数不得少于1个。',

	'admin:unknown_section' => '无效的管理区段。',

	'admin' => "管理",
	'admin:description' => "管理面板允许你控制系统的各个方面，从用户管理到插件行为，从下面选择一个选项开始。",
	
	'admin:statistics' => '统计',
	'admin:server' => '服务器信息',
	'admin:cron' => '任务',
	'admin:cron:record' => '最新Cron工作',
	'admin:cron:period' => 'Cron周期',
	'admin:cron:friendly' => '最新完成',
	'admin:cron:date' => '日期与时间',
	'admin:cron:msg' => '消息',
	'admin:cron:started' => 'Cron 任务 "%s" 开始于 %s',
	'admin:cron:complete' => 'Cron 任务 "%s" 完成于 %s',

	'admin:appearance' => '外观',
	'admin:administer_utilities' => '站点设置',
	'admin:develop_utilities' => '站点设置',
	'admin:configure_utilities' => '站点设置',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "用户",
	'admin:users:online' => '当前在线用户',
	'admin:users:newest' => '最新用户',
	'admin:users:admins' => '管理员',
	'admin:users:add' => '添加新用户',
	'admin:users:description' => "管理面板允许你控制用户设置，从下面选择一个选项开始.",
	'admin:users:adduser:label' => "点击此处添加新用户...",
	'admin:users:opt:linktext' => "配置用户...",
	'admin:users:opt:description' => "配置用户和账户信息. ",
	'admin:users:find' => '查找',
	'admin:users:unvalidated' => '未经验证的',
	'admin:users:unvalidated:no_results' => '无',
	'admin:users:unvalidated:registered' => '注册: %s',
	
	'admin:configure_utilities:maintenance' => '维护模式',
	'admin:upgrades' => '升级',
	'admin:upgrades:run' => '运行升级。',
	'admin:upgrades:error:invalid_batch' => '无法实例化升级%s (%s) 的批处理程序',

	'admin:settings' => '设置',
	'admin:settings:basic' => '基本设置',
	'admin:settings:advanced' => '高级设置',
	'admin:settings:users' => '用户',
	'admin:site:description' => "管理面板允许你控制全局设置，从下面选择一个选项开始.",
	'admin:site:opt:linktext' => "配置网站...",
	'admin:settings:in_settings_file' => '此设置在settings.php里面配置',

	'site_secret:current_strength' => '密钥强度',
	'site_secret:strength:weak' => "弱",
	'site_secret:strength_msg:weak' => "我们强烈推荐你重新生成网站秘级.",
	'site_secret:strength:moderate' => "中",
	'site_secret:strength_msg:moderate' => "为了页面安全，我们建议您重新生成网站秘级.",
	'site_secret:strength:strong' => "强",
	'site_secret:strength_msg:strong' => "你的网站秘级足够强，无需重新生成.",

	'admin:dashboard' => '管理中心',
	'admin:widget:online_users' => '在线用户',
	'admin:widget:online_users:help' => '列出当前正在访问网站的用户',
	'admin:widget:new_users' => '新用户',
	'admin:widget:new_users:help' => '列出最新用户',
	'admin:widget:banned_users' => '被禁用户',
	'admin:widget:banned_users:help' => '列出被禁的用户',
	'admin:widget:content_stats' => '内容统计',
	'admin:widget:content_stats:help' => '跟踪用户创建的内容',
	'admin:widget:cron_status' => 'Cron状态',
	'admin:widget:cron_status:help' => '显示上次Cron工作完成的状态',
	'admin:statistics:numentities' => '内容统计',
	'admin:statistics:numentities:type' => '内容类型',
	'admin:statistics:numentities:number' => '数量',
	'admin:statistics:numentities:searchable' => '搜索',
	'admin:statistics:numentities:other' => '其他',

	'admin:widget:admin_welcome' => '欢迎',
	'admin:widget:admin_welcome:help' => "管理区域的简短介绍",
	'admin:widget:admin_welcome:intro' => '欢迎使用Elgg! 现在你正在查看管理面板，它可以跟踪网站使用情况.',

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />通过底部链接检查可用的资源，感谢使用Elgg!',

	'admin:widget:control_panel' => '管理中心',
	'admin:widget:control_panel:help' => "支持普通控制的快捷访问",

	'admin:cache:flush' => '清除缓存',
	'admin:cache:flushed' => "网站的缓存已清除",

	'admin:footer:faq' => '管理FAQ',
	'admin:footer:manual' => '管理手册',
	'admin:footer:community_forums' => 'Elgg社区论坛',
	'admin:footer:blog' => 'Elgg博客',

	'admin:plugins:category:all' => '全部插件',
	'admin:plugins:category:active' => '激活的插件',
	'admin:plugins:category:inactive' => '未激活的插件',
	'admin:plugins:category:admin' => '管理',
	'admin:plugins:category:bundled' => '捆绑',
	'admin:plugins:category:nonbundled' => '非捆绑',
	'admin:plugins:category:content' => '内容',
	'admin:plugins:category:development' => '开发',
	'admin:plugins:category:enhancement' => '增强',
	'admin:plugins:category:api' => '服务/API',
	'admin:plugins:category:communication' => '通讯',
	'admin:plugins:category:security' => '安全与垃圾',
	'admin:plugins:category:social' => '社交',
	'admin:plugins:category:multimedia' => '多媒体',
	'admin:plugins:category:theme' => '主题',
	'admin:plugins:category:widget' => '页面组件',
	'admin:plugins:category:utility' => '功用',

	'admin:plugins:markdown:unknown_plugin' => '未知插件.',
	'admin:plugins:markdown:unknown_file' => '未知文件.',
	'admin:notices:could_not_delete' => '不能删除通知。',
	'item:object:admin_notice' => '管理通知',

	'admin:options' => '管理选项',

	'admin:security' => '安全',
	'admin:security:information' => '信息',
	
	'admin:security:settings' => '设置',
	'admin:security:settings:description' => '在这个页面上，您可以配置一些安全特性。请仔细阅读设置。',
	'admin:security:settings:label:hardening' => '强化',
	'admin:security:settings:label:account' => '账户',
	'admin:security:settings:label:notifications' => '通知',
	'admin:security:settings:label:site_secret' => '站点安全',
	
	'admin:security:settings:notify_admins' => '当添加或删除管理员时通知所有站点管理员。',
	'admin:security:settings:notify_admins:help' => '这将发出一个通知给所有网站管理员，一个管理员添加/删除网站管理员。',
	
	'admin:security:settings:notify_user_admin' => '当添加或删除管理员角色时通知用户。',
	'admin:security:settings:notify_user_admin:help' => '这将向用户发送一个通知，通知管理员角色被添加到帐户中。',
	
	'admin:security:settings:notify_user_ban' => '当用户帐号被禁止时通知用户。',
	'admin:security:settings:notify_user_ban:help' => '这将发送通知给用户，当他们的帐户被（解除）禁止。',
	
	'admin:security:settings:protect_upgrade' => '保护 upgrade.php',
	'admin:security:settings:protect_upgrade:help' => '这将保护upgrade.php所以你需要一个有效的令牌或你必须是管理员。',
	'admin:security:settings:protect_upgrade:token' => '为了能够使用upgrade.php当注销或作为一个非管理员，需要使用以下URL：',
	
	'admin:security:settings:protect_cron' => '保护 /cron URLs',
	'admin:security:settings:protect_cron:help' => '这将保护配置与令牌的网址，只要是提供了一个有效的令牌将cron执行。',
	'admin:security:settings:protect_cron:token' => '为了能够使用配置URL需要使用以下符号。请注意，每个间隔都有自己的标记。',
	'admin:security:settings:protect_cron:toggle' => '显示/隐藏 cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => '禁用密码字段的自动完成',
	'admin:security:settings:disable_password_autocomplete:help' => '这些字段中输入的数据将被浏览器缓存。可以访问受害者浏览器的攻击者可以窃取此信息。如果应用程序通常用于共享计算机，如网吧或机场终端，这一点尤为重要。如果禁用，密码管理工具不再能自动填充这些字段。对于autocomplete属性可以特定浏览器支持。',
	
	'admin:security:settings:email_require_password' => '更改电子邮件地址需要密码验证。',
	'admin:security:settings:email_require_password:help' => '当用户希望更改其电子邮件地址时，要求他们提供当前密码。',
	
	'admin:security:settings:site_secret:intro' => 'Elgg用来创建各种用途的安全令牌的关键。',
	'admin:security:settings:site_secret:regenerate' => "更新网站的密钥",
	'admin:security:settings:site_secret:regenerate:help' => "注：重新生成你的站点的秘密可能会导致一些用户用于“记住我”，电子邮件验证请求，邀请码等的令牌无效。",
	
	'admin:site:secret:regenerated' => "您的站点密钥已被重新生成",
	'admin:site:secret:prevented' => "站点秘密的再生被阻止。",
	
	'admin:notification:make_admin:admin:subject' => 'A new site administrator was added to %s',
	
	'admin:notification:make_admin:user:subject' => 'You were added as a site administator of %s',
	'admin:notification:remove_admin:admin:subject' => 'A site administrator was removed from %s',
	
	'admin:notification:remove_admin:user:subject' => 'You were removed as a site administator from %s',
	'user:notification:ban:subject' => 'Your account on %s was banned',
	
	'user:notification:unban:subject' => 'Your account on %s is no longer banned',

/**
 * Plugins
 */

	'plugins:disabled' => '插件不能装载,因为有一个名为“disabled”的文件存在于mod文件夹。',
	'plugins:settings:save:ok' => "%s插件的设置保存成功。",
	'plugins:settings:save:fail' => "保存 %s 插件设置时遇到问题。",
	'plugins:usersettings:save:ok' => "%s插件的用户设置成功保存。",
	'plugins:usersettings:save:fail' => "保存%s插件的用户设置时遇到问题。",
	
	'item:object:plugin' => '插件',
	'collection:object:plugin' => '所有插件',

	'admin:plugins' => "插件",
	'admin:plugins:activate_all' => '激活全部',
	'admin:plugins:deactivate_all' => '禁用全部',
	'admin:plugins:activate' => '激活',
	'admin:plugins:deactivate' => '禁用',
	'admin:plugins:description' => "管理面板允许你控制和配置安装于网站的工具。",
	'admin:plugins:opt:linktext' => "配置工具...",
	'admin:plugins:opt:description' => "配置安装于网站的工具. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "名称",
	'admin:plugins:label:copyright' => "版权",
	'admin:plugins:label:categories' => '类别',
	'admin:plugins:label:licence' => "授权许可",
	'admin:plugins:label:website' => "网址",
	'admin:plugins:label:info' => "信息",
	'admin:plugins:label:files' => "文件",
	'admin:plugins:label:resources' => "资源",
	'admin:plugins:label:screenshots' => "截图",
	'admin:plugins:label:repository' => "代码",
	'admin:plugins:label:bugtracker' => "报告问题",
	'admin:plugins:label:donate' => "捐赠",
	'admin:plugins:label:moreinfo' => '更多信息',
	'admin:plugins:label:version' => '版本',
	'admin:plugins:label:location' => '位置',
	'admin:plugins:label:priority' => '优先级',
	'admin:plugins:label:dependencies' => '依赖',

	'admin:plugins:warning:unmet_dependencies' => '插件缺少可依赖性工具，不能被激活，检查依赖性了解更多信息。',
	'admin:plugins:warning:invalid' => '无效插件: %s',
	'admin:plugins:warning:invalid:check_docs' => '检查 <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">Elgg文档</a>了解解决方案.',
	'admin:plugins:cannot_activate' => '不能激活',
	'admin:plugins:cannot_deactivate' => '无法取消激活',
	'admin:plugins:already:active' => '选中的插件已激活。',
	'admin:plugins:already:inactive' => '选中的插件已禁用。',

	'admin:plugins:set_priority:yes' => "重新排序 %s。",
	'admin:plugins:set_priority:no' => "不能重排序%s。",
	'admin:plugins:deactivate:yes' => "禁用%s。",
	'admin:plugins:deactivate:no' => "不能禁用%s。",
	'admin:plugins:deactivate:no_with_msg' => "不能禁用%s。 错误发生: %s",
	'admin:plugins:activate:yes' => "激活%s。",
	'admin:plugins:activate:no' => "不能激活 %s。",
	'admin:plugins:activate:no_with_msg' => "不能激活 %s。 错误发生: %s",
	'admin:plugins:categories:all' => '全部分类',
	'admin:plugins:plugin_website' => '插件网站',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => '版本%s',
	'admin:plugin_settings' => '插件设置',
	'admin:plugins:warning:unmet_dependencies_active' => '插件已经激活，但是缺少依赖工具，您可能会遇到问题。查看下面的"更多信息" 了解细节。',

	'admin:statistics:description' => "这是网站统计总览，如果你需要更多详细统计，可用专业管理特点.",
	'admin:statistics:opt:description' => "查看网站用户和对象的统计信息.",
	'admin:statistics:opt:linktext' => "查看统计...",
	'admin:statistics:label:numentities' => "网站实体",
	'admin:statistics:label:numusers' => "用户数量",
	'admin:statistics:label:numonline' => "在线用户数量",
	'admin:statistics:label:onlineusers' => "当前在线用户",
	'admin:statistics:label:admins'=>"管理",
	'admin:statistics:label:version' => "Elgg版本",
	'admin:statistics:label:version:release' => "发布",
	'admin:statistics:label:version:version' => "数据库版本",
	'admin:statistics:label:version:code' => "代码版本",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => '显示 PHPInfo',
	'admin:server:label:web_server' => '网络服务器',
	'admin:server:label:server' => '服务器',
	'admin:server:label:log_location' => '日志位置',
	'admin:server:label:php_version' => 'PHP 版本',
	'admin:server:label:php_ini' => 'PHP ini文件位置',
	'admin:server:label:php_log' => 'PHP 日志',
	'admin:server:label:mem_avail' => '可用储存',
	'admin:server:label:mem_used' => '已用存储',
	'admin:server:error_log' => "网络服务器错误日志",
	'admin:server:label:post_max_size' => '内容最大尺寸',
	'admin:server:label:upload_max_filesize' => '上传最大尺寸',
	'admin:server:warning:post_max_too_small' => '(注意: 内容最大尺寸必须大于此值才能支持上传)',
	
	'admin:server:requirements:php_extension' => "PHP 扩展: %s",
	
	'admin:user:label:search' => "查找用户:",
	'admin:user:label:searchbutton' => "搜索",

	'admin:user:ban:no' => "不能禁用用户",
	'admin:user:ban:yes' => "被禁用户",
	'admin:user:self:ban:no' => "您不能禁用自己",
	'admin:user:unban:no' => "不能解禁用户",
	'admin:user:unban:yes' => "用户解禁",
	'admin:user:delete:no' => "不能删除用户",
	'admin:user:delete:yes' => "用户 %s 已删除",
	'admin:user:self:delete:no' => "您不能删除自己",

	'admin:user:resetpassword:yes' => "密码重置，用户通知。",
	'admin:user:resetpassword:no' => "密码不能重置。",

	'admin:user:makeadmin:yes' => "用户现在已成为管理员。",
	'admin:user:makeadmin:no' => "不能授权此用户为管理员。",

	'admin:user:removeadmin:yes' => "用户不再是管理员。",
	'admin:user:removeadmin:no' => "不能取消这个用户的管理员权限。",
	'admin:user:self:removeadmin:no' => "您不能取消自己的管理员权限。",

	'admin:configure_utilities:menu_items' => '菜单项',
	'admin:menu_items:configure' => '配置主菜单项',
	'admin:menu_items:hide_toolbar_entries' => '从工具栏菜单去除链接?',
	'admin:menu_items:saved' => '菜单项已经保存。',
	'admin:add_menu_item' => '添加自定义菜单项',
	'admin:add_menu_item:description' => '填入显示名称和URL，加定制菜单到你的导航菜单。',

	'admin:configure_utilities:default_widgets' => '默认组件',
	'admin:default_widgets:unknown_type' => '未知组件类型',

	'admin:robots.txt:instructions' => "编辑网站的以下robots.txt文件",
	'admin:robots.txt:plugins' => "插件正在添加下面的内容到robots.txt文件",
	'admin:robots.txt:subdir' => "robots.txt工具不能工作，因为Elgg安装在子目录",
	'admin:robots.txt:physical' => "robots.txt工具不能工作，因为另一个robots.txt文件已经存在",

	'admin:maintenance_mode:default_message' => '网站正在维护',
	'admin:maintenance_mode:mode_label' => '维护模式',
	'admin:maintenance_mode:message_label' => '维护模式开启时显示给用户的信息',
	'admin:maintenance_mode:saved' => '维护模式设置已经保存',
	'admin:maintenance_mode:indicator_menu_item' => '网站处于维护模式中。',
	'admin:login' => '管理员登陆',

/**
 * User settings
 */

	'usersettings:description' => "用户设置面板允许你控制所有个人设置, 从用户管理到插件行为，从下面选择一个选项开始吧",

	'usersettings:statistics' => "你的账户统计",
	'usersettings:statistics:opt:description' => "你的页面的访问统计.",
	'usersettings:statistics:opt:linktext' => "账户统计",

	'usersettings:statistics:login_history' => "登录历史记录",
	'usersettings:statistics:login_history:date' => "时间",
	'usersettings:statistics:login_history:ip' => "IP",

	'usersettings:user' => "%s的设置",
	'usersettings:user:opt:description' => "您可以控制用户设置。",
	'usersettings:user:opt:linktext' => "变更您的设置",

	'usersettings:plugins' => "工具",
	'usersettings:plugins:opt:description' => "为你的工具配置设置。",
	'usersettings:plugins:opt:linktext' => "配置你的工具",

	'usersettings:plugins:description' => "此面板允许你控制和配置个人设置。",
	'usersettings:statistics:label:numentities' => "您的内容",

	'usersettings:statistics:yourdetails' => "您的细节",
	'usersettings:statistics:label:name' => "全名",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "成员来自",
	'usersettings:statistics:label:lastlogin' => "最后登录",

/**
 * Activity river
 */

	'river:all' => '全站动态',
	'river:mine' => '我的动态',
	'river:owner' => '%s的动态',
	'river:friends' => '研友动态',
	'river:select' => '显示 %s',
	'river:comments:more' => '+%u 更多',
	'river:comments:all' => '查看 %u 条全部评论',
	'river:generic_comment' => '评论%s %s',

/**
 * Icons
 */

	'icon:size' => "图标尺寸",
	'icon:size:topbar' => "顶部栏",
	'icon:size:tiny' => "极小",
	'icon:size:small' => "小",
	'icon:size:medium' => "中",
	'icon:size:large' => "大",
	'icon:size:master' => "超大",

/**
 * Generic action words
 */

	'save' => "保存",
	'save_go' => "保存并跳转至 %s",
	'reset' => '重置',
	'publish' => "发布",
	'cancel' => "取消",
	'saving' => "正在保存 ...",
	'update' => "更新",
	'preview' => "预览",
	'edit' => "编辑",
	'delete' => "删除",
	'accept' => "接受",
	'reject' => "拒绝",
	'decline' => "拒绝",
	'approve' => "赞成",
	'activate' => "激活",
	'deactivate' => "停用",
	'disapprove' => "否决",
	'revoke' => "取消",
	'load' => "装载",
	'upload' => "上传",
	'download' => "下载",
	'ban' => "禁止",
	'unban' => "解禁",
	'banned' => "已禁",
	'enable' => "开启",
	'disable' => "关闭",
	'request' => "找回密码",
	'complete' => "完成",
	'open' => '打开',
	'close' => '关闭',
	'hide' => '隐藏',
	'show' => '显示',
	'reply' => "回复",
	'more' => '更多',
	'more_info' => '更多信息',
	'comments' => '评论',
	'import' => '导入',
	'export' => '导出',
	'untitled' => '无标题',
	'help' => '帮助',
	'send' => '发送',
	'post' => '发布',
	'submit' => '提交',
	'comment' => '评论',
	'upgrade' => '升级',
	'sort' => '筛选',
	'filter' => '过滤',
	'new' => '新',
	'add' => '添加',
	'create' => '创建',
	'remove' => '移除',
	'revert' => '恢复',
	'validate' => '验证',
	'next' => '下一页',
	'previous' => '上一页',
	
	'site' => '网站',
	'activity' => '动态',
	'members' => '成员',
	'menu' => '菜单',

	'up' => '向上',
	'down' => '向下',
	'top' => '顶部',
	'bottom' => '底部',
	'right' => '向右',
	'left' => '向左',
	'back' => '返回',

	'invite' => "邀请",

	'resetpassword' => "重置密码",
	'changepassword' => "修改密码",
	'makeadmin' => "加为管理员",
	'removeadmin' => "取消管理员",

	'option:yes' => "是",
	'option:no' => "否",

	'unknown' => '未知',
	'never' => '从不',

	'active' => '活跃',
	'total' => '总计',
	'unvalidated' => '未经验证的',
	
	'ok' => '好',
	'any' => '任何',
	'error' => '错误',

	'other' => '其他',
	'options' => '选项',
	'advanced' => '高级',

	'learnmore' => "点击此处了解更多",
	'unknown_error' => '未知错误',

	'content' => "内容",
	'content:latest' => '最新动态',

	'link:text' => '查看链接',

/**
 * Generic questions
 */

	'question:areyousure' => '你确定这么做吗?',

/**
 * Status
 */

	'status' => '状态',
	'status:unsaved_draft' => '未保存草稿',
	'status:draft' => '草稿',
	'status:unpublished' => '未发布',
	'status:published' => '发布',
	'status:featured' => '精选',
	'status:open' => '打开',
	'status:closed' => '关闭',
	'status:active' => '活跃',

/**
 * Generic sorts
 */

	'sort:newest' => '最新',
	'sort:popular' => '流行',
	'sort:alpha' => '按字母顺序',
	'sort:priority' => '优先级',

/**
 * Generic data words
 */

	'title' => "标题",
	'description' => "描述",
	'tags' => "标签",
	'all' => "全部",
	'mine' => "自己",

	'by' => '由',
	'none' => '无',

	'annotations' => "注释",
	'relationships' => "关系",
	'metadata' => "Meta数据",
	'tagcloud' => "标签云",

	'on' => '开',
	'off' => '关',

/**
 * Entity actions
 */

	'edit:this' => '编辑',
	'delete:this' => '删除',
	'comment:this' => '评论',

/**
 * Input / output strings
 */

	'deleteconfirm' => "确定想删除此项目吗?",
	'deleteconfirm:plural' => "确定想删除这些项目?",
	'fileexists' => "一个文件已经上传，从以下选择替换:",
	'input:file:upload_limit' => '最大允许上传文件大小： %s',

/**
 * User add
 */

	'useradd:subject' => '用户账户已创建',

/**
 * Messages
 */
	'messages:title:success' => '成功',
	'messages:title:error' => '错误',
	'messages:title:warning' => '警告',
	'messages:title:help' => '帮助',
	'messages:title:notice' => '注意',
	'messages:title:info' => '信息',

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "刚刚",
	'friendlytime:minutes' => "%s 分钟前",
	'friendlytime:minutes:singular' => "1分钟前",
	'friendlytime:hours' => "%s 小时前",
	'friendlytime:hours:singular' => "1小时前",
	'friendlytime:days' => "%s 天前",
	'friendlytime:days:singular' => "昨天",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'friendlytime:future:minutes' => "%s 分钟后",
	'friendlytime:future:minutes:singular' => "1分钟后",
	'friendlytime:future:hours' => "%s小时后",
	'friendlytime:future:hours:singular' => "1小时后",
	'friendlytime:future:days' => "%s天后",
	'friendlytime:future:days:singular' => "明天",

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

	'date:month:short:01' => '1月 %s',
	'date:month:short:02' => '2月 %s',
	'date:month:short:03' => '3月 %s',
	'date:month:short:04' => '4月 %s',
	'date:month:short:05' => '5月 %s',
	'date:month:short:06' => '6月 %s',
	'date:month:short:07' => '7月 %s',
	'date:month:short:08' => '8月 %s',
	'date:month:short:09' => '9月 %s',
	'date:month:short:10' => '10月 %s',
	'date:month:short:11' => '11月 %s',
	'date:month:short:12' => '12月 %s',

	'date:weekday:0' => '星期天',
	'date:weekday:1' => '星期一',
	'date:weekday:2' => '星期二',
	'date:weekday:3' => '星期三',
	'date:weekday:4' => '星期四',
	'date:weekday:5' => '星期五',
	'date:weekday:6' => '星期六',

	'date:weekday:short:0' => '周日',
	'date:weekday:short:1' => '周一',
	'date:weekday:short:2' => '周二',
	'date:weekday:short:3' => '周三',
	'date:weekday:short:4' => '周四',
	'date:weekday:short:5' => '周五',
	'date:weekday:short:6' => '周六',

	'interval:minute' => '每分钟',
	'interval:fiveminute' => '每5分钟',
	'interval:fifteenmin' => '每15分钟',
	'interval:halfhour' => '每半小时',
	'interval:hourly' => '每小时',
	'interval:daily' => '每天',
	'interval:weekly' => '每周',
	'interval:monthly' => '每月',
	'interval:yearly' => '每年',

/**
 * System settings
 */

	'installation:sitename' => "网站名称:",
	'installation:sitedescription' => "网站的简短描述(可选):",
	'installation:sitedescription:help' => "使用捆绑的插件，这只出现在搜索引擎结果的描述meta标签中。",
	'installation:wwwroot' => "网站URL:",
	'installation:path' => "Elgg安装的完整路径:",
	'installation:dataroot' => "数据目录的完整路径:",
	'installation:dataroot:warning' => "你必须手动创建这个路径，它独立于Elgg安装文件夹.",
	'installation:sitepermissions' => "默认访问权限:",
	'installation:language' => "网站默认语言:",
	'installation:debug' => "控制写入服务器日志的信息量.",
	'installation:debug:label' => "日志级别:",
	'installation:debug:none' => '关闭日志(推荐)',
	'installation:debug:error' => '仅记录关键错误',
	'installation:debug:warning' => '记录错误和警告',
	'installation:debug:notice' => '记录所有错误、警告和通知',
	'installation:debug:info' => '记录全部',

	// Walled Garden support
	'installation:registration:description' => '用户注册默认开启，如果你不想让他们自己注册，请关闭此项',
	'installation:registration:label' => '允许新用户注册',
	'installation:walled_garden:description' => '开启将阻止非会员访问网站，除非你期望网站标记为公开（比如注册登录）',
	'installation:walled_garden:label' => '仅对登陆用户开放',

	'installation:view' => "输入将用作站点默认值的视图，或将此空白保留为默认视图（如果不确定，请默认为左）：",

	'installation:siteemail' => "站点电子邮件地址（用于发送系统电子邮件）：",
	'installation:default_limit' => "每页的默认项目数",

	'admin:site:access:warning' => "这是用户在创建新内容时建议的隐私设置。更改它不会改变对内容的访问权限。",
	'installation:allow_user_default_access:description' => "启用此功能，允许用户设置自己建议的隐私设置，从而覆盖系统建议。",
	'installation:allow_user_default_access:label' => "默认允许用户访问",

	'installation:simplecache:description' => "简单缓存通过缓存包括一些CSS和JavaScript文件的静态内容来提高性能。",
	'installation:simplecache:label' => "使用简单缓存（推荐）",

	'installation:cache_symlink:description' => "与简单缓存目录的符号链接允许服务器绕过引擎运行静态视图，这大大提高了性能并减少了服务器负载。",
	'installation:cache_symlink:label' => "使用符号链接到简单的缓存目录（推荐）",
	'installation:cache_symlink:warning' => "建立了符号链接。如果出于某种原因，您想删除链接，请从服务器上删除符号链接目录。",
	'installation:cache_symlink:paths' => '正确配置的符号链接必须链接 <i>%s</i> 到 <i>%s</i>',
	'installation:cache_symlink:error' => "由于服务器配置，无法自动建立符号链接。请参考文档并手动建立符号链接。",

	'installation:minify:description' => "简单缓存也能通过压缩Javascript和CSS文件来提升性能(简单缓存需开启)",
	'installation:minify_js:label' => "压缩JavaScript (推荐)",
	'installation:minify_css:label' => "压缩CSS (推荐)",

	'installation:htaccess:needs_upgrade' => "你必须升级你的.htaccess文件，让路径注入到 GET parameter __elgg_uri (你能用install/config/htaccess.dist作为指导)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg无法连接到它自身去测试重写规则，检查curl是否工作，没有IP限制阻止localhost连接",

	'installation:systemcache:description' => "系统缓存通过缓存数据到文件减少系统的装载时间",
	'installation:systemcache:label' => "使用系统缓存(推荐)",

	'admin:legend:system' => '系统',
	'admin:legend:caching' => '缓存',
	'admin:legend:content' => '内容',
	'admin:legend:content_access' => '内容访问',
	'admin:legend:site_access' => '网站访问',
	'admin:legend:debug' => '调试和纪录',
	'config:remove_branding:label' => "除去Elgg版权",
	'config:remove_branding:help' => "整个网站有不同的链接和标志表明这个网站是使用Elgg创建。如果你有版权考虑请捐赠在https://elgg.org/supporter.php。",
	'config:disable_rss:label' => "关闭 RSS 源",
	'config:disable_rss:help' => "禁用此功能，不再使用RSS提供的可用性。",
	'config:friendly_time_number_of_days:label' => "呈现人性化天数的时间",
	'config:friendly_time_number_of_days:help' => "您可以配置使用人性化时间标记的天数。在设定的天数之后，人性化时间将更改为常规日期格式。将此设置为0将禁用人性化的时间格式。",

	'upgrading' => '升级',
	'upgrade:core' => 'Elgg安装已经升级',
	'upgrade:unlock' => '解锁升级',
	'upgrade:unlock:confirm' => "数据库被另一个升级锁定，允许并行升级非常危险，只有你确认没有其他升级正在运行，才应该解锁",
	'upgrade:locked' => "无法升级，另一个升级正在运行，访问管理平台清除升级锁定",
	'upgrade:unlock:success' => "升级成功解锁",

	'admin:pending_upgrades' => '网站有待处理的升级，需要你的注意',
	'admin:view_upgrades' => '查看待处理的升级',
	'item:object:elgg_upgrade' => '网站升级',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => '安装已经是最新',

	'upgrade:success_count' => '升级:',
	'upgrade:finished' => '升级完成',
	'upgrade:finished_with_errors' => '<p>升级完成，但是仍有错误，刷新页面重新运行升级</p></p><br />如果错误重现，检查服务器错误log找到可能的原因，你也可以从<a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> 寻求帮助</p>',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => '对齐数据库GUID列',
	
/**
 * Welcome
 */

	'welcome' => "欢迎",
	'welcome:user' => '欢迎%s',

/**
 * Emails
 */

	'email:from' => '发件人',
	'email:to' => '收件人',
	'email:subject' => '主题',
	'email:body' => '内容',

	'email:settings' => "Email设置",
	'email:address:label' => "Email地址",
	'email:address:password' => "密码",
	'email:address:password:help' => "为了能够更改您的电子邮件地址，您需要提供您当前的密码。",

	'email:save:success' => "新Email地址已经保存，尚需验证",
	'email:save:fail' => "新Email地址无法保存",
	'email:save:fail:password' => "密码与您当前的密码不匹配，无法更改您的电子邮件地址。",

	'friend:newfriend:subject' => "%s已经加你为好友!",

	'email:changepassword:subject' => "密码已更改!",

	'email:resetpassword:subject' => "密码重置!",

	'email:changereq:subject' => "请求修改密码",

/**
 * user default access
 */

	'default_access:settings' => "你的默认访问级别",
	'default_access:label' => "默认访问",
	'user:default_access:success' => "新的默认访问级别保存成功",
	'user:default_access:failure' => "新的默认访问级别保存失败",

/**
 * Comments
 */

	'comments:count' => "%s 评论",
	'item:object:comment' => '评论',
	'collection:object:comment' => '评论',

	'river:object:default:comment' => '%s 评论 %s',

	'generic_comments:add' => "留下评论",
	'generic_comments:edit' => "编辑评论",
	'generic_comments:post' => "发表评论",
	'generic_comments:text' => "评论",
	'generic_comments:latest' => "最新评论",
	'generic_comment:posted' => "您的评论已发布.",
	'generic_comment:updated' => "评论已更新！",
	'generic_comment:blank' => "请先输入评论内容！",
	'generic_comment:notfound' => "未找到指定内容！",
	'generic_comment:failure' => "保存评论时产生意外错误！",
	'generic_comment:none' => '尚无评论',
	'generic_comment:title' => '%s评论',
	'generic_comment:on' => '%s 评论 %s',
	'generic_comments:latest:posted' => '发表了',
	
	'generic_comment:notification:user:summary' => '新评论： %s',

/**
 * Entities
 */

	'byline' => '作者 %s',
	'byline:ingroup' => '在研究组 %s',
	
	'entity:delete:item' => '项目',
	'entity:delete:item_not_found' => '项目为找到。',
	'entity:delete:permission_denied' => '您还没有权限删除此项目。',
	'entity:delete:success' => '%s 已删除。',
	'entity:delete:fail' => '%s 删除失败。',

/**
 * Annotations
 */
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => '表单丢失了 __token 或者 __ts 字段',
	'actiongatekeeper:tokeninvalid' => "你正在使用的页面已过期，请重试。",
	'actiongatekeeper:timeerror' => '你正在使用的页面已过期，请刷新重试。',
	'actiongatekeeper:pluginprevents' => '由于未知原因，你的表单不能提交。',
	'actiongatekeeper:uploadexceeded' => '上传文件尺寸超出最大限制',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => '联系 %s失败，你可能遇到了问题，请保存内容，刷新页面.',
	'js:lightbox:current' => "图片 %s 来自于 %s",

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
	"cmn" => "Mandarin Chinese", // ISO 639-3
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
	"ja" => "Japanese",
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
	"zh_hans" => "简体中文",
	"zu" => "Zulu",

	"field:required" => '需要的',

	"core:upgrade:2017080900:title" => "Alter database encoding for multi-byte support",
	"core:upgrade:2017080900:description" => "Alters database and table encoding to utf8mb4, in order to support multi-byte characters such as emoji",
);
