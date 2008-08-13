<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$parent_guid = get_input('parent_guid');
	$container_guid = get_input('container_guid');
	if (!$container_guid) $container_guid = page_owner();
?>
<form action="<?php echo $vars['url']; ?>action/pages/edit" method="post">

<?php

	//var_export($vars['profile']);
	if (is_array($vars['config']->pages) && sizeof($vars['config']->pages) > 0)
		foreach($vars['config']->pages as $shortname => $valtype) {
			
			$disabled = "";
			
			if (($vars['entity']) && ($shortname == 'title'))
			{
				$disabled = true;
			}
?>

	<p>
		<label>
			<?php echo elgg_echo("pages:{$shortname}") ?><br />
			<?php echo elgg_view("input/{$valtype}",array(
															'internalname' => $shortname,
															'value' => $vars['entity']->$shortname,
															'disabled' => $disabled
															)); ?>
		</label>
	</p>

<?php
			
		}

?>
	<p>
		<?php
			if ($vars['entity'])
			{ 
			?><input type="hidden" name="pages_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /><?php 
			}
		?>
		<?php
			if ($container_guid)
			{
				?><input type="hidden" name="container_guid" value="<?php echo $container_guid; ?>" /><?php 
			}
		?>
		<input type="hidden" name="parent_guid" value="<?php if ($vars['entity']) echo $vars['entity']->parent_guid; else echo $parent_guid; ?>" />
		<input type="hidden" name="owner_guid" value="<?php if ($vars['entity']) echo $vars['entity']->owner_guid; else echo page_owner(); ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>