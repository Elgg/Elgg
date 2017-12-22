<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'discussion',
			'class' => 'ElggDiscussion',
			'searchable' => true,
		],
	],
	'actions' => [
		'discussion/save' => [],
		'discussion/delete' => [],
	],
	'upgrades' => [
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReply',
		'\Elgg\Discussions\Upgrades\MigrateDiscussionReplyRiver',
	],
];
