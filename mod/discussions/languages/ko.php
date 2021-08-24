<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "토론 주제",
	
	'add:object:discussion' => '토론 주제 추가',
	'edit:object:discussion' => '주제 수정',
	'collection:object:discussion' => '토론 주제들',
	'collection:object:discussion:group' => '모둠 토론',

	'discussion:latest' => '최근의 토론',
	'discussion:none' => '토론 없슴',
	'discussion:updated' => "%s%s의 마지막 댓글",

	'discussion:topic:created' => '토론 주제가 만들어짐.',
	'discussion:topic:updated' => '토론 주제가 변경됨.',
	'entity:delete:object:discussion:success' => '토론 주제가 삭제됨.',

	'discussion:topic:notfound' => '토론 주제가 없습니다.',
	'discussion:error:notsaved' => '토론 주제를 저장할 수 없습니다.',
	'discussion:error:missing' => '제목과 내용이 모두 필요합니다.',
	'discussion:error:permissions' => '이 작업을 할 권한이 없습니다.',

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s가 새 토론 주제를 추가함 %s',
	'river:object:discussion:comment' => '%s가 토론 주제 %s 에 댓글을 남겼습니다',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => '새 토론 주제 요청됨 %s',
	'discussion:topic:notify:subject' => '새 토론 주제: %s',

	'discussion:comment:notify:summary' => '주제의 새 댓글: %s',
	'discussion:comment:notify:subject' => '주제의 새 댓글: %s',

	'groups:tool:forum' => '모둠 토론 허용',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => '주제 현황',
	'discussion:topic:closed:title' => '이 토론은 종료됨.',
	'discussion:topic:closed:desc' => '이 토론은 종료되었으며, 새 댓글을 받지 않습니다.',

	'discussion:topic:description' => '주제 쪽지',
);
