<?php

require_once dirname(__DIR__) . '/autoloader.php';

(new \Elgg\Application())->bootCore();

elgg_deprecated_notice('You should load the core using \Elgg\Application::bootCore() instead of including start.php', "2.0.0");
