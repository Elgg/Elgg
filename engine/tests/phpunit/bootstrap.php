<?php

if (!date_default_timezone_get()) {
	date_default_timezone_set('America/Los_Angeles');
}

error_reporting(E_ALL);

\Elgg\Application::loadCore();
