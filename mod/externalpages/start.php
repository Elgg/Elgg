<?php
	/**
	 * Elgg Simple editing of external pages frontpage/about/term/contact and privacy
	 * 
	 * @package ElggExPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	function expages_init() {
		
		global $CONFIG;
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('expages','expages_page_handler');
		
		// Register a URL handler for external pages
		register_entity_url_handler('expages_url','object','expages');
		
		// extend views
		elgg_extend_view('footer/links', 'expages/footer_menu');
		elgg_extend_view('index/righthandside', 'expages/front_right');
		elgg_extend_view('index/lefthandside', 'expages/front_left');
		
	}
	
	/**
	 * Page setup. Adds admin controls to the admin panel.
	 *
	 */
	function expages_pagesetup()
	{
		if (get_context() == 'admin' && isadminloggedin()) {
			global $CONFIG;
			add_submenu_item(elgg_echo('expages'), $CONFIG->wwwroot . 'pg/expages/');
		}
	}
	
	function expages_url($expage) {
			
			global $CONFIG;
			return $CONFIG->url . "pg/expages/";
			
	}
	
	
	function expages_page_handler($page) 
	{
		global $CONFIG;
		
		if ($page[0])
		{
			switch ($page[0])
			{
				case "read":		set_input('expages',$page[1]);
										include(dirname(__FILE__) . "/read.php");
										break;
				default : include($CONFIG->pluginspath . "externalpages/index.php"); 
			}
		}
		else
			include($CONFIG->pluginspath . "externalpages/index.php"); 
	}
	
	// Initialise log browser
	register_elgg_event_handler('init','system','expages_init');
	register_elgg_event_handler('pagesetup','system','expages_pagesetup');
	
	// Register actions
		global $CONFIG;
		register_action("expages/add",false,$CONFIG->pluginspath . "externalpages/actions/add.php");
		register_action("expages/addfront",false,$CONFIG->pluginspath . "externalpages/actions/addfront.php");
		register_action("expages/edit",false,$CONFIG->pluginspath . "externalpages/actions/edit.php");
		register_action("expages/delete",false,$CONFIG->pluginspath . "externalpages/actions/delete.php");
			
?>