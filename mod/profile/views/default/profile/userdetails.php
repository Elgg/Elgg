<?php

	/**
	 * Elgg user display (details)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

	if ($vars['full'] == true) {
		$iconsize = "large";
	} else {
		$iconsize = "medium";
	}
	
	// wrap all profile info
	echo "<div id=\"profile_info\">";

?>

<table cellspacing="0">
<tr>
<td>

<?php	
	
	// wrap the icon and links in a div
	echo "<div id=\"profile_info_column_left\">";
	
	echo "<div id=\"profile_icon_wrapper\">";
	// get the user's main profile picture
	echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => $iconsize,
											  )
					);

    // display relevant links			
    echo elgg_view("profile/profilelinks", array("entity" => $vars['entity']));
    echo "</div>";
    
    
    // close the icon and links div
    echo "</div>";

?>
<!-- /#profile_info_column_left -->
</td>
<td>
	
	<div id="profile_info_column_middle" >
	<?php 
	
	// display the users name
	echo "<h2><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a></h2>";

		if ($vars['full'] == true) {
	
	?>
	<?php

		if (is_array($vars['config']->profile) && sizeof($vars['config']->profile) > 0)
			foreach($vars['config']->profile as $shortname => $valtype) {
				if ($shortname != "description") {
					$value = $vars['entity']->$shortname;
					if (!empty($value)) {
					
				//This function controls the alternating class
                $even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';					
	

	echo "<p class=\"{$even_odd}\">";
	?>
		<b><?php

			echo elgg_echo("profile:{$shortname}");
		
		?>: </b>
		<?php

			echo elgg_view("output/{$valtype}",array('value' => $vars['entity']->$shortname));
		
		?>
		
	</p>

	<?php
					}
				}
			}
			
		}
	
	?>
	</div><!-- /#profile_info_column_middle -->


</td>
<td>
	<div id="profile_info_column_right">
		<?php
	
		if ($vars['entity']->canEdit()) {

	?>
		<p class="profile_info_edit_buttons">
			<a href="<?php echo $vars['url']; ?>mod/profile/edit.php"><?php echo elgg_echo("profile:editdetails"); ?></a>
		</p>
	<?php

		}
		
	?>
	
	
	<p><b><?php echo elgg_echo("profile:aboutme"); ?></b><br /><?php echo autop($vars['entity']->description); ?></p>
	</div><!-- /#profile_info_column_right -->
</td>
</tr>
</table>



</div><!-- /#profile_info -->
