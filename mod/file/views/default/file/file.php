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
	
	$tags = $vars['tags'];
	$title = $vars['title'];
	$desc = $vars['description'];
	
	$mime = $vars['mimetype'];
	
?>
<div class="file">
	<table width="100%">
		<tr>
			<td valign="top">
				<div class="file_icon">
					<?php echo elgg_view("file/icon", array("mime" => $mime)); ?>
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
