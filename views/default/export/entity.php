<?php
/**
 * Elgg Entity export.
 * Displays an entity using the current view.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$entity = $vars['entity'];
if (!$entity) {
	throw new InvalidParameterException(elgg_echo('InvalidParameterException:NoEntityFound'));
}

$metadata = get_metadata_for_entity($entity->guid);
$annotations = get_annotations($entity->guid);
$relationships = get_entity_relationships($entity->guid);

$exportable_values = $entity->getExportableValues();
?>
<div class="contentWrapper">
<div>
	<?php
		foreach ($entity as $k => $v)
		{
			if ((in_array($k, $exportable_values)) || (isadminloggedin())) {
?>
		<div>
			<p><b><?php echo $k; ?>: </b><?php echo $v; ?></p>
		</div>
<?php
			}
		}
	?>
</div>

<?php if ($metadata) { ?>
<div id="metadata">
<h2><?php echo elgg_echo('metadata'); ?></h2>
	<?php
		foreach ($metadata as $m)
		{
?>
		<div>
			<p><b><?php echo $m->name; ?>: </b><?php echo $m->value; ?></p>
		</div>
<?php
		}
	?>

</div>
<?php } ?>

<?php if ($annotations) { ?>
<div id="annotations">
<h2><?php echo elgg_echo('annotations'); ?></h2>
	<?php
		foreach ($annotations as $a)
		{
?>
		<div>
			<table>
				<p><b><?php echo $a->name; ?>: </b><?php echo $a->value; ?></p>
			</table>
		</div>
<?php
		}
	?>
</div>
<?php } ?>

<?php if ($relationships) { ?>
<div id="relationship">
<h2><?php echo elgg_echo('relationships'); ?></h2>
	<?php
		foreach ($relationships as $r)
		{
?>
		<div>
			<table>
				<p><b><?php echo $r->relationship; ?>: </b><?php echo $r->guid_two; ?></p>
			</table>
		</div>
<?php
		}
	?>
</div>
<?php } ?>
</div>