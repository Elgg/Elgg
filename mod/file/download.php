<?php
/**
 * Elgg file download.
 * 
 * @package ElggFile
 */
require_once dirname(dirname(__DIR__)) . '/autoloader.php';
(new Elgg\Application())->bootCore();

// Get the guid
$file_guid = get_input("file_guid");

forward("file/download/$file_guid");
