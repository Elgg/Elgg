<?php
/**
 * Page edit form.
 *
 * @package ElggPages
 */

$parent_guid = get_input('parent_guid');
$container_guid = get_input('container_guid');
if (!$container_guid) {
	$container_guid = elgg_get_page_owner_guid();
}

$new_page = false;
if (!$vars['entity']) {
	$new_page = true;

	// bootstrap the access permissions in the entity array so we can use defaults
	if (defined('ACCESS_DEFAULT')) {
		$vars['entity']->access_id = ACCESS_DEFAULT;
		$vars['entity']->write_access_id = ACCESS_DEFAULT;
	} else {
		$vars['entity']->access_id = 0;
		$vars['entity']->write_access_id = 0;
	}

	// pull in sticky values from session
	if (isset($_SESSION['page_description'])) {
		$vars['entity']->description = $_SESSION['page_description'];
		$vars['entity']->tags = $_SESSION['page_tags'];
		$vars['entity']->access_id = $_SESSION['page_read_access'];
		$vars['entity']->write_access_id = $_SESSION['page_write_access'];

		// clear them
		unset($_SESSION['page_description']);
		unset($_SESSION['page_tags']);
		unset($_SESSION['page_read_access']);
		unset($_SESSION['page_write_access']);
	}
}
?>
<div class="contentWrapper">
<form action="<?php echo elgg_get_site_url(); ?>action/pages/edit" method="post">
<?php
echo elgg_view('input/securitytoken');
if (is_array($vars['config']->pages) && sizeof($vars['config']->pages) > 0) {
	foreach($vars['config']->pages as $shortname => $valtype) {
?>

<p>
	<label>
		<?php echo elgg_echo("pages:{$shortname}") ?><br />
		<?php echo elgg_view("input/{$valtype}",array(
			'internalname' => $shortname,
			'value' => $vars['entity']->$shortname
		)); ?>
	</label>
</p>

<?php

	}
}

$cats = elgg_view('categories', $vars);
if (!empty($cats)) {
?>
<p>
	<?php
		echo $cats;
	?>
</p>
<?php

	}

?>
<p>
<?php
if (!$new_page) {
	?><input type="hidden" name="pages_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /><?php
}

if ($container_guid) {
	?><input type="hidden" name="container_guid" value="<?php echo $container_guid; ?>" /><?php
}
?>
	<input type="hidden" name="parent_guid" value="<?php if (!$new_page) echo $vars['entity']->parent_guid; else echo $parent_guid; ?>" />
	<input type="hidden" name="owner_guid" value="<?php if (!$new_page) echo $vars['entity']->owner_guid; else echo elgg_get_page_owner_guid(); ?>" />
	<input type="submit" class="submit-button" value="<?php echo elgg_echo("save"); ?>" />
</p>

</form>
</div>