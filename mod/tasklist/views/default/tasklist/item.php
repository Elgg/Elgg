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