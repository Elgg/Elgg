<?php
/**
 * Elgg profile edit form
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 * 
 * @uses $vars['entity'] The user entity
 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
 */
?>
<form action="<?php echo $vars['url']; ?>action/profile/edit" method="post" id="edit_profile" class="margin_top">
<?php echo elgg_view('input/securitytoken') ?>

	<p><label>
		<?php echo elgg_echo('user:name:label'); ?></label>
		<?php	
			echo elgg_view('input/text',array('internalname' => 'name', 'value' => page_owner_entity()->name));
		?>
	</p>

<?php
	if (is_array($vars['config']->profile) && sizeof($vars['config']->profile) > 0)
		foreach($vars['config']->profile as $shortname => $valtype) {
			if ($metadata = get_metadata_byname($vars['entity']->guid, $shortname)) {
				if (is_array($metadata)) {
					$value = '';
					foreach($metadata as $md) {
						if (!empty($value)) $value .= ', ';
						$value .= $md->value;
						$access_id = $md->access_id;
					}
				} else {
					$value = $metadata->value;
					$access_id = $metadata->access_id;
				}
			} else {
				$value = '';
				$access_id = ACCESS_DEFAULT;
			}

?>

	<p>
		<label>
			<?php echo elgg_echo("profile:{$shortname}") ?><br />
			<?php echo elgg_view("input/{$valtype}",array(
															'internalname' => $shortname,
															'value' => $value,
															)); ?>
		</label>
			<?php echo elgg_view('input/access',array('internalname' => 'accesslevel['.$shortname.']', 'value' => $access_id)); ?>
	</p>

<?php

		}

?>

	<p>
		<input type="hidden" name="username" value="<?php echo page_owner_entity()->username; ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>