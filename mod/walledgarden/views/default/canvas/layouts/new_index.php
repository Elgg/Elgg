<?php

	/**
	 * Elgg basic frontpage for the walled garden
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
?>

<div id="custom_index">

    <!-- left column content -->
    <div id="index_left">
    		<?php
	            //this displays some content when the user is logged out
			    if (!isloggedin()){
	            	//display the login form
			    	echo $vars['area1'];
			    	echo "<div class=\"clearfloat\"></div>";
			    }
			?>
    </div>
    
     <div class="clearfloat"></div>
</div>