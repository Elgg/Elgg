<?php
	/**
	 * Elgg groups items view.
	 * This is the messageboard, members, pages and latest forums posts. Each plugin will extend the views
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */
	 
	 //var_export($vars['entity']);
?>

<div id="group_members">
<h2><?php echo elgg_echo("groups:members"); ?></h2>

<?php

    $members = $vars['entity']->getMembers(10);
    foreach($members as $mem){
           
        echo "<div class=\"member_icon\"><a href=\"".$mem->getURL()."\">" . elgg_view("profile/icon",array('entity' => $mem, 'size' => 'tiny', 'override' => 'true')) . "</a></div>";   
           
    }
    
?>
<div class="clearfloat" /></div>
</div>