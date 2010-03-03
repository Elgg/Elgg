<?php
	/**
	 * Elgg Reported content.
	 * 
	 * @package ElggReportedContent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the Reported content and set up the menus.
	 *
	 */
	function reportedcontent_init()
	{
		global $CONFIG;
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('reportedcontent','reportedcontent_page_handler');
		
		// Extend CSS
		elgg_extend_view('css','reportedcontent/css');
				
		// Extend context menu and owner_block with report content link
		if (isloggedin()) {
		    elgg_extend_view('profile/menu/links','reportedcontent/user_report');
			elgg_extend_view('owner_block/extend', 'reportedcontent/owner_block');
		}
	}
	
	/**
	 * Adding the reported content to the admin menu
	 *
	 */
	function reportedcontent_pagesetup()
	{
		if (get_context() == 'admin' && isadminloggedin()) {
			global $CONFIG;
			add_submenu_item(elgg_echo('reportedcontent'), $CONFIG->wwwroot . 'pg/reportedcontent/');
		}
	}
	
	/**
	 * Reported content page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function reportedcontent_page_handler($page) 
	{
		global $CONFIG;
		
		// only interested in one page for now
		include($CONFIG->pluginspath . "reportedcontent/index.php"); 
	}
	
	
	
	// Initialise Reported Content
	register_elgg_event_handler('init','system','reportedcontent_init');
	register_elgg_event_handler('pagesetup','system','reportedcontent_pagesetup');
	
	//register action
	register_action('reportedcontent/add',false,$CONFIG->pluginspath . "reportedcontent/actions/add.php");
	register_action('reportedcontent/delete',false,$CONFIG->pluginspath . "reportedcontent/actions/delete.php");
	register_action('reportedcontent/archive',false,$CONFIG->pluginspath . "reportedcontent/actions/archive.php");
	
?>