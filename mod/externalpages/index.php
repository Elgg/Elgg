<?php
/**
 * Elgg External pages
 * 
 * @package ElggExpages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();
set_context('admin');
//the type of page e.g about, terms, privacy, etc
$type = get_input('type', 'about');

// Set admin user for user block
set_page_owner($_SESSION['guid']);

//display the title
$title = elgg_view_title(elgg_echo('expages'));

// Display the correct form
$edit = elgg_view('expages/forms/edit', array('type' => $type));
	
// Display the menu
$body = elgg_view('page_elements/elgg_content',array('body' => elgg_view('expages/menu', array('type' => $type)).$edit));
	
// Display
page_draw(elgg_echo('expages'),elgg_view_layout("one_column_with_sidebar", $title . $body));
?>