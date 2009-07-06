<?php

	/**
	 * Elgg user display (details)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
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
												'override' => true,
											  )
					);


    echo "</div>";
    echo "<div class=\"clearfloat\"></div>";
     // display relevant links			
    echo elgg_view("profile/profilelinks", array("entity" => $vars['entity']));
       
    // close profile_info_column_left
    echo "</div>";

?>
</td>
<td>
	
	<div id="profile_info_column_middle" >
			<?php
	
		if ($vars['entity']->canEdit()) {

	?>
		<p class="profile_info_edit_buttons">
			<a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/"><?php echo elgg_echo("profile:edit"); ?></a>
		</p>
	<?php

		}
		
	?>
	
	
	
	<?php 
	
	// Simple XFN
	$rel = "";
	if (page_owner() == $vars['entity']->guid)
		$rel = 'me';
	else if (check_entity_relationship(page_owner(), 'friend', $vars['entity']->guid))
		$rel = 'friend';
		
	// display the users name
	echo "<h2><a href=\"" . $vars['entity']->getUrl() . "\" rel=\"$rel\">" . $vars['entity']->name . "</a></h2>";

	//insert a view that can be extended
	echo elgg_view("profile/status", array("entity" => $vars['entity']));
	
		if ($vars['full'] == true) {
	
	?>
	<?php
		$even_odd = null;
		
		if (is_array($vars['config']->profile) && sizeof($vars['config']->profile) > 0)
			foreach($vars['config']->profile as $shortname => $valtype) {
				if ($shortname != "description") {
					$value = $vars['entity']->$shortname;
					if (!empty($value)) {
					
				//This function controls the alternating class
                $even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';					
	

	?>
	<p class="<?php echo $even_odd; ?>">
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
</tr>
<?php if (!get_plugin_setting('user_defined_fields', 'profile')) {?>
<tr>
<td colspan="2">
	<div id="profile_info_column_right">	
	<p class="profile_aboutme_title"><b><?php echo elgg_echo("profile:aboutme"); ?></b></p>
	
	<?php if ($vars['entity']->isBanned()) { ?>
		<div id="profile_banned">	
		<?php 
		    echo elgg_echo('profile:banned'); 
		?>
		</div><!-- /#profile_info_column_right -->
	
	<?php } else { ?>
	
		<?php 
		echo elgg_view('output/longtext', array('value' => $vars['entity']->description));
		//echo autop(filter_tags($vars['entity']->description)); 
		?>
	
	<?php } ?>
	
	</div><!-- /#profile_info_column_right -->

</td>



</tr>
<?php } ?>

</table>



</div><!-- /#profile_info -->
