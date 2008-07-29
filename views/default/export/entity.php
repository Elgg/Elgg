<?php
	/**
	 * Elgg Entity export.
	 * Displays an entity using the current view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$entity = $vars['entity'];
	if (!$entity) throw new InvalidParameterException(elgg_echo('InvalidParameterException:NoEntityFound'));
	
	$metadata = get_metadata_for_entity($entity->guid);
	$annotations = get_annotations($entity->guid);
	$relationships = get_entity_relationships($entity->guid);
	
?>
<div>
	<?php
		foreach ($entity as $k => $v)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $k; ?>: </b></td>
				<td><?php echo $v; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
</div>

<?php if ($metadata) { ?>
<div id="metadata">
<h2>Metadata</h2>	
	<?php
		foreach ($metadata as $m)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $m->name; ?>: </b></td>
				<td><?php echo $m->value; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
	
</div>
<?php } ?>

<?php if ($annotations) { ?>
<div id="annotations">
<h2>Annotations</h2>	
	<?php
		foreach ($annotations as $a)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $a->name; ?>: </b></td>
				<td><?php echo $a->value; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
</div>
<?php } ?>

<?php if ($relationships) { ?>
<div id="relationship">
<h2>Relationships</h2>	
	<?php
		foreach ($relationships as $r)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $r->relationship; ?>: </b></td>
				<td><?php echo $r->guid_two; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
</div>
<?php } ?>