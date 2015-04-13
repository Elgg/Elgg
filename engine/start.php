<?php

$autoloader = (require_once dirname(__DIR__) . '/autoloader.php');

$app = new \Elgg\Application();

$app->bootCore();

elgg_deprecated_notice('You should load the core using \Elgg\Application::bootCore() instead of including start.php', "2.0.0");
