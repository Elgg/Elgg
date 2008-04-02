<form method="post">
	<textarea name="task"></textarea>
	<input type="text" name="tags" />
	<input type="hidden" name="action" value="newtask"/>
	<input type="hidden" name="owner_id" value="<?php echo $vars['owner_id']; ?>"/>
	<input type="submit" name="submit" />
</form>