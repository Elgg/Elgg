<?php
/**
 * Elgg report content plugin form
 * 
 * @package ElggReportContent
 */

$guid = 0;
$title = get_input('title', "");
$address = get_input('address', "");

$description = "";
$owner = elgg_get_logged_in_user_entity();

?>

<div>
	<label>
		<?php
			echo elgg_echo('reportedcontent:title');
			echo elgg_view('input/text', array(
				'name' => 'title',
				'value' => $title,
			));
		?>
	</label>
</div>
<div>
	<label>
		<?php
			echo elgg_echo('reportedcontent:address');
			echo elgg_view('input/url', array(
					'name' => 'address',
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
			'name' => 'description',
			'value' => $description,
		)); 
	?>
</div>
<div class="elgg-foot">
	<?php
		echo elgg_view('input/submit', array(
			'value' => elgg_echo('reportedcontent:report'),
		));
	?>
</div>
