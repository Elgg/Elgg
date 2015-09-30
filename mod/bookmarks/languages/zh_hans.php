<?php
return array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "书签",
	'bookmarks:add' => "添加书签",
	'bookmarks:edit' => "编辑书签",
	'bookmarks:owner' => "%s 的书签",
	'bookmarks:friends' => "好友的书签",
	'bookmarks:everyone' => "全站的所有书签",
	'bookmarks:this' => "将本页面添加书签",
	'bookmarks:this:group' => "%s 中的书签",
	'bookmarks:bookmarklet' => "获取书签小工具",
	'bookmarks:bookmarklet:group' => "获取群组书签小工具",
	'bookmarks:inbox' => "书签收件箱",
	'bookmarks:with' => "共享给",
	'bookmarks:new' => "新书签",
	'bookmarks:address' => "书签地址",
	'bookmarks:none' => '没有书签',

	'bookmarks:notify:summary' => '新书签 %s',
	'bookmarks:notify:subject' => '新书签: %s',
	'bookmarks:notify:body' =>
'%s 添加了新书签: %s

地址: %s

%s

查看并评论该书签:
%s
',

	'bookmarks:delete:confirm' => "你确定删除这个资源？",

	'bookmarks:numbertodisplay' => '要显示的书签个数',

	'bookmarks:shared' => "已添加书签",
	'bookmarks:visit' => "访问资源",
	'bookmarks:recent' => "近期的书签",

	'river:create:object:bookmarks' => '%s 将 %s 添加为书签。',
	'river:comment:object:bookmarks' => '%s 评论了书签 %s',
	'bookmarks:river:annotate' => '此书签的一条评论',
	'bookmarks:river:item' => '一个项目',

	'item:object:bookmarks' => '书签',

	'bookmarks:group' => '群组书签',
	'bookmarks:enablebookmarks' => '启用群组书签',
	'bookmarks:nogroup' => '本群组尚未添加任何书签',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "显示你最近的书签",

	'bookmarks:bookmarklet:description' =>
			"书签小工具是你添加到浏览器链接栏的一种特殊按钮，可以让你将在网络上发现的资源保存到你的书签里，也可以与朋友分享。想要设置书签小工具，将下面的按钮拖动到你的浏览器链接栏。",

	'bookmarks:bookmarklet:descriptionie' =>
			"如果你使用IE浏览器，你需要右击书签小工具图标，选择'添加到收藏'，然后选择链接栏。",

	'bookmarks:bookmarklet:description:conclusion' =>
			"然后你可以在任何时候点击该按钮将任何正在访问的网页添加到书签。",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "项目成功添加到书签。",
	'bookmarks:delete:success' => "书签已删除。",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "书签无法保存。请确认正确输入了标题和地址后再尝试。",
	'bookmarks:save:invalid' => "书签的地址无效，无法保存。",
	'bookmarks:delete:failed' => "书签无法删除。请重试。",
	'bookmarks:unknown_bookmark' => '无法找到特定的书签。',
);
