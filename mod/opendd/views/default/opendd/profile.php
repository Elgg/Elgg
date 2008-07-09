<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
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
		
			<div id="opendd_info_column_left">
				<div id="opendd_icon_wrapper">
				
					<?php
					echo elgg_view(
						"opendd/icon", array(
												'entity' => $vars['entity'],
												//'align' => "left",
												'size' => $iconsize,
											  )
					);

				    // 	display relevant links			
    				echo elgg_view("opendd/links", array("entity" => $vars['entity']));
    				?>
				
				</div>
			</div>
			
		</td>
		
		<td>
			<div id="opendd_info_column_right">
			
					<?php  
						echo "<h2><a href=\"" . $vars['entity']->feedurl. "\">" . $vars['entity']->feedurl . "</a></h2> <br />"; 
						
					?>
						
					<?php
						
						if ($vars['full'] == true) {
							if (is_array($vars['config']->opendd) && sizeof($vars['config']->opendd) > 0)
							{
								
								foreach($vars['config']->opendd as $shortname => $valtype) {
									if ($shortname != "title") {
										$value = $vars['entity']->$shortname;
										
										if (!empty($value)) {
					
											//This function controls the alternating class
                							$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
										}
										
										echo "<p class=\"{$even_odd}\">";
										echo "<b>";
										echo elgg_echo("opendd:{$shortname}");
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
	</tr>
</table>
	
<div id="opendd_info_wide">

	<p class="opendd_info_edit_buttons">
	
<?php
	if ($vars['entity']->canEdit()) 
	{

?>
			
		<a href="<?php echo $vars['url']; ?>mod/opendd/edit.php?feed_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>
		&nbsp; 	
		<a href="<?php echo $vars['url']; ?>action/opendd/feed/delete?feed_guid=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("delete"); ?></a>
<?php
	
	}
	
?>
	
	</p>
</div>
