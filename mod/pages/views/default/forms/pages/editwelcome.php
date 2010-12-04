<?php
/**
 * Welcome page edit form
 *
 * @package ElggPages
 */

//set some variables
if($vars['entity']) {
	foreach($vars['entity'] as $welcome) {
		$current_message = $welcome->description;
		$object_guid = $welcome->guid;
		$access_id = $welcome->access_id;
	}
} else {
	$current_message = '';
	$object_guid = '';
	$access_id = ACCESS_PUBLIC;
}

$page_owner = $vars['owner']->guid;

?>
<form action="<?php echo elgg_get_site_url(); ?>action/pages/editwelcome" method="post">

	<p class="longtext_inputarea">
	<label>
		<?php echo elgg_view("input/longtext",array(
			'internalname' => "pages_welcome",
			'value' => $current_message,
			'disabled' => $disabled
		)); ?>
	</label>
</p>
<p>
	<label>
		<?php echo elgg_echo('access'); ?><br />
		<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id)); ?>
	</label>
</p>
<input type="hidden" name="owner_guid" value="<?php echo $page_owner; ?>" />

<?php
	echo elgg_view('input/securitytoken');

	//if it is editing, include the object guid
	if ($object_guid != ''){
	?>
	<input type="hidden" name="object_guid" value="<?php echo $object_guid; ?>" />
<?php
		}
	?>

<input type="submit" class="submit-button" value="<?php echo elgg_echo("save"); ?>" />
</form>
