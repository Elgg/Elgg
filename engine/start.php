<?php

require_once('/vendor/autoload.php');

$app = new \Elgg\Application();

$app->bootCore();

elgg_deprecated_notice('You should load the core using \Elgg\Application::bootCore() instead of including start.php', "1.11.0");
