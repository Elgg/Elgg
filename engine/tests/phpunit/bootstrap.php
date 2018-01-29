<?php

if (!date_default_timezone_get()) {
	date_default_timezone_set('America/Los_Angeles');
}

error_reporting(E_ALL | E_STRICT);

\Elgg\Application::loadCore();