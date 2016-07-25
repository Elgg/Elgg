<?php
/**
 * Default entity delete action
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_deprecated_notice('entities/delete action file has been deprecated. Use entity/delete action instead', '2.1');

return require dirname(dirname(__FILE__)) . '/entity/delete.php';