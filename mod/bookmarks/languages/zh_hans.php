<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'item:object:bookmarks' => '书签',
	'collection:object:bookmarks' => '书签',
	'collection:object:bookmarks:group' => '圈子书签',
	'collection:object:bookmarks:all' => "全部书签",
	'collection:object:bookmarks:owner' => "%s 的书签",
	'collection:object:bookmarks:friends' => "好友的书签",
	'add:object:bookmarks' => "添加书签",
	'edit:object:bookmarks' => "编辑书签",

	'bookmarks:this' => "添加此页为书签",
	'bookmarks:this:group' => "%s 的书签",
	'bookmarks:bookmarklet' => "添加书签小工具",
	'bookmarks:bookmarklet:group' => "添加圈子书签小工具",
	'bookmarks:address' => "书签地址",
	'bookmarks:none' => '无书签',

	'bookmarks:notify:summary' => '新的书签是 %s',
	'bookmarks:notify:subject' => '新的书签：%s',

	'bookmarks:numbertodisplay' => '显示的书签数量',

	'river:object:bookmarks:create' => '%s 添加了书签 %s',
	'river:object:bookmarks:comment' => '%s 评论了 %s',

	'groups:tool:bookmarks' => '启用圈子书签',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => '书签',
	'widgets:bookmarks:description' => "显示你的最新书签",

	'bookmarks:bookmarklet:description' => "书签小工具是一种在浏览器的地址栏旁特殊的按钮。它允许你在任何网页添加书签。启用它，只需要把按钮拖到浏览器的地址栏旁。",
	'bookmarks:bookmarklet:descriptionie' => "如果你正在使用Internet Explorer（IE浏览器），你需要右键书签小工具按钮，点击添加到收藏栏。",
	'bookmarks:bookmarklet:description:conclusion' => "你可以在任何时候点击按钮添加当前网页到书签。",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "成功添加书签",
	'entity:delete:object:bookmarks:success' => "此书签已被删除",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "书签无法被保存，确保你输入了标题与地址。",
);
