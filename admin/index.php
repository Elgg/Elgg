<?php
/**
 * Elgg administration system index
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Get the Elgg framework
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

// Make sure only valid admin users can see this
admin_gatekeeper();
forward('pg/admin/statistics/');