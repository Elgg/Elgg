<?php
/**
 * Edit/create a group
 */

$entity = elgg_get_array_value('entity', $vars, null);

echo elgg_view('forms/groups/edit', array('entity' => $entity));

if ($entity) {
?>
<div class="delete_group">
	<form action="<?php echo elgg_get_site_url() . "action/groups/delete"; ?>">
		<?php
			echo elgg_view('input/securitytoken');
				$warning = elgg_echo("groups:deletewarning");
			?>
			<input type="hidden" name="group_guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
			<input type="submit" class="elgg-action-button disabled" name="delete" value="<?php echo elgg_echo('groups:delete'); ?>" onclick="javascript:return confirm('<?php echo $warning; ?>')"/><?php
		?>
	</form>
</div>
<?php
}
?>
