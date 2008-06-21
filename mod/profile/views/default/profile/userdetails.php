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
		$iconsize = "medium";
	} else {
		$iconsize = "small";
	}
	
	// display the users name
	echo "<h2><a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a></h2>";
	
	// get the user's main profile picture
	echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												'align' => "left",
												'size' => $iconsize,
											  )
					);

?>

	<div style="margin:0 0 0 210px; width:300px;" >

	<?php 

		if ($vars['full'] == true) {
	
	?>
	<p><b><?php echo elgg_echo("profile:aboutme"); ?></b><br /><?php echo nl2br($vars['entity']->description); ?></p>
	<?php

		if (is_array($vars['config']->profile) && sizeof($vars['config']->profile) > 0)
			foreach($vars['config']->profile as $shortname => $valtype) {
				if ($shortname != "description") {
					$value = $vars['entity']->$shortname;
					if (!empty($value)) {
					
	?>

	<p>
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
	
		if ($vars['entity']->canEdit()) {
	
	?>
	<p>
		<a href="<?php echo $vars['url']; ?>mod/profile/edit.php"><?php echo elgg_echo("edit"); ?></a>
	</p>
	<?php

		
			// TODO: Add admin console options here
			if (isadminloggedin())
			{
				if ($_SESSION['id']!=$vars['entity']->guid)
				{
?>				
				<p>
					<a href="<?php echo $vars['url']; ?>actions/admin/user/ban?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("ban"); ?></a>
				</p>		
				<p>
					<a href="<?php echo $vars['url']; ?>actions/admin/user/delete?guid=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("delete"); ?></a>
				</p>	
<?php 
				}
			}
		}
	
	?>
	
	</div>