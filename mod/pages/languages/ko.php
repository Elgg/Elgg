<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => '페이지',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "All site pages",
	'collection:object:page:owner' => "%s's pages",
	'collection:object:page:friends' => "Friends' pages",
	'collection:object:page:group' => "Group pages",
	'add:object:page' => "Add a page",
	'edit:object:page' => "Edit this page",

	'groups:tool:pages' => 'Enable group pages',

	'pages:delete' => " 이 페이지 삭제",
	'pages:history' => "내역",
	'pages:view' => "페이지 보기",
	'pages:revision' => "개정",

	'pages:navigation' => "길잡이",

	'pages:notify:summary' => '%s라는 새페이지',
	'pages:notify:subject' => "새 페이지:%s",
	'pages:notify:body' =>
'%s added a new page: %s

%s

View and comment on the page:
%s',

	'pages:more' => '페이지 더보기',
	'pages:none' => '아직 페이지를 만들지 않았습니다.',

	/**
	* River
	**/

	'river:object:page:create' => '%s created a page %s',
	'river:object:page:update' => '%s updated a page %s',
	'river:object:page:comment' => '%s commented on a page titled %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => '페이지 제목',
	'pages:description' => '다음 문자',
	'pages:tags' => '꼬리표',
	'pages:parent_guid' => '부모 페이지',
	'pages:access_id' => '읽기 접근',
	'pages:write_access_id' => '쓰기 접근',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => '이 페이지를 수정할 수 없습니다.',
	'pages:saved' => '페이지 저장됨',
	'pages:notsaved' => '페이지를 저장할 수 없슴',
	'pages:error:no_title' => '이 페이지의 제목을 입력해야합니다.',
	'entity:delete:object:page:success' => 'The page was successfully deleted.',
	'pages:revision:delete:success' => '페이지 변경점이 성공적올 제거되었습니다.',
	'pages:revision:delete:failure' => '페이지 변경점을 제거할 수 없습니다.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => '%s의 수정본을 %s가 만듬',

	/**
	 * Widget
	 **/

	'pages:num' => '표시할 페이지의 수',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "페이지 보기",
	'pages:label:edit' => "페이지 수정",
	'pages:label:history' => "페이지 변경내역",

	'pages:newchild' => "하위 페이지 만들기",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrate page_top to page entities",
	'pages:upgrade:2017110700:description' => "Changes the subtype of all top pages to 'page' and sets metadata to ensure correct listing.",
	
	'pages:upgrade:2017110701:title' => "Migrate page_top river entries",
	'pages:upgrade:2017110701:description' => "Changes the subtype of all river items for top pages to 'page'.",
);
