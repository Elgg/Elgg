<?php

	/**
	 * Elgg media embed plugin
	 * 
	 * @package ElggEmbed
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	/**
	 * Init function
	 *
	 */
		function embed_init() {
			
			// Extend useful views with stuff we need for our embed modal
				elgg_extend_view('css','embed/css');
				elgg_extend_view('js/initialise_elgg','embed/js');
				elgg_extend_view('metatags','embed/metatags');
				elgg_extend_view('input/longtext','embed/link',10);
				
			// Page handler for the modal media embed
				register_page_handler('embed','embed_page_handler');
			
		}
		
	/**
	 * Runs the 'embed' script
	 *
	 */
		function embed_page_handler($page) {
			
			switch($page[0]) {
				case 'upload':		require_once(dirname(__FILE__) . '/upload.php');
									exit;
									break;
				default:			require_once(dirname(__FILE__) . '/embed.php');
									exit;
									break;			
			}
			
		}

	// Register the init action
		register_elgg_event_handler('init','system','embed_init',10);

?>
