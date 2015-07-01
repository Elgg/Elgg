<?php

require_once __DIR__ . '/../../../autoload.php';

\Elgg\Application::start();

elgg_deprecated_notice('You should load the core using \Elgg\Application::start() instead of including start.php', "2.0.0");
