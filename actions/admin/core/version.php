<?php

$version = new Elgg_Version();
$latestRelease = $version->getLatestRelease(true);

if ($latestRelease !== false) {
	system_message(elgg_echo('admin:version:action:ok', array($latestRelease)));
} else {
	register_error(elgg_echo('admin:version:action:fail'));
}
