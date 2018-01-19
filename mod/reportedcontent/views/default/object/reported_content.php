<?php
/**
 * Elgg reported content object view
 *
 * @package ElggReportContent
 */

$report = $vars['entity'];
/* @var ElggObject $report */
$reporter = $report->getOwnerEntity();

//find out if the report is current or archive
if ($report->state == 'archived') {
	$reportedcontent_background = "reported-content-archived";
} else {
	$reportedcontent_background = "reported-content-active";
}

?>

<div class="reported-content <?php echo $reportedcontent_background; ?>">
	<div class="clearfix">
		<div class="clearfix controls">
<?php
if ($report->state != 'archived') {
	$attrs = [
	'class' => 'elgg-button elgg-button-action',
	'data-elgg-action' => json_encode([
		'name' => 'reportedcontent/archive',
		'data' => [
			'guid' => $report->guid,
		]
	]),
	];
	echo elgg_format_element('button', $attrs, elgg_echo('reportedcontent:archive'));
}
	$attrs = [
		'class' => 'elgg-button elgg-button-action',
		'data-elgg-action' => json_encode([
			'name' => 'reportedcontent/delete',
			'data' => [
				'guid' => $report->guid,
			]
		]),
	];
	echo elgg_format_element('button', $attrs, elgg_echo('reportedcontent:delete'));
?>
		</div>
		<h3 class="mbm">
			<?php echo elgg_view('output/url', [
				'text' => $report->getDisplayName(),
				'href' => $report->address,
				'is_trusted' => true,
				'class' => 'elgg-reported-content-address elgg-lightbox',
				'data-colorbox-opts' => json_encode([
					'width' => '85%',
					'height' => '85%',
					'iframe' => true,
				]),
			]);
			?>
		</h3>
		<p><b><?php echo elgg_echo('reportedcontent:by') ?></b>
			<?php echo elgg_view('output/url', [
				'href' => $reporter->getURL(),
				'text' => $reporter->getDisplayName(),
				'is_trusted' => true,
			]);
			echo " " . elgg_view_friendly_time($report->time_created);
			?>
		</p>
		<?php if ($report->description) : ?>
			<p><?php echo $report->description; ?></p>
		<?php endif; ?>
	</div>
</div>
