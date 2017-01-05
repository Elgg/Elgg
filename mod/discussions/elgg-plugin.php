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
			// We are registering the entity as public facing,
			// but we will use hooks to combine search results
			// for discussions and discussion replies
			'searchable' => true,
		],
	],
	'actions' => [
		'discussion/save' => [],
		'discussion/delete' => [],
		'discussion/reply/save' => [],
		'discussion/reply/delete' => [],
	],
];
