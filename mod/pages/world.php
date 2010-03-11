<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	global $CONFIG;
	
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		
    if (($page_owner instanceof ElggEntity) && ($page_owner->canWriteToContainer())){
        add_submenu_item(elgg_echo('pages:new'), $CONFIG->url . "pg/pages/new/", 'pagesactions');
        add_submenu_item(elgg_echo('pages:welcome'), $CONFIG->url . "pg/pages/welcome/", 'pagesactions');
    }
    
    if(isloggedin())
    	add_submenu_item(sprintf(elgg_echo("pages:user"), page_owner_entity()->name), $CONFIG->url . "pg/pages/owned/" . page_owner_entity()->username, 'pageslinksgeneral');
    
    add_submenu_item(elgg_echo('pages:all'),$CONFIG->wwwroot."mod/pages/world.php", 'pageslinksgeneral');
    
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	$title = sprintf(elgg_echo("pages:all"),page_owner_entity()->name);
	

	// Get objects
	$context = get_context();
	
	set_context('search');
	
	$objects = elgg_list_entities(array('types' => 'object', 'subtypes' => 'page_top', 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));
	
	set_context($context);
	
	$body = elgg_view_title($title);
	$body .= $objects;
	$body = elgg_view_layout('two_column_left_sidebar','',$body);
	
	// Finally draw the page
	page_draw($title, $body);
?>