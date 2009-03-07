<?php

	/**
	 * Elgg CSS file
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
	
		/*

	    require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
	    
	    $default_css = elgg_view("css");
	    
	    header("Content-type: text/css", true);
		header('Expires: ' . date('r',time() + 864000), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
	    header("Content-Length: " . strlen($default_css));
	       
	    echo $default_css;
		*/

		define('externalpage',true);

		global $viewinput, $override;
		$viewinput['view'] = 'css';
		$viewinput['viewtype'] = $_GET['viewtype'];

		//$override = true;
		
		header("Content-type: text/css", true);
		header('Expires: ' . date('r',time() + 86400000), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
	    // header("Content-Length: " . strlen($default_css));
		require_once(dirname(dirname(__FILE__)) . '/simplecache/view.php');
    
?>