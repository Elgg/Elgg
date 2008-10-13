<?php

	/**
	 *  Chinese Language Package
	 * 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @translator Cosmo Mao
	 * @copyright cOSmoCommerce.com 2008
	 * @link http://www.elggsns.cn/
	 * @version 0.1
	 */

	$chinese= array(

		/**
		 * Sites
		 */
	
			'item:site' => '网站',
	
		/**
		 * Sessions
		 */
			
			'login' => "登录",
			'loginok' => "您已经登录了.",
			'loginerror' => "我们无法让您登录,这是因为你还没有验证您的帐户（验证链接发到您的邮箱），或您所提供的信息不正确。请确认您的信息正确的，然后再试一次。",
	
			'logout' => "登出",
			'logoutok' => "您已经登出了.",
			'logouterror' => "登出遇到问题,请稍候再试.",
	
		/**
		 * Errors
		 */
			'exception:title' => "欢迎来到ElggSNS.cn,最优秀的中文社交网络",
	
			'InstallationException:CantCreateSite' => "无法创建一个Elgg默认站点的证书名称:%s, Url: %s",
		
			'actionundefined' => "请求的行为 (%s) 没有在系统中定义.",
			'actionloggedout' => "很抱歉，您不能执行这个动作的同时登出。",
	
			'notfound' => "所要求的资源无法找到，或者你没有获得它。",
			
			'SecurityException:Codeblock' => "得不到执行特权代码块的权限",
			'DatabaseException:WrongCredentials' => "Elgg无法连接到数据库使用指定的全权证书 %s@%s (pw: %s).",
			'DatabaseException:NoConnect' => "Elgg不能选择数据库 '%s'，请检查该数据库是建立和可以访问它",
			'SecurityException:FunctionDenied' => "访问特权函数 '%s' 被拒绝了。",
			'DatabaseException:DBSetupIssues' => "有以下问题: ",
			'DatabaseException:ScriptNotFound' => "Elgg找不到数据库脚本位于 %s.",
			
			'IOException:FailedToLoadGUID' => "从GUID:%d 加载 %s 失败",
			'InvalidParameterException:NonElggObject' => "传递一个非ElggObject到另一个ElggObject构造函数!",
			'InvalidParameterException:UnrecognisedValue' => "未知值传递给了构造函数。",
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d 不合法 %s",
			
			'PluginException:MisconfiguredPlugin' => "%s 错误的配置了。",
			
			'InvalidParameterException:NonElggUser' => "传递一个非ElggUser到ElggUser构造函数!",
			
			'InvalidParameterException:NonElggSite' => "传递一个非ElggSite到ElggSite构造函数!",
			
			'InvalidParameterException:NonElggGroup' => "传递一个非ElggGroup到ElggGroup构造函数!",
	
			'IOException:UnableToSaveNew' => "无法保存新 %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID 在输出的时候遇到错误",
			'InvalidParameterException:NonArrayReturnValue' => "实体序列化函数传递了一个非数组返回值参",
			
			'ConfigurationException:NoCachePath' => "缓存路径没有设置!",
			'IOException:NotDirectory' => "%s 不是一个目录。",
			
			'IOException:BaseEntitySaveFailed' => "无法保存新的对象的原型实体信息",
			'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
			'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",
			
			'ClassException:ClassnameNotClass' => "%s is not a %s.",
			'ClassNotFoundException:MissingClass' => "Class '%s' was not found, missing plugin?",
			'InstallationException:TypeNotSupported' => "Type %s is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.",

			'ImportException:ImportFailed' => "无法导入元素 %d",
			'ImportException:ProblemSaving' => "保存 %s 过程遇到错误",
			'ImportException:NoGUID' => "New entity created but has no GUID, this should not happen.",
			
			'ImportException:GUIDNotFound' => "实体 '%d' 无法找到",
			'ImportException:ProblemUpdatingMeta' => "There was a problem updating '%s' on entity '%d'",
			
			'ExportException:NoSuchEntity' => "没有实体 GUID:%d", 
			
			'ImportException:NoODDElements' => "No OpenDD elements found in import data, import failed.",
			'ImportException:NotAllImported' => "Not all elements were imported.",
			
			'InvalidParameterException:UnrecognisedFileMode' => "Unrecognised file mode '%s'",
			'InvalidParameterException:MissingOwner' => "All files must have an owner!",
			'IOException:CouldNotMake' => "Could not make %s",
			'IOException:MissingFileName' => "在打开一个文件时候，您必须指定文件名",
			'ClassNotFoundException:NotFoundNotSavedWithFile' => "Filestore not found or class not saved with file!",
			'NotificationException:NoNotificationMethod' => "No notification method specified.",
			'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
			'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
			'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
			'NotificationException:MissingParameter' => "Missing a required parameter, '%s'",
			
			'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
			'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
			'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
			'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
			'DatabaseException:NoACL' => "No access control was provided on query",
			
			'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",
			
			'InvalidParameterException:GUIDNotFound' => "GUID:%s could not be found, or you can not access it.",
			'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
			'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
			'InvalidParameterException:NoDataFound' => "无法找到任何数据。",
			'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
			'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
			'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",
			
			'SecurityException:APIAccessDenied' => "对不起, API 访问功能被管理员关闭了。",
			'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
			'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.", 
			
			'ConfigurationException:NoSiteID' => "No site ID has been specified.",
			'InvalidParameterException:UnrecognisedMethod' => "Unrecognised call method '%s'",
			'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
			'APIException:ParameterNotArray' => "%s does not appear to be an array.",
			'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
			'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
			'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
			'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
			'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
			'CallException:InvalidCallMethod' => "%s must be called using '%s'",
			'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
			'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
			'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
			'APIException:NotGetOrPost' => "Request method must be GET or POST",
			'APIException:MissingAPIKey' => "Missing X-Elgg-apikey HTTP header",
			'APIException:MissingHmac' => "Missing X-Elgg-hmac header",
			'APIException:MissingHmacAlgo' => "Missing X-Elgg-hmac-algo header",
			'APIException:MissingTime' => "Missing X-Elgg-time header",
			'APIException:TemporalDrift' => "X-Elgg-time is too far in the past or future. Epoch fail.",
			'APIException:NoQueryString' => "No data on the query string",
			'APIException:MissingPOSTHash' => "Missing X-Elgg-posthash header",
			'APIException:MissingPOSTAlgo' => "Missing X-Elgg-posthash_algo header",
			'APIException:MissingContentType' => "Missing content type for post data",
			'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
			'SecurityException:DupePacket' => "Packet signature already seen.",
			'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
			'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.",
	
			'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
			'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
			'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",
	
			'PluginException:NoPluginName' => "The plugin name could not be found",
	
			'ConfigurationException:BadDatabaseVersion' => "The database backend you have installed doesn't meet the basic requirements to run Elgg. Please consult your documentation.",
			'ConfigurationException:BadPHPVersion' => "You need at least PHP version 5.2 to run Elgg.",
			
	
			'InstallationException:DatarootNotWritable' => "您的数据目录 %s 不可写",
			'InstallationException:DatarootUnderPath' => "Your data directory %s must be outside of your install path.",
			'InstallationException:DatarootBlank' => "You have not specified a data directory.",
	
		/**
		 * User details
		 */

			'name' => "显示名",
			'email' => "邮箱地址",
			'username' => "用户名",
			'password' => "密码",
			'passwordagain' => "密码 (确认)",
			'admin_option' => "使成为管理员?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "私有",
			'ACCESS_LOGGED_IN' => "登陆的用户",
			'ACCESS_PUBLIC' => "公开",
			'PRIVATE' => "私有",
			'LOGGED_IN' => "登陆的用户",
			'PUBLIC' => "公开",
			'access' => "访问",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "控制面板",
			'dashboard:nowidgets' => "控制面板是您网站的门户。点击'编辑页面'添加插件来跟踪信息，以及展示您的丰富生活。",

			'widgets:add' => '添加插件到您的页面',
			'widgets:add:description' => "选择所需的功能,从右边的<b>插件库</b>添加到下方的三个方框里的任何位置。

要删除插件请将其拖动到<b>插件库</b>方框里。",
			'widgets:position:fixed' => '(页面上的固定位置)',
	
			'widgets' => "插件",
			'widget' => "插件",
			'item:object:widget' => "插件",
			'layout:customise' => "自定义布局",
			'widgets:gallery' => "插件库",
			'widgets:leftcolumn' => "左栏",
			'widgets:fixed' => "固定位置",
			'widgets:middlecolumn' => "中栏",
			'widgets:rightcolumn' => "右栏",
			'widgets:profilebox' => "档案框",
			'widgets:panel:save:success' => "您的插件配置信息保存好了。",
			'widgets:panel:save:failure' => "在保存您的插件配置信息的时候遇到了问题，请稍后再试。",
			'widgets:save:success' => "插件配置信息保存好了。",
			'widgets:save:failure' => "我们不能保存您的插件，请再试一次。",
			
	
		/**
		 * Groups
		 */
	
			'group' => "群组", 
			'item:group' => "群组",
	
		/**
		 * Profile
		 */
	
			'profile' => "档案",
			'user' => "用户",
			'item:user' => "用户",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "您的档案",
			'profile:user' => "%s 的档案",
	
			'profile:edit' => "编辑档案",
			'profile:editicon' => "上传档案图片",
			'profile:profilepictureinstructions' => "的档案图片显示在您的个人资料页上。<br /> 您可以随时修改更换。 (支持格式: GIF, JPG 或者 PNG)",
			'profile:icon' => "档案图片",
			'profile:createicon' => "建立您的头像",
			'profile:currentavatar' => "当前头像",
			'profile:createicon:header' => "档案图片",
			'profile:profilepicturecroppingtool' => "档案图片裁减工具",
			'profile:createicon:instructions' => "点击下面并拖动一个方块来匹配剪裁您希望的图片效果。预览您的剪裁图片将出现在右边的方块中。当你满意预览时候，点击'创建您的头像。剪裁图像将被用于整个网站作为您的头像。",
	
			'profile:editdetails' => "编辑详细情况",
			'profile:editicon' => "编辑档案图标",
	
			'profile:aboutme' => "关于", 
			'profile:description' => "关于",
			'profile:briefdescription' => "简介",
			'profile:location' => "地址",
			'profile:skills' => "技能",  
			'profile:interests' => "兴趣", 
			'profile:contactemail' => "联系电子邮件",
			'profile:phone' => "电话",
			'profile:mobile' => "手机",
			'profile:website' => "网站",

			'profile:river:update' => "%s 更新了他的档案",
			'profile:river:iconupdate' => "%s 更新了他的档案图标",
	
		/**
		 * Profile status messages
		 */
	
			'profile:saved' => "您的档案已经保存成功.",
			'profile:icon:uploaded' => "您的档案图片已经成功的上传好了。",
	
		/**
		 * Profile error messages
		 */
	
			'profile:noaccess' => "您无权编辑这个档案。",
			'profile:notfound' => "对不起；我们没有找到制定的档案。",
			'profile:cantedit' => "对不起；您无权编辑这个档案。",
			'profile:icon:notfound' => "对不起；在您上传档案图片的时候发生了一个错误。",
	
		/**
		 * Friends
		 */
	
			'friends' => "好友",
			'friends:yours' => "您的好友",
			'friends:owned' => "%s 的好友",
			'friend:add' => "添加好友",
			'friend:remove' => "删除好友",
	
			'friends:add:successful' => "你已经成功的添加了 %s 作为好友。",
			'friends:add:failure' => "我们没法添加 %s 作为您的好友，请再试一次。",
	
			'friends:remove:successful' => "您成功的将 %s 从您的好友中移除了。",
			'friends:remove:failure' => "我们无法从您的好友中移除 %s 。请再试一次。",
	
			'friends:none' => "这个用户还没有添加任何好友。",
			'friends:none:you' => "您还未添加任何好友！搜索您的兴趣来找一些志同道合的朋友们吧。",
	
			'friends:none:found' => "没找到好友。",
	
			'friends:of:none' => "还没有人加该用户为好友",
			'friends:of:none:you' => "还没有人加你为好友。请输入一些信息到个人档案里让别人可以找到你！",
	
			'friends:of' => "好友属于",
			'friends:of:owned' => "与 %s 交朋友的人们",

			 'friends:num_display' => "好友显示数量",
			 'friends:icon_size' => "图标大小",
			 'friends:tiny' => "很小",
			 'friends:small' => "小",
			 'friends' => "好友",
			 'friends:of' => "好友属于",
			 'friends:collections' => "好友集",
			 'friends:collections:add' => "添加好友集",
			 'friends:addfriends' => "添加好友",
			 'friends:collectionname' => "集合名称",
			 'friends:collectionfriends' => "集内好友",
			 'friends:collectionedit' => "编辑",
			 'friends:nocollections' => "您还没有任何好友集",
			 'friends:collectiondeleted' => "您的好友集已经删除了",
			 'friends:collectiondeletefailed' => "我们无法删除好友集。或您没有权限执行操作。",
			 'friends:collectionadded' => "您的集合建立好了。",
			 'friends:nocollectionname' => "在创建前，您需要给集合命名。",
		
	        'friends:river:created' => "%s 添加了好友插件。",
	        'friends:river:updated' => "%s 更新了好友插件。",
	        'friends:river:delete' => "%s 移除了好友插件。",
	        'friends:river:add' => "%s 添加了某人作为好友。",
	
		/**
		 * Feeds
		 */
			'feed:rss' => '订阅',
			'feed:odd' => '同步数据（OpenDD）',
	
		/**
		 * River
		 */
			'river' => "河流",			
			'river:relationship:friend' => '现在结交了好友',

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "插件 %s 的配置保存好了。",
			'plugins:settings:save:fail' => "插件 %s 的配置保存遇到了问题。",
			'plugins:usersettings:save:ok' => "插件 %s 的用户设定保存好了。",
			'plugins:usersettings:save:fail' => "插件 %s 的用户设定保存遇到了问题。",
			
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "通知设定",
			'notifications:methods' => "请明确哪种方式允许。",
	
			'notifications:usersettings:save:ok' => "您的通知设定成功的保存好了。",
			'notifications:usersettings:save:fail' => "在保存您的通知设定时遇到了问题",
		/**
		 * Search
		 */
	
			'search' => "搜索",
			'searchtitle' => "搜索: %s",
			'users:searchtitle' => "搜索用户: %s",
			'advancedsearchtitle' => "%s 搜索结果中匹配的有 %s",
			'notfound' => "没找到.",
			'next' => "下一页",
			'previous' => "前一页",
	
			'viewtype:change' => "修改列表类型",
			'viewtype:list' => "列表视图",
			'viewtype:gallery' => "相册视图",
	
			'tag:search:startblurb' => "标签匹配的有 '%s':",

			'user:search:startblurb' => "用户匹配 '%s':",
			'user:search:finishblurb' => "点击查看更多。",
	
		/**
		 * Account
		 */
	
			'account' => "帐户",
			'settings' => "设定",
	
			'register' => "注册",
			'registerok' => "您已经成功注册 %s。要激活您的账户需要您到邮箱中点击链接进行确认。",
			'registerbad' => "您的注册遇到了问题。用户名已存在，两次密码不匹配，或者用户名与密码过短。",
			'registerdisabled' => "注册功能已经被管理员关闭了。",
	
			'registration:notemail' => '您的邮箱地址不合法。',
			'registration:userexists' => '用户名存在',
			'registration:usernametooshort' => '用户名至少4位',
			'registration:passwordtooshort' => '密码至少6位',
			'registration:dupeemail' => '该邮箱地址已经被注册过了',
	
			'adduser' => "添加用户",
			'adduser:ok' => "您成功的添加了一名用户。",
			'adduser:bad' => "无法创建新用户",
			
			'item:object:reported_content' => "报告的项目",
	
			'user:set:name' => "帐户名字设定",
			'user:name:label' => "您的名字",
			'user:name:success' => "成功的修改了您在系统中的项目。",
			'user:name:fail' => "无法修改您在系统中的项目。",
	
			'user:set:password' => "密码",
			'user:password:label' => "新密码",
			'user:password2:label' => "新密码(确认)",
			'user:password:success' => "密码修改成功",
			'user:password:fail' => "无法修改您在系统中的密码。",
			'user:password:fail:notsame' => "两次密码不匹配！",
			'user:password:fail:tooshort' => "密码太短！",
	
			'user:set:language' => "语言设定",
			'user:language:label' => "您的语言",
			'user:language:success' => "您的语言设定已经更新！",
			'user:language:fail' => "您的语言设定还未保存。",
	
			'user:username:notfound' => '用户名 %s 未找到。',
	
			'user:password:lost' => '找回密码',
			'user:password:resetreq:success' => '找回密码的邮件已经发送给您了',
			'user:password:resetreq:fail' => '无法请求生成新的密码。',
	
			'user:password:text' => '生成新密码请在下方输入您的用户名。我们将发送地址的一个唯一的验证网页向你点击通过电子邮件中的链接，电子邮件的正文中和一个新的密码将被发送给您。',
	

		/**
		 * Administration
		 */

			'admin:configuration:success' => "您的设定保存成功。",
			'admin:configuration:fail' => "您的设定无法保存。",
	
			'admin' => "管理",
			'admin:description' => "管理员面板允许您控制系统的所有方面，从用户管理到插件的配置。",
			
			'admin:user' => "用户管理",
			'admin:user:description' => "本面板用来控制用户在您网站上的设定。",
			'admin:user:adduser:label' => "添加新用户...",
			'admin:user:opt:linktext' => "配置用户...",
			'admin:user:opt:description' => "配置用户和账户信息",
			
			'admin:site' => "站点管理",
			'admin:site:description' => "本面板允许您控制整站设定。",
			'admin:site:opt:linktext' => "配置网站...",
			'admin:site:opt:description' => "配置技术和非技术的设定。",
			
			'admin:plugins' => "插件管理",
			'admin:plugins:description' => "本面板允许你配置插件设定。",
			'admin:plugins:opt:linktext' => "配置插件...",
			'admin:plugins:opt:description' => "配置网站的插件设定",
			'admin:plugins:label:author' => "作者",
			'admin:plugins:label:copyright' => "版权",
			'admin:plugins:label:licence' => "协议",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "插件 %s 已经被禁用了.",
			'admin:plugins:disable:no' => "插件 %s 禁用失败。",
			'admin:plugins:enable:yes' => "插件 %s 已经被启用了.",
			'admin:plugins:enable:no' => "插件 %s 启用失败。",
	
			'admin:statistics' => "统计",
			'admin:statistics:description' => "这个是您网站统计信息的总览。",
			'admin:statistics:opt:description' => "查看关于您会员和网站内容的统计信息。",
			'admin:statistics:opt:linktext' => "查看统计...",
			'admin:statistics:label:basic' => "基本统计",
			'admin:statistics:label:numentities' => "网站项目",
			'admin:statistics:label:numusers' => "用户数",
			'admin:statistics:label:numonline' => "在线人数",
			'admin:statistics:label:onlineusers' => "当前在线",
			'admin:statistics:label:version' => "版本",
			'admin:statistics:label:version:release' => "发布",
			'admin:statistics:label:version:version' => "版本",
	
			'admin:user:label:search' => "查找用户:",
			'admin:user:label:seachbutton' => "搜索", 
	
			'admin:user:ban:no' => "屏蔽用户失败",
			'admin:user:ban:yes' => "屏蔽用户成功",
			'admin:user:unban:no' => "取消屏蔽用户失败",
			'admin:user:unban:yes' => "取消屏蔽用户成功",
			'admin:user:delete:no' => "删除用户失败",
			'admin:user:delete:yes' => "用户删除成功",
	
			'admin:user:resetpassword:yes' => "密码重设告知用户了。",
			'admin:user:resetpassword:no' => "密码无法重设。",
	
			'admin:user:makeadmin:yes' => "该用户现在是管理员了。",
			'admin:user:makeadmin:no' => "我们无法设置该用户为管理员",
			
		/**
		 * User settings
		 */
			'usersettings:description' => "用户设定面板允许您控制个人信息。",
	
			'usersettings:statistics' => "您的统计",
			'usersettings:statistics:opt:description' => "查看用户和系统的统计信息。",
			'usersettings:statistics:opt:linktext' => "账户统计",
	
			'usersettings:user' => "您的设定",
			'usersettings:user:opt:description' => "控制用户设定。",
			'usersettings:user:opt:linktext' => "修改设定",
	
			'usersettings:plugins' => "插件",
			'usersettings:plugins:opt:description' => "您当前激活的插件配置",
			'usersettings:plugins:opt:linktext' => "配置插件...",
	
			'usersettings:plugins:description' => "本面板让您可以配置设定个人信息。",
			'usersettings:statistics:label:numentities' => "您的条目",
	
			'usersettings:statistics:yourdetails' => "详细信息",
			'usersettings:statistics:label:name' => "全名",
			'usersettings:statistics:label:email' => "邮箱",
			'usersettings:statistics:label:membersince' => "注册自从",
			'usersettings:statistics:label:lastlogin' => "上次登陆",
	
			
	
		/**
		 * Generic action words
		 */
	
			'save' => "保存",
			'cancel' => "取消",
			'saving' => "保存中 ...",
			'update' => "更新",
			'edit' => "编辑",
			'delete' => "删除",
			'load' => "加载",
			'upload' => "上传",
			'ban' => "屏蔽",
			'unban' => "取消屏蔽",
			'enable' => "激活",
			'disable' => "取消激活",
			'request' => "请求",
	
			'invite' => "邀请",
	
			'resetpassword' => "重置密码",
			'makeadmin' => "设为管理员",
	
			'option:yes' => "是",
			'option:no' => "否",
	
			'unknown' => '未知',
	
			'learnmore' => "点击查看更多.",
	
			'content' => "内容",
			'content:latest' => '最近活动',
			'content:latest:blurb' => '或者，点击这里查看网站最新内容。',
	
		/**
		 * Generic data words
		 */
	
			'title' => "标题",
			'description' => "描述",
			'tags' => "标签",
			'spotlight' => "关注",
			'all' => "所有",
	
			'by' => '由',
	
			'annotations' => "注释",
			'relationships' => "关系",
			'metadata' => "元数据",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "您确认删除这个项目?",
			'fileexists' => "一个文件已经上传好了。要替换该文件，选择下方:",
	
		/**
		 * Import / export
		 */
			'importsuccess' => "导入数据成功",
			'importfail' => "OpenDD 导入数据失败。",
	
		/**
		 * Time
		 */
	
			'friendlytime:justnow' => "刚才",
			'friendlytime:minutes' => "%s 分钟前",
			'friendlytime:minutes:singular' => "一分钟前",
			'friendlytime:hours' => "%s 小时前",
			'friendlytime:hours:singular' => "一小时前",
			'friendlytime:days' => "%s 天前",
			'friendlytime:days:singular' => "昨天",
	
		/**
		 * Installation and system settings
		 */
	
			'installation:error:htaccess' => "Elgg requires a file called .htaccess to be set in the root directory of its installation. We tried to create it for you, but Elgg doesn't have permission to write to that directory. 

Creating this is easy. Copy the contents of the textbox below into a text editor and save it as .htaccess

",
			'installation:error:settings' => "Elgg couldn't find its settings file. Most of Elgg's settings will be handled for you, but we need you to supply your database details. To do this:

1. Rename engine/settings.example.php to settings.php in your Elgg installation.

2. Open it with a text editor and enter your MySQL database details. If you don't know these, ask your system administrator or technical support for help.

Alternatively, you can enter your database settings below and we will try and do this for you...",
	
			'installation:error:configuration' => "Once you've corrected any configuration issues, press reload to try again.",
	
			'installation' => "Installation",
			'installation:success' => "Elgg's database was installed successfully.",
			'installation:configuration:success' => "Your initial configuration settings have been saved. Now register your initial user; this will be your first system administrator.",
	
			'installation:settings' => "系统设定",
			'installation:settings:description' => "Now that the Elgg database has been successfully installed, you need to enter a couple of pieces of information to get your site fully up and running. We've tried to guess where we could, but <b>you should check these details.</b>",
	
			'installation:settings:dbwizard:prompt' => "Enter your database settings below and hit save:",
			'installation:settings:dbwizard:label:user' => "Database user",
			'installation:settings:dbwizard:label:pass' => "Database password",
			'installation:settings:dbwizard:label:dbname' => "Elgg database",
			'installation:settings:dbwizard:label:host' => "Database hostname (usually 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Database table prefix (usually 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "We were unable to save the new settings.php. Please save the following file as engine/settings.php using a text editor.",
	
			'installation:sitename' => "网站名称 (例如 \"My social networking site\"):",
			'installation:sitedescription' => "简要介绍 (可选)",
			'installation:wwwroot' => "The site URL, followed by a trailing slash:",
			'installation:path' => "The full path to your site root on your disk, followed by a trailing slash:",
			'installation:dataroot' => "The full path to the directory where uploaded files will be stored, followed by a trailing slash:",
			'installation:dataroot:warning' => "You must create this directory manually. It should sit in a different directory to your Elgg installation.",
			'installation:language' => "The default language for your site:",
			'installation:debug' => "Debug mode provides extra information which can be used to diagnose faults, however it can slow your system down so should only be used if you are having problems:",
			'installation:debug:label' => "Turn on debug mode",
			'installation:usage' => "This option lets Elgg send anonymous usage statistics back to Curverider.",
			'installation:usage:label' => "Send anonymous usage statistics",
			'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "欢迎 %s",
			'welcome_message' => "欢迎使用本系统",
	
		/**
		 * Emails
		 */
			'email:settings' => "邮件设定",
			'email:address:label' => "您的邮箱地址",
			
			'email:save:success' => "新的邮箱地址保存好了，等待验证。",
			'email:save:fail' => "您新的邮箱地址无法保存。",
	
			'email:confirm:success' => "您的邮箱已经验证通过了！",
			'email:confirm:fail' => "您的邮箱地址还未验证...",
	
			'friend:newfriend:subject' => "%s 加您为好友！",
			'friend:newfriend:body' => "%s 加您为好友！

查看他的个人信息，点击:

	%s

请不要回复本邮件",
	
	
			'email:validate:subject' => "%s 请验证您的邮箱地址！",
			'email:validate:body' => "您好 %s,

请点击下方链接来确认您的邮箱地址:

%s
",
			'email:validate:success:subject' => "邮件验证 %s！",
			'email:validate:success:body' => "您好 %s,
			
祝贺，你已经成功的验证了邮箱地址。",
	
	
			'email:resetpassword:subject' => "密码重置！",
			'email:resetpassword:body' => "您好 %s,
			
您的密码已经重置为: %s",
	
	
			'email:resetreq:subject' => "请求新密码",
			'email:resetreq:body' => "您好 %s,
			
有人 (IP地址为 %s) 请求新的密码设置。

如果使您申请的点击下方链接，不然请忽略本邮件

%s
",

	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"输入数据丢失",
	
		/**
		 * Comments
		 */
	
			'comments:count' => "%s 评论",
			'generic_comments:add' => "添加评论",
			'generic_comments:text' => "评论",
			'generic_comment:posted' => "您的评论已经发表了。",
			'generic_comment:deleted' => "您的评论已经删除了。",
			'generic_comment:blank' => "对不起，在发表评论前请输入内容。",
			'generic_comment:notfound' => "对不起我们找不到指定评论。",
			'generic_comment:notdeleted' => "对不起，我们删除不了该评论。",
			'generic_comment:failure' => "在添加评论的时候我们遇到了一个异常，请再试一次。",
	
			'generic_comment:email:subject' => '您的评论已经发布！',
			'generic_comment:email:body' => "您收到一条评论在 \"%s\" 上，来自 %s。内容是:

			
%s


要回复或者查看项目，点击：

	%s

要查看评论者 %s 的个人资料，点击：

	%s

请不要回复本邮件。",
	
		/**
		 * Entities
		 */
			'entity:default:strapline' => '由 %s 创建 %s ',
			'entity:default:missingsupport:popup' => '本项目可能显示不正确。这是因为插件可能不兼容或者需要升级。',
	
			'entity:delete:success' => '实体 %s 已经被删除了',
			'entity:delete:fail' => '实体 %s 删除失败',
	
	
		/**
		 * Action gatekeeper
		 */
			'actiongatekeeper:missingfields' => '表单缺少 __token 或者 __ts 域',
			'actiongatekeeper:tokeninvalid' => '表单验证和服务器的不一致。',
			'actiongatekeeper:timeerror' => '表单过期了，请重新登陆或者再次刷新页面。',
			'actiongatekeeper:pluginprevents' => '插件屏蔽了这次表单的提交。',
	
		/**
		 * Languages according to ISO 639-1
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
			"in" => "Indonesian",
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
			"qu" => "Quechua",
			"rm" => "Rhaeto-Romance",
			"rn" => "Kirundi",
			"ro" => "Romanian",
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
			"y" => "Yiddish",
			"yo" => "Yoruba",
			"za" => "Zuang",
			"zh" => "Chinese",
			"zu" => "Zulu",
	);
	
	add_translation("zh",$chinese);

?>
