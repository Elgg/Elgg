<?php
	/**
	 * Elgg file browser uploader
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	global $CONFIG;

	if (isset($vars['entity'])) {
		$action_type = "update";
		$action = "file/upload";
		$title = $vars['entity']->title;
		$description = $vars['entity']->description;
		$tags = $vars['entity']->tags;
		$access_id = $vars['entity']->access_id;
		$container_guid = $vars['entity']->container_guid;
	} else  {
		$action_type = "new";
		$action = "file/upload";
		$title = isset($_SESSION['uploadtitle']) ? $_SESSION['uploadtitle'] : '';
		$description = isset($_SESSION['uploaddesc']) ? $_SESSION['uploaddesc'] : '';
		$tags = isset($_SESSION['uploadtags']) ? $_SESSION['uploadtags'] : '';
		if (defined('ACCESS_DEFAULT')) {
			$access_id = ACCESS_DEFAULT;
		} else {
			$access_id = 0;
		}
		$access_id = isset($_SESSION['uploadaccessid']) ? $_SESSION['uploadaccessid'] : $access_id;
		$container_guid = page_owner_entity()->guid;
	}
		
	// make sure session cache is cleared
	unset($_SESSION['uploadtitle']);
	unset($_SESSION['uploaddesc']);
	unset($_SESSION['uploadtags']);
	unset($_SESSION['uploadaccessid']);
	
	
?>
<div class="contentWrapper">
<form action="<?php echo $vars['url']; ?>action/<?php echo $action; ?>" enctype="multipart/form-data" method="post">
<p>
	<label>
<?php
	echo elgg_view('input/securitytoken');
	if ($action_type == "new") {
		echo elgg_echo("file:file");
	} else {
		echo elgg_echo("file:replace");
	}
?>
<br />
<?php

	echo elgg_view("input/file",array('internalname' => 'upload'));
			
?>
	</label>
</p>
<p>
	<label><?php echo elgg_echo("title"); ?><br />
<?php

	echo elgg_view("input/text", array(
									"internalname" => "title",
									"value" => $title,
													));
			
?>
	</label>
</p>
<p class="longtext_editarea">
	<label><?php echo elgg_echo("description"); ?><br />
<?php

	echo elgg_view("input/longtext",array(
									"internalname" => "description",
									"value" => $description,
													));
?>
	</label>
</p>
<p>
	<label><?php echo elgg_echo("tags"); ?><br />
<?php

	echo elgg_view("input/tags", array(
									"internalname" => "tags",
									"value" => $tags,
													));
			
?>
	</label>
</p>
<?php

	$categories = elgg_view('categories',$vars);
	if (!empty($categories)) {
?>

		<p>
			<?php echo $categories; ?>
		</p>

<?php
		}

?>
<p>
	<label>
		<?php echo elgg_echo('access'); ?><br />
		<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id)); ?>
	</label>
</p>
	
<p>
<?php

	echo "<input type=\"hidden\" name=\"container_guid\" value=\"{$container_guid}\" />";
	
	if (isset($vars['entity'])) {
		echo "<input type=\"hidden\" name=\"file_guid\" value=\"{$vars['entity']->getGUID()}\" />";
	}
	
?>
	<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
</p>

</form>
</div>
