<?php

return [
	'plugin' => [
		'name' => 'CKEditor',
		'activate_on_install' => true,
	],
	'views' => [
		'default' => [
			'ckeditor/' => __DIR__ . '/vendors/ckeditor5/build/',
		],
	],
	'view_extensions' => [
		'ckeditor/editor.css' => [
			'ckeditor/elgg_editor.css' => [],
		],
		'elgg.css' => [
			'ckeditor/content.css' => [],
		],
		'email/email.css' => [
			'ckeditor/email_fix.css' => [],
		],
		'input/longtext' => [
			'ckeditor/init' => [],
		],
	],
	'events' => [
		'attributes' => [
			'htmlawed' => [
				'\Elgg\Input\ValidateInputHandler::sanitizeStyles' => ['unregister' => true],
			],
		],
		'config' => [
			'htmlawed' => [
				'Elgg\CKEditor\HTMLawed::changeConfig' => [],
			],
		],
		'elgg.data' => [
			'site' => [
				'\Elgg\CKEditor\Views::setToolbarConfig' => [],
			],
		],
		'to:object' => [
			'entity' => [
				'\Elgg\CKEditor\Views::changeToObjectForLivesearch' => [],
			],
		],
		'usernames' => [
			'mentions' => [
				'\Elgg\CKEditor\Views::extractUsernames' => [],
			],
		],
		'view_vars' => [
			'input/longtext' => [
				'Elgg\CKEditor\Views::setInputLongTextIDViewVar' => [],
			],
			'output/longtext' => [
				'Elgg\CKEditor\Views::setOutputLongTextClass' => [],
				'Elgg\CKEditor\Views::stripEmptyClosingParagraph' => [],
			],
		],
	],
	'routes' => [
		'default:ckeditor:upload' => [
			'path' => '/ckeditor/upload',
			'controller' => \Elgg\CKEditor\Upload::class,
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
];
