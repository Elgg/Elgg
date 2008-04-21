<?php

	/**
	 * Elgg profile edit form
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

<form action="<?php echo $vars['url']; ?>action/profile/edit" method="post">

	<p>
		<label>
			<?php echo elgg_echo("profile:aboutme"); ?><br />
			<?php echo elgg_view("input/longtext",array(
															'internalname' => 'aboutme',
															'value' => $vars['entity']->description,
														)); ?>
		</label>
	</p>
	<p>
		<label>
			<?php echo elgg_echo("profile:location"); ?><br />
			<?php echo elgg_view("input/tags",array(
															'internalname' => 'location',
															'value' => $vars['entity']->location,
														)); ?>
		</label>
	</p>
	<p>
		<label>
			<?php echo elgg_echo("profile:skills"); ?><br />
			<?php echo elgg_view("input/tags",array(
															'internalname' => 'skills',
															'value' => $vars['entity']->skills,
														)); ?>
		</label>
	</p>
	<p>
		<label>
			<?php echo elgg_echo("profile:interests"); ?><br />
			<?php echo elgg_view("input/tags",array(
															'internalname' => 'interests',
															'value' => $vars['entity']->interests,
														)); ?>
		</label>
	</p>
	<p>
		<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>