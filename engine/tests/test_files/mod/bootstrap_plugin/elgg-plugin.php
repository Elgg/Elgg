<?php
return [
	'plugin' => [
		'name' => 'Bootstrap Plugin',
		'version' => '1.9',
		'activate_on_install' => true,
	],
	'bootstrap' => BootstrapPluginTestBootstrap::class,
];
