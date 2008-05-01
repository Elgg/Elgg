<?php
	/**
	 * Elgg file browser.
	 * File renderer.
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	$file_guid = $vars['file_guid'];
	$tags = $vars['tags'];
	$title = $vars['title'];
	$desc = $vars['description'];
	
	$mime = $vars['mimetype'];
	
?>
<div class="file">
	<table width="100%">
		<tr>
			<td valign="top" width="100">
				<div class="file_icon">
					<a href="<?php echo $vars['url']; ?>action/file/download?file_guid=<?php echo $file_guid; ?>"><?php echo elgg_view("file/icon", array("mimetype" => $mime)); ?></a>					
				</div>
			</td>
			<td valign="top">
				<div class="title"><?php echo $title; ?></div>
				<div class="description"><?php echo $desc; ?></div>
				<div class="tags"><?php
					foreach ($tags as $tag)
						echo "<a href=\"" . $CONFIG->wwwroot . "pg/file/". $_SESSION['user']->username . "/world/?tag=$tag\">$tag</a> ";
				?></div>
			</td>
		</tr>
	</table>
</div>
