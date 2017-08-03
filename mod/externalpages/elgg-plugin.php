<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'about',
			'searchable' => false,
		],
		[
			'type' => 'object',
			'subtype' => 'terms',
			'searchable' => false,
		],
		[
			'type' => 'object',
			'subtype' => 'privacy',
			'searchable' => false,
		],
	],
	'actions' => [
		'expages/edit' => [
			'access' => 'admin',
		],
	],
];
