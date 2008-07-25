<?php
	/**
	 * Elgg OpenDD aggregator.
	 * Displays a uuid as a link, but links back to the opendd viewer to display.
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;

	$val = trim($vars['value']);
    if (!empty($val)) {
	    if ((substr_count($val, "http://") == 0) && (substr_count($val, "https://") == 0)) {
	        $val = "http://" . $val;
	    }
	    
	    $vallink = $CONFIG->url . "mod/opendd/viewuuid.php?uuid=" . urlencode($val);
	    
	    echo "<a href=\"{$vallink}\" target=\"_blank\">{$val}</a>";
    }

?>