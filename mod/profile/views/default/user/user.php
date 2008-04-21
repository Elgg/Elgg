<?php

	/**
	 * Elgg user display
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

?>

	<h2><a href="<?php echo $vars['entity']->getUrl(); ?>"><?php echo $vars['entity']->name; ?></a></h2>
	<p>
		*** USER ICON TO COME ***
	</p>
	<?php 

		if ($vars['full'] == true) {
	
	?>
	<p><b><?php echo elgg_echo("profile:aboutme"); ?></b></p>
	<p><?php echo nl2br($vars['entity']->description); ?></p>
	<?php

		}
	
	?>
	<p>
		<b><?php
		
			echo elgg_echo("profile:location");
		
		?>: </b>
		<?php

			echo elgg_view('output/tags',array('tags' => $vars['entity']->location));
		
		?>
	</p>
	<p>
		<b><?php
		
			echo elgg_echo("profile:skills");
		
		?>: </b>
		<?php

			echo elgg_view('output/tags',array('tags' => $vars['entity']->skills));
		
		?>
	</p>
	<p>
		<b><?php
		
			echo elgg_echo("profile:interests");
		
		?>: </b>
		<?php

			echo elgg_view('output/tags',array('tags' => $vars['entity']->interests));
		
		?>
	</p>
	<?php

		if ($vars['entity']->canEdit()) {
	
	?>
	<p>
		<a href="<?php echo $vars['url']; ?>mod/profile/edit.php"><?php echo elgg_echo("edit"); ?></a>
	</p>
	<?php

		}
	
	?>