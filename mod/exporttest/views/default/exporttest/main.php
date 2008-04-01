<form>
	<input type="hidden" name="owner_id" value="<?php echo $vars['owner_id']; ?>" />
	GUID : <input type="text" value="" name="guid" /> <input type="submit" name="export" value="export" />
</form>

<form>
	<input type="hidden" name="owner_id" value="<?php echo $vars['owner_id']; ?>" />
	<input type="hidden" name="action" value="import" />
	IMPORT : 
	<textarea name="xml" cols="50" rows="10"></textarea>
	<input type="submit" name="import" value="import" />
</form>