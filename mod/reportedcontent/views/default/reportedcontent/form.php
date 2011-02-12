<?php
/**
 * Elgg report content plugin form
 * 
 * @package ElggReportContent
 */

$guid = 0;
$title = get_input('title', "");
$description = "";
$address = get_input('address', "");
if ($address == "previous") {
	$address = $_SERVER['HTTP_REFERER'];
}
$tags = array();
$access_id = ACCESS_PRIVATE;
$shares = array();
$owner = elgg_get_logged_in_user_entity();

?>
<form action="<?php echo elgg_get_site_url(); ?>action/reportedcontent/add" method="post" class="mtm">
<?php echo elgg_view('input/securitytoken'); ?>

	<div>
		<label>
			<?php 	echo elgg_echo('reportedcontent:title'); ?>
			<?php

					echo elgg_view('input/text',array(
							'internalname' => 'title',
							'value' => $title,
					)); 
			
			?>
		</label>
	</div>
	<div>
		<label>
			<?php 	echo elgg_echo('reportedcontent:address'); ?>
			<?php

					echo elgg_view('input/url',array(
							'internalname' => 'address',
							'value' => $address,
					)); 
			
			?>
		</label>
	</div>
	<div>
		<label>
			<?php 	echo elgg_echo('reportedcontent:description'); ?>
		</label>
			<?php

					echo elgg_view('input/longtext',array(
							'internalname' => 'description',
							'value' => $description,
					)); 
			
			?>
	</div>
	<div>
		<input type="submit" value="<?php echo elgg_echo('reportedcontent:report'); ?>" />
	</div>
</form>
