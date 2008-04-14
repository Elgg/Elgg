<?php
	/**
	 * Elgg GUID browser
	 * 
	 * @package ElggDevTools
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$entity = $vars['entity'];
	$metadata = $vars['metadata'];
	$annotations = $vars['annotations'];
	$relationships = $vars['relationships'];
	
?>
<div>
	<?php
		foreach ($entity as $k => $v)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $k; ?></b></td>
				<td><?php echo $v; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
</div>
<div id="metadata">
<h2>Metadata</h2>	
	<?php
		foreach ($metadata as $m)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $m->name; ?></b></td>
				<td><?php echo $m->value; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
	
	<div>
		<form method="post">
			<input name="eguid" type="hidden" value="<?php echo $entity->guid; ?>" />
			<input name="owner_id" type="hidden" value="<?php echo page_owner(); ?>" />
			<input name="callaction" type="hidden" value="metadata" />
			Key : <input name="key" type="text" />
			Value : <input name="value" type="text" /> 
			<input name="submit" type="submit" value="submit" />
		</form>
	</div>
	
</div>

<div id="annotations">
<h2>Annotations</h2>	
	<?php
		foreach ($annotations as $a)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $a->name; ?></b></td>
				<td><?php echo $a->value; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
	
	<div>
		<form method="post">
			<input name="eguid" type="hidden" value="<?php echo $entity->guid; ?>" />
			<input name="owner_id" type="hidden" value="<?php echo page_owner(); ?>" />
			<input name="callaction" type="hidden" value="annotations" />
			Key : <input name="key" type="text" />
			Value : <input name="value" type="text" /> 
			<input name="submit" type="submit" value="submit" />
		</form>
	</div>
</div>

<div id="relationship">
<h2>Relationships</h2>	
	<?php
		foreach ($relationships as $r)
		{
?>
		<div>
			<table>
				<tr>
				<td><b><?php echo $r->relationship; ?></b></td>
				<td><?php echo $r->guid_two; ?></td> 
				</tr>
			</table>
		</div>
<?php
		}
	?>
	
	<div>
		<form method="post">
			<input name="eguid" type="hidden" value="<?php echo $entity->guid; ?>" />
			<input name="owner_id" type="hidden" value="<?php echo page_owner(); ?>" />
			<input name="callaction" type="hidden" value="relationship" />
			Relationship : <input name="relationship" type="text" />
			Guid : <input name="guid2" type="text" /> 
			<input name="submit" type="submit" value="submit" />
		</form>
	</div>
</div>