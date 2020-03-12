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

	'item:object:page' => '페이지',
	'collection:object:page' => '페이지',
	'collection:object:page:all' => "모든 페이지",
	'collection:object:page:owner' => "%s의 페이지",
	'collection:object:page:friends' => "친구의 페이지",
	'collection:object:page:group' => "모둠 페이지",
	'add:object:page' => "페이지 추가",
	'edit:object:page' => "이 페이지 수정",

	'groups:tool:pages' => '모둠 페이지 활성화',
	
	'annotation:delete:page:success' => 'The page revision was successfully deleted',
	'annotation:delete:page:fail' => 'The page revision could not be deleted',

	'pages:delete' => " 이 페이지 삭제",
	'pages:history' => "내역",
	'pages:view' => "페이지 보기",
	'pages:revision' => "개정",

	'pages:navigation' => "길잡이",

	'pages:notify:summary' => '%s라는 새페이지',
	'pages:notify:subject' => "새 페이지:%s",
	'pages:notify:body' =>
'%s 가 새 페이지를 추가함: %s

%s

페이지를 보고 댓글을 추가합니다:
%s',

	'pages:more' => '페이지 더보기',
	'pages:none' => '아직 페이지를 만들지 않았습니다.',

	/**
	* River
	**/

	'river:object:page:create' => '%s 가 페이지 %s 를 만들었습니다.',
	'river:object:page:update' => '%s 가 페이지 %s 를 수정했습니다.',
	'river:object:page:comment' => '%s가 %s 제목의 페이지에 댓글을 남겼습니다.',
	
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
	'entity:delete:object:page:success' => '페이지가 성공적으로 삭제되었습니다.',
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
	'widgets:pages:name' => '페이지',
	'widgets:pages:description' => "이것이 당신의 페이지들의 목록입니다.",

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
