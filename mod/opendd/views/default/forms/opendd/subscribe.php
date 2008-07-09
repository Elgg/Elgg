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
?>
<form action="<?php echo $vars['url']; ?>action/opendd/feed/subscribe" method="post">
<?php
	if (is_array($vars['config']->opendd) && sizeof($vars['config']->opendd) > 0)
		foreach($vars['config']->opendd as $shortname => $valtype) {
			
?>

	<p>
		<label>
			<?php echo elgg_echo("opendd:{$shortname}") ?><br />
			<?php echo elgg_view("input/{$valtype}",array(
															'internalname' => $shortname,
															'value' => $vars['entity']->$shortname,
															)); ?>
		</label>
	</p>

<?php
			
		}

?>
	<?php if ($vars['entity']) { ?><input type="hidden" name="feed_guid" value="<?php echo $vars['entity']->guid; ?>" /><?php } ?>
	<input type="hidden" name="user_guid" value="<?php echo page_owner_entity()->guid; ?>" />
	<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
</form>