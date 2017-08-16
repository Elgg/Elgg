<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'messages',
			'searchable' => false,
		],
	],
	'actions' => [
		'messages/send' => [],
		'messages/delete' => [],
		'messages/process' => [],
	],
];
