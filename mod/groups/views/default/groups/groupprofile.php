<?php
	/**
	 * Elgg groups plugin full profile view.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	if ($vars['full'] == true) {
		$iconsize = "large";
	} else {
		$iconsize = "medium";
	}
	
?>

<div id="groups_info_column_right"><!-- start of groups_info_column_right -->
    <div id="groups_icon_wrapper"><!-- start of groups_icon_wrapper -->
				
        <?php
		    echo elgg_view(
					"groups/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => $iconsize,
											  )
					);
        ?>
				
    </div><!-- end of groups_icon_wrapper -->
	<div id="group_stats"><!-- start of group_stats -->
	    <?php
							
		    echo "<p><b>" . elgg_echo("groups:owner") . ": </b><a href=\"" . get_user($vars['entity']->owner_guid)->getURL() . "\">" . get_user($vars['entity']->owner_guid)->name . "</a></p>";
								
	    ?>
	    <p><?php echo elgg_echo('groups:members') . ": " . get_entities_from_relationship('member', $vars['entity']->guid, true, 'user', '', 0, '', 9999, 0, true); ?></p>
    </div><!-- end of group_stats -->
</div><!-- end of groups_info_column_right -->

<div id="groups_info_column_left"><!-- start of groups_info_column_left --> 
    <?php
        if ($vars['full'] == true) {
	        if (is_array($vars['config']->group) && sizeof($vars['config']->group) > 0){
								
		        foreach($vars['config']->group as $shortname => $valtype) {
			        if ($shortname != "name") {
				        $value = $vars['entity']->$shortname;
										
					    if (!empty($value)) {
					        //This function controls the alternating class
                		    $even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
					    }
										
					    echo "<p class=\"{$even_odd}\">";
						echo "<b>";
						echo elgg_echo("groups:{$shortname}");
						echo ": </b>";
										
						echo elgg_view("output/{$valtype}",array('value' => $vars['entity']->$shortname));
										
						echo "</p>";
				    }
				}
		    }
		}
	?>
</div><!-- end of groups_info_column_left -->

<div id="groups_info_wide">

	<p class="groups_info_edit_buttons">
	
<?php
	if ($vars['entity']->canEdit()) 
	{

?>
			
		<a href="<?php echo $vars['url']; ?>mod/groups/edit.php?group_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>
		
			
<?php
	
	}
	
?>
	
	</p>
</div>
<div class="clearfloat"></div>