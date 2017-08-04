<?php

global $CONFIG;

if (!isset($CONFIG)) {
	$CONFIG = new \stdClass;
}

$CONFIG->debug = 'NOTICE';
$CONFIG->security_protect_upgrade = false;