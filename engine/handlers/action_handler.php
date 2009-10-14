<?php

/**
 * Elgg action handler
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 *  Load Elgg framework
 */
define('externalpage',true);
require_once("../start.php");
$action = get_input("action");
action($action);