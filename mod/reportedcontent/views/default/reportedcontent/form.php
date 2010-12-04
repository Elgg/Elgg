<?php
/**
 * Elgg report content plugin form
 * 
 * @package ElggReportContent
 */

$guid = 0;
$title = get_input('title',"");
$description = "";
$address = get_input('address',"");
if ($address == "previous") {
	$address = $_SERVER['HTTP_REFERER'];
}
$tags = array();
$access_id = ACCESS_PRIVATE;
$shares = array();
$owner = get_loggedin_user();

?>
<form action="<?php echo elgg_get_site_url(); ?>action/reportedcontent/add" method="post" class="margin-top">
<?php echo elgg_view('input/securitytoken'); ?>

	<p>
		<label>
			<?php 	echo elgg_echo('reportedcontent:title'); ?>
			<?php

					echo elgg_view('input/text',array(
							'internalname' => 'title',
							'value' => $title,
					)); 
			
			?>
		</label>
	</p>
	<p>
		<label>
			<?php 	echo elgg_echo('reportedcontent:address'); ?>
			<?php

					echo elgg_view('input/url',array(
							'internalname' => 'address',
							'value' => $address,
					)); 
			
			?>
		</label>
	</p>
	<p class="longtext_inputarea">
		<label>
			<?php 	echo elgg_echo('reportedcontent:description'); ?>
		</label>
			<?php

					echo elgg_view('input/longtext',array(
							'internalname' => 'description',
							'value' => $description,
					)); 
			
			?>
	</p>
	<p>
		<input type="submit" value="<?php echo elgg_echo('reportedcontent:report'); ?>" />
	</p>

</form>
