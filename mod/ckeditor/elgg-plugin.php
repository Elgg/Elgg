<?php

return [
	'plugin' => [
		'name' => 'CKEditor',
		'activate_on_install' => true,
	],
	'bootstrap' => Elgg\CKEditor\Bootstrap::class,
	'views' => [
		'default' => [
			'ckeditor/' => 'vendor/ckeditor/ckeditor/',
			'jquery.ckeditor.js' => 'vendor/ckeditor/ckeditor/adapters/jquery.js',
		],
	],
	'view_extensions' => [
		'admin.css' => [
			'ckeditor.css' => [],
		],
		'elgg.css' => [
			'ckeditor.css' => [],
		],
		'elgg/wysiwyg.css' => [
			'elements/reset.css' => ['priority' => 100],
			'elements/typography.css' => ['priority' => 100],
		],
		'input/longtext' => [
			'ckeditor/init' => [],
		],
	],
	'hooks' => [
		'register' => [
			'menu:longtext' => [
				'Elgg\CKEditor\Menus\LongText::registerToggler' => [],
			],
		],
		'view_vars' => [
			'input/longtext' => [
				'Elgg\CKEditor\Views::setInputLongTextIDViewVar' => [],
			],
		],
	],
];
