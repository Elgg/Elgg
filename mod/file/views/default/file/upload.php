<?php
	/**
	 * Elgg file browser uploader
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
?>
<form action="<?php echo $vars['url']; ?>action/file/upload" enctype="multipart/form-data" method="post">

	<table>
	<tr><td><?php echo elgg_echo("file:file");?>:</td><td><div id="file"><input type="file" name="upload" /></div></td></tr>
	<tr><td><?php echo elgg_echo("file:title");?>:</td><td><div id="title"><input type="text" name="title" size="49" /></div></td></tr>
	<tr><td valign="top"><?php echo elgg_echo("file:desc");?>:</td><td><div id="description"><textarea name="description" cols="50" rows="10"></textarea></div></td></tr>
	<tr><td valign="top"><?php echo elgg_echo("file:tags");?>:</td><td><div id="tags"><textarea name="tags" cols="50"></textarea></div></td></tr>
	</table>
	
	<input type="submit" name="Upload" value="Upload" />

</form>