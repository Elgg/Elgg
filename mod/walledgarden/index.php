<?php
/**
 * Elgg Walled Garden
 * 
 * @package WalledGarden
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008
 * @link http://elgg.com/
 */

// Get the Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
//grab the login form
$login = elgg_view("account/forms/login");

//temp message
$area1 = "This site is running in walled garden mode. <br />
			Therefore you will need to be invited & logged in to see anything.";

//display the contents in our new canvas layout
$body = elgg_view_layout('one_column_with_sidebar', $area1, $login);

page_draw($title, $body);