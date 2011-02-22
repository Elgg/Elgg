<?php
/**
 * Elgg dashboard blurb
 *
 */
?>

<div class="elgg-col elgg-col-2of3">
<?php 
	echo elgg_view('output/longtext', array(
		'id' => 'dashboard-info',
		'class' => 'elgg-inner pas mhs mbl',
		'value' => elgg_echo("dashboard:nowidgets"),
	));

?>
</div>