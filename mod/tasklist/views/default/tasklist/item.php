<?php
	/**
	 * Elgg tasklist test
	 * 
	 * @package ElggTasklist
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
?>
<tr>
	<td><b><?php echo $vars['task'] ?></b></td>
	
	<td>
		<?php if ($vars['status']=='done')
			echo "done";
		else
		{
?>
			<form method = "post">
				<input type="hidden" name="action" value="tick" />
				<input type="hidden" name="status" value="done" />
				<input type="hidden" name="owner_id" value="<?php echo $vars['owner_id']; ?>"/>
				<input type="hidden" name="guid" value="<?php echo $vars['guid']; ?>"/>
				<input type="submit" name="Done" value="Done" />
			</form>
<?php
		}
?>
	</td>
</tr>