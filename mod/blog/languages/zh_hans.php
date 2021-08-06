<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => '博客',
	'collection:object:blog' => '博客',
	'collection:object:blog:all' => '所有博客',
	'collection:object:blog:owner' => '%s的博客',
	'collection:object:blog:group' => '圈子博客',
	'collection:object:blog:friends' => '好友的博客',
	'add:object:blog' => '添加博客',
	'edit:object:blog' => '编辑博客',

	'blog:revisions' => '修订',
	'blog:archives' => '归档',

	'groups:tool:blog' => '启用圈子博客',

	// Editing
	'blog:excerpt' => '摘要',
	'blog:body' => '内容',
	'blog:save_status' => '最后保存',

	'blog:revision' => '修订',
	'blog:auto_saved_revision' => '自动保存修订',

	// messages
	'blog:message:saved' => '博客已保存',
	'blog:error:cannot_save' => '无法保存博客',
	'blog:error:cannot_auto_save' => '无法自动保存博客',
	'blog:error:cannot_write_to_container' => '无权限保存圈子博客',
	'blog:messages:warning:draft' => '有未保存的草稿',
	'blog:edit_revision_notice' => '（旧版）',
	'blog:none' => '无博客',
	'blog:error:missing:title' => '请输入博客标题',
	'blog:error:missing:description' => '请输入博客内容',
	'blog:error:post_not_found' => '无法找到指定的博客',
	'blog:error:revision_not_found' => '无法找到此版本',

	// river
	'river:object:blog:create' => '%s 发布了一篇博客 %s',
	'river:object:blog:comment' => '%s 评论了 %s',

	// notifications
	'blog:notify:summary' => '新博客是 %s',
	'blog:notify:subject' => '新博客： %s',

	// widget
	'widgets:blog:name' => '博客',
	'widgets:blog:description' => '显示你最新的博客',
	'blog:moreblogs' => '更多博客',
	'blog:numbertodisplay' => '显示博客的数量',
);
