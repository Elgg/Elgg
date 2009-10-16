<?php
/**
 * Elgg system settings form
 * The form to change system settings
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['action'] If set, the place to forward the form to (usually action/systemsettings/save)
 */
?>

<input type="hidden" name="number_of_files" value="1">
<?php

if (isset($vars['entity'])) {
	$title = $vars['entity']->title;
	$description = $vars['entity']->description;
	$tags = $vars['entity']->tags;
	$access_id = $vars['entity']->access_id;
} else  {
	$title = "";
	$description = "";
	$tags = "";
	$access_id = get_default_access();
}

$plugin = $vars['plugin'];

if (!$vars['entity']) {

?>
	<div id="option_container">
		<p>
			<label><?php echo elgg_echo("title"); ?><br />
			<?php

				echo elgg_view("input/text", array("internalname" => "title_0"));

			?>
			</label>
		</p>
		<p>
			<label><?php echo elgg_echo("$plugin:file"); ?><br />
			<?php

				echo elgg_view("input/file",array('internalname' => 'upload_0'));

			?>
			</label>
		</p>
		</div>
		<p><input type="button" onclick="javascript:file_addtoform()" value="<?php echo elgg_echo("$plugin:add_to_form"); ?>"></p>
<?php
	} else {

?>
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
<?php
	}
?>
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
		<br />
		<p>
			<label><?php echo elgg_echo("tags"); ?><br />
			<?php

				echo elgg_view("input/tags", array(
					"internalname" => "tags",
					"value" => $tags,
				));

			?>
		</label></p>
<?php

		$categories = elgg_view('categories',$vars);
		if (!empty($categories)) {
?>

		<p>
			<?php echo $categories; ?>
		</p>

<?php
		}
		//remove folders until they are ready to use
		//echo elgg_view("$plugin/folders/select",$vars);
?>
		<p>
			<label>
				<?php echo elgg_echo('access'); ?><br />
				<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id)); ?>
			</label>
		</p>

<?php

	if (isset($vars['container_guid'])) {
		echo "<input type=\"hidden\" name=\"container_guid\" value=\"{$vars['container_guid']}\" />";
	}
	if (isset($vars['entity'])) {
		echo "<input type=\"hidden\" name=\"file_guid\" value=\"{$vars['entity']->getGUID()}\" />";
	}