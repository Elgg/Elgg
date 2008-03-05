<?php

	/**
	 * Elgg index page for web-based applications
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Start the Elgg engine
	 */
		require_once(dirname(__FILE__) . "/engine/start.php");

	/**
	 * Load the front page
	 */
		echo page_draw(null, elgg_view("homepage"));

		get_objects(3,"blog","mammals","are lovely", 7, 2, 1);
		
		$obj = new stdClass;
		$obj->id = 3;
		
		
		
		
		// Testing ///////
	
		// Create / load a site
		$site = get_site(1);
		if (!$site)
		{
			$site = new ElggSite();
			
			$site->title = "Test title";
			$site->description = "Test description";
			$site->url = "http://dushka/~icewing/Workingdirectory/elggnew/";
			$site->owner_id = 1;
			$site->access_id = 0;
			
			error_log("LOADED NEW SITE: Saving");
			
			$site->save();
		}
		else
			$site = new ElggSite($site);
			
		
		

		// annotate site


		// add meta data


		// get site 

		// get site metadata
		// change site metadata

		// get site metadata


		// get site annotations
		
		
?>