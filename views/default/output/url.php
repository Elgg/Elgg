<?php

	/**
	 * Elgg URL display
	 * Displays a URL as a link
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The URL to display
	 * 
	 */

    $val = trim($vars['value']);
    if (!empty($val)) {
	    if ((substr_count($val, "http://") == 0) && (substr_count($val, "https://") == 0)) {
	        $val = "http://" . $val;
	    }
	    
	    if ($vars['is_action'])
		{
			$ts = time();
			$token = generate_action_token($ts);
	    	
	    	$sep = "?";
			if (strpos($val, '?')>0) $sep = "&";
			$val = "$val{$sep}__elgg_token=$token&__elgg_ts=$ts";
		}
	    
	    echo "<a href=\"{$val}\" target=\"_blank\">". htmlentities($val, ENT_QUOTES, 'UTF-8'). "</a>";
    }

?>