<?php
/**
 * Elgg reported content object view
 *
 * @package ElggReportContent
 */

$report = $vars['entity'];
$reporter = $report->getOwnerEntity();

$archive_url = elgg_get_site_url() . "action/reportedcontent/archive?guid=$report->guid";
$delete_url = elgg_get_site_url() . "action/reportedcontent/delete?guid=$report->guid";

//find out if the report is current or archive
if ($report->state == 'archived') {
	$reportedcontent_background = "reported-content-archived";
} else {
	$reportedcontent_background = "reported-content-active";
}

?>

<div class="reported-content <?php echo $reportedcontent_background; ?>">
	<div class="clearfix controls">
<?php
	if ($report->state != 'archived') {
		$params = array(
			'href' => $archive_url,
			'text' => elgg_echo('reportedcontent:archive'),
			'is_action' => true,
			'class' => 'elgg-button elgg-button-action',
		);
		echo elgg_view('output/url', $params);
	}
	$params = array(
		'href' => $delete_url,
		'text' => elgg_echo('reportedcontent:delete'),
		'is_action' => true,
		'class' => 'elgg-button elgg-button-action',
	);
	echo elgg_view('output/url', $params);
?>
	</div>
	<p>
		<b><?php echo elgg_echo('reportedcontent:by'); ?>:</b>
		<?php echo elgg_view('output/url', array(
			'href' => $reporter->getURL(),
			'text' => $reporter->name
		));
		?>,
		<?php echo elgg_view_friendly_time($report->time_created); ?>
	</p>
	<p>
		<b><?php echo elgg_echo('reportedcontent:objecttitle'); ?>:</b>
		<?php echo $report->title; ?>
		<br />
		<?php echo elgg_view('output/url', array(
			'href' => "#report-$report->guid",
			'text' => elgg_echo('reportedcontent:moreinfo'),
			'class' => "elgg-toggler",
		));
		?>
	</p>
	<div class="hidden" id="report-<?php echo $report->getGUID();?>">
		<p>
			<b><?php echo elgg_echo('reportedcontent:objecturl'); ?>:</b>
			<?php echo elgg_view('output/url', array(
				'href' => $report->address,
				'text' => elgg_echo('reportedcontent:visit')
			));
			?>
		</p>
		<p>
			<b><?php echo elgg_echo('reportedcontent:reason'); ?>:</b>
			<?php echo $report->description; ?>
		</p>
	</div>
</div>
