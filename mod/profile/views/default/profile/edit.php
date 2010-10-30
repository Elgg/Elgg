<?php
/**
 * Elgg profile edit form
 * 
 * @package ElggProfile
 * 
 * @uses $vars['entity'] The user entity
 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
 */
?>
<form action="<?php echo elgg_get_site_url(); ?>action/profile/edit" method="post" id="edit_profile" class="margin_top">
<?php echo elgg_view('input/securitytoken') ?>

	<p><label>
		<?php echo elgg_echo('user:name:label'); ?></label>
		<?php	
			echo elgg_view('input/text',array('internalname' => 'name', 'value' => elgg_get_page_owner()->name));
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

	if ($shortname == 'description') { // change label positioning to allow for additional longtext field controls 
?>
	<p>
		<label>
			<?php echo elgg_echo("profile:{$shortname}") ?></label>
			<?php echo elgg_view("input/{$valtype}",array(
															'internalname' => $shortname,
															'value' => $value,
															)); ?>
		
			<?php echo elgg_view('input/access',array('internalname' => 'accesslevel['.$shortname.']', 'value' => $access_id)); ?>
	</p>
<?php			
	} else {
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

		}

?>

	<p>
		<input type="hidden" name="username" value="<?php echo elgg_get_page_owner()->username; ?>" />
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("save"); ?>" />
	</p>

</form>