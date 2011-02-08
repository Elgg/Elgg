<?php
/**
 * Elgg report this link
 *
 * @package ElggReportContent
 */

$title = elgg_echo('reportedcontent:this:tooltip');
$text  = elgg_echo('reportedcontent:this');

$url = elgg_get_site_url() . 'pg/reportedcontent/add/?address=';
$href = "javascript:location.href='$url'";
$href .= "+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)";
$href = elgg_format_url($href);
?>

<a class="report-this" href="<?php echo $href; ?>" title="<?php echo $title; ?>">
	<span class="elgg-icon report-this-icon"></span>
	<?php echo $text; ?>
</a>
