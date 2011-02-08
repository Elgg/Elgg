<?php
/**
 * Action handler.
 *
 * This file dispatches actions.  It is called via a URL rewrite in .htaccess
 * from http://site/action/.  Anything after 'action/' is considered the action
 * and will be passed to {@link action()}.
 *
 * @warning This sets the input named 'action' to the current action.  When calling
 * an action, get_input('action') will always return the action name.
 *
 * @package Elgg.Core
 * @subpackage Actions
 * @link http://docs.elgg.org/Tutorials/Actions
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

$action = get_input("action");
action($action);
