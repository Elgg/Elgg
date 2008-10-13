<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
	/**
	 *  Chinese Language Package
	 * 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @translator Cosmo Mao
	 * @copyright cOSmoCommerce.com 2008
	 * @link http://www.elggsns.cn/
	 * @version 0.1
	 */
	$chinese = array(
	
		/**
		 * Menu items and titles
		 */
			
			'groups' => "群组",
			'groups:owned' => "您建立的群组",
			'groups:yours' => "您加入的群组",
			'groups:user' => "%s 的群组",
			'groups:all' => "整站群组",
			'groups:new' => "创建群组",
			'groups:edit' => "编辑群组",
	
			'groups:icon' => '群组图标',
			'groups:name' => '群组名字',
			'groups:username' => '群组简称 (在URL中显示,只能输入英文字母)',
			'groups:description' => '详细描述',
			'groups:briefdescription' => '简介',
			'groups:interests' => '兴趣',
			'groups:website' => '网站',
			'groups:members' => '群组成员',
			'groups:membership' => "成员",
			'groups:access' => "访问权限",
			'groups:owner' => "所有者",
	        'groups:widget:num_display' => '显示多少个群组',
	        'groups:widget:membership' => '群组成员',
	        'groups:widgets:description' => '显示您参与的群组在您的档案里',
			'groups:noaccess' => '无法访问群组',
			'groups:cantedit' => '无法编辑群组',
			'groups:saved' => '群组保存了',
	
			'groups:joinrequest' => '请求加入',
			'groups:join' => '加入群组',
			'groups:leave' => '离开群组',
			'groups:invite' => '邀请好友',
			'groups:inviteto' => "邀请好友到 '%s'",
			'groups:nofriends' => "您的好友都加入这个群组了",
	
			'groups:group' => "群组",
			
			'item:object:groupforumtopic' => "论坛主题",
	
			/*
			  Group forum strings
			*/
			
			'groups:forum' => '群组论坛',
			'groups:addtopic' => '添加主题',
			'groups:forumlatest' => '最新主题',
			'groups:latestdiscussion' => '最新话题',
			'groupspost:success' => '您的评论已经发布',
			'groups:alldiscussion' => '最新话题',
			'groups:edittopic' => '编辑话题',
			'groups:topicmessage' => '话题消息',
			'groups:topicstatus' => '话题状态',
			'groups:reply' => '发布评论',
			'groups:topic' => '主题',
			'groups:posts' => '回复',
			'groups:lastperson' => '最后回复者',
			'groups:when' => '时间',
			'grouptopic:notcreated' => '没有主题创建。',
			'groups:topicopen' => '开放',
			'groups:topicclosed' => '关闭',
			'groups:topicresolved' => '解决',
			'grouptopic:created' => '您的话题已经创建了。',
			'groupstopic:deleted' => '这个话题已经删除了。',
			'groups:topicsticky' => '置顶',
			'groups:topicisclosed' => '话题已经关闭。',
			'groups:topiccloseddesc' => '话题已经关闭并且不再接受新的评论了。',
			
	
			'groups:privategroup' => '这个群组是个人的，需要申请才能加入。',
			'groups:notitle' => '群组必须有标题',
			'groups:cantjoin' => '无法加入',
			'groups:cantleave' => '无法离开',
			'groups:addedtogroup' => '成功添加了用户到群组里',
			'groups:joinrequestnotmade' => '可以申请加入',
			'groups:joinrequestmade' => '申请已经发出',
			'groups:joined' => '成功加入了群组！',
			'groups:left' => '成功离开了群组',
			'groups:notowner' => '抱歉您不是群组的所有者。',
			'groups:alreadymember' => '您已经是该群组的成员!',
			'groups:userinvited' => '邀请已经发出。',
			'groups:usernotinvited' => '用户还未被邀请',
	
			'groups:invite:subject' => "%s 您已经被邀请加入群组 %s!",
			'groups:invite:body' => " %s 您好,

您已经被邀请加入 '%s' 群组, 点击下方确认:

%s",

			'groups:welcome:subject' => "欢迎来到 %s 群组!",
			'groups:welcome:body' => " %s 您好!
		
您已经是 '%s' 群组的成员! 点击下方开始发帖!

%s",
	
			'groups:request:subject' => "%s 请求加入群组 %s",
			'groups:request:body' => " %s 您好,

%s 请求加入 '%s' 群组, 点击下方查看他们的信息:

%s

或者您可以点击下方确认请求:

%s",
	
			'groups:river:member' => '现在加入了',
	
			'groups:nowidgets' => '该群组没有被构件定义过。',
	
	
			'groups:widgets:members:title' => '群组成员',
			'groups:widgets:members:description' => '列出群组的成员。',
			'groups:widgets:members:label:displaynum' => '列出群组的成员。',
			'groups:widgets:members:label:pleaseedit' => '请配置该构件。',
	
			'groups:widgets:entities:title' => "群组对象",
			'groups:widgets:entities:description' => "列出群组的对象。",
			'groups:widgets:entities:label:displaynum' => '列出群组的对象。',
			'groups:widgets:entities:label:pleaseedit' => '请配置该构件。',
		
	);
					
	add_translation("zh",$chinese);
?>