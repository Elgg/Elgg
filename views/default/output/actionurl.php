<?php

	/**
	 * Elgg Action URL display
	 * This is identical to the output/url view except that it also adds action gatekeeper tokens, making
	 * it suitable for calling actions.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The URL to display
	 * 
	 */

    $val = trim($vars['value']);
    if (!empty($val)) {

    	// Generate token 
    	$ts = time();
		$token = generate_action_token($ts);
    	
    	$sep = "?";
		if (strpos($val, '?')>0) $sep = "&";
		$val = "$val{$sep}__elgg_token=$token&__elgg_ts=$ts";
		
		echo elgg_view('output/url', array('value' => $val));
    }

?>