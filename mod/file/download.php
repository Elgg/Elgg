<?php
/**
 * Elgg file download.
 * 
 * @package ElggFile
 */
require_once __DIR__ . '/../../vendor/autoload.php';

\Elgg\Application::start();

// Get the guid
$file_guid = get_input("file_guid");

forward("file/download/$file_guid");
