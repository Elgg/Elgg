<?php
	/**
	 * Elgg groups plugin full profile view.
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	if ($vars['full'] == true) {
		$iconsize = "large";
	} else {
		$iconsize = "medium";
	}
	
?>
	
<table>
	<tr>	
		<td>
			<div id="groups_info_column_left">
			
					<?php
						
						if ($vars['full'] == true) {
							if (is_array($vars['config']->group) && sizeof($vars['config']->group) > 0)
							{
								
								foreach($vars['config']->group as $shortname => $valtype) {
									if ($shortname != "title") {
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
			</div>
		</td>
		<td>
		
			<div id="groups_info_column_right">
				<div id="groups_icon_wrapper">
				
					<?php
					echo elgg_view(
						"groups/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => $iconsize,
											  )
					);
    				?>
				
				</div>
			</div>
			
			<div id="group_admins">
							<?php
							
								echo "<p><b>" . elgg_echo("groups:owner") . "</b></p>";
								echo "<p><a href=\"" . get_user($vars['entity']->owner_guid)->getURL() . "\">" . get_user($vars['entity']->owner_guid)->name . "</a></p>";
								
							?>
			</div>
			
		</td>
	</tr>
</table>
	
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