<?php
/**
 * Elgg report this link
 *
 * @package ElggReportContent
 */

$title = elgg_echo('reportedcontent:this:title');
$text  = elgg_echo('reportedcontent:this');
?>

<div id="report_this">
	<a href="javascript:location.href='<?php echo $vars['url']; ?>mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)" title="<?php echo $title; ?>"><?php echo $text; ?></a>
</div>
