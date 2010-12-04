<?php
/**
 * Elgg report this link
 *
 * @package ElggReportContent
 */

$title = elgg_echo('reportedcontent:this:tooltip');
$text  = elgg_echo('reportedcontent:this');
?>

<div id="report_this">
	<a href="javascript:location.href='<?php echo elgg_get_site_url(); ?>pg/reportedcontent/add/?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)" title="<?php echo $title; ?>"><?php echo $text; ?></a>
</div>
