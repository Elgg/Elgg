<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'messages',
			'class' => 'ElggMessage',
			'searchable' => false,
		],
	],
	'actions' => [
		'messages/send' => [],
		'messages/delete' => [],
		'messages/process' => [],
	],
];
