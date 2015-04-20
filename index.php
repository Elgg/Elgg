<?php
/**
 * Elgg front controller entry point
 *
 * @package Elgg
 * @subpackage Core
 */

require_once __DIR__ . '/autoloader.php';

$app = new \Elgg\Application();

return $app->run();
