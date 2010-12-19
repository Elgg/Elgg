<?php
/**
 * Elgg report this link
 *
 * @package ElggReportContent
 */

$title = elgg_echo('reportedcontent:this:tooltip');
$text  = elgg_echo('reportedcontent:this');
?>


<a class="report-this" href="javascript:location.href='<?php echo elgg_get_site_url(); ?>pg/reportedcontent/add/?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)" title="<?php echo $title; ?>">
	<span class="elgg-icon report-this-icon"></span>
	<?php echo $text; ?>
</a>
