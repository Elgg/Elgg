<?php
/**
 * Elgg report this link
 *
 * @package ElggReportContent
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$title = elgg_echo('reportedcontent:this:title');
$text  = elgg_echo('reportedcontent:this');
?>

<div id="report_this">
	<a href="javascript:location.href='<?php echo $vars['url']; ?>mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)" title="<?php echo $title; ?>"><?php echo $text; ?></a>
</div>
