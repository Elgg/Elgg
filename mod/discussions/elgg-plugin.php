<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'discussion',
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'discussion_reply',
			'class' => 'ElggDiscussionReply',
		],
	],
	'actions' => [
		'discussion/save' => [],
		'discussion/delete' => [],
		'discussion/reply/save' => [],
		'discussion/reply/delete' => [],
	],
];
