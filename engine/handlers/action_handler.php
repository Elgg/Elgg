<?php
/**
 * Action handler.
 *
 * This file dispatches actions.  It is called via a URL rewrite in .htaccess
 * from http://site/action/.  Anything after 'action/' is considered the action
 * and will be passed to {@link action()}.
 *
 * @package Elgg.Core
 * @subpackage Actions
 * @link http://docs.elgg.org/Tutorials/Actions
 */

require_once("../start.php");

$action = get_input("action");
action($action);