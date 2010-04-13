<?php

/**
 * Elgg categories plugin settings page
 *
 * @package ElggCategories
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

// Load engine and restrict to admins 
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');
admin_gatekeeper();

// Set context
set_context('admin');

// Get site and categories
global $CONFIG;
$site = $CONFIG->site;
$categories = $site->categories;

if (empty($categories)) {
	$categories = array();
}

// Load category save view
$body = elgg_view('categories/settings',array('categories' => $categories));


$body = elgg_view_layout('two_column_left_sidebar', '', $body);

page_draw(elgg_echo('categories:settings'), $body);
