<?php
$engine = dirname(dirname(dirname(dirname(__FILE__)))) . '/engine';

// Set up class auto-loading
require_once "$engine/lib/autoloader.php";

// error messages require elgg_echo()
require_once "$engine/lib/languages.php";

// languages library requires sanitise_filepath() and getting logged in user
require_once "$engine/lib/elgglib.php";
require_once "$engine/lib/sessions.php";
_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));

elgg_register_classes(dirname(dirname(__FILE__)) . '/classes/');
